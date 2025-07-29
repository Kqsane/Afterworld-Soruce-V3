<?php
// user information
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	header("Location: /newlogin");
	exit();
}
$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if(!$info){
	logout();
	header("Location: /");
	exit();
}

$userId = $info['UserId'] ?? null;

if ($userId) {

    $stmt = $pdo->prepare('SELECT Membership FROM users WHERE UserId = :userId LIMIT 1');
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $membership = isset($row['Membership']) ? (int)$row['Membership'] : 0;
} else {
    $membership = 0;
}

$stmt = $pdo->query("SELECT v.id, v.title, v.views, v.uploadedAt, v.uploaderUsername FROM video_archive AS v");
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<title>AFTERWORLD - Video Archive</title>
<link rel='stylesheet' href='/CSS/Base/CSS/main___52c69b42777a376ab8c76204ed8e75e2_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___c7d63abcc3de510b8a7b8ab6d435f9b6_m.css' />
<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php';
	?>
<div id="navContent" class="nav-content  nav-no-left" style="margin-left: 0px; width: 100%;">
        <div class="nav-content-inner">
            <div class="container-main    ">
            <script type="text/javascript">
                if (top.location != self.location) {
                    top.location = self.location.href;
                }
            </script>
        <noscript><div class="SystemAlert"><div class="rbx-alert-info" role="alert">Please enable Javascript to use all the features on this site.</div></div></noscript>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>
        <div class="content  ">
                    <div id="RepositionBody">
                <div id="Body" style="width:970px;">
				<h1>Afterworld Video Archive</h1>
				<p>This is an archive of all videos that have been uploaded on Afterworld V1.</p>	
				<?php if (count($videos) > 0): ?>
            <?php foreach ($videos as $video): ?>
			<hr>
                <div class="video-item">
				<span class="post-response-options" align="left">
                    <a class="video-link" href="/videos/watcharchive.aspx?id=<?= (int)$video['id'] ?>">
                        <div class="video-title"><?= htmlspecialchars($video['title']) ?></div>
                    </a>
                    <div class="video-meta">
                        Uploaded by <strong><?= htmlspecialchars($video['uploaderUsername']) ?></strong> |
                        <?= (int)$video['views'] ?> views |
                        <?= date('F j, Y, g:i a', strtotime($video['uploadedAt'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No videos uploaded yet.</p>
        <?php endif; ?>
    </div>
				
				</div>
				</div>
				</div>