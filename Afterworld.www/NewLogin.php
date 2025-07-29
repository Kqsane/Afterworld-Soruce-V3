<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $username = $_POST['Username'] ?? $_POST["username"];
	 $password = $_POST['Password'] ?? $_POST['password'];
	 if(!isset($username) || !isset($password)){
		 exit("Please full fill the required information in order to log in");
	 }
	 $functionload = login($username, $password);
	if($functionload == true){
		header("Location: /home");
		exit;
	}else{
		exit("Username or Password is incorrect.");
	}
}
?>
<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="en" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
        <!-- MachineID: WEB281 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Language" content="en-us"/>
    <meta name="author" content="ROBLOX Corporation"/>
    <meta name="description" content="User-generated MMO gaming site for kids, teens, and adults. Players architect their own worlds. Builders create free online games that simulate the real world. Create and play amazing 3D games. An online gaming cloud and distributed physics engine."/>
    <meta name="keywords" content="free games, online games, building games, virtual worlds, free mmo, gaming cloud, physics engine"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    

    <title>aftwld.xyz</title>
    <link rel="icon" type="image/vnd.microsoft.icon" href="https://devopstest1.aftwld.xyz/favicon.ico"/>
    
    

<link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css"/>

    
<link rel="stylesheet" href="/CSS/Base/CSS/page___3297996122eaf8389c3412b669a3c56c_m.css"/>

        
	<script type="text/javascript">

        var _gaq = _gaq || [];

		    _gaq.push(['_setAccount', 'UA-11419793-1']);
		    _gaq.push(['_setCampSourceKey', 'rbx_source']);
		    _gaq.push(['_setCampMediumKey', 'rbx_medium']);
		    _gaq.push(['_setCampContentKey', 'rbx_campaign']);
		        _gaq.push(['_setDomainName', '194.62.248.75:34533']);
		_gaq.push(['b._setAccount', 'UA-486632-1']);
		_gaq.push(['b._setCampSourceKey', 'rbx_source']);
		_gaq.push(['b._setCampMediumKey', 'rbx_medium']);
		_gaq.push(['b._setCampContentKey', 'rbx_campaign']);

		_gaq.push(['b._setDomainName', '194.62.248.75:34533']);
        
            _gaq.push(['b._setCustomVar', 1, 'Visitor', 'Anonymous', 2]);
            _gaq.push(['b._trackPageview']);    
        
        
        

		_gaq.push(['c._setAccount', 'UA-26810151-2']);
		_gaq.push(['c._setDomainName', '194.62.248.75:34533']);

		(function() {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();

	</script>
<div id="roblox-linkify" data-enabled="true" data-regex="(https?\:\/\/)?(?:www\.)?([a-z0-9\-]{2,}\.)*((m|de|www|web|api|blog|wiki|help|corp|polls|bloxcon|developer)\.blox14\.lol|robloxlabs\.com)((\/[A-Za-z0-9-+&amp;@#\/%?=~_|!:,.;]*)|(\b|\s))" data-regex-flags="gm"></div>
    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
<script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js"></script>
<script type="text/javascript">window.Sys || document.write("<script type='text/javascript' src='/js/Microsoft/MicrosoftAjax.js'><\/script>")</script>

    <script type="text/javascript" src="/rbxcdn_js/4564b16e8c662d0f22e92bfbfe939d9d.js"></script>

    <script type="text/javascript" src="/rbxcdn_js/9715e76471ffacd5f6d9c24a5ab101ad.js"></script>

    <script type="text/javascript">Roblox.config.externalResources = [];Roblox.config.paths['Pages.Catalog'] = '/rbxcdn_js/a2ff3787d1fd8d3c2492b5f5c5ec70b6.js';Roblox.config.paths['Pages.CatalogShared'] = '/rbxcdn_js/4eb48eec34ca711d5a7b08a4291ac753.js';Roblox.config.paths['Pages.Messages'] = '/rbxcdn_js/e8cbac58ab4f0d8d4c707700c9f97630.js';Roblox.config.paths['Resources.Messages'] = '/rbxcdn_js/fb9cb43a34372a004b06425a1c69c9c4.js';Roblox.config.paths['Widgets.AvatarImage'] = '/rbxcdn_js/bbaeb48f3312bad4626e00c90746ffc0.js';Roblox.config.paths['Widgets.DropdownMenu'] = '/rbxcdn_js/7b436bae917789c0b84f40fdebd25d97.js';Roblox.config.paths['Widgets.GroupImage'] = '/rbxcdn_js/33d82b98045d49ec5a1f635d14cc7010.js';Roblox.config.paths['Widgets.HierarchicalDropdown'] = '/rbxcdn_js/fbb86cf0752d23f389f983419d3085b4.js';Roblox.config.paths['Widgets.ItemImage'] = '/rbxcdn_js/838ec9c8067ba6fd6793a8bdbdb48a5c.js';Roblox.config.paths['Widgets.PlaceImage'] = '/rbxcdn_js/f2697119678d0851cfaa6c2270a727ed.js';Roblox.config.paths['Widgets.SurveyModal'] = '/rbxcdn_js/d6e979598c460090eafb6d38231159f6.js';</script>
    
    <script type="text/javascript" src="/rbxcdn_js/35e3099062484350420449f2689c0e2c.js"></script>


<script type="text/javascript">
    $(function () {
        Roblox.JSErrorTracker.initialize({ 'suppressConsoleError': true});
    });
</script>    <script type="text/javascript">
        $(function () {
            RobloxEventManager.triggerEvent('rbx_evt_newuser', {});
        });

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
        <script type="text/javascript">
            Roblox.XsrfToken.setToken('1VArKaRP1KuE');
        </script>
    <script type="text/javascript">
        Roblox.FixedUI.gutterAdsEnabled = false;
    </script>   
    
    
    <script type="text/javascript">
        var Roblox = Roblox || {};
        Roblox.jsConsoleEnabled = false;
    </script>
    
    <script>
        $(function () {
            Roblox.DeveloperConsoleWarning.showWarning();
        });
    </script>
    

    
</head>
<body class="">
    


<div id="fb-root"></div>

<div class="nav-container no-gutter-ads">


<div id="header" class="navbar-fixed-top rbx-header" role="navigation">
    <div class="container-fluid">
        <div class="rbx-navbar-header">
            <div data-behavior="nav-notification" class="rbx-nav-collapse" onselectstart="return false;">
                            </div>
            <div class="navbar-header">
                <a class="navbar-brand" href="https://devopstest1.aftwld.xyz/"><span class="logo"></span></a>
            </div>
        </div>
        <ul class="nav rbx-navbar hidden-xs hidden-sm col-md-4 col-lg-3">
            <li>
                <a href="https://devopstest1.aftwld.xyz/games">Games</a>
            </li>
            <li>
                <a href="https://devopstest1.aftwld.xyz/catalog">Catalog</a>
            </li>
            <li>
                <a href="https://devopstest1.aftwld.xyz/develop">Develop</a>
            </li>
            <li>
                <a class="buy-robux" href="https://devopstest1.aftwld.xyz/upgrades/robux">ROBUX</a>
            </li>
        </ul><!--rbx-navbar-->
        <div id="navbar-universal-search" class="navbar-left rbx-navbar-search col-xs-5 col-sm-6 col-md-3" data-behavior="univeral-search" role="search">
            <div class="input-group rbx-input-group">

                <input id="navbar-search-input" class="form-control rbx-input-field" type="text" placeholder="Search" maxlength="120"/>
                <div class="input-group-btn rbx-input-group-btn">
                    <button id="navbar-search-btn" class="rbx-input-addon-btn" type="submit">
                        <span class="rbx-icon-nav-search"></span>
                    </button>
                </div>
            </div>
            <ul data-toggle="dropdown-menu" class="rbx-dropdown-menu" role="menu">
                <li class="rbx-navbar-search-option selected" data-searchurl="/users/search?keyword=">
                    <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in People</span>
                </li>
                        <li class="rbx-navbar-search-option" data-searchurl="/games/?Keyword=">
                            <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in Games</span>
                        </li>
                        <li class="rbx-navbar-search-option" data-searchurl="/catalog/browse.aspx?CatalogContext=1&amp;Keyword=">
                            <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in Catalog</span>
                        </li>
                        <li class="rbx-navbar-search-option" data-searchurl="/groups/search.aspx?val=">
                            <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in Groups</span>
                        </li>
                        <li class="rbx-navbar-search-option" data-searchurl="/develop/library?CatalogContext=2&amp;Category=6&amp;Keyword=">
                            <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in Library</span>
                        </li>
            </ul>
        </div><!--rbx-navbar-search-->
        <div class="navbar-right rbx-navbar-right col-xs-4 col-sm-3">
                <ul class="nav navbar-right rbx-navbar-right-nav" data-display-opened="False">
                    <li>
                        <a id="header-login" class="rbx-navbar-login" data-behavior="login" data-toggle="popover" data-bind="popover-login" data-viewport="#header">Log In</a>
                    </li>
                    <div id="iFrameLogin" class="rbx-popover-content" data-toggle="popover-login" role="menu">
                        <iframe class="rbx-navbar-login-iframe" src="https://devopstest1.aftwld.xyz/Login/iFrameLogin.aspx?loginRedirect=False&amp;parentUrl=https%3a%2f%2f194.62.248.75:34533%2fNewLogin" scrolling="no" frameborder="0" width="320"></iframe>
                    </div>
                    <li>
                        <a class="rbx-navbar-signup" href="https://devopstest1.aftwld.xyz/Login/NewAge.aspx">Sign Up</a>
                    </li>
                    <li class="rbx-navbar-right-search" data-toggle="toggle-search">
                        <a class="rbx-menu-icon">
                            <span class="rbx-icon-nav-search-white"></span>
                        </a>
                    </li>
                </ul>
        </div><!-- navbar right-->
        <ul class="nav rbx-navbar hidden-md hidden-lg col-xs-12">
            <li>
                <a href="https://devopstest1.aftwld.xyz/games">Games</a>
            </li>
            <li>
                <a href="https://devopstest1.aftwld.xyz/catalog">Catalog</a>
            </li>
            <li>
                <a href="https://devopstest1.aftwld.xyz/develop">Develop</a>
            </li>
            <li>
                <a class="buy-robux" href="https://devopstest1.aftwld.xyz/upgrades/robux">ROBUX</a>
            </li>
        </ul><!--rbx-navbar-->
    </div>
</div>


<!-- LEFT NAV MENU -->
    <div id="navContent" class="nav-content ">
        <div class="nav-content-inner">
            <div id="MasterContainer">
                    <script type="text/javascript">
                        if (top.location != self.location) {
                            top.location = self.location.href;
                        }
                    </script>
                

<script type="text/javascript">
$(function(){
    function trackReturns() {
	    function dayDiff(d1, d2) {
		    return Math.floor((d1-d2)/86400000);
	    }
        if (!localStorage) return; 

	    var cookieName = 'RBXReturn';
	    var cookieOptions = {expires:9001};
        var cookie = localStorage.getItem(cookieName) || {};

	    if (typeof cookie.ts === "undefined" || isNaN(new Date(cookie.ts))) {
	        localStorage.setItem(cookieName, { ts: new Date().toDateString() });
		    return;
	    }

	    var daysSinceFirstVisit = dayDiff(new Date(), new Date(cookie.ts));
	    if (daysSinceFirstVisit == 1 && typeof cookie.odr === "undefined") {
		    RobloxEventManager.triggerEvent('rbx_evt_odr', {});
		    cookie.odr = 1;
	    }
	    if (daysSinceFirstVisit >= 1 && daysSinceFirstVisit <= 7 && typeof cookie.sdr === "undefined") {
		    RobloxEventManager.triggerEvent('rbx_evt_sdr', {});
		    cookie.sdr = 1;
	    }
	
	    localStorage.setItem(cookieName, cookie);
    }
    
        GoogleListener.init();
    
   
    
        RobloxEventManager.initialize(true);
        RobloxEventManager.triggerEvent('rbx_evt_pageview');
        trackReturns();
    
    
    
        RobloxEventManager._idleInterval = 450000;
        RobloxEventManager.registerCookieStoreEvent('rbx_evt_initial_install_start');
        RobloxEventManager.registerCookieStoreEvent('rbx_evt_ftp');
        RobloxEventManager.registerCookieStoreEvent('rbx_evt_initial_install_success');
        RobloxEventManager.registerCookieStoreEvent('rbx_evt_fmp');
        RobloxEventManager.startMonitor();
    

});

</script>


                <div>
                                                            <noscript><div class="SystemAlert"><div class="SystemAlertText">Please enable Javascript to use all the features on this site.</div></div></noscript>
                    <div id="BodyWrapper" class="">
                        <div id="RepositionBody">
                            <div id="Body" style="width:970px">
                                
<!--[if IE 7]>
<style>        
    #signInButtonPanel a 
    {
        margin-right: 143px;
    }
</style>
<![endif]-->
<h1>Login to ROBLOX</h1>
<div>


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
            <a class="ImageButton btn-neutral btn-large roblox-ok">OK<span class="btn-text">OK</span></a>
        </div>  
    </div>
</div>
<form action="https://devopstest1.aftwld.xyz/newlogin" id="loginForm" method="post">            <div id="loginarea" class="divider-bottom" data-is-captcha-on="False">
                <div id="leftArea">
                    <div id="loginPanel">
                        <table id="logintable">
                            <tr id="username">
                                <td><label class="form-label" for="Username">Username:</label></td>
                                <td><input class="text-box text-box-medium" data-val="true" data-val-required="The Username field is required." id="Username" name="Username" type="text" value=""/></td>
                            </tr>
                            <tr id="password">
                                <td><label class="form-label" for="Password">Password:</label></td>
                                <td><input class="text-box text-box-medium" data-val="true" data-val-required="The Password field is required." id="Password" name="Password" type="password"/></td>
                            </tr>
                        </table>
                        <div>
                        </div>
                        <div>
                            <div id="forgotPasswordPanel">
                                <a class="text-link" href="https://devopstest1.aftwld.xyz/Login/ResetPasswordRequest.aspx" target="_blank">Forgot your password?</a>
                            </div>
                            <div id="signInButtonPanel" data-use-apiproxy-signin="False" data-sign-on-api-path="https://devopstest1.aftwld.xyz/login/v1">
                                <a roblox-js-onclick class="btn-medium btn-neutral">Sign In</a>
                                <a roblox-js-oncancel class="btn-medium btn-negative">Cancel</a>
                            </div>
                            <div class="clearFloats">
                            </div>
                        </div>
                        <span id="fb-root">
                                    <div id="SplashPageConnect" class="fbSplashPageConnect">
                                        <a class="facebook-login" href="https://devopstest1.aftwld.xyz/Facebook/SignIn?returnTo=/home" ref="form-facebook">
                                            <span class="left"></span>
                                            <span class="middle">Login with Facebook<span>Login with Facebook</span></span>
                                            <span class="right"></span>
                                        </a>
                                    </div>
                        </span>
                    </div>
                </div>
                <div id="rightArea" class="divider-left">
                    <div id="signUpPanel" class="FrontPageLoginBox">
                        <p class="text">Not a member?</p>
                        <h2>Sign Up to Build &amp; Make Friends</h2>
<a roblox-js-onsignup class="btn-medium btn-primary">Sign Up</a>                    </div>
                </div>
            </div>
<input id="ReturnUrl" name="ReturnUrl" type="hidden" value=""/></form>
</div>
<script type="text/javascript">
    if (typeof Roblox === "undefined") {
        Roblox = {};
    }
    if (typeof Roblox.Login === "undefined") {
        Roblox.Login = {};
    }

    Roblox.Login.Resources = {
        //<sl:translate>
        january: "January"
		, february: "February"
		, march: "March"
		, april: "April"
		, may: "May"
		, june: "June"
		, july: "July"
		, august: "August"
		, september: "September"
		, october: "October"
		, november: "November"
		, december: "December"
        //</sl:translate>
    };
</script>

<div id="guestarea">
    <h2>You don't need an account to play ROBLOX</h2>
    <br/>
    <p class="text">You can start playing right now, in guest mode! <a href="https://devopstest1.aftwld.xyz/games" class="btn-small btn-neutral" id="guestButton">Play as Guest</a></p>
    
</div>
                                <div style="clear:both"></div>
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
    <a href="https://privacy.truste.com/privacy-seal/Roblox-Corporation/validation?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" title="TRUSTe Children privacy certification" target="_blank">
        <img style="border: none" src="https://privacy-policy.truste.com/privacy-seal/Roblox-Corporation/seal?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" width="133" height="45" alt="TRUSTe Children privacy certification"/>
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

</div>                </div>
            </div> 
        </div> 
    </div> 
</div> 


<div id="ChatContainer" style="position:fixed; bottom:0; right:0; z-index:10020;">


</div>


    <script type="text/javascript">function urchinTracker() {}</script>


<div id="PlaceLauncherStatusPanel" style="display:none;width:300px" data-new-plugin-events-enabled="True" data-is-protocol-handler-launch-enabled="False" data-is-user-logged-in="False" data-os-name="Windows" data-protocol-name-for-client="roblox-player" data-protocol-name-for-studio="roblox-studio">
    <div class="modalPopup blueAndWhite PlaceLauncherModal" style="min-height: 160px">
        <div id="Spinner" class="Spinner" style="margin:0 1em 1em 0; padding:20px 0;">
            <img src="/rbxcdn_img/e998fb4c03e8c2e30792f2f3436e9416.gif" height="32" width="32" alt="Progress"/>
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
                <img src="/rbxcdn_img/4bed93c91f909002b1f17f05c0ce13d1.gif" width="82" height="24"/>
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
                <a href="https://en.help.aftwld.xyz/hc/en-us/articles/203313020-ROBLOX-Launcher-Plugin" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>
<div id="ProtocolHandlerClickAlwaysAllowed" class="ph-clickalwaysallowed" style="display:none;">
    <p class="larger-font-size">
        <span class="rbx-icon-moreinfo"></span>
        Check <b>Remember my choice</b> and click <img src="/rbxcdn_img/7c8d7a39b4335931221857cca2b5430b.png" alt="Launch Application"/>  in the dialog box above to join games faster in the future!
    </p>
</div>


<script type="text/javascript" src="/rbxcdn_js/e59cc9c921c25a5cd61d18f0a7fd5ac8.js"></script>
 
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
        Roblox.VideoPreRoll.videoLogNote = "HTTPS";
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
             return RobloxLaunch.CheckRobloxInstall('/install/download.aspx');
    }

</script>

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
                        <img id="win_chrome_install_img" class="InstallInstructionsImage" data-modalwidth="890" data-delaysrc="/rbxcdn_img/0e5c6e93201539a0d2e72beed1590602.png" data-activationsrc="/rbxcdn_img/3edc01b73bf1e1b27b83b0ab46408d2f.jpg" alt="Installation Instructions"/>
        <br/><br/>
    </div>
</div>



<div id="pluginObjDiv" style="height:1px;width:1px;visibility:hidden;position: absolute;top: 0;"></div>
<iframe id="downloadInstallerIFrame" style="visibility:hidden;height:0;width:1px;position:absolute"></iframe>

<script type="text/javascript" src="/rbxcdn_js/4726cb4f73131ee2d1c2694e87e9d495.js"></script>

<script type="text/javascript">
    Roblox.Client._skip = null;
    Roblox.Client._CLSID = '76D50904-6780-4c8b-8986-1A7EE0B1716D';
    Roblox.Client._installHost = '194.62.248.75:34533';
    Roblox.Client.ImplementsProxy = true;
    Roblox.Client._silentModeEnabled = true;
    Roblox.Client._bringAppToFrontEnabled = false;
    Roblox.Client._currentPluginVersion = '';

        
    Roblox.Client._installSuccess = function() {
        if(GoogleAnalyticsEvents){
            GoogleAnalyticsEvents.ViewVirtual('InstallSuccess');
            GoogleAnalyticsEvents.FireEvent(['Plugin','Install Success']);
        }
    }
    
            
    if ((window.chrome || window.safari) && window.location.hash == '#chromeInstall') {
        window.location.hash = '';
        var continuation = '(' + $.cookie('chromeInstall') + ')';
        $.cookie('chromeInstall', null);
        RobloxLaunch._GoogleAnalyticsCallback = function() { var isInsideRobloxIDE = 'website'; if (Roblox && Roblox.Client && Roblox.Client.isIDE && Roblox.Client.isIDE()) { isInsideRobloxIDE = 'Studio'; };GoogleAnalyticsEvents.FireEvent(['Plugin Location', 'Launch Attempt', isInsideRobloxIDE]);GoogleAnalyticsEvents.FireEvent(['Plugin', 'Launch Attempt', 'Play']);EventTracker.fireEvent('GameLaunchAttempt_Win32', 'GameLaunchAttempt_Win32_Plugin');  }; 
        Roblox.Client.ResumeTimer(eval(continuation));
    }
    
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
        <div class="ConfirmationModalButtonContainer GenericModalButtonContainer">
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



</body>
</html>
<!--
     FILE ARCHIVED ON 23:48:51 Mar 23, 2015 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 20:07:43 Apr 26, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
-->
<!--
playback timings (ms):
  captures_list: 0.654
  exclusion.robots: 0.186
  exclusion.robots.policy: 0.176
  cdx.remote: 0.057
  esindex: 0.009
  LoadShardBlock: 381.316 (3)
  PetaboxLoader3.datanode: 155.564 (4)
  PetaboxLoader3.resolve: 268.5 (2)
  load_resource: 157.511
-->