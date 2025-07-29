<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

$loggedInUser = null;
if (isset($_COOKIE["_ROBLOSECURITY"])) {
    $loggedInUser = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
}

$uid = $_GET['id'] ?? $_GET['ID'] ?? $_GET['Id'] ?? $_GET['iD'] ?? null;
if (empty($uid) && $loggedInUser) {
    $uid = (int)$loggedInUser['UserId'];
}

$FindUser = $pdo->prepare('SELECT * FROM users WHERE UserId = :uID');
$FindUser->bindParam("uID", $uid, PDO::PARAM_INT);
$FindUser->execute();
$row = $FindUser->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    header("Location: /RobloxDefaultErrorPage.aspx?mode=&code=404");
    exit();
}

$stmt = $pdo->prepare("SELECT membership FROM users WHERE userid = ?");
$stmt->execute([$uid]);
$membership = $stmt->fetchColumn();

function renderUserStatus(?int $userId = null): string
{
    global $pdo;
    if ($userId === null) {
        foreach ($_GET as $key => $value) {
            if (strcasecmp($key, 'id') === 0 && is_numeric($value)) {
                $userId = (int)$value;
                break;
            }
        }
    }
    if ($userId === null || $userId <= 0) {
        return offlineSpan();
    }
    $stmt = $pdo->prepare('SELECT status FROM users WHERE UserId = :userId LIMIT 1');
    $stmt->execute(['userId' => $userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || !is_numeric($row['status'])) {
        return offlineSpan();
    }
    $map = [
        1 => ['[ Online: Website ]', 'red'],
        2 => ['[ Online: In-Game ]', 'green'],
        3 => ['[ Online: Studio ]', 'orange'],
    ];
    $status = (int)$row['status'];
    if (!isset($map[$status])) {
        return offlineSpan();
    }
    [$text, $color] = $map[$status];
    return sprintf('<span id="ctl00_cphRoblox_rbxUserPane_lUserOnlineStatus" class="UserOnlineMessage" style="color: %s;">%s</span>', $color, htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

function offlineSpan(): string
{
    return '<span id="ctl00_cphRoblox_rbxUserPane_lUserOnlineStatus" class="UserOfflineMessage">[ Offline ]</span>';
}

$loggedIn = isset($_COOKIE['_ROBLOSECURITY']) && ($user = getuserinfo($_COOKIE['_ROBLOSECURITY']));
$from_id = $loggedIn ? (int)$user['UserId'] : 0;
$to_id = (int)($uid ?? 0);
$hasIdInUrl = isset($_GET['id']) || isset($_GET['ID']) || isset($_GET['Id']) || isset($_GET['iD']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_COOKIE['_ROBLOSECURITY'])) {
    $user = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    if ($user && isset($_POST['statusmessage'])) {
        $status = trim($_POST['statusmessage']);
        if (strlen($status) > 0 && strlen($status) <= 255) {
            $stmt = $pdo->prepare("UPDATE users SET statusmessage = :status WHERE UserId = :uid");
            $stmt->execute(['status' => $status, 'uid' => $user['UserId']]);
            $stmt = $pdo->prepare("INSERT INTO feeds (pid, content, PostedAt, isGroup) VALUES (:uid, :content, :timestamp, 0)");
            $stmt->execute(['uid' => $user['UserId'], 'content' => $status, 'timestamp' => time()]);
            header("Location: /User.aspx");
            exit;
        }
    }
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM friends WHERE (from_id = :id OR to_id = :id) AND type IN (1, 2)");
$stmt->execute(['id' => $to_id]);
$totalFriends = $stmt->fetchColumn();

$groupsPerPage = 12;
$page = isset($_GET['grouppage']) ? (int)$_GET['grouppage'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $groupsPerPage;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM GroupJoin WHERE UserID = ?");
$stmt->execute([$uid]);
$totalGroups = (int)$stmt->fetchColumn();
$totalPages = ceil($totalGroups / $groupsPerPage);
if ($totalPages < 1) $totalPages = 1;
if ($page > $totalPages) $page = $totalPages;

$stmt = $pdo->prepare("
    SELECT g.GID, g.Name
    FROM GroupJoin gj
    INNER JOIN `groups` g ON gj.GID = g.GID
    WHERE gj.UserID = :uid AND gj.Status = 'approved'
    ORDER BY gj.ID DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->bindValue(':limit', $groupsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

function cleanUsername($username) {
    return ltrim($username, '@');
}

$socials = [
    'roblox_link' => ['url' => 'https://www.roblox.com/users/profile?username=', 'icon_class' => 'roblox', 'label' => 'Roblox'],
    'youtube_link' => ['url' => 'https://youtube.com/@', 'icon_class' => 'youtube', 'label' => 'YouTube'],
    'twitter_link' => ['url' => 'https://twitter.com/', 'icon_class' => 'twitter', 'label' => 'Twitter'],
    'twitch_link' => ['url' => 'https://twitch.tv/', 'icon_class' => 'twitch', 'label' => 'Twitch'],
    'github_link' => ['url' => 'https://github.com/', 'icon_class' => 'github', 'label' => 'GitHub'],
];

$stmt = $pdo->prepare("SELECT Robux, Tix FROM users WHERE UserId = :uid");
$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$currencyRow = $stmt->fetch(PDO::FETCH_ASSOC);

$robux = (int)($currencyRow['Robux'] ?? 0);
$tix = (int)($currencyRow['Tix'] ?? 0);

?>

<?php
$stmt = $pdo->prepare("SELECT COUNT(*) FROM friends WHERE (from_id = :id OR to_id = :id) AND type IN (1, 2)");
$stmt->execute(['id' => $to_id]);
$totalFriends = $stmt->fetchColumn();
?>

<?php
$groupsPerPage = 12;
$page = isset($_GET['grouppage']) ? (int)$_GET['grouppage'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $groupsPerPage;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM GroupJoin WHERE UserID = ?");
$stmt->execute([$uid]);
$totalGroups = (int)$stmt->fetchColumn();
$totalPages = ceil($totalGroups / $groupsPerPage);
if ($totalPages < 1) $totalPages = 1;
if ($page > $totalPages) $page = $totalPages;

$stmt = $pdo->prepare("
    SELECT g.GID, g.Name
    FROM GroupJoin gj
    INNER JOIN `groups` g ON gj.GID = g.GID
    WHERE gj.UserID = :uid AND gj.Status = 'approved'
    ORDER BY gj.ID DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->bindValue(':limit', $groupsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM users WHERE UserId = ?");
$stmt->execute([$uid]);
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
?>






<!DOCTYPE html>
<html xmlns:fb="https://www.facebook.com/2008/fbml">
<head id="ctl00_Head1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true"/>
	<meta http-equiv="Content-Language" content="en-us"/>
	<title>
		<?php echo htmlspecialchars($row['Username']); ?> - AFTERWORLD
	</title>
	<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico"/>
	<meta name="author" content="ROBLOX Corporation"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta id="ctl00_metadescription" name="description" content=<?php echo "\"View {$row['Username']}'s profile on AFTERWORLD.  AFTERWORLD is the place for free games online, where people like {$row['Username']} imagine, build, and share their creations with their friends in a kid-safe environment.  There are millions of free games on AFTERWORLD.  3 of them are {$row['Username']}'s pics on ROBLOX for best free games.  {$row['Username']} is the creator of 27 free games.  Visit AFTERWORLD now to play {$row['Username']}'s free games and discover thousands of others!\""; ?>/>
	<meta id="ctl00_metakeywords" name="keywords" content="free games, online games, building games, virtual worlds, free mmo, gaming cloud, physics engine"/>
	<link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css"/>
	<link rel="stylesheet" href="/CSS/Base/CSS/page___6d7bcbdfd9dfa4d697c4e627e71f4fc1_m.css"/>
	<link rel="stylesheet" href="/CSS/Base/CSS/SocialLink.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="/rbxcdn_js/7e92c5bd1a834d8d927b9e3456f1ea02.js"></script>
	<div id="roblox-linkify" data-enabled="true" data-regex="(https?\:\/\/)?(?:www\.)?([a-z0-9\-]{2,}\.)*((m|de|www|web|api|blog|wiki|help|corp|polls|bloxcon|developer)\.roblox\.com|robloxlabs\.com)((\/[A-Za-z0-9-+&amp;@#\/%?=~_|!:,.;]*)|(\b|\s))" data-regex-flags="gm"></div>
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
	<script src="/rbxcdn_js/b473a9e6a3d2f591d08f37b64e6fa9ca.js"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js"></script>
	<script src="/rbxcdn_js/de8fbc4295a64a1d99f7f1bc5d2c7640.js"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js"></script>
	<script src="/rbxcdn_js/37f1a55cce7b44e3b0d920db124f681f.js"></script>
	<script src="/rbxcdn_js/4c42bda98e20213d2da09f4953dc9332.js"></script>
	<script src="/rbxcdn_js/8a5d7f0c9f0a4c6d87144be7ec015d81.js"></script>
	<script src="/rbxcdn_js/f5b3ec0a0a65426ca9a4b06445e9bcf3.js"></script>
	<script src="/rbxcdn_js/a2144683b28c827b54bffdcff692386a.js"></script>
	<script src="/rbxcdn_js/1e8e9d84f7f748d289e2a10b8f9b4ee2.js"></script>
	<script src="https://tap-cdn.rubiconproject.com/partner/scripts/rubicon/dorothy.js?pc=9874/18868"></script>
	<script src="/rbxcdn_js/34c71f29dbe548fda73cbfa5bf3ae94d.js"></script>
	<script src="https://tap-cdn.rubiconproject.com/partner/scripts/rubicon/dorothy.js?pc=9874/18868"></script>
	<script src="/rbxcdn_js/62c457eaf4084e1cb21463b09e5704c7.js"></script>
	<style>
		body {
		  margin: 0;
		  min-height: 100vh;
		  <?php if ($membership === 3): ?>
		  background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
					  url('http://devopstest1.aftwld.xyz/images/obcbackground.png');
		  background-size: cover;
		  background-repeat: no-repeat;
		  background-position: center;
		  background-attachment: fixed;
		  <?php endif; ?>
		}
	</style>
</head>

<body class="">
	<script src="/rbxcdn_js/9d2ab0e53cd8474ca27f3821e47ed8ae.js"></script>
	<script src="/rbxcdn_js/c8e4ad7fbbdb46b98b3d35c9054c1d21.js"></script>
	<form name="aspnetForm" method="post" action="https://devopstest1.aftwld.xyz/user.aspx?ID=1" id="aspnetForm" class="nav-container no-gutter-ads">
		<div>
			<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="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"/>
		</div>

		<script src="/ScriptResource.axd?d=9m0YvDyK5Zjy8u9fetfcFPnckfsYXv8oXxQat_uhbJHr8w8yA4unksFVcxm0Gzk4CgjQDgJU60gJ4hk1mfqa5beSwiaZtDvlUyQ9TsN3D0aGcEpzVc9ufpHTyn9KGe55sAKCo_jB0ROVw08zmtfO9-IuKJFmK-wKJt6TnIz1pymgCoeWX5KBrNIz8ooT3wa23o9JW1FEyphoVdKX76mZEqjRdG2ac-mgb1vDEqzVnP2asEHP0kRDolVjAScOxLQfsL2MLbNMJek28-9UE1bAvWaP_FWdvnh_fBj7it5KhG4RcwpytikjyVKvnjkfgtEVyQpWp5OsSyZdzui6doge7VEPTcKZpf-FmJ_AAGTO5TGigSdF-4W7025W7N3AXlo8mm2Z56-XMjAgfDZKsvHRKe2ePZyr6z49Y2TsN9ZTwd_bMJDI0" type="text/javascript"></script>
		
		<div>
			<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="38D9001F"/>
			<input type="hidden" name="__VIEWSTATEENCRYPTED" id="__VIEWSTATEENCRYPTED" value=""/>
			<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="ZNgOpb+DggtJzWoa5YD7XI71HOUn67I2UbxA6hYZV7CFyxONnNZ4mlJwcivo3n2qo5/Fqf5sSIj+xWNti98KtQFpl3VsPZmnhytuowqi7HqVDzhW6ZShEj6riiYQOcu+WJnFrGEtZHZtBt/zbDlDeJnSfmt0GM4NpoJTELfcBa97LoPkf+VBdQ5V8OeY57o990/PBvoc9U+8SXjUhdG9WUq6/2TDUCOm6NT3US5bJUzFMT4Dqq5TFaj+Zml4R9EGuAUg11OLICa6ZmNYpC7xTosQtYX+iWhfvhCq2G5q+gG8ovaao+wb5tv6rEzHEupOMx8zPYVCO6iUbtDOkmEjO5qoRirMEghXKJq3mvlns8tvndP2xtH+Zho8rsGtYWGmtz87v0HaT1uY8mkAbvza9tOCeajeqmwgd6BdU2Hdvog9wbDZTcZg6S9U9cFkWCq0h81z5Q=="/>
		</div>

		<div id="fb-root">
		</div>

		<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>

        <div id="navContent" class="nav-content">
			<div class="nav-content-inner">
				<div id="MasterContainer">
					<script src="/rbxcdn_js/a1fcb032a6de4e2db97944c2ef0b8714.js"></script>
					<script src="/rbxcdn_js/b37f84e0d2f9474db3a1f092d605ba9c.js"></script>

					<div id="Container">
					</div>

					<div id="AdvertisingLeaderboard">
						<iframe allowtransparency="true" frameborder="0" height="110" scrolling="no" src="/userads/1/" width="728" data-js-adtype="iframead"></iframe>
					</div>

					<noscript>
						<div class="SystemAlert">
							<div class="SystemAlertText">
								Please enable Javascript to use all the features on this site.
							</div>
						</div>
					</noscript>

					<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>

					<div id="BodyWrapper">
						
						<div id="RepositionBody">
							<div id="Body" style="width:970px;">
								<style type="text/css">
									#Body {
										padding: 10px;
									}
								</style>

								<div>

									<?php if (isset($_GET['id']) || isset($_GET['ID']) || isset($_GET['Id']) || isset($_GET['iD'])): ?>
											
											<?php if ((int)$row['Membership'] === 1): ?>
												<div id="ctl00_cphRoblox_OBCmember" class="blank-box" style="margin-top: 10px; width: 951px; float: left; padding: 8px">
													<div style="float: left; margin-right: 10px;">
														<img border="0" alt="BC" title="BC member" src="/images/1de063c8fb4572a6934ee0a971cbd65f.png">
													</div>

													<div style="float: left; line-height: 30px;">
														You are viewing the profile of an <a href="/Upgrades/BuildersClubMemberships.aspx">Builders Club</a> member..
													</div>
												</div>
											<?php endif; ?>

											<?php if ((int)$row['Membership'] === 2): ?>
												<div id="ctl00_cphRoblox_OBCmember" class="blank-box" style="margin-top: 10px; width: 951px; float: left; padding: 8px">
													<div style="float: left; margin-right: 10px;">
														<img border="0" alt="TBC" title="TBC member" src="/images/f4b93e80d65a1c92cde7340f2986b7a1.png">
													</div>

													<div style="float: left; line-height: 30px;">
														You are viewing the profile of an <a href="/Upgrades/BuildersClubMemberships.aspx">Turbo Builders Club</a> member..
													</div>
												</div>
											<?php endif; ?>
											
											<?php if ((int)$row['Membership'] === 3): ?>
												<div id="ctl00_cphRoblox_OBCmember" class="blank-box" style="margin-top: 10px; width: 951px; float: left; padding: 8px">
													<div style="float: left; margin-right: 10px;">
														<img border="0" alt="OBC" title="OBC member" src="/images/9cb3b66f03c1129835cc9f78d6e4c423.png">
													</div>

													<div style="float: left; line-height: 30px;">
														You are viewing the profile of an <a href="/Upgrades/BuildersClubMemberships.aspx">Outrageous Builders Club</a> member..
													</div>
												</div>
											<?php endif; ?>
											
											<?php if ((int)$uid === 2): ?>
											<div id="ctl00_cphRoblox_OBCmember" class="blank-box" style="margin-top: 10px; width: 951px; float: left; padding: 8px">
												<div style="float: left; margin-right: 10px;">
													<img border="0" alt="OBC" title="Exclamation" src="/images/UI/error/exclamation.png" style="height: 32px; width: 32px;">
												</div>

												<div style="float: left; line-height: 30px;">
													This guy might be Jerking off.. Please look out and stay safe!
												</div>
											</div>
										<?php endif; ?>

										<?php if ((int)$uid === 3): ?>
											<div id="ctl00_cphRoblox_OBCmember" class="blank-box" style="margin-top: 10px; width: 951px; float: left; padding: 8px">
												<div style="float: left; margin-right: 10px;">
													<img border="0" alt="OBC" title="Exclamation" src="/images/UI/error/exclamation.png" style="height: 32px; width: 32px;">
												</div>

												<div style="float: left; line-height: 30px;">
													You are viewing the profile of an <a href="/Chloe/Idiot.aspx">Fucking idiot</a> developer..
												</div>
											</div>
										<?php endif; ?>

										<?php if ((int)$uid === 11): ?>
											<div id="ctl00_cphRoblox_NSCmember" class="blank-box" style="margin-top: 10px; width: 951px; float: left; padding: 8px">
												<div style="float: left; margin-right: 10px;">
													<img src="/images/9b1f4aec23c8d907a6d45e30b1796e2f.jpg" width="32" height="32" border="0" alt="NSB" title="NSB member"/>
												</div>

												<div style="float: left; line-height: 30px;">
													<b>BEWARE! WARNING:</b> This user is Neuro-Sama, which is a AI V-Tuber.
												</div>
											</div>
										<?php endif; ?>

										<?php if ((int)$uid === 12): ?>
											<div id="ctl00_cphRoblox_NSCmember" class="blank-box" style="margin-top: 10px; width: 951px; float: left; padding: 8px">
												<div style="float: left; margin-right: 10px;">
													<img src="/images/c27af18306d9b7e0f54e1ac9283db491.jpg" width="32" height="32" border="0" alt="NSB" title="NSB member"/>
												</div>

												<div style="float: left; line-height: 30px;">
													<b>BEWARE! WARNING:</b> This user is Evil Neuro, and she can break your bones.
												</div>
											</div>
										<?php endif; ?>

										<?php if (!$hasIdInUrl): ?>
											<div style="clear: both; margin: 0; padding: 0;"></div>
												<div id="ctl00_cphRoblox_rbxHeaderPane_statusBox" class="blank-box" style="width:951px; padding: 8px;word-wrap: break-word;display:block;">
													<span style="font-size:12px;color: #888;word-wrap: normal;">
														Right now I'm: 
													</span>
													&nbsp;&nbsp;
													
													<span id="ctl00_cphRoblox_rbxHeaderPane_statusRegion" style="font-size:14px;" class="notranslate">
														<i>
															<?php $status = htmlspecialchars($row['statusmessage'] ?? ''); echo '<i>"' . ($status ?: 'No status set.') . '"</i>'; ?>
														</i>
													</span>
													
													&nbsp;&nbsp;
											
													<a href="UserControls/#" id="ctl00_cphRoblox_rbxHeaderPane_updateStatusLink" style="font-size:14px;word-wrap:normal;display:block;" onclick="document.getElementById('updateStatusBox').style.display='block';document.getElementById('ctl00_cphRoblox_rbxHeaderPane_updateStatusLink').style.display='none'; return false;">
														&gt; Update My Status
													</a>
											
													<form method="POST" action="/User.aspx?submenu=true">
														<div id="updateStatusBox" style="display:none;">
															<input type="text" style="visibility:hidden;position:absolute">
																<span style="position:relative">
																	<input name="statusmessage" type="text" id="ctl00_cphRoblox_rbxHeaderPane_txtStatusMessage" style="margin-bottom:5px;width:560px;height:17px;" maxlength="254" value="<?php $status = htmlspecialchars($row['statusmessage'] ?? ''); ?>">&nbsp;&nbsp;
																</span>
															<input type="submit" name="ctl00$cphRoblox$rbxHeaderPane$btnUpdateStatus" value="Save" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl00$cphRoblox$rbxHeaderPane$btnUpdateStatus&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, false))" id="ctl00_cphRoblox_rbxHeaderPane_btnUpdateStatus" class="translate">&nbsp;<input type="button" value="Cancel" onclick="document.getElementById('updateStatusBox').style.display='none';document.getElementById('ctl00_cphRoblox_rbxHeaderPane_updateStatusLink').style.display='inline';" <br="">
														</div>
													</form>
												</div>
										<?php endif; ?>
									<?php endif; ?>

									<div class="divider-right" style="width: 484px; float: left">
										<h2 class="title" style="display: flex; justify-content: space-between; align-items: center;">
											<span id="ctl00_cphRoblox_rbxUserPane_lUserRobloxURL"><?php echo htmlspecialchars($row['Username']) ?>'s Profile</span>

											<div class="user-social-links">
												<div class="user-social-links">
													<?php foreach ($socials as $field => $info): ?>
														<?php if (!empty($row[$field])): ?>
															<?php $username = cleanUsername($row[$field]); ?>
															<a title="<?= htmlspecialchars($info['label']) ?>" class="connectionLink-0-2-230" href="<?= htmlspecialchars($info['url'] . $username) ?>" target="_blank" rel="noopener noreferrer">
																<span class="social-link-icon <?= htmlspecialchars($info['icon_class']) ?>"></span>
															</a>
														<?php endif; ?>
													<?php endforeach; ?>
												</div>
											</div>
										</h2>

										<div class="divider-bottom" style="position: relative;z-index:3;padding-bottom: 20px">
											<div style="width: 100%">
												<div id="ctl00_cphRoblox_rbxUserPane_onlineStatusRow">
													<div style="text-align: center;">
														<?php
														$hasIdInUrl = isset($_GET['id']) || isset($_GET['ID']) || isset($_GET['Id']) || isset($_GET['iD']);
														if ($hasIdInUrl) {
															echo renderUserStatus($uid);
														} elseif (isset($loggedInUser['UserId'])) {
															$myId = (int)$loggedInUser['UserId'];
															echo '<a href="/User.aspx?id=' . $myId . '">(View Public Profile)</a>';
														}
														?>
													</div>
												</div>

												<div>
													<div>
														<div class="UserPaneContainer">
															<div style="margin-bottom: 10px;">
															</div>

															<div id="UserAvatar" class="thumbnail-holder" data-3d-thumbs-enabled data-url="https://devopstest1.aftwld.xyz/thumbnail/user-avatar?userId=1&amp;thumbnailFormatId=124&amp;width=352&amp;height=352" style="width:352px; height:352px;">
																<span class="thumbnail-span" data-3d-url="/avatar-thumbnail-3d/json?userId=<?php echo intval($uid); ?>" data-js-files="https://aftwld.xyz/rbxcdn_js/2cdabe2b5b7eb87399a8e9f18dd7ea05.js"><img alt="<?php echo $row['Username']; ?>" class="" <?php echo'src="/Thumbs/Avatar.ashx?userId='.$row['UserId'].'"'; ?>/></span>
																<img class="user-avatar-overlay-image" src=/Thumbs/BCOverlay.ashx?Username=<?php echo htmlspecialchars($row['Username']); ?>" alt=""/>
																<span class="enable-three-dee btn-control btn-control-small"></span>
															</div>

															<br/>

															<div class="PointsContainer">
																<img class="points-image" src="/images/d73731e112f8a06ce3978d7755b2ab8d.png" alt="User Points"/>
																	<span class="points-text">
																	Player Points: 
																	<span class="roblox-se-player-points tooltip-bottom" title="NaN">
																	NaN
																	</span>
																</span>
															</div>

															<div id="ctl00_cphRoblox_rbxUserPane_PrimaryGroupContainer" style="margin-top:10px;font-size:10px">
																<div>
																	<b>
																		Clan:
																	</b>

																	<br/>

																	<a id="ctl00_cphRoblox_rbxUserPane_PrimaryGroupAssetImage" title="Afterworld" href="/Groups/Group.aspx?gid=1" style="display:inline-block;height:42px;width:42px;cursor:pointer;">
																		<img src="/Thumbs/Group.ashx?gid=1&width=42&height=42" height="42" width="42" border="0" onerror="return Roblox.Controls.Image.OnError(this)" alt="Afterworld"/>
																	</a>

																	<br/>
																	<a id="ctl00_cphRoblox_rbxUserPane_HyperLink1" href="/Groups/Group.aspx?gid=1">
																		Afterworld
																	</a>
																</div>
															</div>
															
															<?php if ($hasIdInUrl): ?>
															<div class="UserBlurb linkify" style="margin-top: 10px; overflow-y: auto; max-height: 450px; text-align: center;">
																<?php echo htmlspecialchars($row['Description']); ?>
															</div>
															<?php endif; ?>

															<div id="ProfileButtons" style="margin:10px auto; width: 250px; text-align: center; justify-content: center;">
																<?php if ($hasIdInUrl): ?>
																	<div class="SendMessageProfileBtnDiv">
																		<a id="MessageButton" style="margin: 0 5px;" class="btn-control btn-control-large" href="/My/NewMessage.aspx?RecipientID=<?= $to_id ?>">
																			Send Message
																		</a>
																	</div>

																	<?php if ($from_id && $to_id && $from_id !== $to_id): ?>

																	<a id="FriendButton" class="btn-control btn-control-large disabled">
																		Loading...
																	</a>

																	<script src="/rbxcdn_js/f6e40a918a8e479fb24c4cb3cf169a53.js"></script>
																	<?php endif; ?>
																<?php endif; ?>
																<div class="clear">
																</div>

																<div style="display:none" class="status-error">
																</div>

																<script src="/rbxcdn_js/d3b2fe609c26474697ad4f35f9c31ff8.js"></script>

															</div>

															<?php if (!$hasIdInUrl): ?>
																<div class="ProfileAlertPanel" style="margin: 15px auto 0px auto; width: auto;">
																	<div id="ctl00_cphRoblox_rbxUserPane_Alerts1_AlertSpacePanel">
																		<div class="SmallHeaderAlertSpaceLeft">
																			<div class="AlertSpace">
																				<div class="MessageAlert">
																					<a id="ctl00_cphRoblox_rbxUserPane_Alerts1_MessageAlertCaptionHyperLink" class="MessageAlertCaption tooltip-bottom" href="/my/messages" title="Inbox">
																						?
																					</a>
																				</div>

																				<div class="FriendsAlert">
																					<a id="ctl00_cphRoblox_rbxUserPane_Alerts1_FriendsAlertCaptionHyperLink" class="FriendsAlertCaption tooltip-bottom" href="Friends.aspx" title="Friend Requests">
																						?
																					</a>
																				</div>

																				<div class="RobuxAlert">
																					<a id="ctl00_cphRoblox_rbxUserPane_Alerts1_RobuxAlertCaptionHyperLink" class="RobuxAlertCaption tooltip-bottom" href="My/Money.aspx?tab=MyTransactions" title="ROBUX">
																						<?= htmlspecialchars($robux) ?>
																					</a>
																				</div>

																				<div class="TicketsAlert">
																					<a id="ctl00_cphRoblox_rbxUserPane_Alerts1_TicketsAlertCaptionHyperLink" class="TicketsAlertCaption tooltip-bottom" href="My/Money.aspx?tab=MyTransactions" title="Tickets">
																						<?= htmlspecialchars($tix) ?>
																					</a>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															<?php endif; ?>

															<div style="margin-right: 20px">
																
															</div>
															<?php
															$getUserStmt = $pdo->prepare('SELECT previousUsernames FROM users WHERE UserId = :uid');
															$getUserStmt->execute(['uid' => $row['UserId']]);
															$user = $getUserStmt->fetch(PDO::FETCH_ASSOC);
															if ($user && !empty($user['previousUsernames'])) {
																$previousUsernames = json_decode($user['previousUsernames'], true);
																if (json_last_error() === JSON_ERROR_NONE && is_array($previousUsernames) && !empty($previousUsernames)) {
																	$usernamesReversed = array_reverse($previousUsernames);
																	$tooltip = 'Previous usernames: ' . implode(', ', $usernamesReversed);
																	echo '<div id="ctl00_cphRoblox_rbxUserPane_PreviousUserNames" style="text-align:center;">';
																	echo 'This user has changed usernames. ';
																	echo '<img class="tooltip-bottom" style="cursor:pointer;" src="http://images.rbxcdn.com/d3246f1ece35d773099f876a31a38e5a.png" title="' . htmlspecialchars($tooltip) . '"/>';
																	echo '</div>';
																}
															}
															?>
														</div>
													</div>
												</div>
											</div>
										</div>

<h2 class="title">
<span>AFTERWORLD Badges</span>
</h2>

<div class="divider-bottom" style="padding-bottom: 20px">
    <div style="display: inline-block; position: relative; left: -8px;">
	    <table id="ctl00_cphRoblox_rbxUserBadgesPane_dlBadges" cellspacing="0" align="Left" border="0" style="border-collapse:collapse;">
	<tr>

<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
$userId = 0;
if (isset($_GET['ID']) || isset($_GET['id']) || isset($_GET['Id']) || isset($_GET['iD'])) {
    $userId = (int)($_GET['ID'] ?? $_GET['id'] ?? $_GET['Id'] ?? $_GET['iD']);
} elseif (isset($_COOKIE['_ROBLOSECURITY'])) {
    $userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    if (is_array($userInfo)) {
        $userId = (int)$userInfo['UserId'];
    }
}
if ($userId <= 0) {
    echo "<p><span>User not found.</span></p>";
}
$stmt = $pdo->prepare("SELECT username, aftwld_badges FROM users WHERE UserId = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo "<p><span>User not found.</span></p>";
}
$username = htmlspecialchars($user['username'] ?? 'Unknown');
$badges = json_decode($user['aftwld_badges'] ?? '[]', true);
if (!is_array($badges) || empty($badges)) {
    echo "<p><span>$username does not have any AFTERWORLD badges.</span></p>";
}
include $_SERVER['DOCUMENT_ROOT'] . "/config/badges.php";
echo "<table><tr>";
$col = 0;
foreach ($badges as $id) {
    if (!isset($badgeMap[$id])) continue;
    $safeName = htmlspecialchars($badgeMap[$id]['name'], ENT_QUOTES);
    $safeImg  = htmlspecialchars($badgeMap[$id]['img'],  ENT_QUOTES);
    echo <<<HTML
<td valign="top">
  <div class="Badge notranslate">
    <div class="BadgeImage">
      <a href="Badges.aspx#Badge$id">
        <img src="$safeImg" alt="$safeName Badge" style="height:75px;width:75px;border-width:0;" />
      </a>
    </div>
    <div class="BadgeLabel">
      <a href="Badges.aspx#Badge$id">$safeName</a>
    </div>
  </div>
</td>
HTML;
    $col++;
    if ($col % 5 === 0) echo "</tr><tr>";
}
if ($col % 5 !== 0) {
    echo str_repeat("<td></td>", 5 - ($col % 5));
}
echo "</tr></table>";
?>
    </div>
</div>

<?php
$getGamesStmt = $pdo->prepare('SELECT * FROM assets WHERE CreatorID = :uid AND AssetType = 9');
$getGamesStmt->execute(['uid' => $row['UserId']]);
$fetchedGames = $getGamesStmt->fetchAll(PDO::FETCH_ASSOC);
$visits = 0;
foreach ($fetchedGames as $game) {
    $visits += (int)$game["Visits"];
}

$badges = $userRow['aftwld_badges'];
$membership = (int)$userRow['Membership'];
$KOs = (int)$userRow['KOs'];

$badgeList = json_decode($badges, true);
if (!is_array($badgeList)) $badgeList = [];

function giveBadge(&$badgeList, $id) {
    if (!in_array($id, $badgeList)) $badgeList[] = $id;
}

if ($membership !== 0) {
    giveBadge($badgeList, 18);
    if ($membership === 1) giveBadge($badgeList, 11);
    if ($membership === 2) giveBadge($badgeList, 15);
    if ($membership === 3) giveBadge($badgeList, 16);
}

if ($totalFriends >= 20) {
    giveBadge($badgeList, 2);
}

if ($KOs > 10) giveBadge($badgeList, 3);
if ($KOs > 100) giveBadge($badgeList, 4);
if ($KOs > 250) giveBadge($badgeList, 5);

if ($visits > 100) giveBadge($badgeList, 6);
if ($visits > 1000) giveBadge($badgeList, 7);

$badgeJson = json_encode($badgeList);

$update = $pdo->prepare("UPDATE users SET aftwld_badges = ? WHERE UserId = ?");
$update->execute([$badgeJson, $uid]);
?>

            



<style>
.statsLabel { font-weight:bold; width:200px; text-align:right; padding-right:10px;}
.statsValue { font-weight:normal; width:200px; text-align:left;}
.statsTable { width:400px; }
</style>
<h2 class="title"><span>Statistics</span></h2>

<div class="divider-bottom" style="padding-bottom: 20px">
<table class="statsTable">
    <tr>
<td class="statsLabel">
    <acronym title="How many friends this user has.">Friends</acronym>:
</td>
<td class="statsValue">
    <span id="ctl00_cphRoblox_rbxUserStatisticsPane_lFriendsStatistics"><?php echo $totalFriends ?></span>
</td>


<?php
$stmt = $pdo->prepare("SELECT COUNT(*) FROM forums WHERE UserId = ?");
$stmt->execute([$to_id]);
$totalForumPosts = (int)$stmt->fetchColumn();
?>

                
        <td class="statsLabel"><acronym title="The number of times this user's place has been visited.">Place Visits</acronym>:</td>
        <td class="statsValue"><span id="ctl00_cphRoblox_rbxUserStatisticsPane_lPlaceVisitsStatistics" class="notranslate"><?php echo number_format($visits); ?></span></td>
    </tr>
    <tr>
        <td class="statsLabel"><acronym title="The number of times this user's character has destroyed another user's character in-game.">Knockouts</acronym>:</td>
        <td class="statsValue"><span id="ctl00_cphRoblox_rbxUserStatisticsPane_lKillsStatistics" class="notranslate"><?php echo $row['KOs']; ?></span></td>

        <td class="statsLabel"><acronym title="The number of posts this user has made to the AFTERWORLD forum.">Forum Posts</acronym>:</td>
        <td class="statsValue"><span id="ctl00_cphRoblox_rbxUserStatisticsPane_lForumPostsStatistics" class="notranslate"><?php echo $totalForumPosts; ?></span></td>
    </tr>	
    <tr>
    </tr>
    <tr>
        <td class="statsLabel"><acronym title="How many followers this user has.">Followers</acronym>:</td>
        <td class="statsValue"><span id="ctl00_cphRoblox_rbxUserStatisticsPane_lFollowersStatistics">0</span></td>
        
    </tr>
</table>    
</div>
            

<div class="divider-bottom" style="padding-bottom: 20px">
    <div>
        <h2 class="title" style="display:block;float: left;">
            <span class="notranslate"><?php echo htmlspecialchars($row['Username']); ?></span>'s Sets
        </h2>
        <a data-js-my-button href class="btn-small btn-neutral" id="ToggleBetweenOwnedSubscribedSets" style="float: right; margin-right: 20px; margin-top: 25px" onclick="Roblox.SetsPaneObject.toggleBetweenSetsOwnedSubscribed();return false;">View Subscribed<span class="btn-text" id="SetsToggleSpan">View Subscribed</span></a>
        <div class="clear"></div>
    </div>
    <div id="OwnedSetsContainerDiv" style="padding-bottom:0;">
        <div id="SetsItemContainer" style="margin-bottom: 30px; margin-left: 15px"></div>
        <div style="clear:both;"></div>
        <div class="SetsPager_Container" style="position: relative">
            <div id="SetsPagerContainer"></div>
        </div>
    </div>
    <div id="SubscribedSetsContainerDiv" style="display:none; padding-bottom: 0px">
        <div id="SubscribedSetsItemContainer" style="margin-bottom: 30px; margin-left: 15px"></div>
        <div style="clear:both;"></div>
        <div class="SetsPager_Container" style="position: relative">
            <div id="SubscribedSetsPagerContainer"></div>
        </div>
    </div>
    
    <div id="SetsPaneItemTemplate" style="display:none;">
        <div class="AssetThumbnail">
            <img class="$ImageAssetID"></img>
        </div>
        <div class="AssetDetails">
            <div class="AssetName notranslate">
                <a href="https://devopstest1.aftwld.xyz/My/Sets.aspx?id=$ID">$Name</a>
            </div>
            <div class="AssetCreator">
                <span class="Label">Creator:&nbsp;</span>
                <span class="Detail">
                    <a href="https://devopstest1.aftwld.xyz/User.aspx?id=$CreatorUserID" class="notranslate">$CreatorName</a>
                </span>
            </div>
        </div>
    </div>
</div>

<div id="UserGroupsPane" style="clear: both;">
    <h2 class="title"><span>Groups</span></h2>
    <div style="clear:both; padding-bottom: 20px; padding-left: 30px;">
        <div id="ctl00_cphRoblox_rbxUserGroupsPane_ctl00">
            <?php if (empty($groups)): ?>
                <p>This user is not in any groups.</p>
            <?php else: ?>
                <?php foreach ($groups as $group): 
                    $groupID = (int)$group['GID'];
                    $groupName = htmlspecialchars($group['Name']);
                ?>
                    <div style="float: left; margin: 10px; width: 70px;">
                        <div class="groupEmblemThumbnail" style="width:70px; overflow:hidden; margin-bottom:5px;">
                            <div class="groupEmblemImage notranslate" style="width: 70px; height:72px;">
                                <a href="/Groups/Group.aspx?gid=<?php echo $groupID ?>" title="<?php echo $groupName ?>" style="display:inline-block; height:62px; width:60px;">
                                    <img src="/Thumbs/Group.ashx?gid=<?php echo $groupID ?>&width=60&height=62" height="62" width="60" border="0" alt="<?php echo $groupName ?>" onerror="this.src='/images/default_group.png';">
                                </a>
                            </div>
                        </div>
                        <div style="text-align:center; font-size:12px; font-weight:bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <a href="/Groups/Group.aspx?gid=<?php echo $groupID ?>" style="color:#333;"><?php echo $groupName ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div style="clear:both;"></div>
            <?php endif; ?>
        </div>

        <div id="ctl00_cphRoblox_rbxUserGroupsPane_GroupDataPagerFooter" style="margin-left: 125px; display: inline-block; margin-top: 20px;">
            <?php if ($page <= 1): ?>
                <input type="image" class="pager previous" src="/images/empty.png" alt="Previous" style="border-width:0px;" disabled="disabled">
            <?php else: ?>
                <a href="?id=<?php echo $uid ?>&grouppage=<?php echo $page - 1 ?>">
                    <input type="image" class="pager previous" src="/images/empty.png" alt="Previous" style="border-width:0px;">
                </a>
            <?php endif; ?>

            <span style="display: inline-block; padding: 5px; vertical-align: top;">
                Page <span><?php echo $page ?></span> of <span><?php echo $totalPages ?></span>
            </span>

            <?php if ($page >= $totalPages): ?>
                <input type="image" class="pager next" src="/images/empty.png" alt="Next" style="border-width:0px;" disabled="disabled">
            <?php else: ?>
                <a href="?id=<?php echo $uid ?>&grouppage=<?php echo $page + 1 ?>">
                    <input type="image" class="pager next" src="/images/empty.png" alt="Next" style="border-width:0px;">
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
			
<script type="text/javascript">
    if (typeof Roblox === "undefined") {
        Roblox = {};
    }

    $(function () {
        Roblox.SetsPaneObject = Roblox.SetsPane('https://devopstest1.aftwld.xyz/', 1);

        var options = { Paging_PageNumbers_AreLinks: false };
        Roblox.OwnedSetsJSDataPager = new DataPager(5, 9, 'SetsItemContainer', 'SetsPagerContainer',
            Roblox.SetsPaneObject.getSetsPaged, Roblox.SetsPaneObject.ownedItemFormatter, Roblox.SetsPaneObject.getSetAssetImageThumbnail, options
        );
        Roblox.SubscribedSetsJSDataPager = new DataPager(0, 9, 'SubscribedSetsItemContainer', 'SubscribedSetsPagerContainer',
            Roblox.SetsPaneObject.getSubscribedSetsPaged, Roblox.SetsPaneObject.subscribedItemFormatter, Roblox.SetsPaneObject.getSetAssetImageThumbnail, options
        );
    });
</script>

            
        </div>
        <div class="divider-left" style="width: 450px; float: left; position: relative; left: -1px">
            <div class="divider-bottom" style="padding-bottom: 20px; padding-left: 20px">
                <h2 class="title" style="float: left">
                    <span>Active Places</span>
                </h2>
                
                <div id="UserPlacesPane">
                    <div id="ctl00_cphRoblox_rbxUserPlacesPane_pnlUserPlaces">
	
<div id="UserPlaces" style="overflow: hidden">

    <div id="accordion" class="accordion">
    <?php
	$getGamesStmt = $pdo->prepare('SELECT * FROM assets where CreatorID = :uid AND AssetType = 9');
	$getGamesStmt->execute(['uid' => $row['UserId']]);
	$fetchedGames = $getGamesStmt->fetchAll(PDO::FETCH_ASSOC);
	if(count($fetchedGames) > 0){
		foreach ($fetchedGames as $index => $games) {
			if ($index === array_key_first($fetchedGames)) {
				echo '<div class="accord-section accord-section-open">';
			} else {
				echo '<div class="accord-section ">';
			}

			echo '
            <div class="accord-header notranslate">
                <div class="accord-arrow">&#x25b6;</div>
			    '.htmlspecialchars($games['Name']).'
            </div>
            <div class="accord-content notranslate">
			    

<div class="Place">
    
    <div class="PlayStatus">
        
<span class="PlaceAccessIndicator">
	<span id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_rbxPlaceAccessIndicator_FriendsOnlyLocked" style="display: none">
        <a class="iLocked tooltip" title="Friends Only"></a><span class="invisible">&nbsp;Friends-only</span>
	</span>
    <span id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_rbxPlaceAccessIndicator_FriendsOnlyUnlocked" style="display: none">
        <a class="iUnlocked tooltip" title="Friends Only - You are friends"></a><span class="invisible">&nbsp;Friends-only: You are friends</span>
	</span>
	<span id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_rbxPlaceAccessIndicator_ExpiredSelf" style="display: none">
        <a class="iLocked tooltip" title="Locked"></a>
        <span class="invisible">&nbsp;Your Outrageous Builders Club, Turbo Builders Club, or Builders Club membership has expired, so you can
        only have one open place. Your places will not be deleted, and you can <a id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_rbxPlaceAccessIndicator_RBXLDownloadLink">download the RBXL here.</a> To unlock all of your places,
        please <a href="https://devopstest1.aftwld.xyz/upgrades/BuildersClubMemberships.aspx">re-order Outrageous Builders Club, Turbo Builders Club, or Builders
            Club </a>.<br/></span>
    </span>
    <span id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_rbxPlaceAccessIndicator_ExpiredOther" style="display: none">
        <a class="iLocked tooltip" title="Locked"></a>
        <span class="invisible">This place is locked because the creator\'s <a href="https://devopstest1.aftwld.xyz/upgrades/BuildersClubMemberships.aspx">Builders
            Club / Turbo Builders Club / Outrageous Builders Club </a>has expired.
		</span>
	</span>	
</span>
	
    </div>
    <br>
    <div class="Statistics" style="color: #999; font-size: 14px; letter-spacing: normal">
        <span id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_lStatistics">Click <b>into the thumbnail</b> to see more information.</span></div>
    <div class="Thumbnail" style="overflow:hidden;position: relative;">
        <a id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_rbxPlaceThumbnail" title="'.htmlspecialchars($games['Name']).'" href="https://devopstest1.aftwld.xyz/games/'.$games['AssetID'].'/Game" style="display:inline-block;height:230px;width:420px;cursor:pointer;"><img src="/Thumbs/Asset.ashx?assetId='.$games['AssetID'].'" height="230" width="420" border="0" onerror="return Roblox.Controls.Image.OnError(this)" alt="'.htmlspecialchars($games['AssetID']).'"/></a>
        
    </div>
    <div id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_pDescription">
		
        <div class="Description linkify" style="overflow-y: auto; max-height: 160px; font-family: arial; color: #666; font-size: 12px;">
            <span id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_lDescription">'.htmlspecialchars($games['Description']).'</span>
        </div>
    
	</div>
	

    <div class="PlayOptions" style="display:block">
        
        <div class="VisitButtonContainer" data-item-id="'.$games['AssetID'].';">
            
        <div class="VisitButtonsLeft VisitButtonPlayAnyBCLevel ">
            
            <a class="btn-medium btn-primary" id="Play" data-placeid="'.$games['AssetID'].';" href="#">Play</a>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const playBtn = document.getElementById("Play");

    playBtn.addEventListener("click", function (e) {
        e.preventDefault(); // Stop the "#" from jumping
        const placeId = this.getAttribute("data-placeid");
        window.location.href = "/games/start?placeid='.$games['AssetID'].';" + encodeURIComponent(placeId);
    });
});
</script>

            
           <!-- <div id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_rbxVisitButtons_MultiplayerVisitButton" data-action="play" class="VisitButton VisitButtonPlay" placeid="'.$games['AssetID'].';" data-is-membership-level-ok="true">
                <a class="btn-medium btn-primary" href"/">Play</a>
            </div>  
            
           <div id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePlaces_ctl02_rbxPlatform_rbxVisitButtons_EditButton" class="VisitButton VisitButtonEdit" placeid="65033" data-allowupload="False" data-universeid="0">
                <a title="Open in Studio Mode" class="btn-medium btn-primary tooltip">Edit</a>
            </div> -->
            
        </div>
    

<script type="text/javascript">
    var play_placeId = '.$games['AssetID'].';
    function redirectPlaceLauncherToLogin() {
        location.href = "/login/default.aspx?ReturnUrl=" + encodeURIComponent("/user.aspx?id=1");
    }
    function redirectPlaceLauncherToRegister() {
        location.href = "/login/NewAge.aspx?ReturnUrl=" + encodeURIComponent("/user.aspx?id=1");
    }
    function fireEventAction(action) {
        RobloxEventManager.triggerEvent(\'rbx_evt_popup_action\', { action: action });
    }
</script>


<div id="BCOnlyModal" class="modalPopup unifiedModal smallModal" style="display:none;">
 	<div style="margin:4px 0px;">
        <span>Builders Club Only</span>
    </div>
    <div class="simplemodal-close">
        <a class="ImageButton closeBtnCircle_20h" style="margin-left:400px;"></a>
    </div>
    <div class="unifiedModalContent" style="padding-top:5px; margin-bottom: 3px; margin-left: 3px; margin-right: 3px">
        <div class="ImageContainer">
            <img class="GenericModalImage BCModalImage" alt="Builder\'s Club" src="https://aftwld.xyz/rbxcdn_img/ae345c0d59b00329758518edc104d573.png"/>
            <div id="BCMessageDiv" class="BCMessage Message">
                Builders Club membership is required to play in this place.
            </div>
        </div>
        <div style="clear:both;"></div>
        <div style="clear:both;"></div>
        <div class="GenericModalButtonContainer" style="padding-bottom: 13px">
            <div style="text-align:center">
                <a id="BClink" href="https://devopstest1.aftwld.xyz/Upgrades/BuildersClubMemberships.aspx" class="btn-primary btn-large">Upgrade Now</a>
            </div>
            <div style="clear:both;"></div>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>

<script type="text/javascript">
    function showBCOnlyModal(modalId) {
        var modalProperties = { overlayClose: true, escClose: true, opacity: 80, overlayCss: { backgroundColor: "#000" } };
        if (typeof modalId === "undefined")
            $("#BCOnlyModal").modal(modalProperties);
        else
            $("#" + modalId).modal(modalProperties);
    }
    $(document).ready(function () {
        $(\'#VOID\').click(function () {
            showBCOnlyModal("BCOnlyModal");
            return false;
        });
    });
</script>
 

<div class="GenericModal modalPopup unifiedModal smallModal" style="display:none;">
    <div class="Title"></div>
    <div class="GenericModalBody">
        <div>
            <div class="ImageContainer roblox-item-image" data-image-size="small" data-no-overlays data-no-click>
                <img class="GenericModalImage" alt="generic image"/>
            </div>
            <div class="Message"></div>  
            <div style="clear:both"></div>
        </div>
        <div class="GenericModalButtonContainer">
            <a class="ImageButton btn-neutral btn-large roblox-ok">OK<span class="btn-text">OK</span></a> 
        </div>  
    </div>
</div>



        </div>
    </div>
</div>

			    
            </div>
        </div>
		';
		}
	}else{
		echo "<p>".htmlspecialchars($row['Username'])." does not have any games.</p>";
	}
	?>

    </div>
    



<!--	<div id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcaseFooter" class="PanelFooter" style="margin-top:5px;margin-left:20px;padding:3px;">
		
	    <div id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePager_PanelPages" style="text-align:center;">
			
     <a id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePager_LinkButtonFirst" class="pager previous disabled"></a>
     
    <span class="PageNumbers" style="vertical-align: top; display: inline-block; padding: 5px; padding-top: 6px">Page 1 of 2</span>
     
    <a id="ctl00_cphRoblox_rbxUserPlacesPane_ShowcasePager_LinkButtonNext" class="pager next" href="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl00$cphRoblox$rbxUserPlacesPane$ShowcasePager$LinkButtonNext&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, true))"></a>
    

		</div>
	    
	
	</div>
	 -->
 </div>
 
</div>

 
 <div class="ItemPurchaseAjaxContainer">
    

<div id="ItemPurchaseAjaxData" data-authenticateduser-isnull="True" data-user-balance-robux="0" data-user-balance-tickets="0" data-user-bc="0" data-continueshopping-url="" data-imageurl="" data-alerturl="https://aftwld.xyz/rbxcdn_img/cbb24e0c0f1fb97381a065bd1e056fcb.png" data-builderscluburl="https://aftwld.xyz/rbxcdn_img/ae345c0d59b00329758518edc104d573.png"></div>

    <div id="ProcessingView" style="display:none">
        <div class="ProcessingModalBody">
            <p style="margin:0px"><img src="https://aftwld.xyz/rbxcdn_img/ec4e85b0c4396cf753a06fade0a8d8af.gif" alt="Processing..."/></p>
            <p style="margin:7px 0px">Processing Transaction</p>
        </div>
    </div>
    
    <script type="text/javascript">
        //<sl:translate>
        Roblox.ItemPurchase.strings = {
            insufficientFundsTitle : "Insufficient Funds",
            insufficientFundsText : "You need {0} more to purchase this item.",
            cancelText : "Cancel",
            okText : "OK",
            buyText : "Buy",
            buyTextLower : "buy",
            tradeCurrencyText : "Trade Currency",
            priceChangeTitle : "Item Price Has Changed",
            priceChangeText : "While you were shopping, the price of this item changed from {0} to {1}.",
            buyNowText : "Buy Now",
            buyAccessText: "Buy Access",
            buildersClubOnlyTitle : "{0} Only",
            buildersClubOnlyText : "You need {0} to buy this item!",
            buyItemTitle : "Buy Item",
            buyItemText : "Would you like to {0} {5}the {1} {2} from {3} for {4}?",
            balanceText : "Your balance after this transaction will be {0}",
            freeText : "Free",
            purchaseCompleteTitle : "Purchase Complete!",
            purchaseCompleteText : "You have successfully {0} {5}the {1} {2} from {3} for {4}.",
            continueShoppingText : "Return to Profile",
            customizeCharacterText : "Customize Character",
            orText : "or",
            rentText : "rent",
            accessText: "access to "
        }
    //</sl:translate>
    </script>

</div>
 <script type="text/javascript">
     Roblox.require('Widgets.DropdownMenu', function (dropdown) {
         dropdown.InitializeDropdown();
     });
</script>

                </div>
            </div>
            <div style="padding-left: 20px" class="divider-bottom">
                

<div style="margin: 12px 0 20px; overflow:visible">
    <h2 style="float: left"><?php echo htmlspecialchars($row['Username']); ?>'s Friends</h2>
    <?php
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM friends WHERE (from_id = :id OR to_id = :id) AND type IN (1, 2)");
    $stmt->execute(['id' => $to_id]);
    $totalFriends = $stmt->fetchColumn();
    ?>
    <a data-js-my-button style="float: right" href="Friends.aspx?UserID=1" class="btn-small btn-neutral" id="HeaderButton">See All<span class="btn-text">See All <?= $totalFriends ?></span></a>
    
</div>
<div style="padding-top: 50px">
    
	
	<div id="ctl00_cphRoblox_rbxFriendsPane">
<?php
$user = isset($_COOKIE['_ROBLOSECURITY']) ? getuserinfo($_COOKIE['_ROBLOSECURITY']) : null;
$to_id = 0;
foreach (["id", "ID", "Id", "iD"] as $key) {
    if (isset($_GET[$key]) && is_numeric($_GET[$key])) {
        $to_id = (int)$_GET[$key];
        break;
    }
}
if ($to_id <= 0 && isset($user['UserId'])) {
    $to_id = (int)$user['UserId'];
}
if ($to_id <= 0) {
    echo "<p><span>User not found.</span></p>";
}
if (!$row) {
    echo "<p>User not found.</p>";
}
$stmt = $pdo->prepare("SELECT * FROM friends WHERE (from_id = :id OR to_id = :id) AND type IN (1, 2) LIMIT 6");
$stmt->execute(['id' => $to_id]);
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($friends) === 0) {
    echo '<p><span id="ctl00_cphRoblox_rbxFriendsPane_lNoResults">' . htmlspecialchars($row['Username']) . ' does not have any AFTERWORLD friends.</span></p>';
}
echo "<table id='ctl00_cphRoblox_rbxFriendsPane_dlFriends' cellspacing='0' align='Center' border='0' style='border-collapse:collapse;'><tr><td>";
$count = 0;
foreach ($friends as $friend) {
    $friendId = ($friend['from_id'] == $to_id) ? $friend['to_id'] : $friend['from_id'];
    $stmt = $pdo->prepare("SELECT Username, Membership, LastSeen FROM users WHERE UserId = ?");
    $stmt->execute([$friendId]);
    $friendUser = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$friendUser) continue;
    $membershipOverlay = '';
    if ($friendUser['Membership'] == 1) {
        $membershipOverlay = '/images/icons/overlay_bcOnly.png';
    } elseif ($friendUser['Membership'] == 2) {
        $membershipOverlay = '/images/icons/overlay_tbcOnly.png';
    } elseif ($friendUser['Membership'] == 3) {
        $membershipOverlay = '/images/icons/overlay_obcOnly.png';
    }
    $username = htmlspecialchars($friendUser['Username']);
    $profileUrl = "/User.aspx?ID={$friendId}";
    echo '<td>
        <div class="Friend notranslate">
            <div class="Avatar">
                <a title="' . $username . '" href="' . $profileUrl . '" style="display:inline-block;height:100px;width:100px;cursor:pointer;">
                    <img src="/Thumbs/Avatar.ashx?userId=' . $friendId . '" height="100" width="100" border="0" alt="' . $username . '" onerror="return Roblox.Controls.Image.OnError(this)" />';
    if ($membershipOverlay) {
        echo '<img src="' . "/Thumbs/BCOverlay.ashx?Username=" . $username . '" class="bcOverlay" align="left" style="position:relative;top:-19px;" />';
    }
    echo '</a>
            </div>
            <div class="Summary">
                <span class="OnlineStatus"><img src="/images/offline.png" alt="' . $username . ' is offline." style="border-width:0px;" /></span>
                <span class="Name"><a href="' . $profileUrl . '">' . $username . '</a></span>
            </div>
        </div>
    </td>';

    $count++;
    if ($count % 3 === 0) {
        echo "</tr><tr><td>";
    }
}
echo "</tr></table>";
?>


	
</div>
</div>

            </div>
            

<div class="divider-bottom" style="padding-left: 20px; padding-bottom: 20px">
    <div id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesPane">
	
	        <div style="overflow: auto">
                <h2 class="title" style="float: left">Favorites</h2>
                <div class="PanelFooter" style="float: right;">
			        Category:&nbsp;
			        <select name="ctl00$cphRoblox$rbxFavoritesPane$AssetCategoryDropDownList" id="ctl00_cphRoblox_rbxFavoritesPane_AssetCategoryDropDownList">
		<option value="24">Animations</option>
		<option value="3">Audio</option>
		<option value="13">Decals</option>
		<option value="18">Faces</option>
		<option value="19">Gear</option>
		<option value="8">Hats</option>
		<option value="17">Heads</option>
		<option value="10">Models</option>
		<option value="12">Pants</option>
		<option selected="selected" value="9">Places</option>
		<option value="38">Plugins</option>
		<option value="11">Shirts</option>
		<option value="2">T-Shirts</option>

	</select>
		        </div>
            </div>
            <?php echo htmlspecialchars($row['Username']); ?> does not have any favorites.
	<!--	    <div>
			
			    <div id="FavoritesContent">
				    <table id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList" cellspacing="0" border="0" style="border-collapse:collapse;">
	<tr>
			<td class="Asset" valign="top">
					        <div style="padding:5px; margin-right: 30px; margin-left: 10px">
						        <div class="AssetThumbnail notranslate">
							        <a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl00_AssetThumbnailHyperLink" class=" notranslate" title="Apocalypse Rising" class=" notranslate" href="https://devopstest1.aftwld.xyz/Apocalypse-Rising-place?id=1600503" style="display:inline-block;height:110px;width:110px;cursor:pointer;"><img src="https://t7.rbxcdn.com/33bb5580b467200193ba0361200a6859" height="110" width="110" border="0" onerror="return Roblox.Controls.Image.OnError(this)" alt="Apocalypse Rising" class=" notranslate"/></a>
							    
						        </div>
						        <div class="AssetDetails notranslate" style="clear:both;">
							        <div class="AssetName"><a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl00_AssetNameHyperLink" href="https://devopstest1.aftwld.xyz/Apocalypse-Rising-place?id=1600503">Apocalypse Rising</a></div>
							        <div class="AssetCreator"><span class="Label">Creator:</span> <span class="Detail"><a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl00_AssetCreatorHyperLink" href="User.aspx?ID=281519">Gusmanak</a></span></div>
						            
						        </div>
						    </div>
					    </td><td class="Asset" valign="top">
					        <div style="padding:5px; margin-right: 30px; margin-left: 10px">
						        <div class="AssetThumbnail notranslate">
							        <a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl01_AssetThumbnailHyperLink" class=" notranslate" title="ROBLOX Base Wars FPS" class=" notranslate" href="https://devopstest1.aftwld.xyz/ROBLOX-Base-Wars-FPS-place?id=50430" style="display:inline-block;height:110px;width:110px;cursor:pointer;"><img src="https://t0.rbxcdn.com/456354bfb30ced7acb2ba401a0bc33ce" height="110" width="110" border="0" onerror="return Roblox.Controls.Image.OnError(this)" alt="ROBLOX Base Wars FPS" class=" notranslate"/></a>
							    
						        </div>
						        <div class="AssetDetails notranslate" style="clear:both;">
							        <div class="AssetName"><a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl01_AssetNameHyperLink" href="https://devopstest1.aftwld.xyz/ROBLOX-Base-Wars-FPS-place?id=50430">ROBLOX Base Wars FPS</a></div>
							        <div class="AssetCreator"><span class="Label">Creator:</span> <span class="Detail"><a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl01_AssetCreatorHyperLink" href="User.aspx?ID=21557">Games</a></span></div>
						            
						        </div>
						    </div>
					    </td><td class="Asset" valign="top">
					        <div style="padding:5px; margin-right: 30px; margin-left: 10px">
						        <div class="AssetThumbnail notranslate">
							        <a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl02_AssetThumbnailHyperLink" class=" notranslate" title="Sword Fights on the Heights IV" class=" notranslate" href="https://devopstest1.aftwld.xyz/Sword-Fights-on-the-Heights-IV-place?id=47324" style="display:inline-block;height:110px;width:110px;cursor:pointer;"><img src="https://t4.rbxcdn.com/f5494c4ba5a9462697b1b913d96bdf89" height="110" width="110" border="0" onerror="return Roblox.Controls.Image.OnError(this)" alt="Sword Fights on the Heights IV" class=" notranslate"/></a>
							    
						        </div>
						        <div class="AssetDetails notranslate" style="clear:both;">
							        <div class="AssetName"><a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl02_AssetNameHyperLink" href="https://devopstest1.aftwld.xyz/Sword-Fights-on-the-Heights-IV-place?id=47324">Sword Fights on the Heights IV</a></div>
							        <div class="AssetCreator"><span class="Label">Creator:</span> <span class="Detail"><a id="ctl00_cphRoblox_rbxFavoritesPane_FavoritesDataList_ctl02_AssetCreatorHyperLink" href="User.aspx?ID=261">Shedletsky</a></span></div>
						            
						        </div>
						    </div>
					    </td>
					    
		</tr>
	</table> 
				    
				    
			    </div> -->
		    </div>

</div>
		<?php if ($row['UserId'] === 11): ?>
			<div style="padding-left: 20px" class="divider-bottom">
				<div style="margin: 12px 0 20px; overflow:visible">
					<h2 style="float: left">NeuroSama's Sister</h2>	
				</div>
			<div style="padding-top: 50px">
				<div id="ctl00_cphRoblox_rbxFriendsPane">
					<table id="ctl00_cphRoblox_rbxFriendsPane_dlFriends" cellspacing="0" align="Center" border="0" style="border-collapse:collapse;"><tbody><tr><td></td><td>
							<div class="Friend notranslate">
								<div class="Avatar">
									<a title="Evil" href="/User.aspx?ID=12" style="display:inline-block;height:100px;width:100px;cursor:pointer;">
										<img src="/Thumbs/Avatar.ashx?userId=12" height="100" width="100" border="0" alt="Evil" onerror="return Roblox.Controls.Image.OnError(this)">
										<img src="/Thumbs/BCOverlay.ashx?Username=Evil" class="bcOverlay" align="left" style="position:relative;top:-19px;">
										</a>
								</div>
								<div class="Summary">
									<span class="OnlineStatus"><img src="/images/offline.png" alt="Evil is offline." style="border-width:0px;"></span>
									<span class="Name"><a href="/User.aspx?ID=12">Evil</a></span>
								</div>
							</div>
						</td></tr></tbody>
					</table>
				</div>
			</div>

            </div>
		<?php endif; ?>
		<?php if ($row['UserId'] === 12): ?>
			<div style="padding-left: 20px" class="divider-bottom">
				<div style="margin: 12px 0 20px; overflow:visible">
					<h2 style="float: left">Evil's Sister</h2>	
				</div>
			<div style="padding-top: 50px">
				<div id="ctl00_cphRoblox_rbxFriendsPane">
					<table id="ctl00_cphRoblox_rbxFriendsPane_dlFriends" cellspacing="0" align="Center" border="0" style="border-collapse:collapse;"><tbody><tr><td></td><td>
							<div class="Friend notranslate">
								<div class="Avatar">
									<a title="NeuroSama" href="/User.aspx?ID=11" style="display:inline-block;height:100px;width:100px;cursor:pointer;">
										<img src="/Thumbs/Avatar.ashx?userId=11" height="100" width="100" border="0" alt="NeuroSama" onerror="return Roblox.Controls.Image.OnError(this)">
										<img src="/Thumbs/BCOverlay.ashx?Username=NeuroSama" class="bcOverlay" align="left" style="position:relative;top:-19px;">
									</a>
								</div>
								<div class="Summary">
									<span class="OnlineStatus"><img src="/images/offline.png" alt="NeuroSama is offline." style="border-width:0px;"></span>
									<span class="Name"><a href="/User.aspx?ID=11">NeuroSama</a></span>
								</div>
							</div>
						</td></tr></tbody>
					</table>
				</div>
			</div>

            </div>
		<?php endif; ?>
</div>



            <div style="clear: both; margin: 20px;width:300px;">
            </div>
        </div>
        <br clear="all"/>
    </div>
<?php
$userId = 0;
foreach (['id', 'Id', 'iD', 'ID'] as $key) {
    if (isset($_GET[$key]) && is_numeric($_GET[$key])) {
        $userId = (int)$_GET[$key];
        break;
    }
}

if ($userId === 0 && isset($user['UserId'])) {
    $userId = (int)$user['UserId'];
}

$loggedIn = isset($user) && isset($user['UserId']);
$loggedInUserId = $loggedIn ? (int)$user['UserId'] : 0;

$stmt = $pdo->prepare("SELECT show_current_experience, inventory_visibility, trading FROM users WHERE UserId = :uid");
$stmt->execute(['uid' => $userId]);
$privacy = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$privacy) {
    echo "<div>User not found.</div>";
    exit;
}

function isFriend($pdo, $userId, $viewerId) {
    if ($userId === $viewerId) return true;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM friends WHERE ((from_id = :user AND to_id = :viewer) OR (from_id = :viewer AND to_id = :user)) AND type = 1");
    $stmt->execute(['user' => $userId, 'viewer' => $viewerId]);
    return (bool)$stmt->fetchColumn();
}

function canViewInventory($privacySetting, $pdo, $userId, $viewerId) {
    if ($userId === $viewerId) return true;

    if ($privacySetting === 'Everyone') return true;
    if ($privacySetting === 'No One') return false;

    if ($privacySetting === 'Friends') {
        return isFriend($pdo, $userId, $viewerId);
    }
    return false;
}

if (!canViewInventory($privacy['inventory_visibility'], $pdo, $userId, $loggedInUserId)) {
    echo "";
    exit;
}

$assetTypeTabs = [
    17 => "Heads",
    18 => "Faces",
    19 => "Gear",
    8 => "Hats",
    2 => "T-Shirts",
    11 => "Shirts",
    12 => "Pants",
    7 => "Decals",
    1 => "Images",
    20 => "Models",
    4 => "Meshes",
    21 => "Plugins",
    24 => "Animations",
    9 => "Places",
    10 => "Game Passes",
    3 => "Audio",
    25 => "Badges",
    26 => "Left Arms",
    27 => "Right Arms",
    28 => "Left Legs",
    29 => "Right Legs",
    30 => "Torsos",
    31 => "Packages"
];

$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$itemsPerPage = 18;

$stmt = $pdo->prepare("SELECT assetId, assetType FROM inventory WHERE userId = :userId ORDER BY assetType, purchasedWhen DESC");
$stmt->execute(['userId' => $userId]);
$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

$assetsByType = [];
foreach ($inventory as $item) {
    $assetsByType[$item['assetType']][] = $item['assetId'];
}

?>

<div id="UserContainer">
    <div id="UserAssetsPane" style="border-top: 1px solid #ccc;">
        <div id="ctl00_cphRoblox_rbxUserAssetsPane_upUserAssetsPane">
            <h2 class="title" style="width:970px; display:block;">
                <span>Inventory</span>
            </h2>
            <div id="UserAssets">
                <div id="AssetsMenu" class="divider-right">
					<?php
					$selectedCat = isset($_GET['cat']) && is_numeric($_GET['cat']) ? (int)$_GET['cat'] : 8;
					if (!isset($assetTypeTabs[$selectedCat])) {
						$selectedCat = 8;
					}
					?>
					<?php foreach ($assetTypeTabs as $typeId => $typeName): ?>
						<div id="AssetCategorySelectorPanel_<?= $typeId ?>" class="verticaltab<?= ($typeId === $selectedCat ? " selected" : "") ?>">
							<a href="?id=<?= $userId ?>&page=1&cat=<?= $typeId ?>" id="AssetCategorySelector_<?= $typeId ?>"><?= htmlspecialchars($typeName) ?></a>
						</div>
					<?php endforeach; ?>
                </div>
                <div id="AssetsContent">
                    <?php
                    $cat = isset($_GET['cat']) && is_numeric($_GET['cat']) ? (int)$_GET['cat'] : 8;
                    if (!isset($assetTypeTabs[$cat])) {
                        $cat = 8;
                    }

                    if (!isset($assetsByType[$cat]) || count($assetsByType[$cat]) === 0):
                        ?>
                        <div id="AssetContent_<?= $cat ?>">
                            <p>No items in this category.</p>
                        </div>
                    <?php else: ?>
                        <?php
                        $assetIds = $assetsByType[$cat];
                        $totalItems = count($assetIds);
                        $totalPages = (int)ceil($totalItems / $itemsPerPage);

                        if ($page > $totalPages) $page = $totalPages;
                        if ($page < 1) $page = 1;

                        $offset = ($page - 1) * $itemsPerPage;
                        $pageAssetIds = array_slice($assetIds, $offset, $itemsPerPage);

                        $placeholders = implode(',', array_fill(0, count($pageAssetIds), '?'));
                        $stmt2 = $pdo->prepare("SELECT AssetID, Name, Limited FROM assets WHERE AssetID IN ($placeholders)");
                        $stmt2->execute($pageAssetIds);
                        $assetsInfoRaw = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                        $assetsInfo = [];
                        foreach ($assetsInfoRaw as $asset) {
                            $assetsInfo[$asset['AssetID']] = $asset;
                        }
                        ?>
                        <div id="AssetContent_<?= $cat ?>">
                            <table cellspacing="0" border="0" style="border-collapse:collapse;">
                                <tbody>
                                <?php
                                $count = 0;
                                for ($row = 0; $row < 3; $row++):
                                    echo "<tr>";
                                    for ($col = 0; $col < 6; $col++):
                                        $index = $row * 6 + $col;
                                        if (!isset($pageAssetIds[$index])) {
                                            echo "<td></td>";
                                            continue;
                                        }
                                        $assetId = $pageAssetIds[$index];
                                        if (!isset($assetsInfo[$assetId])) {
                                            echo "<td></td>";
                                            continue;
                                        }
                                        $asset = $assetsInfo[$assetId];
                                        $name = htmlspecialchars($asset['Name']);
                                        $assetUrlName = str_replace(' ', '-', $name);
                                        $limited = (int)$asset['Limited'];
                                        $thumbUrl = "/Thumbs/Asset.ashx?assetId={$assetId}&width=100&height=100";
                                        ?>
                                        <td class="Asset" valign="top" style="padding:5px;">
                                            <div style="position:relative;">
                                                <div class="AssetThumbnail" style="position:relative;">
                                                    <a id="AssetThumbnailHyperLink_<?= $assetId ?>"
                                                       class="notranslate"
                                                       title="<?= $name ?>"
                                                       href="/<?= $assetUrlName ?>-item?id=<?= $assetId ?>"
                                                       style="display:inline-block;height:110px;width:110px;cursor:pointer;">
                                                        <img src="<?= $thumbUrl ?>"
                                                             height="110"
                                                             width="110"
                                                             border="0"
                                                             alt="<?= $name ?>"
                                                             class="notranslate"
                                                             onerror="this.src='/images/placeholder.png';"/>
                                                    </a>
                                                    <?php if ($limited === 1): ?>
                                                        <div style="position:relative;left:-21px;top:-19px;">
                                                            <img src="/images/assetIcons/limited.png" alt="Limited">
                                                        </div>
                                                    <?php elseif ($limited === 2): ?>
                                                        <div style="position:relative;left:-12px;top:-19px;">
                                                            <img src="/images/assetIcons/limitedunique.png" alt="Limited Unique">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="AssetDetails" style="width:110px;">
                                                <div class="AssetName" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    <a id="AssetNameHyperLink_<?= $assetId ?>"
                                                       class="noranslate"
                                                       href="/<?= $assetUrlName ?>-item?id=<?= $assetId ?>"><?= $name ?></a>
                                                </div>
                                            </div>
                                        </td>
                                        <?php
                                    endfor;
                                    echo "</tr>";
                                endfor;
                                ?>
                                </tbody>
                            </table>

                            <div id="ctl00_cphRoblox_rbxUserAssetsPane_FooterPagerPanel" class="FooterPager" style="width: 780px">
                                <?php
                                $prevDisabled = $page <= 1 ? "disabled" : "";
                                $nextDisabled = $page >= $totalPages ? "disabled" : "";
                                $prevPage = max(1, $page - 1);
                                $nextPage = min($totalPages, $page + 1);
                                ?>
                                <span class="pager previous <?= $prevDisabled ?>"></span>
                                <span id="ctl00_cphRoblox_rbxUserAssetsPane_FooterPagerLabel" style="vertical-align: top; display: inline-block; padding: 5px; padding-top: 6px">
                                    Page <?= $page ?> of <?= $totalPages ?>
                                </span>
                                <?php if ($nextDisabled === "disabled"): ?>
                                    <span class="pager next disabled"></span>
                                <?php else: ?>
                                    <a id="ctl00_cphRoblox_rbxUserAssetsPane_FooterPageSelector_Next" href="?id=<?= $userId ?>&page=<?= $nextPage ?>&cat=<?= $cat ?>"><span class="pager next"></span></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
</div>


                    <div style="clear:both"></div>
                </div>
            </div>
        </div> 
        </div>
        
            <div id="Footer" class="footer-container">
    <div class="FooterNav">
        <a href="https://devopstest1.aftwld.xyz/info/Privacy.aspx">Privacy Policy</a>
        &nbsp;|&nbsp;
        <a href="https://corp.aftwld.xyz/advertise-on-roblox" class="roblox-interstitial">Advertise with Us</a>
        &nbsp;|&nbsp;
        <a href="https://corp.aftwld.xyz/press" class="roblox-interstitial">Press</a>
        &nbsp;|&nbsp;
        <a href="https://corp.aftwld.xyz/contact-us" class="roblox-interstitial">Contact Us</a>
        &nbsp;|&nbsp;
        <a href="https://corp.aftwld.xyz/about" class="roblox-interstitial">About Us</a>
        &nbsp;|&nbsp;
        <a href="https://blog.aftwld.xyz/" class="roblox-interstitial">Blog</a>
        &nbsp;|&nbsp;
        <a href="https://corp.aftwld.xyz/careers" class="roblox-interstitial">Jobs</a>
        &nbsp;|&nbsp;
        <a href="https://corp.aftwld.xyz/parents" class="roblox-interstitial">Parents</a>
    </div>
    <div class="legal">
        <div class="left">
            <div id="a15b1695-1a5a-49a9-94f0-9cd25ae6c3b2">
    <a href="//web.archive.orghttps://privacy.truste.com/privacy-seal/Roblox-Corporation/validation?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" title="TRUSTe Children privacy certification" target="_blank">
        <img style="border: none" src="//web.archive.orghttps://privacy-policy.truste.com/privacy-seal/Roblox-Corporation/seal?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" width="133" height="45" alt="TRUSTe Children privacy certification"/>
    </a>
</div>
        </div>
        <div class="right">
            <p class="Legalese">
    ROBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of <a href="https://corp.aftwld.xyz/" ref="footer-smallabout" class="roblox-interstitial">ROBLOX Corporation</a>, ©2015. Patents pending.
    ROBLOX is not sponsored, authorized or endorsed by any producer of plastic building bricks, including The LEGO Group, MEGA Brands, and K'Nex, and no resemblance to the products of these companies is intended. Use of this site signifies your acceptance of the <a href="https://devopstest1.aftwld.xyz/info/terms-of-service" ref="footer-terms">Terms and Conditions</a>.
</p>
        </div>
        <div class="clear"></div>
    </div>

</div>
        
        </div></div>
    </div>
    <div id="ChatContainer" style="position: fixed; bottom: 0; right: 0; z-index: 10020">
        

    </div>

        
        <script type="text/javascript">
            function urchinTracker() { };
            GoogleAnalyticsReplaceUrchinWithGAJS = true;
        </script>
    

    

<script type="text/javascript">
//<![CDATA[
Roblox.Controls.Image.ErrorUrl = "https://devopstest1.aftwld.xyz/Analytics/BadHtmlImage.ashx";$(function () { $('.VisitButtonPlayAnyBCLevel .VisitButtonPlay').click(function () {play_placeId=$(this).attr('placeid');Roblox.CharacterSelect.placeid = play_placeId;Roblox.CharacterSelect.show();});$('.VisitButtonPersonalServer').click(function () {play_placeId=$(this).attr('placeid');Roblox.CharacterSelect.placeid = play_placeId;Roblox.CharacterSelect.show();});$('.VisitButtonBuild').click(function () {RobloxLaunch._GoogleAnalyticsCallback = function() { var isInsideRobloxIDE = 'website'; if (Roblox && Roblox.Client && Roblox.Client.isIDE && Roblox.Client.isIDE()) { isInsideRobloxIDE = 'Studio'; };GoogleAnalyticsEvents.FireEvent(['Plugin Location', 'Launch Attempt', isInsideRobloxIDE]);GoogleAnalyticsEvents.FireEvent(['Plugin', 'Launch Attempt', 'Build']);EventTracker.fireEvent('GameLaunchAttempt_Unknown', 'GameLaunchAttempt_Unknown_Plugin');  }; play_placeId = (typeof $(this).attr('placeid') === 'undefined') ? play_placeId : $(this).attr('placeid'); Roblox.Client.WaitForRoblox(function() { window.location = '/Login/Default.aspx?ReturnUrl=http%3a%2f%2f194.62.248.75:34533%2fuser.aspx%3fID%3d1' }); return false;});$('.VisitButtonEdit').click(function () {RobloxLaunch._GoogleAnalyticsCallback = function() { var isInsideRobloxIDE = 'website'; if (Roblox && Roblox.Client && Roblox.Client.isIDE && Roblox.Client.isIDE()) { isInsideRobloxIDE = 'Studio'; };GoogleAnalyticsEvents.FireEvent(['Plugin Location', 'Launch Attempt', isInsideRobloxIDE]);GoogleAnalyticsEvents.FireEvent(['Plugin', 'Launch Attempt', 'Edit']);EventTracker.fireEvent('GameLaunchAttempt_Unknown', 'GameLaunchAttempt_Unknown_Plugin');  }; play_placeId = (typeof $(this).attr('placeid') === 'undefined') ? play_placeId : $(this).attr('placeid'); Roblox.Client.WaitForRoblox(function() { RobloxLaunch.StartGame('https://devopstest1.aftwld.xyz//Game/edit.ashx?PlaceID='+play_placeId+'&upload=', 'edit.ashx', 'https://devopstest1.aftwld.xyz//Login/Negotiate.ashx', 'FETCH', true) }); return false;});Roblox.CharacterSelect.robloxLaunchFunction = function (genderTypeID) { if (genderTypeID == 3) { var isInsideRobloxIDE = 'website'; if (Roblox && Roblox.Client && Roblox.Client.isIDE && Roblox.Client.isIDE()) { isInsideRobloxIDE = 'Studio'; };GoogleAnalyticsEvents.FireEvent(['Plugin Location', 'Launch Attempt', isInsideRobloxIDE]);GoogleAnalyticsEvents.FireEvent(['Plugin', 'Launch Attempt', 'Play']);EventTracker.fireEvent("GameLaunchAttempt_Unknown", "GameLaunchAttempt_Unknown_Plugin"); } else { var isInsideRobloxIDE = 'website'; if (Roblox && Roblox.Client && Roblox.Client.isIDE && Roblox.Client.isIDE()) { isInsideRobloxIDE = 'Studio'; };GoogleAnalyticsEvents.FireEvent(['Plugin Location', 'Launch Attempt', isInsideRobloxIDE]);GoogleAnalyticsEvents.FireEvent(['Plugin', 'Launch Attempt', 'Play']);EventTracker.fireEvent("GameLaunchAttempt_Unknown", "GameLaunchAttempt_Unknown_Plugin"); }play_placeId = (typeof $(this).attr('placeid') === 'undefined') ? play_placeId : $(this).attr('placeid'); Roblox.Client.WaitForRoblox(function() { RobloxLaunch.RequestGame('PlaceLauncherStatusPanel', play_placeId, genderTypeID); }); return false;};$('.VisitButtonPlayBCOnlyModal .VisitButtonPlay a').click(function () {showBCOnlyModal('BCOnlyModal'); return false;});});//]]>
</script>
</form>
    
    
    

<style>
    #win_firefox_install_img .activation {
    }

    #win_firefox_install_img .installation {
        width: 869px;
        height: 331px;
    }

    #mac_firefox_install_img .activation {
    }

    #mac_firefox_install_img .installation {
        width: 250px;
    }

    #win_chrome_install_img .activation {
    }

    #win_chrome_install_img .installation {
    }

    #mac_chrome_install_img .activation {
        width: 250px;
    }

    #mac_chrome_install_img .installation {
    }
