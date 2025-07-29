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
        <h2 id="ctl00_cphRoblox_PostView1_ctl00_PostTitle" CssClass="notranslate" style="margin-bottom:20px">Preview Message</h2>
    </td>
  </tr>
  <tr>
  <td align="left" colspan="2">
  <span class="normalTextSmallBold">Hi</span>
  <br>
  <span class="normalTextSmall">Hello.</span>
  <br>
  <div style="margin-top: 10px;">
        <button type="button" onclick="window.location.href='/Forum/ShowForum.aspx?ForumID=46'" style="padding: 3px 10px; margin-right: 4px;">Edit</button>
        <button type="button" onclick="window.location.href='/Forum/ShowForum.aspx?ForumID=46'" style="padding: 3px 10px; margin-right: 4px;">Post</button>
    </div>
  	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td align="left" valign="top">
			<span id="ctl00_cphRoblox_ThreadView1_ctl00_Whereami2" name="Whereami2">
<div>
    <nobr>
        <a id="ctl00_cphRoblox_ThreadView1_ctl00_Whereami2_ctl00_LinkHome" class="linkMenuSink notranslate" href="/Forum/Default.aspx">Afterworld Forum</a>
    </nobr>
    <nobr>
        <span id="ctl00_cphRoblox_ThreadView1_ctl00_Whereami2_ctl00_ForumGroupSeparator" class="normalTextSmallBold"> » </span>
        <a id="ctl00_cphRoblox_ThreadView1_ctl00_Whereami2_ctl00_LinkForumGroup" class="linkMenuSink notranslate" href="/Forum/ShowForumGroup.aspx?ForumGroupID=1">Afterworld</a>
    </nobr>
    <nobr>
        <span id="ctl00_cphRoblox_ThreadView1_ctl00_Whereami2_ctl00_ForumSeparator" class="normalTextSmallBold"> » </span>
        <a id="ctl00_cphRoblox_ThreadView1_ctl00_Whereami2_ctl00_LinkForum" class="linkMenuSink notranslate" href="/Forum/ShowForum.aspx?ForumID=<?= $currentForumId ?>"><?= htmlspecialchars($forumName) ?></a>
    </nobr>
</div></span>
			
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
</span>
			</td>

			<td class="CenterColumn">&nbsp;&nbsp;&nbsp;</td>	
            </td>
            <td class="RightColumn">&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>
    
    
                    <div style="clear:both"></div>
                </div>
            </div>
        </div> 
        </div>
        
            <div id="Footer" class="footer-container">
    <div class="FooterNav">
        <a href="/info/Privacy.aspx">Privacy Policy</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20151106063046/http://corp.roblox.com/advertise-on-roblox" class="roblox-interstitial">Advertise with Us</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20151106063046/http://corp.roblox.com/press" class="roblox-interstitial">Press</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20151106063046/http://corp.roblox.com/contact-us" class="roblox-interstitial">Contact Us</a>
        &nbsp;|&nbsp;
            <a href="https://web.archive.org/web/20151106063046/http://corp.roblox.com/about" class="roblox-interstitial">About Us</a>
&nbsp;|&nbsp;        <a href="https://web.archive.org/web/20151106063046/http://blog.roblox.com/">Blog</a>
        &nbsp;|&nbsp;
            <a href="https://web.archive.org/web/20151106063046/http://corp.roblox.com/careers" class="roblox-interstitial">Jobs</a>
&nbsp;|&nbsp;        <a href="https://web.archive.org/web/20151106063046/http://corp.roblox.com/parents" class="roblox-interstitial">Parents</a>
    </div>
    <div class="legal">
            <div class="left">
                <div id="a15b1695-1a5a-49a9-94f0-9cd25ae6c3b2">
    <a href="//web.archive.org/web/20151106063046/http://privacy.truste.com/privacy-seal/Roblox-Corporation/validation?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" title="TRUSTe Children privacy certification" target="_blank">
        <img style="border: none" src="//web.archive.org/web/20151106063046im_/http://privacy-policy.truste.com/privacy-seal/Roblox-Corporation/seal?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" width="133" height="45" alt="TRUSTe Children privacy certification"/>
    </a>
</div>
            </div>
            <div class="right">
                <p class="Legalese">
    ROBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of <a href="https://web.archive.org/web/20151106063046/http://corp.roblox.com/" ref="footer-smallabout" class="roblox-interstitial">ROBLOX Corporation</a>, ©2015. Patents pending.
    ROBLOX is not sponsored, authorized or endorsed by any producer of plastic building bricks, including The LEGO Group, MEGA Brands, and K'Nex, and no resemblance to the products of these companies is intended.
    Use of this site signifies your acceptance of the <a href="/info/terms-of-service" ref="footer-terms">Terms and Conditions</a>.
</p>
            </div>
        <div class="clear"></div>
    </div>

</div>
        
        </div></div>
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
        <br/><br/>
    </div>
</div>



