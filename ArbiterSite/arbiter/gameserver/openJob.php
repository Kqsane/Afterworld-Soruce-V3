<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
use TrashBlx\Core\SoapUtils;
verifyKey();
$utils = new SoapUtils();
echo json_encode($utils->openJobGS(
    port: $_GET['port'] ?? rand(6000, 6500),
    placeId: $_GET['placeId'] ?? 1,
    soapPort: $_GET['soapPort'] ?? rand(6501, 7000),
    creatorId: $_GET['creatorId'] ?? 1,
    clientYear: $_GET['clientYear'] ?? 2015
));