</style>
<div id="InstallationInstructions" class="modalPopup blueAndWhite" style="display:none;overflow:hidden">
    <a id="CancelButton2" onclick="return Roblox.Client._onCancel();" class="ImageButton closeBtnCircle_35h ABCloseCircle"></a>
    <div style="padding-bottom:10px;text-align:center">
        <br/><br/>
    </div>
</div>



<div id="pluginObjDiv" style="height:1px;width:1px;visibility:hidden;position: absolute;top: 0;"></div>
<iframe id="downloadInstallerIFrame" style="visibility:hidden;height:0;width:1px;position:absolute"></iframe>

<script type="text/javascript" src="https://aftwld.xyz/rbxcdn_js/4726cb4f73131ee2d1c2694e87e9d495.js"></script>

<script type="text/javascript">
    Roblox.Client._skip = '/install/unsupported.aspx';
    Roblox.Client._CLSID = '';
    Roblox.Client._installHost = '';
    Roblox.Client.ImplementsProxy = false;
    Roblox.Client._silentModeEnabled = false;
    Roblox.Client._bringAppToFrontEnabled = false;
    Roblox.Client._currentPluginVersion = '';

        
    Roblox.Client._installSuccess = function() {
        if(GoogleAnalyticsEvents){
            GoogleAnalyticsEvents.ViewVirtual('InstallSuccess');
            GoogleAnalyticsEvents.FireEvent(['Plugin','Install Success']);
        }
    }
    
    </script>


