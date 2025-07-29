<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/classes/twitchHelpers.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$cookie = $_COOKIE['_ROBLOSECURITY'] ?? null;
if (!$cookie) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$info = getuserinfo($cookie);
if (!$info) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userId = $info['UserId'];
$robuxBalance = $info['Robux'];
$tixBalance = $info['Tix'];

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

$currency = strtoupper($input['currency'] ?? '');
$amount = floatval($input['amount'] ?? 0);
$message = trim($input['message'] ?? '');
$streamId = intval($_GET['stream_id'] ?? 0);

if (!$streamId) {
    echo json_encode(['error' => 'Stream ID missing']);
    exit();
}

if ($amount <= 0) {
    echo json_encode(['error' => 'Invalid donation amount']);
    exit();
}

if (!in_array($currency, ['ROBUX', 'TIX'])) {
    echo json_encode(['error' => 'Invalid currency']);
    exit();
}

if (($currency === 'ROBUX' && $robuxBalance < $amount) ||
    ($currency === 'TIX' && $tixBalance < $amount)) {
    echo json_encode(['error' => 'Insufficient balance']);
    exit();
}
$stmt = $pdo->prepare("SELECT streamer_id, stream_url FROM stream WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $streamId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || !$row['streamer_id'] || empty($row['stream_url'])) {
    echo json_encode(['error' => 'Streamer not found']);
    exit();
}

$streamerId = (int)$row['streamer_id'];
$streamUrl = $row['stream_url'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE UserId = :userId LIMIT 1");
$stmt->execute([':userId' => $userId]);
$donorRow = $stmt->fetch(PDO::FETCH_ASSOC);
$donorUserName = $donorRow['username'] ?? 'Unknown Donor';
$streamerUserName = 'Unknown Streamer';
if ($streamUrl && function_exists('getTwitchDisplayName')) {
    $displayName = getTwitchDisplayName($streamUrl);
    if ($displayName) {
        $streamerUserName = $displayName;
    }
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO stream_donations (stream_id, user_id, amount, currency, message, donated_at) VALUES (:stream_id, :user_id, :amount, :currency, :message, NOW())");
    $stmt->execute([
        ':stream_id' => $streamId,
        ':user_id' => $userId,
        ':amount' => $amount,
        ':currency' => $currency,
        ':message' => $message,
    ]);
    if ($currency === 'ROBUX') {
        $stmt = $pdo->prepare("UPDATE users SET Robux = Robux - :amount WHERE UserId = :userId");
    } else {
        $stmt = $pdo->prepare("UPDATE users SET Tix = Tix - :amount WHERE UserId = :userId");
    }
    $stmt->execute([':amount' => $amount, ':userId' => $userId]);
    if ($currency === 'ROBUX') {
        $stmt = $pdo->prepare("UPDATE users SET Robux = Robux + :amount WHERE UserId = :streamerId");
    } else {
        $stmt = $pdo->prepare("UPDATE users SET Tix = Tix + :amount WHERE UserId = :streamerId");
    }
    $stmt->execute([':amount' => $amount, ':streamerId' => $streamerId]);

    $pdo->commit();
    $amountFormatted = number_format($amount);
    $donorSafe = htmlspecialchars($donorUserName, ENT_QUOTES, 'UTF-8');
    $streamerSafe = htmlspecialchars($streamerUserName, ENT_QUOTES, 'UTF-8');
    $messagePart = $message !== '' ? " with message " . htmlspecialchars($message) : "";
    if ($currency === 'ROBUX') {
        $donationMessage = "{$donorUserName} has cheered {$amountFormatted} ROBUX to {$streamerUserName}{$messagePart}";
    } else {
        $donationMessage = "{$donorUserName} has cheered {$amountFormatted} Tix to {$streamerUserName}{$messagePart}";
    }

    $stmt = $pdo->prepare("INSERT INTO stream_chat (stream_id, user_id, message, sent_at) VALUES (:streamId, 1, :message, NOW())");
    $stmt->execute([
        ':streamId' => $streamId,
        ':message' => $donationMessage,
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['error' => 'Failed to process donation']);
}
