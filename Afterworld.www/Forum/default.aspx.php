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

?>

<title>Afterworld - Forum</title>
<link rel='stylesheet' href='/CSS/Base/CSS/main___52c69b42777a376ab8c76204ed8e75e2_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___c7d63abcc3de510b8a7b8ab6d435f9b6_m.css' />
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
			<td class="LeftColumn">&nbsp;&nbsp;&nbsp;</td>
			
            <!-- center column -->
			<td id="ctl00_cphRoblox_CenterColumn" width="95%" class="CenterColumn">
				<br>
            	<span id="ctl00_cphRoblox_NavigationMenu2">

<div id="forum-nav" style="text-align: right">
	<a id="ctl00_cphRoblox_NavigationMenu2_ctl00_HomeMenu" class="menuTextLink first" href="/Forum/Default.aspx">Home</a>
	<a id="ctl00_cphRoblox_NavigationMenu2_ctl00_SearchMenu" class="menuTextLink" href="/Forum/Search/default.aspx">Search</a>
	<?php
	if (isset($_COOKIE['_ROBLOSECURITY']) && ($userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY'])) && isset($userInfo['UserId'])) {
       echo "<a id='ctl00_cphRoblox_NavigationMenu2_ctl00_SearchMenu' class='menuTextLink' href='/Forum/MyForums.aspx'>MyForums</a>";
    }
    ?>
	
	
	
	
	
	
	
</div>
</span>
				<br>
				<table cellpadding="0" cellspacing="2" width="100%">
					<tr>
						<td align="left">
							<span class="normalTextSmallBold">Current time: </span><span class="normalTextSmall"><?php echo date('F j, g:i A'); ?></span>
						</td>
						<td align="right">
						    <span id="ctl00_cphRoblox_SearchRedirect">

<span>
    <span class="normalTextSmallBold">Search Afterworld Forums:</span>
    <input name="SearchText" type="text" maxlength="50" id="SearchText" class="notranslate" size="20"/>
    <input type="submit" name="SearchButton" value="Go" id="SearchButton" class="translate btn-control btn-control-medium forum-btn-control-medium"/>
</span></span>
							
						</td>
					</tr>
				</table>
                <div style="height:7px;"></div>
<?php
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

$groupNames = [
    1 => 'Afterworld',
    2 => 'Club Houses',
    9 => 'Game Creation and Development',
    6 => 'Entertainment',
];

$forumDescriptions = [
    46 => 'The area for discussions purely about Afterworld – the features, the games, and company news.',
    14 => 'Seeking account or technical help? Post your questions here.',
    21 => 'Do you have a suggestion and ideas for Afterworld? Share your feedback here.',
    54 => 'Check here to see the crazy things Afterworld is doing. Contest information can be found here. Afterworld is going to be at various Maker Faires and conferences around the globe. Discuss those events here!',
    13 => 'A popular hangout where Afterworldians talk about various topics.',
    18 => 'When no other forum makes sense for your post, Off Topic will help it make even less sense.',
    32 => 'Talk about what’s going on in your Clans, Groups, Companies, and Guilds, and about the Groups feature in general.',
    35 => 'A fast paced community dedicated to mastering the Limited Trades and Sales market, and divining the subtleties of the Afterworld Currency Exchange.',
    62 => 'This is where you show off your awesome creations, talk about how to advertise your game or share your marketing and sale tactics.',
    40 => 'This is the forum to get help, talk about future Afterworld game ideas, or gather an awesome building team.',
    33 => 'This is the place for discussion about scripting. Anything about scripting that is not a help request or topic belongs here.',
    42 => 'Talk about your favorite video and computer games outside of Afterworld, with other fanatical video gamers!',
    52 => 'This forum is for your sweet game play footage or that awesome viral video you saw on YouTube. Also to talk about your favorite Twitch streamers.',
    26 => 'For the many leagues of Afterworld sports, real life sports fans.',
    24 => 'Come here to find what Afterworldians think is a must read, see or hear.',
    23 => 'The forum for story telling and imagination. Start a role-playing thread here involving your fictional characters, or role-play out a scenario with other players.',
];


foreach ($forumGroups as $groupId => $forums) {
    $groupName = htmlspecialchars($groupNames[$groupId] ?? "Group {$groupId}");
    echo <<<HTML
<table cellpadding="2" cellspacing="1" border="0" width="100%" class="table">
<tr class="table-header forum-table-header">
    <th class="first" colspan="2">
        <a class="forumTitle" href="/Forum/ShowForumGroup.aspx?ForumGroupID={$groupId}">{$groupName}</a>
    </th>
    <th style="width:50px;white-space:nowrap;">&nbsp;&nbsp;Threads&nbsp;&nbsp;</th>
    <th style="width:50px;white-space:nowrap;">&nbsp;&nbsp;Posts&nbsp;&nbsp;</th>
    <th style="width:135px;white-space:nowrap;">&nbsp;Last Post&nbsp;</th>
</tr>
HTML;
    foreach ($forums as $forumId => $forumTitle) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM forums WHERE ForumId = ?");
        $stmt->execute([$forumId]);
        $totalPosts = (int)$stmt->fetchColumn();
        $latestStmt = $pdo->prepare("SELECT Id, PostedAt, UserId FROM forums WHERE ForumId = ? ORDER BY PostedAt DESC LIMIT 1");
        $latestStmt->execute([$forumId]);
        $latestPost = $latestStmt->fetch(PDO::FETCH_ASSOC);
        if ($latestPost) {
            $postId = (int)$latestPost['Id'];
            $postedAt = date("h:i A", $latestPost['PostedAt']);
            $userId = (int)$latestPost['UserId'];
            $userStmt = $pdo->prepare("SELECT Username FROM users WHERE UserId = ?");
            $userStmt->execute([$userId]);
            $username = htmlspecialchars($userStmt->fetchColumn() ?? 'Unknown');
        } else {
            $postId = 0;
            $postedAt = 'N/A';
            $username = 'N/A';
        }
        $escapedTitle = htmlspecialchars($forumTitle);
        $forumDesc = htmlspecialchars($forumDescriptions[$forumId] ?? '');
        echo <<<HTML
<tr class="forum-table-row">
    <td colspan="2" style="width:80%;">
        <a class="forum-summary" href="/Forum/ShowForum.aspx?ForumID={$forumId}">
            <div class="forumTitle">{$escapedTitle}</div>
            <div>{$forumDesc}</div>
        </a>
    </td>
    <td class="forum-centered-cell" align="center"><span class="normalTextSmaller">WIP</span></td>
    <td class="forum-centered-cell" align="center"><span class="normalTextSmaller">{$totalPosts}</span></td>
    <td align="center">
HTML;
        if ($postId) {
            echo <<<HTML
        <a class="last-post" href="/Forum/ShowPost.aspx?PostID={$postId}">
            <span class="normalTextSmaller">
                <div><b>{$postedAt}</b></div>
            </span>
            <span class="normalTextSmaller notranslate">
                <div class="notranslate">{$username}</div>
            </span>
        </a>
HTML;
        } else {
            echo '<span class="normalTextSmaller">No posts yet</span>';
        }

        echo "</td></tr>\n";
    }

    echo "</table>\n";
}
?>
				<p></p>
			</td>

			<td class="CenterColumn">&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>


                    <div style="clear:both"></div>
                </div>
            </div>
        </div> 
        </div>
        
            <div id="Footer" class="footer-container">
    <div class="FooterNav">
        <a href="https://web.archive.org/web/20151105094539/http://www.roblox.com/info/Privacy.aspx">Privacy Policy</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20151105094539/http://corp.roblox.com/advertise-on-roblox" class="roblox-interstitial">Advertise with Us</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20151105094539/http://corp.roblox.com/press" class="roblox-interstitial">Press</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20151105094539/http://corp.roblox.com/contact-us" class="roblox-interstitial">Contact Us</a>
        &nbsp;|&nbsp;
            <a href="https://web.archive.org/web/20151105094539/http://corp.roblox.com/about" class="roblox-interstitial">About Us</a>
