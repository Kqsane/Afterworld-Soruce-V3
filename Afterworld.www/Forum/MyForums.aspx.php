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
            <br
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
    