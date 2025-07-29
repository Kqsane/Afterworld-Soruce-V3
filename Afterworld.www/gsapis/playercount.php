<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';

use TrashBlx\Core\SoapUtils;

$soaputils = new SoapUtils();

$apikey = "qQSy7dxQRU3FCfV";

if($_GET['apiKey'] != $apikey ?? !isset($_GET['apiKey'])){
	die('wrong api key or not set');
}

$playercount = $_GET["playerCount"];
$jobId = $_GET["jobId"];
$playerlist = urldecode($_GET['playerList']);
$playerlist = json_validate($playerlist) ? $playerlist : "[]";

$stmt = $pdo->prepare("UPDATE `jobs` SET `players` = :players, playerList = :plr WHERE `jobId` = :jobId");
$stmt->bindParam(":players", $playercount);
$stmt->bindParam(":plr", $playerlist);
$stmt->bindParam(":jobId", $jobId);
$stmt->execute();

$stmt = $pdo->prepare('SELECT * FROM `jobs` WHERE `jobid` = :jobId');
$stmt->bindParam(":jobId", $jobId);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$gameId = $row["placeId"];

$stmt = $pdo->prepare("SELECT * FROM `assets` WHERE `AssetID` = :gameId AND AssetType = 9");
$stmt->bindParam(":gameId", $gameId);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$playercount1 = $row["players"];

$stmt = $pdo->prepare('UPDATE `assets` SET `players` = :players WHERE `AssetID` = :gameId');
$stmt->bindParam(":players", $playercount);
$stmt->bindParam(":gameId", $gameId);
$stmt->execute();