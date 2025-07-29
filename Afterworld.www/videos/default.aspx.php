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

// this will fetch the videos from the database realalalalal
$stmt = $pdo->query("SELECT v.id, v.title, v.views, v.uploadedAt, v.uploaderId, u.Username FROM videos v JOIN users u ON v.uploaderId = u.UserId ORDER BY v.uploadedAt DESC");

$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$verifiedUsers = ['ROBLOX', 'Chloe', 'ExampleName'];
?>
<title>AFTERWORLD - Videos</title>
<link rel='stylesheet' href='/videos/default.css'/>
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
				<div id="ctl00_cphRoblox_OBCmember" class="blank-box" style="margin-top: 10px; width: 951px; float: left; padding: 8px">
												<div style="float: left; margin-right: 10px;">
													<img border="0" alt="OBC" title="Exclamation" src="/images/UI/error/exclamation.png" style="height: 32px; width: 32px;">
												</div>

												<div style="float: left; line-height: 30px;">
													<b>WARNING</b>: Please use https:// so that the css gets loaded. i will fix this very soon
												</div>
											</div>
				<h1>Videos</h1>
				<p>Afterworld now has a video section just like youtube, upload anything what you would like, but please follow the TOS!</p>
				<tr style="padding-bottom:5px;">
		<td valign="bottom" align="left">
		    <a id="ctl00_cphRoblox_ThreadView1_ctl00_NewThreadLinkTop" class="btn-control btn-control-medium verified-email-act" href="/videos/upload.aspx">
				Upload a video
			</a>
			<a id="ctl00_cphRoblox_ThreadView1_ctl00_NewThreadLinkTop" class="btn-control btn-control-medium verified-email-act" href="/videos/archive.aspx">
				Afterworld V1 Video Archive
			</a>
			<a id="ctl00_cphRoblox_ThreadView1_ctl00_NewThreadLinkTop" class="btn-control btn-control-medium verified-email-act" href="/videos/stream/default.aspx">
				Streams
			</a>
		</td>
		
	</tr>
				
<?php if (count($videos) > 0): ?>
    <?php foreach ($videos as $video): ?>
        <div class="video-list-card">
            <div class="video-thumb">
                <img alt="Video Thumbnail" src="https://placehold.co/280x158" />
                <span class="video-duration">N/A Not finished lol</span>
            </div>
            <div class="video-info">
                <a href="/videos/watch.aspx?id=<?= (int)$video['id'] ?>" class="video-title">
                    <?= htmlspecialchars($video['title']) ?>
                </a>
                <div class="video-meta">
                    <?= (int)$video['views'] ?> views • <?= date('F j, Y, g:i a', strtotime($video['uploadedAt'])) ?>
                </div>
                <div class="video-channel">
                    <img class="channel-avatar" src="https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=<?= (int)$video['uploaderId'] ?>&amp;x=200&amp;y=200&amp;mode=headshot" alt="User Avatar" />
                    <span class="channel-name">
                        <a href="/User.aspx?id=<?= (int)$video['uploaderId'] ?>">
                            <strong><?= htmlspecialchars($video['Username']) ?></strong>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No videos uploaded yet.</p>
<?php endif; ?>

				
				</div>
				</div>
				</div>