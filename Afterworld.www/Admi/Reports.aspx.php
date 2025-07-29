<?php
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
if ($adminLevel < AdminLevel::MODERATOR) {
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
ob_start();
$sql = "SELECT r.id, r.UserId, r.PlaceId, r.JobId, r.Comment, r.Messages, r.CreatedAt,  r.MarkAsResolved, u.Username AS reporter_name FROM reports r LEFT JOIN users u ON u.UserId = r.UserId ORDER BY r.CreatedAt DESC LIMIT 200";
$reports = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$abuserIds = [];
foreach ($reports as $row) {
    if (!empty($row['Comment'])) {
        $parts = explode(';', $row['Comment']);
        if (isset($parts[0]) && str_starts_with($parts[0], 'AbuserID:')) {
            $abuserId = (int)str_replace('AbuserID:', '', $parts[0]);
            if ($abuserId > 0) {
                $abuserIds[$abuserId] = true;
            }
        }
    }
}
$abuserUsernames = [];
if (!empty($abuserIds)) {
    $abuserIdList = array_keys($abuserIds);
    $inQuery = implode(',', array_fill(0, count($abuserIdList), '?'));
    $stmt = $pdo->prepare("SELECT UserId, Username FROM users WHERE UserId IN ($inQuery)");
    $stmt->execute($abuserIdList);

    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $abuserUsernames[(int)$user['UserId']] = $user['Username'];
    }
}
?>


<div class="main-content">
    <br><br>
    <div class="header">
        <h1>Reports</h1>
    </div>
    <br>
<table>
    <thead>
        <tr>
            <th class="bold bigger">Reporter</th>
            <th class="bold bigger">Place ID</th>
            <th class="bold bigger">Game Job ID</th>
            <th class="bold bigger">Abuser</th>
            <th class="bold bigger">Report Type</th>
            <th class="bold bigger">Comment</th>
            <th class="bold bigger">Message Count</th>
            <th class="bold bigger">Created At</th>
            <th class="bold bigger">Resolved</th>
            <th class="bold bigger">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reports as $row): ?>
            <?php
            $commentParts = explode(';', $row['Comment']);
            $abuser = 'Guest';
            $type = 'Guest';
            $realComment = '';
            if (isset($commentParts[0]) && str_starts_with($commentParts[0], 'AbuserID:')) {
                $abuser = (int)str_replace('AbuserID:', '', $commentParts[0]);
            }

            if (isset($commentParts[1])) {
                $type = htmlspecialchars(trim($commentParts[1]));
            }

            if (isset($commentParts[2])) {
                $realComment = htmlspecialchars(trim($commentParts[2]));
            }
            $msgCount = 0;
            if (!empty($row['Messages'])) {
                $msgDoc = new DOMDocument();
                @$msgDoc->loadXML($row['Messages']);
                $msgCount = $msgDoc->getElementsByTagName("Message")->length;
            }
            $reporterName = isset($row['reporter_name']) ? htmlspecialchars($row['reporter_name']) : "User {$row['UserId']}";
            ?>
            <tr>
                <td class="bigger">
                    <a href="/User.aspx?id=<?= (int)$row['UserId'] ?>"><?= $reporterName ?></a>
                </td>
                <td class="bigger"><?= (int)$row['PlaceId'] ?></td>
                <td class="bigger"><?= htmlspecialchars($row['JobId']) ?></td>
                <td class="bigger">
                    <?php if ($abuser !== 'Guest'): ?>
                        <?php if (isset($abuserUsernames[$abuser])): ?>
                            <a href="/User.aspx?id=<?= $abuser ?>"><?= htmlspecialchars($abuserUsernames[$abuser]) ?></a>
                        <?php else: ?>
                            <span>Guest (<?= $abuser ?>)</span>
                        <?php endif; ?>
                    <?php else: ?>
                        Guest
                    <?php endif; ?>
                </td>
                <td class="bigger"><?= $type ?></td>
                <td class="bigger"><?= $realComment ?></td>
                <td class="bigger"><?= $msgCount ?></td>
                <td class="bigger"><?= date("m/d/Y h:i A", strtotime($row['CreatedAt'])) ?></td>
                <td class="bigger"><?= $row['MarkAsResolved'] ? 'Yes' : 'No' ?></td>
                <td class="bigger">
                    <a href="/Admi/ReportDetails.aspx?id=<?= (int)$row['id'] ?>">View / Handle</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>