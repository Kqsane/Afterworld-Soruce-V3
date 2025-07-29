<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
use TrashBlx\Core\SoapUtils;
verifyKey();
$pid = (int)($_GET['pid'] ?? 0);
if ($pid < 1) exit(json_encode(['success' => false, 'error' => 'Missing PID']));
$utils = new SoapUtils();
$utils->killRcc($pid);
echo json_encode(['success' => true]);
