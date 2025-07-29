<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';

use TrashBlx\Core\SoapUtils;

$soaputils = new SoapUtils();

$apikey = "qQSy7dxQRU3FCfV";

if($_GET['apiKey'] != $apikey ?? !isset($_GET['apiKey'])){
	die('wrong api key or not set');
}

$query = $pdo->prepare('SELECT * FROM `jobs` WHERE `jobid` = :jobid');
$query->bindParam(":jobid", $_GET["jobId"], PDO::PARAM_STR);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);


$port = $row["soapport"];
$gameId = $row["placeId"];
$soaputils->killRcc($row['pid']);

$stmt = $pdo->prepare('UPDATE `assets` SET `players` = 0 WHERE `AssetID` = :gameId AND AssetType = 9');
$stmt->bindParam(":gameId", $gameId);

$query = $pdo->prepare("DELETE FROM jobs WHERE jobid= :jobid");
$query->execute(["jobid" => $_GET['jobId']]);
/* $ws = new COM("WScript.Shell");

$ws->Run('taskkill /F /IM "RCCService.exe', 1, false);
*/
exit('Server has been shutdown');