<div id="PlaceLauncherStatusPanel" style="display:none;width:300px" data-new-plugin-events-enabled="True" data-is-protocol-handler-launch-enabled="False" data-is-user-logged-in="False" data-os-name="Unknown" data-protocol-name-for-client="roblox-player" data-protocol-name-for-studio="roblox-studio">
    <div class="modalPopup blueAndWhite PlaceLauncherModal" style="min-height: 160px">
        <div id="Spinner" class="Spinner" style="margin:0 1em 1em 0; padding:20px 0;">
            <img src="https://aftwld.xyz/rbxcdn_img/e998fb4c03e8c2e30792f2f3436e9416.gif" height="32" width="32" alt="Progress"/>
        </div>
        <div id="status" style="min-height:40px;text-align:center;margin:5px 20px">
            <div id="Starting" class="PlaceLauncherStatus MadStatusStarting" style="display:block">
                Starting Roblox...
            </div>
            <div id="Waiting" class="PlaceLauncherStatus MadStatusField">Connecting to Players...</div>
            <div id="StatusBackBuffer" class="PlaceLauncherStatus PlaceLauncherStatusBackBuffer MadStatusBackBuffer"></div>
        </div>
        <div style="text-align:center;margin-top:1em">
            <input type="button" class="Button CancelPlaceLauncherButton translate" value="Cancel"/>
        </div>
    </div>