<div id="pluginObjDiv" style="height:1px;width:1px;visibility:hidden;position: absolute;top: 0;"></div>
<iframe id="downloadInstallerIFrame" style="visibility:hidden;height:0;width:1px;position:absolute"></iframe>

<script type="text/javascript" src="https://web.archive.org/web/20151106063046js_/http://js.rbxcdn.com/6077529ce969aded942c2ec9b40c91c0.js"></script>

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


<div id="PlaceLauncherStatusPanel" style="display:none;width:300px" data-new-plugin-events-enabled="True" data-event-stream-for-plugin-enabled="True" data-event-stream-for-protocol-enabled="True" data-is-protocol-handler-launch-enabled="False" data-is-user-logged-in="False" data-os-name="Unknown" data-protocol-name-for-client="roblox-player" data-protocol-name-for-studio="roblox-studio" data-protocol-url-includes-launchtime="true" data-protocol-detection-enabled="true">
    <div class="modalPopup blueAndWhite PlaceLauncherModal" style="min-height: 160px">
        <div id="Spinner" class="Spinner" style="padding:20px 0;">
            <img src="https://web.archive.org/web/20151106063046im_/http://images.rbxcdn.com/e998fb4c03e8c2e30792f2f3436e9416.gif" height="32" width="32" alt="Progress"/>
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
            <img src="/images/Logo/logo_meatball.svg" width="90" height="90" alt="R"/>
        </div>
        <div class="ph-areyouinstalleddialog-content">
            <p class="larger-font-size">
                ROBLOX is now loading. Get ready to play!
            </p>
            <div class="ph-startingdialog-spinner-row">
                <img src="https://web.archive.org/web/20151106063046im_/http://images.rbxcdn.com/4bed93c91f909002b1f17f05c0ce13d1.gif" width="82" height="24"/>
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
            <img src="/images/Logo/logo_meatball.svg" width="90" height="90" alt="R"/>
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
                <a href="https://web.archive.org/web/20151106063046/https://en.help.roblox.com/hc/en-us/articles/204473560" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>
<div id="ProtocolHandlerClickAlwaysAllowed" class="ph-clickalwaysallowed" style="display:none;">
    <p class="larger-font-size">
        <span class="rbx-icon-moreinfo"></span>
        Check <b>Remember my choice</b> and click <img src="https://web.archive.org/web/20151106063046im_/http://images.rbxcdn.com/7c8d7a39b4335931221857cca2b5430b.png" alt="Launch Application"/>  in the dialog box above to join games faster in the future!
    </p>
</div>


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
            <a href="https://web.archive.org/web/20151106063046/https://www.roblox.com/premium/membership?ctx=preroll" target="_blank" class="btn-medium btn-primary" id="videoPrerollJoinBCButton">Join Builders Club</a>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            if (Roblox.VideoPreRoll) {
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
            }
        });
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
                <a href="/?returnUrl=http%3A%2F%2Fforum.roblox.com%2FForum%2FShowForum.aspx%3FForumID%3D46"><div class="RevisedCharacterSelectSignup"></div></a>
                <a class="HaveAccount" href="https://web.archive.org/web/20151106063046/https://www.roblox.com/newlogin?returnUrl=http%3A%2F%2Fforum.roblox.com%2FForum%2FShowForum.aspx%3FForumID%3D46">I have an account</a>
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
            <div class="ImageContainer roblox-item-image" data-image-size="small" data-no-overlays data-no-click>
                <img class="GenericModalImage" alt="generic image"/>
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


    
        <script type="text/javascript">
            $(function() {
                if (Roblox.EventStream) {
                    Roblox.EventStream.InitializeEventStream("null", "8", "//web.archive.org/web/20151106063046/http://ecsv2.roblox.com/www/e.png");
                }
            });
        </script>
    
        <script>
            $(function () {
                Roblox.DeveloperConsoleWarning.showWarning();
            });
        </script>
    

    <script type="text/javascript">
        $(function () {
            Roblox.CookieUpgrader.domain = 'roblox.com';
            Roblox.CookieUpgrader.upgrade("GuestData", { expires: Roblox.CookieUpgrader.thirtyYearsFromNow });
            Roblox.CookieUpgrader.upgrade("RBXSource", { expires: function (cookie) { return Roblox.CookieUpgrader.getExpirationFromCookieValue("rbx_acquisition_time", cookie); } });
            Roblox.CookieUpgrader.upgrade("RBXViralAcquisition", { expires: function (cookie) { return Roblox.CookieUpgrader.getExpirationFromCookieValue("time", cookie); } });
            
                Roblox.CookieUpgrader.upgrade("RBXMarketing", { expires: Roblox.CookieUpgrader.thirtyYearsFromNow });
            
                        
                Roblox.CookieUpgrader.upgrade("RBXSessionTracker", { expires: Roblox.CookieUpgrader.fourHoursFromNow });
            
        });
    </script>

</body>                
</html>