&nbsp;|&nbsp;        <a href="https://web.archive.org/web/20151105094539/http://blog.roblox.com/">Blog</a>
        &nbsp;|&nbsp;
            <a href="https://web.archive.org/web/20151105094539/http://corp.roblox.com/careers" class="roblox-interstitial">Jobs</a>
&nbsp;|&nbsp;        <a href="https://web.archive.org/web/20151105094539/http://corp.roblox.com/parents" class="roblox-interstitial">Parents</a>
    </div>
    <div class="legal">
            <div class="left">
                <div id="a15b1695-1a5a-49a9-94f0-9cd25ae6c3b2">
    <a href="//web.archive.org/web/20151105094539/http://privacy.truste.com/privacy-seal/Roblox-Corporation/validation?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" title="TRUSTe Children privacy certification" target="_blank">
        <img style="border: none" src="//web.archive.org/web/20151105094539im_/http://privacy-policy.truste.com/privacy-seal/Roblox-Corporation/seal?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" width="133" height="45" alt="TRUSTe Children privacy certification"/>
    </a>
</div>
            </div>
            <div class="right">
                <p class="Legalese">
    ROBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of <a href="https://web.archive.org/web/20151105094539/http://corp.roblox.com/" ref="footer-smallabout" class="roblox-interstitial">ROBLOX Corporation</a>, ©2015. Patents pending.
    ROBLOX is not sponsored, authorized or endorsed by any producer of plastic building bricks, including The LEGO Group, MEGA Brands, and K'Nex, and no resemblance to the products of these companies is intended.
    Use of this site signifies your acceptance of the <a href="https://web.archive.org/web/20151105094539/http://www.roblox.com/info/terms-of-service" ref="footer-terms">Terms and Conditions</a>.
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
<script type="text/javascript" src="https://web.archive.org/web/20151105094539js_/http://js.rbxcdn.com/6077529ce969aded942c2ec9b40c91c0.js"></script>

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
            <img src="https://web.archive.org/web/20151105094539im_/http://images.rbxcdn.com/e998fb4c03e8c2e30792f2f3436e9416.gif" height="32" width="32" alt="Progress"/>
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
            <img src="/web/20151105094539im_/http://roblox.com/images/Logo/logo_meatball.svg" width="90" height="90" alt="R"/>
        </div>
        <div class="ph-areyouinstalleddialog-content">
            <p class="larger-font-size">
                ROBLOX is now loading. Get ready to play!
            </p>
            <div class="ph-startingdialog-spinner-row">
                <img src="https://web.archive.org/web/20151105094539im_/http://images.rbxcdn.com/4bed93c91f909002b1f17f05c0ce13d1.gif" width="82" height="24"/>
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
            <img src="/web/20151105094539im_/http://roblox.com/images/Logo/logo_meatball.svg" width="90" height="90" alt="R"/>
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
                <a href="https://web.archive.org/web/20151105094539/https://en.help.roblox.com/hc/en-us/articles/204473560" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>