</div>
<div id="ProtocolHandlerStartingDialog" style="display:none;">
    <div class="modalPopup ph-modal-popup">
        <div class="ph-modal-header">

        </div>
        <div class="ph-logo-row">
            <img src="https://devopstest1.aftwld.xyz/images/Logo/logo_meatball.svg" width="90" height="90" alt="R"/>
        </div>
        <div class="ph-areyouinstalleddialog-content">
            <p class="larger-font-size">
                ROBLOX is now loading. Get ready to play!
            </p>
            <div class="ph-startingdialog-spinner-row">
                <img src="https://aftwld.xyz/rbxcdn_img/4bed93c91f909002b1f17f05c0ce13d1.gif" width="82" height="24"/>
            </div>
        </div>
    </div>
</div>
<div id="ProtocolHandlerAreYouInstalled" style="display:none;">
    <div class="modalPopup ph-modal-popup">
        <div class="ph-modal-header">
            <span class="rbx-icon-close simplemodal-close"></span>
        </div>
        <div class="ph-logo-row">
            <img src="https://devopstest1.aftwld.xyz/images/Logo/logo_meatball.svg" width="90" height="90" alt="R"/>
        </div>
        <div class="ph-areyouinstalleddialog-content">
            <p class="larger-font-size">
                You're moments away from getting into the game!
            </p>
            <div>
                <button type="button" class="btn rbx-btn-primary-sm" id="ProtocolHandlerInstallButton">
                    Download and Install ROBLOX
                </button>
            </div>
            <div class="rbx-small rbx-text-notes">
                <a href="https://en.help.aftwld.xyz/hc/en-us/articles/204473560" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>
