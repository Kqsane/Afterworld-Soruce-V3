<?php
// So we still have problem, big problem - the heavy
// random 2025
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
// why.
$stmt = $pdo->query("SELECT AssetID, Name, CreatorID, isApproved, AssetType FROM assets WHERE isApproved = '1' ORDER BY Created_At DESC LIMIT 20");
$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$searchResult = null;

// gonna be used soon jst be patient
$statusMap = [
    0 => 'Unapproved',
    1 => 'Approved',
    2 => 'Waiting',
    3 => 'Review'
];
?>

<div class="main-content">
    <br><br>
    <div class="header">
        <h1>Asset Approval</h1>
    </div>
    <br>
<table>
    <thead>
        <tr>
            <th class="bold bigger">Name</th>
            <th class="bold bigger">ID</th>
            <th class="bold bigger">Author</th>           
            <th class="bold bigger">Asset Type</th>
        </tr>
    </thead>
   <tbody>
    <?php foreach ($assets as $row): ?>
        <?php
            switch($row['AssetType']){
                case 9:
                    $AssetType = "Place";
                    break;
                case 8:
                    $AssetType = "Hat";
                    break;
                default:
                    $AssetType = "Undefined";
                    break;
            }
        ?>
        <tr>
            <td><a class="bigger"><?= htmlspecialchars($row['Name']) ?></a></td>
            <td class="bigger"><?= (int)$row['AssetID'] ?></td>
            <td class="bigger"><?= (int)$row['CreatorID'] ?></td>
            <td class="bigger"><?= $AssetType ?></td>
            <td class="bigger"><a href="/Admi/asset/Approve?assetId=<?= urlencode($row['AssetID']) ?>">Approve</a></td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>
