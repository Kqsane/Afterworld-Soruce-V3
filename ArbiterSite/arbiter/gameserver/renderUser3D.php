<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
use TrashBlx\Core\SoapUtils;
verifyKey();
$utils = new SoapUtils();
$userId = (int)($_GET['userId'] ?? 0);
if ($userId < 1) exit(json_encode(['success' => false, 'error' => 'Missing userId']));
$result = $utils->renderUser3D($userId);
echo json_encode(['success' => true, 'data' => $result]);