<div id="ProtocolHandlerClickAlwaysAllowed" class="ph-clickalwaysallowed" style="display:none;">
    <p class="larger-font-size">
        <span class="rbx-icon-moreinfo"></span>
        Check <b>Remember my choice</b> and click <img src="https://aftwld.xyz/rbxcdn_img/7c8d7a39b4335931221857cca2b5430b.png" alt="Launch Application"/>  in the dialog box above to join games faster in the future!
    </p>
</div>


<script type="text/javascript" src="https://aftwld.xyz/rbxcdn_js/e59cc9c921c25a5cd61d18f0a7fd5ac8.js"></script>
 
    <div id="videoPrerollPanel" style="display:none">
        <div id="videoPrerollTitleDiv">
            Gameplay sponsored by:
        </div>
        <div id="videoPrerollMainDiv"></div>
        <div id="videoPrerollCompanionAd"></div>
        <div id="videoPrerollLoadingDiv">
            Loading <span id="videoPrerollLoadingPercent">0%</span> - <span id="videoPrerollMadStatus" class="MadStatusField">Starting game...</span><span id="videoPrerollMadStatusBackBuffer" class="MadStatusBackBuffer"></span>
            <div id="videoPrerollLoadingBar">
                <div id="videoPrerollLoadingBarCompleted">
                </div>
            </div>
        </div>
        <div id="videoPrerollJoinBC">
            <span>Get more with Builders Club!</span>
            <a href="https://devopstest1.aftwld.xyz/Upgrades/BuildersClubMemberships.aspx?ref=vpr" target="_blank" class="btn-medium btn-primary" id="videoPrerollJoinBCButton">Join Builders Club</a>
        </div>
    </div>
    <script type="text/javascript">
        Roblox.VideoPreRoll.showVideoPreRoll = false;
        Roblox.VideoPreRoll.isPrerollShownEveryXMinutesEnabled = true;
        Roblox.VideoPreRoll.loadingBarMaxTime = 33000;
        Roblox.VideoPreRoll.videoOptions.key = "robloxcorporation"; 
            Roblox.VideoPreRoll.videoOptions.categories = "AgeUnknown,GenderUnknown";
                     Roblox.VideoPreRoll.videoOptions.id = "games";
        Roblox.VideoPreRoll.videoLoadingTimeout = 11000;
        Roblox.VideoPreRoll.videoPlayingTimeout = 41000;
        Roblox.VideoPreRoll.videoLogNote = "NotWindows";
        Roblox.VideoPreRoll.logsEnabled = true;
        Roblox.VideoPreRoll.excludedPlaceIds = "32373412";
        Roblox.VideoPreRoll.adTime = 15;
            
                Roblox.VideoPreRoll.specificAdOnPlacePageEnabled = true;
                Roblox.VideoPreRoll.specificAdOnPlacePageId = 192800;
                Roblox.VideoPreRoll.specificAdOnPlacePageCategory = "stooges";
            
                    
                Roblox.VideoPreRoll.specificAdOnPlacePage2Enabled = true;
                Roblox.VideoPreRoll.specificAdOnPlacePage2Id = 2370766;
                Roblox.VideoPreRoll.specificAdOnPlacePage2Category = "lego";
            
        $(Roblox.VideoPreRoll.checkEligibility);
    </script>


