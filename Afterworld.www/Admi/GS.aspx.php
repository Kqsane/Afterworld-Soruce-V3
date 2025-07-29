<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$isAdmin  = 0;
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
?>
<?php
$stmt = $pdo->query("SELECT * FROM jobs WHERE isRenderer = 0");
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="main-content">
    <br><br>
    <div class="header">
        <h1>Game Server Manager</h1>
    </div>
    <br>
<table>
    <thead>
        <tr>
            <th class="bold bigger">Job ID</th>
            <th class="bold bigger">Place ID</th>
            <th class="bold bigger">Soap Port</th>
            <th class="bold bigger">Port</th>
            <th class="bold bigger">Running</th>
            <th class="bold bigger">Heartbeat</th>
            <th class="bold bigger">Creation Date</th>
            <th class="bold bigger">Player Count</th>
            <th class="bold bigger">Process ID</th>
            <th class="bold bigger">Player List</th>
            <th class="bold bigger">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($jobs as $row): ?>
            <tr>
                <td><a class="bigger" href="#"><?= htmlspecialchars($row['jobid']) ?></a></td>
                <td class="bigger"><?= (int)$row['placeId'] ?></td>
                <td class="bigger"><?= (int)$row['soapport'] ?></td>
                <td class="bigger"><?= (int)$row['port'] ?></td>
                <td class="bigger"><?= $row['isRunning'] ? 'Yes' : 'No' ?></td>
                <td class="bigger"><?= date("m/d/Y h:i A", (int)$row['heartbeat']) ?></td>
                <td class="bigger"><?= date("m/d/Y h:i A", (int)$row['startTime']) ?></td>
                <td class="bigger"><?= (int)$row['players'] ?></td>
                <td class="bigger"><?= (int)$row['pid'] ?></td>
                <td class="bigger"><?= htmlspecialchars(trim($row['playerList'], '[]')) ?></td>
                <td class="bigger"><a href="/Admi/GS/manageJob.aspx?jobid=<?= urlencode($row['jobid']) ?>">Manage</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
