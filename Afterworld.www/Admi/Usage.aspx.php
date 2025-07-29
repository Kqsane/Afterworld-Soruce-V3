<?php
// Code rewritten by Skyler to use powershell instead of wmic
// coded back in 2022, openblox made by newuser
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$isAdmin = 0;
$currentPage = basename($_SERVER['PHP_SELF']);
if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    logout();
    header("Location: /newlogin");
    exit();
}
$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if (!$info || !isset($info['UserId'])) {
    logout();
    header("Location: /");
    exit();
}
if (!empty($_COOKIE['_ROBLOSECURITY'])) {
    $userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    if (is_array($userInfo) && !empty($userInfo['UserId'])) {
        $userId = (int)$userInfo['UserId'];
        $stmt = $pdo->prepare('SELECT isAdmin FROM users WHERE UserId = :userId LIMIT 1');
        $stmt->execute([':userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $isAdmin = isset($row['isAdmin']) ? (int)$row['isAdmin'] : 0;
    }
}
if ($isAdmin === 0 && stripos($currentPage, 'home') === false) {
    header('Location: /home');
    exit();
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/Admi/SideBar.php';
if ($adminLevel < AdminLevel::DEV) {
    echo <<<HTML
<div class="main-content"><br>
    <div class="header">
        <h1>You do not have permission to use this.</h1>
    </div>
</div>
</body>
</html>
HTML;
exit;
}
function getServerSpecs() {
    $specs = [
        'OS' => php_uname(),
        'PHP Version' => PHP_VERSION,
        'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
    ];
    $cpuInfo = shell_exec('powershell -Command "Get-CimInstance Win32_Processor | Select-Object -ExpandProperty Name"');
    $coreCount = shell_exec('powershell -Command "(Get-CimInstance Win32_Processor).NumberOfCores"');
    $ramBytes = shell_exec('powershell -Command "(Get-CimInstance Win32_ComputerSystem).TotalPhysicalMemory"');

    $specs['CPU Model'] = trim($cpuInfo ?: 'Unknown');
    $specs['CPU Cores'] = trim($coreCount ?: 'Unknown');
    $specs['Total RAM'] = $ramBytes ? round(trim($ramBytes) / 1024 / 1024, 2) . ' MB' : 'Unknown';

    return $specs;
}

function getCPUUsage() {
    $cpuUsage = shell_exec("powershell -Command \"(Get-Counter -Counter '\\Processor(_Total)\\% Processor Time').CounterSamples.CookedValue\"");
    return $cpuUsage ? round(trim($cpuUsage), 2) : 'Unknown';
}

function getRAMUsage() {
    $total = shell_exec('powershell -Command "(Get-CimInstance Win32_OperatingSystem).TotalVisibleMemorySize"');
    $free = shell_exec('powershell -Command "(Get-CimInstance Win32_OperatingSystem).FreePhysicalMemory"');

    if ($total && $free) {
        $totalKB = (float)trim($total);
        $freeKB = (float)trim($free);
        $usedKB = $totalKB - $freeKB;
        return [
            'used' => round($usedKB / 1024, 2),
            'total' => round($totalKB / 1024, 2),
            'percent' => round(($usedKB / $totalKB) * 100, 2)
        ];
    }

    return null;
}

$specs = getServerSpecs();
$cpuUsage = getCPUUsage();
$ramUsage = getRAMUsage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .main-content {
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
        }
        .highlight {
            font-weight: bold;
            color: #007700;
        }
    </style>
</head>
<body>
<div class="main-content">
    <h2>System Info</h2>
    <br>
    <table>
        <?php foreach ($specs as $label => $value): ?>
            <tr>
                <th><?= htmlspecialchars($label) ?></th>
                <td><?= htmlspecialchars($value) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>

    <h2>Current Usage</h2>
    <table>
        <tr>
            <th>CPU Usage</th>
            <td class="highlight"><?= is_numeric($cpuUsage) ? $cpuUsage . '%' : 'Unknown' ?></td>
        </tr>
        <?php if ($ramUsage): ?>
            <tr>
                <th>RAM Usage</th>
                <td class="highlight"><?= $ramUsage['used'] ?> MB / <?= $ramUsage['total'] ?> MB (<?= $ramUsage['percent'] ?>%)</td>
            </tr>
        <?php else: ?>
            <tr>
                <th colspan="2">RAM information not available.</th>
            </tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>