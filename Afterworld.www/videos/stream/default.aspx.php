<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/classes/twitchHelpers.php';
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
$membership = 0;
if ($userId) {
    $stmt = $pdo->prepare('SELECT Membership FROM users WHERE UserId = :userId LIMIT 1');
    $stmt->execute([':userId' => $userId]);
    $membershipRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $membership = isset($membershipRow['Membership']) ? (int)$membershipRow['Membership'] : 0;
}

$stmt = $pdo->query("SELECT id, title, description, stream_url, updated_at FROM stream ORDER BY updated_at DESC");
$streams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AFTERWORLD - Streams</title>
    <link rel="stylesheet" href="/CSS/Base/CSS/main___52c69b42777a376ab8c76204ed8e75e2_m.css">
    <link rel="stylesheet" href="/CSS/Base/CSS/page___c7d63abcc3de510b8a7b8ab6d435f9b6_m.css">
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>

<div id="navContent" class="nav-content nav-no-left" style="margin-left: 0px; width: 100%;">
    <div class="nav-content-inner">
        <div class="container-main">
            <script>
                if (top.location !== self.location) {
                    top.location = self.location.href;
                }
            </script>
            <noscript>
                <div class="SystemAlert">
                    <div class="rbx-alert-info" role="alert">Please enable Javascript to use all the features on this site.</div>
                </div>
            </noscript>

            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>

            <div class="content">
                <div id="RepositionBody">
                    <div id="Body" style="width:970px;">
                        <h1>Streams</h1>
                        <p>We now support embedded Twitch livestreams with custom chat integration.</p>

                        <?php if (!empty($streams)): ?>
                            <?php foreach ($streams as $stream): ?>
                                <hr>
                                <div class="video-item">
	                			<span class="post-response-options" align="left">
                                    <a class="video-link" href="/videos/stream/watch.aspx?id=<?= (int)$stream['id'] ?>">
                                        <div class="video-title"><?= htmlspecialchars(getTwitchStreamTitle($stream['stream_url'] ?? 'Untitled', $stream['title'] ?? 'Untitled')) ?></div>
                                    </a>
                                    <div class="video-meta">
                                        Owned by <a href="<?= htmlspecialchars($stream['stream_url']) ?>"><strong><?= htmlspecialchars(getTwitchDisplayName($stream['stream_url'])) ?></strong></a> |
                                        <?= htmlspecialchars(getLiveViewers($stream['stream_url'])) ?> |
                                        <?= htmlspecialchars(getTwitchStartTime($stream['stream_url'] ?? 'N/A')) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No active streams right now.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
