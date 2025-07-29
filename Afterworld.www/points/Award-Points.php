<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header("content-type: application/json");

$placeid = $_GET["placeId"];
$userid = (int) $_GET["userId"];
$points = $_GET["amount"];

try {
	if (!getuserinfo($userid)){
		throw new Exception("User does not exist");
	}

	$stmt = $pdo->prepare("SELECT UniverseID FROM assets WHERE AssetID = :placeId AND AssetType = 9");
	$stmt->bindParam(':placeId', $placeid);
	$stmt->execute();
	
	$universeid = $stmt->fetchColumn();
	
	if ($universeid == 0)
		throw new Exception("Place does not exist");
	
	
}
catch (Exception $e)
{
	http_response_code(400);
    die(json_encode(['success' => false, 'message' => $e->getMessage()]));
}

$stmt = $pdo->prepare("INSERT INTO pointshistory (universeId, userId, points, time) VALUES (?, ?, ?, ?)"); 
$stmt->execute([$universeid, $userid, $points, time()]);
	
$stmt = $pdo->prepare("SELECT * FROM points WHERE userId = ? AND universeId = ?");
$stmt->execute([$userid, $universeid]);
$pointsinfo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pointsinfo == null){
	$stmt = $pdo->prepare("INSERT INTO points (universeId, userId, allTimePoints) VALUES (?, ?, ?)"); 
	$stmt->execute([$universeid, $userid, $points]);
}else{
	$stmt = $pdo->prepare("UPDATE points SET allTimePoints = allTimePoints + ? WHERE userId = ? AND universeId = ?");		
	$stmt->execute([$points, $userid, $universeid]);
}
		
$stmt = $pdo->prepare("SELECT allTimePoints FROM points WHERE userId = ? AND universeId = ?");
$stmt->execute([$userid, $universeid]);
$points = $stmt->fetchColumn();

$success = [
			'success' => true,
			'userGameBalance' => $points,
			'userBalance' => 0,
			'pointsAwarded' => (int)$_GET["amount"]
];
			
die(json_encode($success));
?>
