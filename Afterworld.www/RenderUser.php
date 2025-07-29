<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
use TrashBlx\Core\SoapUtils;
if (!isset($_GET['uID']) || !ctype_digit($_GET['uID'])) {
    http_response_code(400);
    exit("Invalid or missing user ID. Use ?uID=123");
}
$userId = (int)$_GET['uID'];
$renderServerURL = "http://neuroticgs.aftwld.xyz/arbiter/gameserver/renderUser.php?apiKey=a4b9d3e6-38f2-4731-bdb9-92f2cb8607fe&userId=" . $userId;
$ch = curl_init($renderServerURL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_CONNECTTIMEOUT => 5,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (curl_errno($ch)) {
    http_response_code(502);
    echo json_encode([
        "success" => false,
        "error" => "Failed to reach render server",
        "details" => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}
curl_close($ch);
http_response_code($httpCode);
header("Content-Type: application/json");
echo $response;
