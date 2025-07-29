<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
$apikey = "qQSy7dxQRU3FCfV";
if($_GET['apiKey'] != $apikey ?? !isset($_GET['apiKey'])){
	die('wrong api key or not set');
}

$jobid = $_GET["jobId"];

$stmt = $pdo->prepare('UPDATE `jobs` SET `heartbeat` = :heartbeat WHERE `jobid` = :jobid');
$time = time();
$stmt->bindParam(':heartbeat', $time);
$stmt->bindParam(':jobid', $jobid);
$stmt->execute();