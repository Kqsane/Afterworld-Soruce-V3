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

$isAdmin = 0;

if (isset($_COOKIE['_ROBLOSECURITY'])) {
    $userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    $currentPage = basename($_SERVER['PHP_SELF']);
    
}

$filter_forum_id = isset($_GET['ForumID']) ? (int)$_GET['ForumID'] : null;
$sql = "SELECT f.id AS post_id, f.isPinned, f.Subject, f.UserId, f.Views, f.PostedAt, f.ForumId, u.Username FROM forums AS f LEFT JOIN users AS u ON f.UserId = u.UserId";
if ($filter_forum_id !== null) {
    $sql .= " WHERE f.ForumId = :forumId";
}
$sql .= " ORDER BY f.isPinned DESC, f.PostedAt DESC";
$stmt = null;

try {
    $stmt = $pdo->prepare($sql);
    if ($filter_forum_id !== null) {
        $stmt->bindParam(':forumId', $filter_forum_id, PDO::PARAM_INT);
    }
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching forum posts: " . $e->getMessage();
} finally {
    if (isset($stmt)) {
        $stmt->closeCursor();
    }
}

$forumGroups = [
    1 => [
        46 => 'All Things Afterworld',
        14 => 'Help (Technical Support and Account Issues)',
        21 => 'Suggestions & Ideas',
        54 => 'Game Nights & Afterworld events',
    ],
    2 => [
        13 => 'Afterworld Talk',
        18 => 'Off Topic',
        32 => 'Clans & Guilds',
        35 => "Let's Make a Deal",
    ],
    9 => [
        62 => 'Game Marketing',
        40 => 'Game Design',
        33 => 'Scripters',
    ],
    6 => [
        42 => 'Video Game Central',
        52 => 'Video Creation with Afterworld',
        26 => 'Ro-Sports',
        24 => 'Pop-Culture (Music/Books/Movies/TV)',
        23 => 'Role-Playing',
    ],
];

