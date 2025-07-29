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
?>

<title>Afterworld - Builders Club</title>
<link rel='stylesheet' href='/CSS/Base/CSS/main___3cf17678f5d36ad2b9b72dd3439177be_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___2f5e37edbf24411f0229cfa2dd229eb7_m.css' />

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
                <div id="BodyWrapper">
            
            <div id="RepositionBody">
                <div id="Body" style="">
                    
    <style>
        #Body {
            width: 970px;
            padding: 10px;
        }
    </style>
    
		   
<div id="BCPageContainer">
	<div id="UserDataInfo" data-auth="false" data-active-bc="false"></div>
	<div class="header">
		<span><h1>Upgrade to Afterworld Builders Club</h1></span>
	</div>
	<div class="left-column">
		<table cellspacing="0" border="0">
			<thead class="product-title">
				<tr>
					<td class="center-bold">
						<h2 class="product-space">Free</h2>
						<img src="https://s3.amazonaws.com/images.roblox.com/77add140640c3388e6c9603bc5983846.png" alt="bc"/>
					</td>
					<td class="center-bold">
						<h2 class="product-space">Classic</h2>
						<img src="https://s3.amazonaws.com/images.roblox.com/ba707f47bb20a1f4804da461fb5d3c31.png" alt="bc"/>
					</td>
					<td class="center-bold">
						<h2 class="product-space">Turbo</h2>
						<img src="https://s3.amazonaws.com/images.roblox.com/d7eb3ed186e351d99ce8c11503675721.png" alt="tbc"/>
					</td>
					<td class="center-bold">
						<h2 class="product-space">Outrageous</h2>
						<img src="https://s3.amazonaws.com/images.roblox.com/ca1d0aef06c5fc06a2d8b23aea5e20d2.png" alt="obc"/>
					</td>
				</tr>
			</thead>
				<tbody class="product-summary summary-big">
			<tr>
				<td class="divider-top">
					<span class="product-description">Daily ROBUX</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product ">
					R$15
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					R$35
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        R$60
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Active Places</span>
					<span class="nbc-product">1</span>
				</td>
				<td class="divider-top bc-product ">
					10
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					25
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        100!
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Join Groups</span>
					<span class="nbc-product">5</span>
				</td>
				<td class="divider-top bc-product ">
					10
				</td>
				<td class="divider-top tbc-product ">
					20
				</td>
			    <td class="divider-top obc-product ">
			        100!
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Create Groups</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product ">
					10
				</td>
				<td class="divider-top tbc-product ">
					20
				</td>
			    <td class="divider-top obc-product ">
			        100!
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Signing Bonus*</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product ">
					R$100
				</td>
				<td class="divider-top tbc-product ">
					R$100
				</td>
			    <td class="divider-top obc-product ">
			        R$100
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Paid Access</span>
					<span class="nbc-product">10%</span>
				</td>
				<td class="divider-top bc-product ">
					70%
				</td>
				<td class="divider-top tbc-product ">
					70%
				</td>
			    <td class="divider-top obc-product ">
			        70%
			    </td>
			</tr>
                    <tr>
                <td colspan="4">* Signing bonus is for first time membership purchase only.</td>
            </tr>
	</tbody>

<tbody class="product-grid">
			<tr>
		<td class="product-cell divider-left">
		</td>
		<td class="product-cell divider-left">
				<div class="product-cell">
						<div class="product-text">
    		<h3>Boosting our discord server (ab. 1-2 boosts)</h3>
	</div>


				</div>
		</td>
		<td class="product-cell divider-left">
				<div class="product-cell">
						<div class="product-text">
		<h3>Giveaways or event winners</h3>
	</div>


				</div>
		</td>
		<td class="product-cell divider-left">
				<div class="product-cell">
						<div class="product-text">
		<h3>Giveaways or event winners</h3>
	</div>

				</div>
		</td>
			</tr>
</tbody>
	<tbody class="product-summary summary-small">
			<tr>
				<td class="divider-top">
					<span class="product-description">Ad Free</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product 		emphasis
">
					✔
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					✔
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        ✔
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Sell Stuff</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product 		emphasis
">
					✔
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					✔
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        ✔
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Virtual Hat</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product 		emphasis
">
					✔
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					✔
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        ✔
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Bonus Gear</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product 		emphasis
">
					✔
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					✔
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        ✔
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Create Badges</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product 		emphasis
">
					✔
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					✔
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        ✔
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">BC Beta Features</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product 		emphasis
">
					✔
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					✔
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        ✔
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Personal Servers</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product 		emphasis
">
					✔
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					✔
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        ✔
			    </td>
			</tr>
			<tr>
				<td class="divider-top">
					<span class="product-description">Trade System</span>
					<span class="nbc-product">No</span>
				</td>
				<td class="divider-top bc-product 		emphasis
">
					✔
				</td>
				<td class="divider-top tbc-product 		emphasis
">
					✔
				</td>
			    <td class="divider-top obc-product 		emphasis