<div id="GuestModePrompt_BoyGirl" class="Revised GuestModePromptModal" style="display:none;">
    <div class="simplemodal-close">
        <a class="ImageButton closeBtnCircle_20h" style="cursor: pointer; margin-left:455px;top:7px; position:absolute;"></a>
    </div>
    <div class="Title">
        Choose Your Character
    </div>
    <div style="min-height: 275px; background-color: white;">
        <div style="clear:both; height:25px;"></div>

        <div style="text-align: center;">
            <div class="VisitButtonsGuestCharacter VisitButtonBoyGuest" style="float:left; margin-left:45px;"></div>
            <div class="VisitButtonsGuestCharacter VisitButtonGirlGuest" style="float:right; margin-right:45px;"></div>
        </div>
        <div style="clear:both; height:25px;"></div>
        <div class="RevisedFooter">
            <div style="width:200px;margin:10px auto 0 auto;">
                <a href="#" onclick="redirectPlaceLauncherToRegister(); return false;"><div class="RevisedCharacterSelectSignup"></div></a>
                <a class="HaveAccount" href="#" onclick="redirectPlaceLauncherToLogin();return false;">I have an account</a>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
function checkRobloxInstall() {
    window.location = '/install/unsupported.aspx'; return false;
}
</script>

<script type="text/javascript">
var Roblox = Roblox || {};
Roblox.UpsellAdModal = Roblox.UpsellAdModal || {};
Roblox.UpsellAdModal.Resources = {
    title: "Remove Ads Like This",
    body: "Builders Club members do not see external ads like these.",
    accept: "Upgrade Now",
    decline: "No, thanks"
};
</script>

