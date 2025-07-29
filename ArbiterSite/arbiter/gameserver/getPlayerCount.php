<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
use TrashBlx\Core\SoapUtils;
verifyKey();
$utils = new SoapUtils();
$soapPort = (int)($_GET['soapPort'] ?? 0);
$jobId = $_GET['jobId'] ?? '';
if ($soapPort < 1 || !$jobId) {
    exit(json_encode(['success' => false, 'error' => 'Missing soapPort or jobId']));
}
$count = $utils->getPlayerCount($soapPort, $jobId);
echo json_encode(['success' => true, 'playerCount' => $count]);