">
			        ✔
			    </td>
			</tr>
        	</tbody>






		</table>
	</div>
	<div class="right-column">

<div id="RightColumnWrapper">
    <div class="cell cellDivider">
        For questions: <span class="SL_swap" id="CsEmailLink"><a href="https://discord.gg/ctCUkEN3tY">Join our discord.</a></span>
    </div>
    <div class="">


<div class="GenericModal modalPopup unifiedModal smallModal" style="display:none;">
    <div class="Title"></div>
    <div class="GenericModalBody">
        <div>
            <div class="ImageContainer">
                <img class="GenericModalImage" alt="generic image"/>
            </div>
            <div class="Message"></div>
        </div>
        <div class="clear"></div>
        <div id="GenericModalButtonContainer" class="GenericModalButtonContainer">
            <a class="ImageButton btn-neutral btn-large roblox-ok">OK</a>
        </div>  
    </div>
</div>

    </div>
    <div class="cell cellDivider">
        <h3>Buy ROBUX</h3>
        <p>ROBUX is the virtual currency used in many of our online games. You can also use ROBUX for finding a great look for your character. Get cool gear to take into multiplayer battles. Buy Limited items to sell and trade. You’ll need ROBUX to make it all happen. What are you waiting for?</p>
        <p>
            <a href="https://aftwld.xyz/upgrades/robux.aspx" class="btn-medium btn-primary">Buy ROBUX</a>
        </p>
        <h3>Buy ROBUX with</h3><br/><br/>
        <a href="https://aftwld.xyz/rixtypin"><img src="https://s3.amazonaws.com/images.roblox.com/028e16231452041ab6d702ea467e96dd.png" alt="rixty"/></a><br/><br/>
        <a href="https://itunes.apple.com/us/app/roblox-mobile/id431946152?mt=8"><img src="https://s3.amazonaws.com/images.roblox.com/70deff83e869746b0bbc41a86f420844.png" alt="itunes"/></a>
    </div>
        <div class="cell cellDivider">
            <h3>Gift Cards</h3><br/>
            <a href="httsp://aftwld.xyz/upgrades/giftcards.aspx" class="giftCardImage"><img src="https://s3.amazonaws.com/images.roblox.com/bf9f4b65f937ad01f07ae6714eaba723.png" alt="giftcard"/></a>
            <div>
                    <div class="giftCardButton">
                        <a href="/web/20150602221452/http://roblox.com/upgrades/giftcards.aspx" class="btn-small btn-primary">Buy Card</a>
                    </div>
                                    <div><a href="/web/20150602221452/http://roblox.com/gamecard" class="redeemLink">Redeem card</a></div>
                <div style="clear: both"></div>
            </div>
        </div>
    <div class="cell cellDivider">
        <h3>Game Cards</h3>
        <a href="/Home"><img alt="ROBLOX Gamecards" src="https://s3.amazonaws.com/images.roblox.com/863c65342816d665de28411cf47cde42.png"/></a>
        <div class="gameCardControls">
            <div class="gameCardButton">
                <a href="/Home" class="btn-small btn-primary">Where to Buy</a>
            </div>
            <div><a href="/promocodes.aspx" class="redeemLink">Redeem Card</a></div>
            <div style="clear: both"></div>
        </div>
    </div>
    <div class="cell">
        <h3>Parents</h3>
        <p>Learn more about Builders Club and how we help <a href="https://corp.aftwld.xyz/parents" class="roblox-interstitial">keep kids safe.</a></p>
        <h3>Cancellation</h3>
        <p>You can turn off membership auto renewal at any time before the renewal date and you will continue to receive Builders Club privileges for the remainder of the currently paid period. To turn off membership auto renewal, please click the 'Cancel Membership Renewal button' on the <a href="/web/20150602221452/http://roblox.com/my/account?tab=billing" class="roblox-interstitial">Billing</a> tab of the Settings page and confirm the cancellation.</p>
    </div>
</div>
	</div>
<div id="dialog-confirmation" style="display: none;"></div>
</div>
                    <div style="clear:both"></div>
                </div>
            </div>
        </div> 
        </div>
        
            <div id="Footer" class="footer-container">
    <div class="FooterNav">
        <a href="/web/20150602221452/http://roblox.com/info/Privacy.aspx">Privacy Policy</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20150602221452/http://corp.roblox.com/advertise-on-roblox" class="roblox-interstitial">Advertise with Us</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20150602221452/http://corp.roblox.com/press" class="roblox-interstitial">Press</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20150602221452/http://corp.roblox.com/contact-us" class="roblox-interstitial">Contact Us</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20150602221452/http://corp.roblox.com/about" class="roblox-interstitial">About Us</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20150602221452/http://blog.roblox.com/" class="roblox-interstitial">Blog</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20150602221452/http://corp.roblox.com/careers" class="roblox-interstitial">Jobs</a>
        &nbsp;|&nbsp;
        <a href="https://web.archive.org/web/20150602221452/http://corp.roblox.com/parents" class="roblox-interstitial">Parents</a>
    </div>
    <div class="legal">
        <div class="left">
            <div id="a15b1695-1a5a-49a9-94f0-9cd25ae6c3b2">
    <a href="//web.archive.org/web/20150602221452/http://privacy.truste.com/privacy-seal/Roblox-Corporation/validation?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" title="TRUSTe Children privacy certification" target="_blank">
        <img style="border: none" src="//web.archive.org/web/20150602221452im_/http://privacy-policy.truste.com/privacy-seal/Roblox-Corporation/seal?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" width="133" height="45" alt="TRUSTe Children privacy certification"/>
    </a>