<script>
(function() {
  const validUrls = [
    "https://devopstest1.aftwld.xyz/user.aspx?id=6",
    "https://devopstest1.aftwld.xyz/user.aspx?ID=6"
  ].map(u => u.toLowerCase());

  const currentUrl = window.location.href.toLowerCase();
  if (!validUrls.includes(currentUrl)) return;

  function replaceFavoritesPane() {
    const favoritesPane = document.getElementById("ctl00_cphRoblox_rbxFavoritesPane_FavoritesPane");
    if (favoritesPane) {
      favoritesPane.innerHTML = `
        <h2 class="title">xQc's Twitch Stream</h2>
        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%;">
          <iframe
            src="https://player.twitch.tv/?channel=xqc&parent=devopstest1.aftwld.xyz"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
            allowfullscreen
            scrolling="no">
          </iframe>
        </div>
      `;
      observer.disconnect();
      return true;
    }
    return false;
  }
  if (replaceFavoritesPane()) return;
  const observer = new MutationObserver(() => {
    if (replaceFavoritesPane()) {}
  });
  observer.observe(document.body, { childList: true, subtree: true });
})();
</script>

<script>
(function() {
  const validUrls = [
    "https://devopstest1.aftwld.xyz/user.aspx?id=11",
    "https://devopstest1.aftwld.xyz/user.aspx?ID=11",
    "http://devopstest1.aftwld.xyz/user.aspx?id=11",
    "http://devopstest1.aftwld.xyz/user.aspx?ID=11",
    "https://devopstest1.aftwld.xyz/User.aspx?id=11",
    "https://devopstest1.aftwld.xyz/User.aspx?ID=11",
    "http://devopstest1.aftwld.xyz/User.aspx?id=11",
    "http://devopstest1.aftwld.xyz/User.aspx?ID=11",
  ].map(u => u.toLowerCase());

  const currentUrl = window.location.href.toLowerCase();
  if (!validUrls.includes(currentUrl)) return;

  function replaceFavoritesPane() {
    const favoritesPane = document.getElementById("ctl00_cphRoblox_rbxFavoritesPane_FavoritesPane");
    if (favoritesPane) {
      favoritesPane.innerHTML = `
        <h2 class="title">Neuro's Twitch Stream</h2>
        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%;">
          <iframe
            src="https://player.twitch.tv/?channel=vedal987&parent=devopstest1.aftwld.xyz"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
            allowfullscreen
            scrolling="no">
          </iframe>
        </div>
      `;
      observer.disconnect();
      return true;
    }
    return false;
  }
  if (replaceFavoritesPane()) return;
  const observer = new MutationObserver(() => {
    if (replaceFavoritesPane()) {}
  });
  observer.observe(document.body, { childList: true, subtree: true });
})();
</script>

