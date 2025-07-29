<?php
/**
* 
* InGameChatHandler.ashx.php
*
* Handles Roblox XML Reports and converts them into DB Data then stores them on the 'reports' table.
* 
* Copyright 2025 AFTERWORLD.
* 
*/
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$rawXml = file_get_contents('php://input');
if (!$rawXml) {
    http_response_code(400);
    exit('No payload received.');
}
libxml_use_internal_errors(true);
$dom = new DOMDocument();
if (!$dom->loadXML($rawXml, LIBXML_NOENT | LIBXML_NONET | LIBXML_NOBLANKS)) {
    http_response_code(400);
    exit('Malformed XML.');
}
libxml_clear_errors();
$report = $dom->getElementsByTagName('report')->item(0);
if (!$report) {
    http_response_code(400);
    exit('Missing <report> root node.');
}
$userId = (int) $report->getAttribute('userID');
$placeId = (int) $report->getAttribute('placeID');
$gameJobId = $report->getAttribute('gameJobID');
$commentNode = $report->getElementsByTagName('comment')->item(0);
$comment = $commentNode ? trim($commentNode->textContent) : null;
$messages = [];
$messagesNode = $report->getElementsByTagName('messages')->item(0);
if ($messagesNode) {
    foreach ($messagesNode->getElementsByTagName('message') as $msg) {
        $messages[] = [
            'userID' => (int) $msg->getAttribute('userID'),
            'guid' => $msg->getAttribute('guid'),
            'text' => trim($msg->textContent),
        ];
    }
}
$messagesJson = json_encode($messages, JSON_UNESCAPED_UNICODE);
$sql = <<<SQL
INSERT INTO reports (UserId, PlaceId, JobId, Comment, Messages, RawXML) VALUES (:UserId, :PlaceId, :JobId, :Comment, :Messages, :RawXML)
SQL;

$stmt = $pdo->prepare($sql);
$ok = $stmt->execute(['UserId' => $userId, 'PlaceId' => $placeId, 'JobId' => $gameJobId, 'Comment' => $comment, 'Messages' => $messagesJson, 'RawXML' => $rawXml,]);
if ($ok) {
    echo 'Report logged.';
} else {
    http_response_code(500);
    echo 'Database insert failed.';
}