</div>
        </div>
        <div class="right">
            <p class="Legalese">
    ROBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of <a href="https://web.archive.org/web/20150602221452/http://corp.roblox.com/" ref="footer-smallabout" class="roblox-interstitial">ROBLOX Corporation</a>, ©2015. Patents pending.
    ROBLOX is not sponsored, authorized or endorsed by any producer of plastic building bricks, including The LEGO Group, MEGA Brands, and K'Nex, and no resemblance to the products of these companies is intended. Use of this site signifies your acceptance of the <a href="/web/20150602221452/http://roblox.com/info/terms-of-service" ref="footer-terms">Terms and Conditions</a>.
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
        <br/><br/>
    </div>
</div>



<div id="pluginObjDiv" style="height:1px;width:1px;visibility:hidden;position: absolute;top: 0;"></div>
<iframe id="downloadInstallerIFrame" style="visibility:hidden;height:0;width:1px;position:absolute"></iframe>

<script type="text/javascript" src="https://web.archive.org/web/20150602221452js_/https://s3.amazonaws.com/js.roblox.com/c58c4d65bf2ed5c05e036534627c45d7.js"></script>

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


<div id="PlaceLauncherStatusPanel" style="display:none;width:300px" data-new-plugin-events-enabled="True" data-event-stream-for-plugin-enabled="True" data-event-stream-for-protocol-enabled="True" data-is-protocol-handler-launch-enabled="False" data-is-user-logged-in="False" data-os-name="Unknown" data-protocol-name-for-client="roblox-player" data-protocol-name-for-studio="roblox-studio">
    <div class="modalPopup blueAndWhite PlaceLauncherModal" style="min-height: 160px">
        <div id="Spinner" class="Spinner" style="padding:20px 0;">
            <img src="https://web.archive.org/web/20150602221452im_/https://s3.amazonaws.com/images.roblox.com/e998fb4c03e8c2e30792f2f3436e9416.gif" height="32" width="32" alt="Progress"/>
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
            <img src="/web/20150602221452im_/http://roblox.com/images/Logo/logo_meatball.svg" width="90" height="90" alt="R"/>
        </div>
        <div class="ph-areyouinstalleddialog-content">
            <p class="larger-font-size">
                ROBLOX is now loading. Get ready to play!
            </p>
            <div class="ph-startingdialog-spinner-row">
                <img src="https://web.archive.org/web/20150602221452im_/https://s3.amazonaws.com/images.roblox.com/4bed93c91f909002b1f17f05c0ce13d1.gif" width="82" height="24"/>
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
            <img src="/web/20150602221452im_/http://roblox.com/images/Logo/logo_meatball.svg" width="90" height="90" alt="R"/>
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
                <a href="https://web.archive.org/web/20150602221452/https://en.help.roblox.com/hc/en-us/articles/204473560" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>
<div id="ProtocolHandlerClickAlwaysAllowed" class="ph-clickalwaysallowed" style="display:none;">
    <p class="larger-font-size">
        <span class="rbx-icon-moreinfo"></span>
        Check <b>Remember my choice</b> and click <img src="https://web.archive.org/web/20150602221452im_/https://s3.amazonaws.com/images.roblox.com/7c8d7a39b4335931221857cca2b5430b.png" alt="Launch Application"/>  in the dialog box above to join games faster in the future!
    </p>
</div>


<script type="text/javascript" src="https://web.archive.org/web/20150602221452js_/https://s3.amazonaws.com/js.roblox.com/e59cc9c921c25a5cd61d18f0a7fd5ac8.js"></script>
 
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
            <a href="/web/20150602221452/http://roblox.com/Upgrades/BuildersClubMemberships.aspx?ref=vpr" target="_blank" class="btn-medium btn-primary" id="videoPrerollJoinBCButton">Join Builders Club</a>
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
                <a href="/web/20150602221452/http://roblox.com/?returnUrl=%2FUpgrades%2FBuildersClubMemberships.aspx"><div class="RevisedCharacterSelectSignup"></div></a>
                <a class="HaveAccount" href="/web/20150602221452/http://roblox.com/newlogin?returnUrl=%2FUpgrades%2FBuildersClubMemberships.aspx">I have an account</a>
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
                    Roblox.EventStream.InitializeEventStream("null", "8", "https://web.archive.org/web/20150602221452/http://public.ecs.roblox.com/www/e.png");
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