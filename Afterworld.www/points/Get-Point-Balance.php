<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header("Content-Type: application/json");

$placeId = filter_input(INPUT_GET, 'placeId', FILTER_VALIDATE_INT);
$userId = filter_input(INPUT_GET, 'userId', FILTER_VALIDATE_INT);

if (!$placeId || !$userId || !getuserinfo($userId)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid parameters']));
}

try {
	
	$stmt = $pdo->prepare("SELECT UniverseID FROM assets WHERE AssetID = :placeId AND AssetType = 9");
	$stmt->bindParam(':placeId', $placeId);
	$stmt->execute();
	
	$universeId = $stmt->fetchColumn();
	
	if ($universeId == 0)
		throw new Exception("Place does not exist");
}
catch (Exception $e)
{
	http_response_code(400);
    die(json_encode(['success' => false, 'message' => $e->getMessage()]));
}

$stmt = $pdo->prepare("SELECT allTimePoints FROM points WHERE userId = :userId AND universeId = :universeId");
$stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
$stmt->bindValue(':universeId', $universeId, PDO::PARAM_INT);
$stmt->execute();
$points = (int)$stmt->fetchColumn();

$response = ['pointBalance' => $points ?? 0];
echo json_encode($response);
?>