<div id="ProtocolHandlerClickAlwaysAllowed" class="ph-clickalwaysallowed" style="display:none;">
    <p class="larger-font-size">
        <span class="rbx-icon-moreinfo"></span>
        Check <b>Remember my choice</b> and click <img src="https://web.archive.org/web/20151105094539im_/http://images.rbxcdn.com/7c8d7a39b4335931221857cca2b5430b.png" alt="Launch Application"/>  in the dialog box above to join games faster in the future!
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
            <a href="https://web.archive.org/web/20151105094539/https://www.roblox.com/premium/membership?ctx=preroll" target="_blank" class="btn-medium btn-primary" id="videoPrerollJoinBCButton">Join Builders Club</a>
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
                <a href="https://web.archive.org/web/20151105094539/http://www.roblox.com/?returnUrl=http%3A%2F%2Fwww.roblox.com%2FForum%2FDefault.aspx"><div class="RevisedCharacterSelectSignup"></div></a>
                <a class="HaveAccount" href="https://web.archive.org/web/20151105094539/https://www.roblox.com/newlogin?returnUrl=http%3A%2F%2Fwww.roblox.com%2FForum%2FDefault.aspx">I have an account</a>
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
                    Roblox.EventStream.InitializeEventStream("null", "8", "//web.archive.org/web/20151105094539/http://ecsv2.roblox.com/www/e.png");
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