$forumId = isset($_GET['ForumID']) ? (int)$_GET['ForumID'] : 0;
if (!isset($_COOKIE['_ROBLOSECURITY']) || !($userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY'])) || !isset($userInfo['UserId'])) {
    die("You must be logged in to post.");
}
$UserId = (int)$userInfo['UserId'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($forumId && $title !== '' && $content !== '') {
        $stmt = $pdo->prepare("INSERT INTO forums (UserId, ForumId, PostedAt, Closed, Subject, Message, isPinned, Views) VALUES (:UserId, :ForumId, :PostedAt, 0, :Subject, :Message, 0, 0)");
        $stmt->execute([':UserId'   => $UserId, ':ForumId'  => $forumId, ':PostedAt' => time(), ':Subject'  => $title, ':Message'  => $content,]);
        $postId = $pdo->lastInsertId();
        header("Location: /Forum/ShowPost.aspx?PostID=$postId");
        exit;
    } else {
        echo "<p style='color:red;'>Please fill in all fields.</p>";
    }
}
?>

<title>Afterworld - Forum</title>
<link rel='stylesheet' href='/CSS/Base/CSS/main___3cf17678f5d36ad2b9b72dd3439177be_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___b3be82695b3ef2061fcc71f48ca60b85_m.css' />
<link rel='stylesheet' href='/Forum/skins/default/style/default.css' />
<script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>
   <script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>




    
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,600,700" rel="stylesheet" type="text/css">
    <script type='text/javascript' src='https://js.rbxcdn.com/fbb8598e3acc13fe8b4d8c1c5b676f2e.js.gzip'></script>
	
<div class="nav-container no-gutter-ads">
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
                    <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
        <tr valign="top">
            <!-- left column -->
            <td>&nbsp;&nbsp;&nbsp;</td>

<?php
$currentForumId = isset($_GET['ForumID']) ? (int)$_GET['ForumID'] : null;
$forumName = null;
foreach ($forumGroups as $group => $forums) {
    if (isset($forums[$currentForumId])) {
        $forumName = $forums[$currentForumId];
        break;
    }
}
?>
            <!-- center column -->
            <td id="ctl00_cphRoblox_CenterColumn" width="95%" class="CenterColumn">
                <br>
                <span id="ctl00_cphRoblox_PostView1">
<table cellPadding="0" width="100%">
  <tr>
    <td align="left">
        <span id="ctl00_cphRoblox_PostView1_ctl00_Whereami1" NAME="Whereami1">
<div>
    <nobr>
        <a id="ctl00_cphRoblox_PostView1_ctl00_Whereami1_ctl00_LinkHome" class="linkMenuSink notranslate" href="/Forum/Default.aspx">Afterworld Forum</a>
    </nobr>
    <nobr>
        <span id="ctl00_cphRoblox_PostView1_ctl00_Whereami1_ctl00_ForumGroupSeparator" class="normalTextSmallBold"> » </span>
        <a id="ctl00_cphRoblox_PostView1_ctl00_Whereami1_ctl00_LinkForumGroup" class="linkMenuSink notranslate" href="/Forum/ShowForumGroup.aspx?ForumGroupID=1">Afterworld</a>
    </nobr>
    <nobr>
        <span id="ctl00_cphRoblox_PostView1_ctl00_Whereami1_ctl00_ForumSeparator" class="normalTextSmallBold"> » </span>
        <a id="ctl00_cphRoblox_PostView1_ctl00_Whereami1_ctl00_LinkForum" class="linkMenuSink notranslate" href="/Forum/ShowForum.aspx?ForumID=<?= $currentForumId ?>"><?= htmlspecialchars($forumName) ?></a>
    </nobr>
</div></span>
    </td>
    <td align="right">
        <span id="ctl00_cphRoblox_PostView1_ctl00_Navigationmenu1">

<div id="forum-nav" style="text-align: right">
	<a id="ctl00_cphRoblox_PostView1_ctl00_Navigationmenu1_ctl00_HomeMenu" class="menuTextLink first" href="/Forum/Default.aspx">Home</a>
	<a id="ctl00_cphRoblox_PostView1_ctl00_Navigationmenu1_ctl00_SearchMenu" class="menuTextLink" href="/Forum/Search/default.aspx">Search</a>
	<?php
	if (isset($_COOKIE['_ROBLOSECURITY']) && ($userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY'])) && isset($userInfo['UserId'])) {
       echo "<a id='ctl00_cphRoblox_NavigationMenu2_ctl00_SearchMenu' class='menuTextLink' href='/Forum/MyForums.aspx'>MyForums</a>";
    }
    ?>

</div>
</span>
</td>
  </tr>
  <tr>
    <td align="left" colSpan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" colSpan="2">
        <h2 id="ctl00_cphRoblox_PostView1_ctl00_PostTitle" CssClass="notranslate" style="margin-bottom:20px">New Post</h2>
    </td>
  </tr>
  <tr>
  <td align="left" colspan="2">
  <form method="POST">
    <input type="hidden" name="section_id" value="1">
    <label for="title" style="display: inline-block; width: 90px; vertical-align: top;"><span class="normalTextSmallBold">Subject (60):</span></label>
    <input type="text" name="title" id="title" required style="width: 700px; padding: 4px; border: 1px solid #888;"><br><br>
    <label for="content" style="display: inline-block; width: 80px; vertical-align: top;"><span class="normalTextSmallBold"  style="direction: rtl;">
	50,000
	Message:</span></label>
    <textarea name="content" id="content" required style="width: 700px; height: 200px; padding: 4px; border: 1px solid #888;"></textarea><br><br>
    <div style="margin-left: 80px;">
        <label>
            <input type="checkbox" name="no_replies">
            Do not allow replies to this post.
        </label>
    </div>
    <div style="margin-left: 80px; margin-top: 10px;">
        <button type="button" onclick="window.location.href='/Forum/ShowForum.aspx?ForumID=<?= htmlspecialchars($forumId) ?>'"
                style="padding: 3px 10px; margin-right: 4px;">Cancel</button>
        <button type="button" onclick="alert('Preview is not implemented.')"
                style="padding: 3px 10px; margin-right: 4px;">Preview</button>
        <button type="submit" style="padding: 3px 10px;">Post</button>
    </div>
  </form>
  </td>
</tr>
</body>                
</html>
