<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
use TrashBlx\Core\SoapUtils;
verifyKey();
$utils = new SoapUtils();
$result = $utils->getAllRccs();
header("Content-Type: application/json");
echo json_encode(["success" => true, "rccs" => $result]);
