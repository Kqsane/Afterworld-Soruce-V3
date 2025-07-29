<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    header("Location: /newlogin");
    exit();
}

$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if (!$info) {
    logout();
    header("Location: /");
    exit();
}

$userId = $info['UserId'] ?? null;

$stmt = $pdo->prepare("SELECT isBanned FROM users WHERE userId = ?");
$stmt->execute([$userId]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData || $userData['isBanned'] == 0) {
    header("Location: /");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM bans WHERE UserId = ? ORDER BY ID DESC LIMIT 1");
$stmt->execute([$userId]);
$banData = $stmt->fetch(PDO::FETCH_ASSOC);

$isWarning = isset($banData['isWarning']) ? (int)$banData['isWarning'] : 0;
$banType = $banData['BanType'] ?? '';
$reviewedAt = $banData['ReviewedAt'] ?? 0;
$modNote = $banData['ModNote'] ?? 'No moderator note';

$reviewDate = date("n/j/Y g:i:s A", $reviewedAt);
$currentTime = time();
$banLengthSeconds = isset($banData['BanLength']) ? (int)$banData['BanLength'] : 0;
$isPermanentBan = in_array($banLengthSeconds, [999999999, 999999998, 999999997]);

$unbanReady = false;
$durations = [
    'Ban 1 Day' => 1,
    'Ban 3 Days' => 3,
    'Ban 7 Days' => 7,
    'Ban 14 Days' => 14
];

if ($isWarning === 1 || $banLengthSeconds === 0) {
    $unbanReady = true;
} elseif ($banLengthSeconds > 0 && $banLengthSeconds < 999999997) {
    $banEnd = $reviewedAt + $banLengthSeconds;
    if ($currentTime >= $banEnd) {
        $unbanReady = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        logout();
        header("Location: /");
        exit();
    }
    if (isset($_POST['reactivate']) && $unbanReady) {
        $updateStmt = $pdo->prepare("UPDATE users SET isBanned = 0 WHERE userId = ?");
        $updateStmt->execute([$userId]);

        header("Location: /");
        exit();
    }
}

$banLengths = [
    'warn' => 0,
    'ban1' => 86400,
    'ban3' => 259200,
    'ban7' => 604800,
    'ban14' => 1209600,
    'delete' => 999999999,
    'poison' => 999999998,
    'macban' => 999999997,
];

$reverseBanLengths = array_flip($banLengths);

$banKey = $reverseBanLengths[$banLengthSeconds] ?? null;

$banTextMap = [
    'warn' => 'Warning',
    'ban1' => 'Banned for 1 day',
    'ban3' => 'Banned for 3 days',
    'ban7' => 'Banned for 7 days',
    'ban14' => 'Banned for 14 days',
    'delete' => 'Account Deleted',
    'poison' => 'Poison Banned',
    'macban' => 'MAC Banned',
];

if ($isWarning == 1) {
    $pageTitle = "Warning | Afterworld";
    $headingText = "Warning";
} else {
    $pageTitle = "Afterworld | Disabled Account";

    if ($banKey !== null && isset($banTextMap[$banKey])) {
        $headingText = $banTextMap[$banKey];
    } else {
        $headingText = "Banned";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css"/>
    <link rel="stylesheet" href="/CSS/Pages/Legacy/Navigation.css"/>
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,600,700" rel="stylesheet" type="text/css">
    <script type='text/javascript' src='https://js.rbxcdn.com/fbb8598e3acc13fe8b4d8c1c5b676f2e.js.gzip'></script>
    <script type="text/javascript" src="/rbxcdn_js/9715e76471ffacd5f6d9c24a5ab101ad.js"></script>
</head>
<body>
<div id="navContent" class="nav-content nav-no-left" style="margin-left: 0px; width: 100%;">
    <div class="nav-content-inner">
        <div class="container-main">
            <script type="text/javascript">
                if (top.location != self.location) {
                    top.location = self.location.href;
                }
            </script>
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
            <noscript>
                <div class="SystemAlert">
                    <div class="rbx-alert-info" role="alert">
                        Please enable Javascript to use all the features on this site.
                    </div>
                </div>
            </noscript>
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>
            <div id="BodyWrapper">
                <div id="RepositionBody">
                    <div id="Body" style="width:970px; justify-content: center;">
                        <div style="width: 500px; height: auto; border: 2px solid black; padding: 10px; margin-left: 25%;">
<h2 style="margin-top: 15px; margin-left: 15px;"><?= htmlspecialchars($headingText) ?></h2>



                                <p style="margin-top: 10px; margin-left: 15px;">
                                    Our content monitors have determined that your behavior at Afterworld has been in violation of our Terms of Service.
                                    We will terminate your account if you do not abide by the rules.
                                </p>

                            <p style="margin-top: 10px; margin-left: 15px;">Reviewed: <strong><?= htmlspecialchars($reviewDate) ?></strong></p>
                            <p style="margin-top: 10px; margin-left: 15px;">Moderator Note: <strong><?= htmlspecialchars($modNote) ?></strong></p>
                            <p style="margin-top: 10px; margin-left: 15px;">
                                Please abide by the <a href="https://devopstest1.aftwld.xyz/legal/terms">Afterworld Community Guidelines</a> so that Afterworld can be fun for everyone.
                            </p>
                            <p style="margin-top: 10px; margin-left: 15px;">If you wish to appeal, please join our <a href="#">Discord server</a>.</p>

                            <?php if ($isPermanentBan): ?>
    <p style="margin-top: 10px; margin-left: 15px;">
        Your account has been permanently terminated. You may log out and create a new account.
    </p>
<?php elseif ($unbanReady): ?>
    <form method="POST" style="margin-left: 15px;">
        <?php if ($isWarning === 1): ?>
            <center>
                <input type="checkbox" id="acceptTerms" onchange="document.getElementById('reactivateBtn').disabled = !this.checked;">
                <label for="acceptTerms"> I accept</label>
            </center>
        <?php endif; ?>
        <div style="text-align: center;">
            <button type="submit" name="reactivate" id="reactivateBtn"
                <?php if ($isWarning === 1) echo 'disabled'; ?>
                style="margin-top: 15px;">Re-activate My Account</button>
        </div>
    </form>
<?php else: ?>
    <p style="margin-top: 10px; margin-left: 15px;">
        You may not reactivate your account until the ban period is over.
    </p>
<?php endif; ?>
                            <div style="text-align: center;">
                                <form method="POST">
                                    <button type="submit" name="logout" style="margin-top: 15px; margin-bottom: 20px;">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
</body>
</html>


