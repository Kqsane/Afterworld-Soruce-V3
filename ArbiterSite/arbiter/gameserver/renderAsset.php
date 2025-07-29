<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
use TrashBlx\Core\SoapUtils;
verifyKey();
$utils = new SoapUtils();
$assetId = (int)($_GET['assetId'] ?? 0);
$assetType = (int)($_GET['assetType'] ?? 0);
if ($assetId < 1 || $assetType < 1) {
    exit(json_encode(['success' => false, 'error' => 'Missing assetId or assetType']));
}
$result = $utils->renderAsset($assetId, $assetType);
echo json_encode(['success' => true, 'data' => $result]);
