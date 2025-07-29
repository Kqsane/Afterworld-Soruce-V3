<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

header("Content-Type: image/png");

$username = isset($_GET['Username']) ? trim($_GET['Username']) : '';
if ($username === '') {
    readfile($_SERVER['DOCUMENT_ROOT'] . '/images/empty.png');
    exit;
}

$stmt = $pdo->prepare("SELECT Membership FROM users WHERE Username = :username LIMIT 1");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    readfile($_SERVER['DOCUMENT_ROOT'] . '/images/empty.png');
    exit;
}

switch ((int)$user['Membership']) {
    case 1:
        $imagePath = '/images/icons/overlay_bcOnly.png';
        break;
    case 2:
        $imagePath = '/images/icons/overlay_tbcOnly.png';
        break;
    case 3:
        $imagePath = '/images/icons/overlay_obcOnly.png';
        break;
    default:
        $imagePath = '/images/empty.png';
        break;
}

readfile($_SERVER['DOCUMENT_ROOT'] . $imagePath);
exit;
?>