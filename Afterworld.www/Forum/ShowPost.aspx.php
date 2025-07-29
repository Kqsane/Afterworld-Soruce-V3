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

if ($userId) {
    $stmt = $pdo->prepare('SELECT Membership, isAdmin FROM users WHERE UserId = :userId LIMIT 1');
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $adminLevel = (int)($row['isAdmin'] ?? 0);
    $isAdmin = in_array($adminLevel, [2, 3, 4, 5]) ? 1 : 0;
} else {
    $isAdmin = 0;
}

$currentForumId = null;
$forumName = null;

$postId = (int)($_GET['PostID'] ?? 0);

$stmt = $pdo->prepare("SELECT f.*, u.Username, u.JoinData, u.isAdmin, u.Membership 
    FROM forums AS f 
    LEFT JOIN users AS u ON f.UserId = u.UserId 
    WHERE f.id = :postId LIMIT 1");
$stmt->execute([':postId' => $postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

$currentForumId = (int)$post['ForumId'];

$forumGroups = [
    1 => [46 => 'All Things Afterworld', 14 => 'Help (Technical Support and Account Issues)', 21 => 'Suggestions & Ideas', 54 => 'Game Nights & Afterworld events'],
    2 => [13 => 'Afterworld Talk', 18 => 'Off Topic', 32 => 'Clans & Guilds', 35 => "Let's Make a Deal"],
    9 => [62 => 'Game Marketing', 40 => 'Game Design', 33 => 'Scripters'],
    6 => [42 => 'Video Game Central', 52 => 'Video Creation with Afterworld', 26 => 'Ro-Sports', 24 => 'Pop-Culture (Music/Books/Movies/TV)', 23 => 'Role-Playing'],
];

foreach ($forumGroups as $group => $forums) {
    if (isset($forums[$currentForumId])) {
        $forumName = $forums[$currentForumId];
        break;
    }
}

$replyStmt = $pdo->prepare("SELECT r.*, u.Username, u.JoinData, u.isAdmin, u.Membership 
    FROM forum_replies AS r 
    LEFT JOIN users AS u ON r.UserId = u.UserId 
    WHERE r.PostId = :postId 
    ORDER BY r.PostedAt ASC");
$replyStmt->execute([':postId' => $postId]);
$replies = $replyStmt->fetchAll(PDO::FETCH_ASSOC);
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
        <?php if ($forumName): ?>
        <a id="ctl00_cphRoblox_PostView1_ctl00_Whereami1_ctl00_LinkForum" class="linkMenuSink notranslate" href="/Forum/ShowForum.aspx?ForumID=<?= $currentForumId ?>"><?= htmlspecialchars($forumName) ?></a>
        <?php endif; ?>
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
	
	
	
	
	
<?php
$stmt = $pdo->prepare("SELECT f.id AS PostID, f.Message AS PostContent, f.PostedAt AS PostDate, f.Subject AS Subject, u.UserId, u.Username, u.Membership, u.isAdmin, u.JoinData FROM forums AS f LEFT JOIN users AS u ON f.UserId = u.UserId WHERE f.id = :postId");
$stmt->execute([':postId' => $_GET['PostID']]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
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
        <h2 id="ctl00_cphRoblox_PostView1_ctl00_PostTitle" CssClass="notranslate" style="margin-bottom:20px"><?= htmlspecialchars($post['Subject']) ?></h2>
    </td>
  </tr>
  <tr>
    <td vAlign="middle" align="left">
	    <span class="normalTextSmallBold"></span>
    </td>
    <td vAlign="middle" align="right">
        <span class="normalTextSmallBold">Sort: </span>
        <select name="ctl00$cphRoblox$PostView1$ctl00$SortOrder" id="ctl00_cphRoblox_PostView1_ctl00_SortOrder" style="margin-bottom:5px;">
	<option selected="selected" value="0">Oldest to newest</option>
	<option value="1">Newest to oldest</option>

</select>
    </td>
  </tr>
  <tr>
    <td colSpan="2">
        <table id="ctl00_cphRoblox_PostView1_ctl00_PostList" class="tableBorder" cellspacing="1" cellpadding="0" border="0" style="width:100%;">
	<tr>
		<td class="forumHeaderBackgroundAlternate" colspan="2" style="height:20px;"><table class="forum-table-header" cellspacing="0" cellpadding="0" border="0" style="width:100%;border-collapse:collapse;">
			<tr>
				<td align="left" style="width:50%;"></td><td class="tableHeaderText table-header-right-align" align="right"><a id="ctl00_cphRoblox_PostView1_ctl00_PostList_ctl00_PreviousThread" class="linkSmallBold" href="javascript:__doPostBack(&#39;ctl00$cphRoblox$PostView1$ctl00$PostList$ctl00$PreviousThread&#39;,&#39;&#39;)">Previous Thread</a>&nbsp;<span class="normalTextSmallBold">::</span>&nbsp;<a id="ctl00_cphRoblox_PostView1_ctl00_PostList_ctl00_NextThread" class="linkSmallBold" href="javascript:__doPostBack(&#39;ctl00$cphRoblox$PostView1$ctl00$PostList$ctl00$NextThread&#39;,&#39;&#39;)">Next Thread</a>&nbsp;</td>
			</tr>
		</table></td>
	</tr>
<tr class="forum-post">
    <td class="forum-content-background" valign="top" style="width:0px;white-space:nowrap;">
        <table border="0">
            <tr>
                <td>
                    <img src="/Forum/skins/default/images/user_IsOnline.gif" alt="<?= htmlspecialchars($post['Username']) ?> is online." style="border-width:0;" />
                    &nbsp;
                    <a class="normalTextSmallBold notranslate" href="/User.aspx?id=<?= (int)$post['UserId'] ?>">
                        <?= htmlspecialchars($post['Username']) ?>
                    </a><br>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="/User.aspx?id=<?= (int)$post['UserId'] ?>" style="width:100px;height:100px;position:relative;display:inline-block;">
                        <img src="/Thumbs/Avatar.ashx?userId=<?= (int)$post['UserId'] ?>" style="width:100px;height:100px;border-width:0;" />
<?php
$membership = (int)($post['Membership'] ?? 0);
if ($membership == 1) {
    echo '<img class="user-avatar-overlay-image" src="/images/BC.png" style="position:absolute;left:0;bottom:0;border-width:0;" alt="BC"/>';
} elseif ($membership == 2) {
    echo '<img class="user-avatar-overlay-image" src="/images/TBC.png" style="position:absolute;left:0;bottom:0;border-width:0;" alt="TBC"/>';
} elseif ($membership == 3) {
    echo '<img class="user-avatar-overlay-image" src="/images/OBC.png" style="position:absolute;left:0;bottom:0;border-width:0;" alt="OBC"/>';
}
?>

                    </a>
                </td>
            </tr>
            <?php if (in_array($post['isAdmin'], [2, 3, 4, 5])): ?>
            <tr>
                <td>
                    <img src="/Forum/skins/default/images/users_moderator.gif" alt="Forum Moderator" style="border-width:0;" />
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>
                    <span class="normalTextSmaller"><b>Joined:</b> <?= htmlspecialchars(date('d M Y', $post['JoinData'])) ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="normalTextSmaller"><b>Total Posts: WIP</span>
                </td>
            </tr>
            
            <tr>
                <td style="height:20px;">
                    <span class="normalTextSmaller primaryGroupInfo notranslate" username="<?= htmlspecialchars($post['Username']) ?>" style="display:none;"></span>
                </td>
            </tr>
        </table>
    </td>

    <td class="forum-content-background" valign="top">
        <table cellspacing="0" cellpadding="3" border="0" style="width:100%;border-collapse:collapse;table-layout:fixed;overflow:hidden;word-wrap:break-word;">
            <tr>
                <td colspan="2">
                    <span class="normalTextSmaller">
                        <?= htmlspecialchars(date('d M Y h:i A', $post['PostDate'])) ?>
                        <a name="<?= (int)$post['PostID'] ?>"></a>
                    </span>
                </td>
            </tr>
            <tr>
                <td valign="top" colspan="2" style="height:125px;">
                    <span class="normalTextSmall notranslate linkify"><?= nl2br(htmlspecialchars($post['PostContent'])) ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2"><span class="normalTextSmaller notranslate"></span></td>
            </tr>
            <tr>
                <td style="height:2px;"></td>
            </tr>
            <tr>
                <td align="left" style="height:29px;"></td>
                <td align="right">
                <?php if (in_array($isAdmin, [2, 3, 4, 5])): ?>
    <div class="admin-tools" style="margin-top: 10px;">
        <a href="/Forum/EditPost.aspx?PostID=<?= (int)$post['PostID'] ?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="/Forum/DeletePost.aspx?PostID=<?= (int)$post['PostID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
    </div>
<?php endif; ?>

                    <span class="post-response-options">
                    
                        <span class="ReportAbuse">
                            <span class="AbuseButton">
                                <a href="/AbuseReport/ForumPost.aspx?PostID=<?= (int)$post['PostID'] ?>">Report Abuse</a>
                            </span>
                        </span>
                    </span>
                </td>
            </tr>
        </table>
    </td>
</tr><tr>
<?php if ($replies): ?>
    <?php foreach ($replies as $reply): ?>
        <tr class="forum-post" style="background-color:#f8f8f8;">
            <td class="forum-content-background" valign="top" style="width:0px;white-space:nowrap;">
                <table border="0">
                    <tr>
                        <td>
                            <img src="/Forum/skins/default/images/user_IsOnline.gif" alt="<?= htmlspecialchars($reply['Username']) ?> is online." style="border-width:0;" />
                            &nbsp;
                            <a class="normalTextSmallBold notranslate" href="/User.aspx?id=<?= (int)$reply['UserId'] ?>">
                                <?= htmlspecialchars($reply['Username']) ?>
                            </a><br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="/User.aspx?id=<?= (int)$reply['UserId'] ?>" style="width:100px;height:100px;position:relative;display:inline-block;">
                                <img src="/Thumbs/Avatar.ashx?userId=<?= (int)$reply['UserId'] ?>" style="width:100px;height:100px;border-width:0;" />
<?php
$membership = (int)($reply['Membership'] ?? 0);
if ($membership === 1) {
    echo '<img class="user-avatar-overlay-image" src="https://aftwld.xyz/images/BC.png" style="position:absolute;left:0;bottom:0;border-width:0;" alt="BC"/>';
} elseif ($membership === 2) {
    echo '<img class="user-avatar-overlay-image" src="https://aftwld.xyz/images/TBC.png" style="position:absolute;left:0;bottom:0;border-width:0;" alt="TBC"/>';
} elseif ($membership === 3) {
    echo '<img class="user-avatar-overlay-image" src="https://aftwld.xyz/images/OBC.png" style="position:absolute;left:0;bottom:0;border-width:0;" alt="OBC"/>';
}
?>
 <?php if (in_array($reply['isAdmin'], [2, 3, 4, 5])): ?>
            <tr>
                <td>
                    <img src="/Forum/skins/default/images/users_moderator.gif" alt="Forum Moderator" style="border-width:0;" />
                </td>
            </tr>
            <?php endif; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="normalTextSmaller"><b>Joined:</b> <?= date('d M Y', $reply['JoinData']) ?></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="forum-content-background" valign="top">
                <table cellspacing="0" cellpadding="3" border="0" style="width:100%;border-collapse:collapse;table-layout:fixed;overflow:hidden;word-wrap:break-word;">
                    <tr>
                        <td colspan="2">
                            <span class="normalTextSmaller">
                                <?= date('d M Y h:i A', $reply['PostedAt']) ?>
                                <a name="reply<?= (int)$reply['id'] ?>"></a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" colspan="2" style="height:125px;">
                            <span class="normalTextSmall notranslate linkify"><?= nl2br(htmlspecialchars($reply['Content'])) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="height:2px;"></td>
                    </tr>
                    <tr>
                        <td align="left" style="height:29px;"></td>
                        <td align="right">
    <?php if (in_array($isAdmin, [2, 3, 4, 5])): ?>
        <div class="admin-tools" style="margin-bottom: 5px;">
            <a href="/Forum/EditReply.aspx.php?ReplyID=<?= (int)$reply['id'] ?>" class="btn btn-sm btn-primary">Edit Reply</a>
            <a href="/Forum/DeleteReply.aspx.php?ReplyID=<?= (int)$reply['id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Are you sure you want to delete this reply?');">Delete Reply</a>
        </div>
    <?php endif; ?>
    <span class="post-response-options">
        <span class="ReportAbuse">
            <span class="AbuseButton">
                <a href="/AbuseReport/ForumPost.aspx?ReplyID=<?= (int)$reply['id'] ?>">Report Abuse</a>
            </span>
        </span>
    </span>
</td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>

	<td colspan="2" style="height:20px;"><table class="forum-table-header" cellspacing="0" cellpadding="0" border="0" style="width:100%;border-collapse:collapse;">
		<tr>
			<td align="left" style="width:50%;"></td><td class="tableHeaderText table-header-right-align" align="right"><a id="ctl00_cphRoblox_PostView1_ctl00_PostList_ctl03_PreviousThread" class="linkSmallBold" href="javascript:__doPostBack(&#39;ctl00$cphRoblox$PostView1$ctl00$PostList$ctl03$PreviousThread&#39;,&#39;&#39;)">Previous Thread</a>&nbsp;<span class="normalTextSmallBold">::</span>&nbsp;<a id="ctl00_cphRoblox_PostView1_ctl00_PostList_ctl03_NextThread" class="linkSmallBold" href="javascript:__doPostBack(&#39;ctl00$cphRoblox$PostView1$ctl00$PostList$ctl03$NextThread&#39;,&#39;&#39;)">Next Thread</a>&nbsp;</td>
		</tr>
		</table></td>
	</tr>
</table>
        <span id="ctl00_cphRoblox_PostView1_ctl00_Pager"><table cellspacing="0" cellpadding="0" border="0" style="width:100%;border-collapse:collapse;">
	<tr>
		<td><span class="normalTextSmallBold">Page 1 of 1</span></td>
	</tr>
</table></span>
    </td>
  </tr>
  <tr>
    <td colSpan="2"><div style="text-align: center; margin: 5px 0;">
<a href="/Forum/NewReply.aspx?PostID=<?= (int)$post['PostID'] ?>" class="btn-control btn-control-medium verified-email-act" id="ctl00_cphRoblox_PostReply1_ctl00_NewPostReply">Add a Reply</a>
</div></td>
  </tr>
  <tr>
    <td align="middle" colSpan="2">
        
        
    </td>
  </tr>
  <tr>
    <td colSpan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" colSpan="2">
      <span id="ctl00_cphRoblox_PostView1_ctl00_Whereami2" NAME="Whereami2">
<div>
    <nobr>
        <a id="ctl00_cphRoblox_PostView1_ctl00_Whereami2_ctl00_LinkHome" class="linkMenuSink notranslate" href="/Forum/Default.aspx">Afterworld Forum</a>
    </nobr>
    <nobr>
        <span id="ctl00_cphRoblox_PostView1_ctl00_Whereami2_ctl00_ForumGroupSeparator" class="normalTextSmallBold"> » </span>
        <a id="ctl00_cphRoblox_PostView1_ctl00_Whereami2_ctl00_LinkForumGroup" class="linkMenuSink notranslate" href="/Forum/ShowForumGroup.aspx?ForumGroupID=1">Afterworld</a>
    </nobr>
    <nobr>
        <span id="ctl00_cphRoblox_PostView1_ctl00_Whereami2_ctl00_ForumSeparator" class="normalTextSmallBold"> » </span>
        <?php if ($forumName): ?>
        <a id="ctl00_cphRoblox_PostView1_ctl00_Whereami1_ctl00_LinkForum" class="linkMenuSink notranslate" href="/Forum/ShowForum.aspx?ForumID=<?= $currentForumId ?>"><?= htmlspecialchars($forumName) ?></a>
        <?php endif; ?>
    </nobr>
</div></span>
    </td>
  </tr>
</table>
</span>
            </td>

            <td>&nbsp;&nbsp;&nbsp;</td>

            <!-- right column -->
            <td id="ctl00_cphRoblox_RightColumn" nowrap="nowrap" width="160" class="RightColumn">
                
                <div style="height: 138px;">&nbsp;</div>
                <div class="roblox-skyscraper" style="height: 620px; margin-top: 10px;">
                    


<iframe class='IframeAdHide'
    allowtransparency="true"
    frameborder="0"
    height="600"
    scrolling="no"
    src="/userads/2/"
    width="160"
    data-js-adtype="iframead"></iframe>

    <div class="ad-annotations " style="width: 160px">
        <span class="ad-identification">Advertisement</span>
        <a class="BadAdButton" href="/Ads/ReportAd.aspx" title="click to report an offensive ad">Report</a>
    </div>

                </div>
            </td>

            <td>&nbsp;&nbsp;&nbsp;</td>
        </tr>
    </table>
    
    <script type="text/javascript">
        var users = [];

        $(".primaryGroupInfo").each(function (index, element) {
            var name = $(element).attr("username");
            if ($.inArray(name, users) == -1)
                users.push(name);
        });

        $.getJSON("/Groups/GetPrimaryGroupInfo.ashx", { "users": users.toString() }, function (data) {
            if (data != null) {
                for (var i = 0; i < users.length; i++) {
                    var username = users[i];
                    var groupInfo = data[username];
                    if (groupInfo != null) {
                        $("span[username='" + username + "']").each(function (i, e) {
                            var groupLink = $("<a href='/Groups/Group.aspx?gid=" + groupInfo.GroupId + "' title='" + groupInfo.GroupName + "'>" + fitStringToWidthSafe(groupInfo.GroupName, 120) + "</a>");
                            $(e).append(groupLink);
                            $(e).show();
                        });
                    }
                }
            }
        });
    </script>
    
    <script type="text/javascript">
        $(window).load(function () {
            var skyscraper = $(".roblox-skyscraper").first();
            var numAds = $("#Body").height() / skyscraper.height();
            for (var i = 2; i < numAds; i++) {
                skyscraper.clone().appendTo("#ctl00_cphRoblox_RightColumn");
	        }
	    });
    </script>
    
                    <div style="clear:both"></div>
                </div>
            </div>
        </div> 
        </div>
        
            <div id="Footer" class="footer-container">
    <div class="FooterNav">
        <a href="/info/Privacy.aspx">Privacy Policy</a>
        &nbsp;|&nbsp;
        <a href="http://corp.roblox.com/advertise-on-roblox" class="roblox-interstitial">Advertise with Us</a>
        &nbsp;|&nbsp;
        <a href="http://corp.roblox.com/press" class="roblox-interstitial">Press</a>
        &nbsp;|&nbsp;
        <a href="http://corp.roblox.com/contact-us" class="roblox-interstitial">Contact Us</a>
        &nbsp;|&nbsp;
        <a href="http://corp.roblox.com/about" class="roblox-interstitial">About Us</a>
        &nbsp;|&nbsp;
        <a href="http://blog.roblox.com" class="roblox-interstitial">Blog</a>
        &nbsp;|&nbsp;
        <a href="http://corp.roblox.com/careers" class="roblox-interstitial">Jobs</a>
        &nbsp;|&nbsp;
        <a href="http://corp.roblox.com/parents" class="roblox-interstitial">Parents</a>
    </div>
    <div class="legal">
        <div class="left">
            <div id="a15b1695-1a5a-49a9-94f0-9cd25ae6c3b2">
    <a href="//privacy.truste.com/privacy-seal/Roblox-Corporation/validation?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" title="TRUSTe Children privacy certification" target="_blank">
        <img style="border: none" src="//privacy-policy.truste.com/privacy-seal/Roblox-Corporation/seal?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" width="133" height="45" alt="TRUSTe Children privacy certification"/>
    </a>
</div>
        </div>
        <div class="right">
            <p class="Legalese">
    ROBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of <a href="http://corp.roblox.com/" ref="footer-smallabout" class="roblox-interstitial">ROBLOX Corporation</a>, ©2015. Patents pending.
    ROBLOX is not sponsored, authorized or endorsed by any producer of plastic building bricks, including The LEGO Group, MEGA Brands, and K'Nex, and no resemblance to the products of these companies is intended. Use of this site signifies your acceptance of the <a href="/info/terms-of-service" ref="footer-terms">Terms and Conditions</a>.
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
        <br /><br />
    </div>
</div>



<div id="pluginObjDiv" style="height:1px;width:1px;visibility:hidden;position: absolute;top: 0;"></div>
<iframe id="downloadInstallerIFrame" style="visibility:hidden;height:0;width:1px;position:absolute"></iframe>

<script type='text/javascript' src='http://js.rbxcdn.com/c58c4d65bf2ed5c05e036534627c45d7.js'></script>

<script type="text/javascript">
    Roblox.Client._skip = '/install/unsupported.aspx';
    Roblox.Client._CLSID = '';
    Roblox.Client._installHost = '';
    Roblox.Client.ImplementsProxy = false;
    Roblox.Client._silentModeEnabled = false;
    Roblox.Client._bringAppToFrontEnabled = false;
    Roblox.Client._currentPluginVersion = '';
    Roblox.Client._eventStreamLoggingEnabled = false;

        
        Roblox.Client._installSuccess = function() {
            if(GoogleAnalyticsEvents){
                GoogleAnalyticsEvents.ViewVirtual('InstallSuccess');
                GoogleAnalyticsEvents.FireEvent(['Plugin','Install Success']);
                if (Roblox.Client._eventStreamLoggingEnabled && typeof Roblox.GamePlayEvents != "undefined") {
                    Roblox.GamePlayEvents.SendInstallSuccess(Roblox.Client._launchMode, play_placeId);
                }
            }
        }
        
    </script>


<div id="PlaceLauncherStatusPanel" style="display:none;width:300px"
     data-new-plugin-events-enabled="True"
     data-event-stream-for-plugin-enabled="True"
     data-event-stream-for-protocol-enabled="True"
     data-is-protocol-handler-launch-enabled="False"
     data-is-user-logged-in="False"
     data-os-name="Unknown"
     data-protocol-name-for-client="roblox-player"
     data-protocol-name-for-studio="roblox-studio">
    <div class="modalPopup blueAndWhite PlaceLauncherModal" style="min-height: 160px">
        <div id="Spinner" class="Spinner" style="padding:20px 0;">
            <img src="http://images.rbxcdn.com/e998fb4c03e8c2e30792f2f3436e9416.gif" height="32" width="32" alt="Progress" />
        </div>
        <div id="status" style="min-height:40px;text-align:center;margin:5px 20px">
            <div id="Starting" class="PlaceLauncherStatus MadStatusStarting" style="display:block">
                Starting Roblox...
            </div>
            <div id="Waiting" class="PlaceLauncherStatus MadStatusField">Connecting to Players...</div>
            <div id="StatusBackBuffer" class="PlaceLauncherStatus PlaceLauncherStatusBackBuffer MadStatusBackBuffer"></div>
        </div>
        <div style="text-align:center;margin-top:1em">
            <input type="button" class="Button CancelPlaceLauncherButton translate" value="Cancel" />
        </div>
    </div>
</div>
<div id="ProtocolHandlerStartingDialog" style="display:none;">
    <div class="modalPopup ph-modal-popup">
        <div class="ph-modal-header">

        </div>
        <div class="ph-logo-row">
            <img src="/images/Logo/logo_meatball.svg" width="90" height="90" alt="R" />
        </div>
        <div class="ph-areyouinstalleddialog-content">
            <p class="larger-font-size">
                ROBLOX is now loading. Get ready to play!
            </p>
            <div class="ph-startingdialog-spinner-row">
                <img src="http://images.rbxcdn.com/4bed93c91f909002b1f17f05c0ce13d1.gif" width="82" height="24" />
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
            <img src="/images/Logo/logo_meatball.svg" width="90" height="90" alt="R" />
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
                <a href="https://en.help.roblox.com/hc/en-us/articles/204473560" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>
<div id="ProtocolHandlerClickAlwaysAllowed" class="ph-clickalwaysallowed" style="display:none;">
    <p class="larger-font-size">
        <span class="rbx-icon-moreinfo"></span>
        Check <b>Remember my choice</b> and click <img src="http://images.rbxcdn.com/7c8d7a39b4335931221857cca2b5430b.png" alt="Launch Application" />  in the dialog box above to join games faster in the future!
    </p>
</div>


<script type='text/javascript' src='http://js.rbxcdn.com/3d3019f5822c52dc67b15cded3c860d8.js'></script>
 
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
            <a href="/Upgrades/BuildersClubMemberships.aspx?ref=vpr" target="_blank" class="btn-medium btn-primary" id="videoPrerollJoinBCButton">Join Builders Club</a>
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
        <div class="RevisedFooter" >
            <div style="width:200px;margin:10px auto 0 auto;">
                <a href="/?returnUrl=%2FForum%2FShowPost.aspx%3FPostID%3D80620879"><div class="RevisedCharacterSelectSignup"></div></a>
                <a class="HaveAccount" href="/newlogin?returnUrl=%2FForum%2FShowPost.aspx%3FPostID%3D80620879">I have an account</a>
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
        //<sl:translate>
        title: "Remove Ads Like This",
        body: "Builders Club members do not see external ads like these.",
        accept: "Upgrade Now",
        decline: "No, thanks"
        //</sl:translate>
    };
</script>  

<div class="ConfirmationModal modalPopup unifiedModal smallModal" data-modal-handle="confirmation" style="display:none;">
    <a class="genericmodal-close ImageButton closeBtnCircle_20h"></a>
    <div class="Title"></div>
    <div class="GenericModalBody">
        <div class="TopBody">
            <div class="ImageContainer roblox-item-image"  data-image-size="small" data-no-overlays data-no-click>
                <img class="GenericModalImage" alt="generic image" />
            </div>
            <div class="Message"></div>
        </div>
        <div class="ConfirmationModalButtonContainer">
            <a href id="roblox-confirm-btn"><span></span></a>
            <a href id="roblox-decline-btn"><span></span></a>
        </div>
        <div class="ConfirmationModalFooter">
        
        </div>  
    </div>   
    <script type="text/javascript">
        Roblox = Roblox || {};
        Roblox.Resources = Roblox.Resources || {};

        //<sl:translate>
        Roblox.Resources.GenericConfirmation = {
            yes: "Yes",
            No: "No",
            Confirm: "Confirm",
            Cancel: "Cancel"
        };
        //</sl:translate>
    </script>
</div>


        <img src="https://secure.adnxs.com/seg?add=550800&t=2" width="1" height="1" style="display:none;"/>

        <script type="text/javascript">
            $(function() {
                if (Roblox.EventStream) {
                    Roblox.EventStream.InitializeEventStream("null", "8", "http://ecsv2.roblox.com/www/e.png");
                }
            });
        </script>
    
        <script>
            $(function () {
                Roblox.DeveloperConsoleWarning.showWarning();
            });
        </script>
    
</body>                
</html>