<script>
(function() {
  const validUrls = [
    "https://devopstest1.aftwld.xyz/user.aspx?id=12",
    "https://devopstest1.aftwld.xyz/user.aspx?ID=12",
    "http://devopstest1.aftwld.xyz/user.aspx?id=12",
    "http://devopstest1.aftwld.xyz/user.aspx?ID=12",
    "https://devopstest1.aftwld.xyz/User.aspx?id=12",
    "https://devopstest1.aftwld.xyz/User.aspx?ID=12",
    "http://devopstest1.aftwld.xyz/User.aspx?id=12",
    "http://devopstest1.aftwld.xyz/User.aspx?ID=12"
  ].map(u => u.toLowerCase());

  const currentUrl = window.location.href.toLowerCase();
  if (!validUrls.includes(currentUrl)) return;

  function replaceFavoritesPane() {
    const favoritesPane = document.getElementById("ctl00_cphRoblox_rbxFavoritesPane_FavoritesPane");
    if (favoritesPane) {
      favoritesPane.innerHTML = `
        <h2 class="title">Evil's Twitch Stream</h2>
        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%;">
          <iframe
            src="https://player.twitch.tv/?channel=vedal987&parent=devopstest1.aftwld.xyz"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
            allowfullscreen
            scrolling="no">
          </iframe>
        </div>
      `;
      observer.disconnect();
      return true;
    }
    return false;
  }
  if (replaceFavoritesPane()) return;
  const observer = new MutationObserver(() => {
    if (replaceFavoritesPane()) {}
  });
  observer.observe(document.body, { childList: true, subtree: true });
})();
</script>

<script>
(function () {
  const validUrls = [
    "https://devopstest1.aftwld.xyz/user.aspx?id=6",
    "https://devopstest1.aftwld.xyz/user.aspx?ID=6",
    "http://devopstest1.aftwld.xyz/user.aspx?id=6",
    "http://devopstest1.aftwld.xyz/user.aspx?ID=6",
    "https://devopstest1.aftwld.xyz/User.aspx?id=6",
    "https://devopstest1.aftwld.xyz/User.aspx?ID=6",
    "http://devopstest1.aftwld.xyz/User.aspx?id=6",
    "http://devopstest1.aftwld.xyz/User.aspx?ID=6"
  ];

  const currentUrl = window.location.href.toLowerCase();
  if (!validUrls.includes(currentUrl)) return;

  document.addEventListener("DOMContentLoaded", () => {
    const statsTable = document.querySelector("table.statsTable");
    if (!statsTable) return;

    statsTable.outerHTML = `
      <table class="statsTable">
        <tbody>
          <tr>
            <td class="statsLabel">
              <acronym title="How many friends this user has.">Friends</acronym>:
            </td>
            <td class="statsValue">
              <span id="ctl00_cphRoblox_rbxUserStatisticsPane_lFriendsStatistics">4</span>
            </td>
            <td class="statsLabel">
              <acronym title="The number of times this user's place has been visited.">Place Visits</acronym>:
            </td>
            <td class="statsValue">
              <span id="ctl00_cphRoblox_rbxUserStatisticsPane_lPlaceVisitsStatistics" class="notranslate">0</span>
            </td>
          </tr>
          <tr>
            <td class="statsLabel">
              <acronym title="The number of times this user's character has destroyed another user's character in-game.">Knockouts</acronym>:
            </td>
            <td class="statsValue">
              <span id="ctl00_cphRoblox_rbxUserStatisticsPane_lKillsStatistics" class="notranslate">132</span>
            </td>
            <td class="statsLabel">
              <acronym title="The number of posts this user has made to the AFTERWORLD forum.">Forum Posts</acronym>:
            </td>
            <td class="statsValue">
              <span id="ctl00_cphRoblox_rbxUserStatisticsPane_lForumPostsStatistics" class="notranslate">1</span>
            </td>
          </tr>
          <tr></tr>
          <tr>
            <td class="statsLabel">
              <acronym title="How many followers this user has.">Followers</acronym>:
            </td>
            <td class="statsValue">
              <span id="ctl00_cphRoblox_rbxUserStatisticsPane_lFollowersStatistics">12.1M</span>
            </td>
          </tr>
        </tbody>
      </table>
    `;
  });
})();
<script>
document.addEventListener("DOMContentLoaded", function () {
  const emojiMap = {
    ":xqcL:": "https://i.redd.it/yakhrtg6nm1a1.png",
    ":LUL:": "https://static-cdn.jtvnw.net/emoticons/v1/425618/1.0",
    ":NeuroSama:": "https://preview.redd.it/neuro-global-twitch-emote-v0-8iorab058lie1.jpeg?width=2104&format=pjpg&auto=webp&s=8d562775494b5f35c54e2d32ad92c8f5e2369552",
    ":PepeLaugh:": "https://www.streamscheme.com/wp-content/uploads/2020/08/pepelaugh-emote.png",
    ":PogChamp:": "https://www.streamscheme.com/wp-content/uploads/2020/04/Pogchamp.png"
  };

  function walkAndReplace(node) {
    if (node.nodeType === 3) {
      let replaced = node.nodeValue;
      let replacedFlag = false;

      for (const code in emojiMap) {
        const imgTag = `<img src="${emojiMap[code]}" style="width:24px;height:24px;vertical-align:middle;margin:0 2px;">`;
        const regex = new RegExp(code.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g');
        if (regex.test(replaced)) {
          replaced = replaced.replace(regex, imgTag);
          replacedFlag = true;
        }
      }

      if (replacedFlag) {
        const wrapper = document.createElement("span");
        wrapper.innerHTML = replaced;
        node.replaceWith(wrapper);
      }
    } else if (node.nodeType === 1 && node.tagName !== "SCRIPT" && node.tagName !== "STYLE") {
      for (let i = 0; i < node.childNodes.length; i++) {
        walkAndReplace(node.childNodes[i]);
      }
    }
  }

  walkAndReplace(document.body);
});
</script>


</body>                
</html>