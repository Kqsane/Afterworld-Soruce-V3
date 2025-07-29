<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if (!$info) {
    logout();
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html xmlns:fb="https://www.facebook.com/2008/fbml"><!-- MachineID: WEB109 --><head id="ctl00_ctl00_Head1"><style type="text/css">@charset "UTF-8";[ng\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide{display:none !important;}ng\:form{display:block;}.ng-animate-block-transitions{transition:0s all!important;-webkit-transition:0s all!important;}.ng-hide-add-active,.ng-hide-remove{display:block!important;}</style>
    <title>Create New Place - ROBLOX</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true">
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,600,700" rel="stylesheet" type="text/css">
    
    <script type="text/javascript" async="" src="https://cdn.siftscience.com/s.js"></script><script async="" src="https://sb.scorecardresearch.com/beacon.js"></script><script async="" type="text/javascript" src="https://www.googletagservices.com/tag/js/gpt.js"></script><script type="text/javascript" async="" src="https://ssl.google-analytics.com/ga.js"></script><script async="" type="text/javascript" src="https://www.googletagservices.com/tag/js/gpt.js"></script><script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
    <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>
    <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js"></script>
    <script type="text/javascript">window.Sys || document.write("<script type='text/javascript' src='/js/Microsoft/MicrosoftAjax.js'><\/script>")</script>
    
        <meta property="og:type" content="Website">
        <meta property="og:url" content="https://watrbx.wtf/places/create">
        <meta property="og:site_name" content="ROBLOX">
        <meta property="og:description" content="User-generated MMO gaming site for kids, teens, and adults. Players architect their own worlds. Builders create free online games that simulate the real world. Create and play amazing 3D games. An online gaming cloud and distributed physics engine.">
        <meta property="og:title" content="ROBLOX - Create New Place">
    	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/CSS/Base/CSS/main___7000c43d73500e63554d81258494fa21_m.css">
        <link rel="stylesheet" href="/CSS/Base/CSS/page___693f28640f335d1c8bc50c5a11d7ad3d_m.css">
        <script type="text/javascript" src="/js/c5827143734572fa7bd8fcc79c3c126b.js.gzip"></script>
        <script type="text/javascript" src="/js/22f5b93b0e23b69d9c48f68ea3c65fe3.js.gzip"></script>
        <script type="text/javascript" src="/js/6385cae49dc708a8f2f93167ad17466d.js.gzip"></script>
        <script type="text/javascript" src="/js/f3251ed8271ce1271b831073a47b65e3.js.gzip"></script>
        <script type="text/javascript" src="/js/2580e8485e871856bb8abe4d0d297bd2.js.gzip"></script>
        
    <style>
        .original-image {
            height: 100px;
            width: 100px;
        }
    </style>
    
	<script type="text/javascript">

        var _gaq = _gaq || [];

		    _gaq.push(['_setAccount', 'UA-11419793-1']);
		    _gaq.push(['_setCampSourceKey', 'rbx_source']);
		    _gaq.push(['_setCampMediumKey', 'rbx_medium']);
		    _gaq.push(['_setCampContentKey', 'rbx_campaign']);
		        _gaq.push(['_setDomainName', 'roblox.com']);
		_gaq.push(['b._setAccount', 'UA-486632-1']);
		_gaq.push(['b._setCampSourceKey', 'rbx_source']);
		_gaq.push(['b._setCampMediumKey', 'rbx_medium']);
		_gaq.push(['b._setCampContentKey', 'rbx_campaign']);

		_gaq.push(['b._setDomainName', 'roblox.com']);
        
            _gaq.push(['b._setCustomVar', 1, 'Visitor', 'Member', 2]);
            _gaq.push(['b._trackPageview']);    
        
        
        

		_gaq.push(['c._setAccount', 'UA-26810151-2']);
		_gaq.push(['c._setDomainName', 'roblox.com']);

		(function() {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();

	</script>
        <meta http-equiv="origin-trial" content="AlK2UR5SkAlj8jjdEc9p3F3xuFYlF6LYjAML3EOqw1g26eCwWPjdmecULvBH5MVPoqKYrOfPhYVL71xAXI1IBQoAAAB8eyJvcmlnaW4iOiJodHRwczovL2RvdWJsZWNsaWNrLm5ldDo0NDMiLCJmZWF0dXJlIjoiV2ViVmlld1hSZXF1ZXN0ZWRXaXRoRGVwcmVjYXRpb24iLCJleHBpcnkiOjE3NTgwNjcxOTksImlzU3ViZG9tYWluIjp0cnVlfQ=="><meta http-equiv="origin-trial" content="Amm8/NmvvQfhwCib6I7ZsmUxiSCfOxWxHayJwyU1r3gRIItzr7bNQid6O8ZYaE1GSQTa69WwhPC9flq/oYkRBwsAAACCeyJvcmlnaW4iOiJodHRwczovL2dvb2dsZXN5bmRpY2F0aW9uLmNvbTo0NDMiLCJmZWF0dXJlIjoiV2ViVmlld1hSZXF1ZXN0ZWRXaXRoRGVwcmVjYXRpb24iLCJleHBpcnkiOjE3NTgwNjcxOTksImlzU3ViZG9tYWluIjp0cnVlfQ=="><meta http-equiv="origin-trial" content="A9wSqI5i0iwGdf6L1CERNdmsTPgVu44ewj8QxTBYgsv1LCPUVF7YmWOvTappqB1139jAymxUW/RO8zmMqo4zlAAAAACNeyJvcmlnaW4iOiJodHRwczovL2RvdWJsZWNsaWNrLm5ldDo0NDMiLCJmZWF0dXJlIjoiRmxlZGdlQmlkZGluZ0FuZEF1Y3Rpb25TZXJ2ZXIiLCJleHBpcnkiOjE3MzY4MTI4MDAsImlzU3ViZG9tYWluIjp0cnVlLCJpc1RoaXJkUGFydHkiOnRydWV9"><meta http-equiv="origin-trial" content="A+d7vJfYtay4OUbdtRPZA3y7bKQLsxaMEPmxgfhBGqKXNrdkCQeJlUwqa6EBbSfjwFtJWTrWIioXeMW+y8bWAgQAAACTeyJvcmlnaW4iOiJodHRwczovL2dvb2dsZXN5bmRpY2F0aW9uLmNvbTo0NDMiLCJmZWF0dXJlIjoiRmxlZGdlQmlkZGluZ0FuZEF1Y3Rpb25TZXJ2ZXIiLCJleHBpcnkiOjE3MzY4MTI4MDAsImlzU3ViZG9tYWluIjp0cnVlLCJpc1RoaXJkUGFydHkiOnRydWV9"><script src="https://securepubads.g.doubleclick.net/pagead/managed/js/gpt/m202507160101/pubads_impl.js?cb=31093534" async=""></script></head><body data-internal-page-name="Avatar" data-performance-relative-value="0.5" class="" id="rbx-body"><div id="EventStreamData" data-default-url="//ecsv2.roblox.com/www/e.png" data-www-url="//ecsv2.roblox.com/www/e.png" data-studio-url="//ecsv2.roblox.com/pe?t=studio"></div>


<div id="page-heartbeat-event-data-model" class="hidden" data-page-heartbeat-event-intervals="[2,8,20,60]">
</div>
<script type="text/javascript">Roblox.config.externalResources = [];Roblox.config.paths['Pages.Catalog'] = 'https://js.rbxcdn.com/46776eac503b939a2fd9146d77d735a3.js';Roblox.config.paths['Pages.CatalogShared'] = 'https://js.rbxcdn.com/da18f3da914691fb825e5ae9f00c9e49.js';Roblox.config.paths['Pages.Messages'] = 'https://js.rbxcdn.com/e8cbac58ab4f0d8d4c707700c9f97630.js';Roblox.config.paths['Resources.Messages'] = 'https://js.rbxcdn.com/fb9cb43a34372a004b06425a1c69c9c4.js';Roblox.config.paths['Widgets.AvatarImage'] = 'https://js.rbxcdn.com/bbaeb48f3312bad4626e00c90746ffc0.js';Roblox.config.paths['Widgets.DropdownMenu'] = 'https://js.rbxcdn.com/7b436bae917789c0b84f40fdebd25d97.js';Roblox.config.paths['Widgets.GroupImage'] = 'https://js.rbxcdn.com/33d82b98045d49ec5a1f635d14cc7010.js';Roblox.config.paths['Widgets.HierarchicalDropdown'] = 'https://js.rbxcdn.com/3368571372da9b2e1713bb54ca42a65a.js';Roblox.config.paths['Widgets.ItemImage'] = 'https://js.rbxcdn.com/8babd891cf420dfe3999b3824a0154cb.js';Roblox.config.paths['Widgets.PlaceImage'] = 'https://js.rbxcdn.com/f2697119678d0851cfaa6c2270a727ed.js';Roblox.config.paths['Widgets.SurveyModal'] = 'https://js.rbxcdn.com/d6e979598c460090eafb6d38231159f6.js';</script>
<script>$(function () {
        Roblox.JSErrorTracker.initialize({ 'suppressConsoleError': true});
    });
</script>

    <script type="text/javascript">


    googletag.cmd.push(function() {
        Roblox = Roblox || {};
        Roblox.AdsHelper = Roblox.AdsHelper || {};
        Roblox.AdsHelper.slots = [];
        Roblox.AdsHelper.slots = Roblox.AdsHelper.slots || []; Roblox.AdsHelper.slots.push({slot:googletag.defineSlot("/1015347/Roblox_MyCharacter_Top_728x90", [728, 90], "34323331303032").addService(googletag.pubads()), id: "34323331303032", path: "/1015347/Roblox_MyCharacter_Top_728x90"});

        for (var key in Roblox.AdsHelper.slots) {
            var slot = Roblox.AdsHelper.slots[key].slot;
            var id = Roblox.AdsHelper.slots[key].id;
            var path = Roblox.AdsHelper.slots[key].path;

            if (slot.renderEnded != "undefined") {
                (function(slot, id)
                {
                    slot.renderEndedOld = slot.renderEnded;
                    slot.renderEnded = function() {
                        slot.renderEndedOld();
                        if ($('#' + id + '.gutter').css('display') == "none") {
                            $(document).trigger("GuttersHidden");
                        }
                        if ($('#' + id + '.filmstrip').css('display') == "none") {
                            $(document).trigger("FilmStripHidden");
                        }
                    };
                }(slot, id));
            }
        }

        googletag.pubads().setTargeting("Age", ["36", "18AndOver" ]);
                        googletag.pubads().setTargeting("Env",  "Production");
                                                googletag.pubads().setTargeting("Gender", "Male");
                        googletag.pubads().setTargeting("PLVU", "False");
        googletag.pubads().enableSingleRequest();
        googletag.pubads().collapseEmptyDivs();
        googletag.enableServices();
    });
    </script>  
<script type="text/javascript">
if (typeof(Roblox) === "undefined") { Roblox = {}; }
Roblox.Endpoints = Roblox.Endpoints || {};
Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
Roblox.Endpoints.Urls['/asset/'] = '/asset/';
Roblox.Endpoints.Urls['/client-status/set'] = '/client-status/set';
Roblox.Endpoints.Urls['/client-status'] = '/client-status';
Roblox.Endpoints.Urls['/game/'] = '/game/';
Roblox.Endpoints.Urls['/game/edit.ashx'] = '/game/edit.ashx';
Roblox.Endpoints.Urls['/game/getauthticket'] = '/game/getauthticket';
Roblox.Endpoints.Urls['/game/placelauncher.ashx'] = '/game/placelauncher.ashx';
Roblox.Endpoints.Urls['/game/report-stats'] = '/game/report-stats';
Roblox.Endpoints.Urls['/game/report-event'] = '/game/report-event';
Roblox.Endpoints.Urls['/chat/chat'] = '/chat/chat';
Roblox.Endpoints.Urls['/presence/users'] = '/presence/users';
Roblox.Endpoints.Urls['/presence/user'] = '/presence/user';
Roblox.Endpoints.Urls['/friends/list'] = '/friends/list';
Roblox.Endpoints.Urls['/navigation/getCount'] = '/navigation/getCount';
Roblox.Endpoints.Urls['/catalog/browse.aspx'] = 'https://www.watrbx.wtf/catalog/browse.aspx';
Roblox.Endpoints.Urls['/catalog/html'] = '/catalog/html';
Roblox.Endpoints.Urls['/catalog/json'] = '/catalog/json';
Roblox.Endpoints.Urls['/catalog/contents'] = '/catalog/contents';
Roblox.Endpoints.Urls['/catalog/lists.aspx'] = '/catalog/lists.aspx';
Roblox.Endpoints.Urls['/asset-hash-thumbnail/image'] = '/asset-hash-thumbnail/image';
Roblox.Endpoints.Urls['/asset-hash-thumbnail/json'] = '/asset-hash-thumbnail/json';
Roblox.Endpoints.Urls['/asset-thumbnail-3d/json'] = '/asset-thumbnail-3d/json';
Roblox.Endpoints.Urls['/asset-thumbnail/image'] = '/asset-thumbnail/image';
Roblox.Endpoints.Urls['/asset-thumbnail/json'] = '/asset-thumbnail/json';
Roblox.Endpoints.Urls['/asset-thumbnail/url'] = '/asset-thumbnail/url';
Roblox.Endpoints.Urls['/asset/request-thumbnail-fix'] = '/asset/request-thumbnail-fix';
Roblox.Endpoints.Urls['/avatar-thumbnail-3d/json'] = '/avatar-thumbnail-3d/json';
Roblox.Endpoints.Urls['/avatar-thumbnail/image'] = '/avatar-thumbnail/image';
Roblox.Endpoints.Urls['/avatar-thumbnail/json'] = '/avatar-thumbnail/json';
Roblox.Endpoints.Urls['/avatar-thumbnails'] = '/avatar-thumbnails';
Roblox.Endpoints.Urls['/avatar/request-thumbnail-fix'] = '/avatar/request-thumbnail-fix';
Roblox.Endpoints.Urls['/bust-thumbnail/json'] = '/bust-thumbnail/json';
Roblox.Endpoints.Urls['/group-thumbnails'] = '/group-thumbnails';
Roblox.Endpoints.Urls['/groups/getprimarygroupinfo.ashx'] = '/groups/getprimarygroupinfo.ashx';
Roblox.Endpoints.Urls['/headshot-thumbnail/json'] = '/headshot-thumbnail/json';
Roblox.Endpoints.Urls['/item-thumbnails'] = '/item-thumbnails';
Roblox.Endpoints.Urls['/outfit-thumbnail/json'] = '/outfit-thumbnail/json';
Roblox.Endpoints.Urls['/place-thumbnails'] = '/place-thumbnails';
Roblox.Endpoints.Urls['/thumbnail/asset/'] = '/thumbnail/asset/';
Roblox.Endpoints.Urls['/thumbnail/avatar-headshot'] = '/thumbnail/avatar-headshot';
Roblox.Endpoints.Urls['/thumbnail/avatar-headshots'] = '/thumbnail/avatar-headshots';
Roblox.Endpoints.Urls['/thumbnail/user-avatar'] = '/thumbnail/user-avatar';
Roblox.Endpoints.Urls['/thumbnail/resolve-hash'] = '/thumbnail/resolve-hash';
Roblox.Endpoints.Urls['/thumbnail/place'] = '/thumbnail/place';
Roblox.Endpoints.Urls['/thumbnail/get-asset-media'] = '/thumbnail/get-asset-media';
Roblox.Endpoints.Urls['/thumbnail/remove-asset-media'] = '/thumbnail/remove-asset-media';
Roblox.Endpoints.Urls['/thumbnail/set-asset-media-sort-order'] = '/thumbnail/set-asset-media-sort-order';
Roblox.Endpoints.Urls['/thumbnail/place-thumbnails'] = '/thumbnail/place-thumbnails';
Roblox.Endpoints.Urls['/thumbnail/place-thumbnails-partial'] = '/thumbnail/place-thumbnails-partial';
Roblox.Endpoints.Urls['/thumbnail_holder/g'] = '/thumbnail_holder/g';
Roblox.Endpoints.Urls['/users/{id}/profile'] = '/users/{id}/profile';
Roblox.Endpoints.addCrossDomainOptionsToAllRequests = true;
</script>
<script type="text/javascript">
if (typeof(Roblox) === "undefined") { Roblox = {}; }
Roblox.Endpoints = Roblox.Endpoints || {};
Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
Roblox.Endpoints.Urls['/authentication/is-logged-in'] = '/authentication/is-logged-in';
</script>

<script type="text/javascript">
    // IMPORTANT! If the user is logged in, set to user_id; else, set to ''
    var _user_id = '1649';

    // IMPORTANT! Set to a unique session ID for the visitor's current browsing session.
    var _session_id = '1649';

    var _sift = window._sift = window._sift || [];

    // IMPORTANT! Insert your JavaScript snippet key here!
    _sift.push(['_setAccount', '5238aa5d58']);

    _sift.push(['_setUserId', _user_id]);
    _sift.push(['_setSessionId', _session_id]);
    _sift.push(['_trackPageview']);

    (function () {
        function ls() {
            var e = document.createElement('script');
            e.type = 'text/javascript';
            e.async = true;
            e.src = ('https:' === document.location.protocol ? 'https://' : 'https://') + 'cdn.siftscience.com/s.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(e, s);
        }
        if (window.attachEvent) {
            window.attachEvent('onload', ls);
        } else {
            window.addEventListener('load', ls, false);
        }
    }());
</script>

    <div id="roblox-linkify" data-enabled="true" data-regex="(https?\:\/\/)?(?:www\.)?([a-z0-9\-]{2,}\.)*(((m|de|www|web|api|blog|wiki|help|corp|polls|bloxcon|developer|devforum|forum)\.roblox\.com|robloxlabs\.com)|(www\.shoproblox\.com))((\/[A-Za-z0-9-+&amp;@#\/%?=~_|!:,.;]*)|(\b|\s))" data-regex-flags="gm"></div>
    <script type="text/javascript">Roblox.XsrfToken.setToken('u256v2GAyfHL');</script>
 
    <script type="text/javascript">
        if (top.location != self.location) {
            top.location = self.location.href;
        }
    </script>
  
<style type="text/css">
    
</style>
<form name="aspnetForm" method="post" action="/places/create" id="aspnetForm" class="nav-container no-gutter-ads">
<div>
<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="">
<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="">
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="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">
</div>

<script type="text/javascript">
//<![CDATA[
var theForm = document.forms['aspnetForm'];
if (!theForm) {
    theForm = document.aspnetForm;
}
function __doPostBack(eventTarget, eventArgument) {
    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
        theForm.__EVENTTARGET.value = eventTarget;
        theForm.__EVENTARGUMENT.value = eventArgument;
        theForm.submit();
    }
}
//]]>
</script>


<div>

	<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="1ECBBB27">
	<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="xOmPvfndnEm3JMiMV5tCLHSfECUjMeyrWbkDTol1fEjdGdIazwksiz4E8v4maKOhxOXSQ21c4mu4yJTQwPXma9zrKeQZDgZFsBIhQZIKGq7fU1CFET8X5bcn33zxXubqgrDR7ZyO8gpC8Wv7ZexAg9ePWKD9DC/ukw3frh9hClTGrtPF/J/5+e1lfgPrjF4htDe6AC3B7IyLEar/38lcG4tZFAMtlmlHvfPeGveASm+Zw5bK+8thZDazeVhuhwtdyNrnMRpuHPk/l1MXKjgmCb6na7HcZH6qqi4t+GlU/ZzOWKvEO1JM5F51rpz8wCGCwzcl5bK3zn1pISl5ZOaOMV9G04IbHbZ1DzEoEuy8I8u1RDLkByd4uycgZz1hY/jy4jdb7TlQFp1r0jyYaoWgo9P82wqBWy7wCGavMaUn10xb5c8XRU6Em4m9KsGQ+whk8m6mahmViIseMHSz3pGfK+mtJv2O/HdeS5bD/RTo1/bzoT7Ki+ZbL0q7J2txHcKzRvtXlh7kf5Us1JjRL6aLXqLH3+2v00t2NyWisGflPdqYOvYXyGTapFJSxq0ygt/CbcqiHsP3ZWgne8+HNF7Wfgzv0lEbkSdTMl9L4jo6gOn6LTXISG8vvVCzN6fnJOQMTFK2fSKV6PX/PlBtk5oKhd0XRuo=">
</div>
    <div id="fb-root">
    </div>







    

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
    <div id="MasterContainer">
        

<script type="text/javascript">
    $(function(){
        function trackReturns() {
            function dayDiff(d1, d2) {
                return Math.floor((d1-d2)/86400000);
            }
            if (!localStorage) {
                return false;
            }

            var cookieName = 'RBXReturn';
            var cookieOptions = {expires:9001};
            var cookieStr = localStorage.getItem(cookieName) || "";
            var cookie = {};

            try {
                cookie = JSON.parse(cookieStr);
            } catch (ex) {
                // busted cookie string from old previous version of the code
            }

            try {
                if (typeof cookie.ts === "undefined" || isNaN(new Date(cookie.ts))) {
                    localStorage.setItem(cookieName, JSON.stringify({ ts: new Date().toDateString() }));
                    return false;
                }
            } catch (ex) {
                return false;
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
            try {
                localStorage.setItem(cookieName, JSON.stringify(cookie));
            } catch (ex) {
                return false;
            }
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



        <script type="text/javascript">Roblox.FixedUI.gutterAdsEnabled=false;</script>

        

        <div id="Container">
            
            
        </div>
        
        
        
        <noscript><div class="SystemAlert"><div class="SystemAlertText">Please enable Javascript to use all the features on this site.</div></div></noscript><div class="SystemAlert" style="background-color: red;"><div class="SystemAlertText">Game Publishing &amp; Studio IDE is here! Download studio and create away!</div></div><div id="AdvertisingLeaderboard">
    <iframe allowtransparency="true" frameborder="0" height="110" scrolling="no" src="/userads/1" width="728" data-js-adtype="iframead"></iframe>
</div>
<div id="BodyWrapper">
    <div id="RepositionBody">
        <div id="Body" style="width:970px;">
            <h1>Create Place</h1>
           <div id="CreateTabs" class="tab-container">
                <div id="TemplatesLink" class="tab-active">Templates</div>
                <div id="BasicSettingsLink" class="">Basic Settings</div>
                <div id="AccessLink">Access</div>
                <div id="AdvancedSettingsLink">Advanced Settings</div>
            </div>
            <div>
                <div id="Templates" class="tab-active">
                    <h2>Templates</h2>
                    <br><br>
                    <ul class="nav nav-pills">
                        <li class="active"><a>Basic</a></li>
                        <li class=""><a>Theme</a></li>
                        <li class=""><a>Gameplay</a></li>
                    </ul>
                    <br>
                    <div class="template" placeid="95206881">
                            <a class="game-image"><img class="" src="/images/Templates/17507896d5a29371ea8048fbdc3b1d74.jpg"></a>
                            <p>Baseplate</p>
                        </div>
                    <br><br>
                    <a class="btn-medium btn-primary" style="float:left;" onclick="__doPostBack()">Create Place</a>
                    <a class="btn-medium btn-negative" onclick="window.close();" style="margin-left: 15px;">Cancel</a>
                </div>
                <div id="BasicSettings" class="">
                    <h2>Basic Settings</h2>
                    <br><br><br>
                    
                        <label>Name:</label><br>
                        <input type="text" name="name" value="<?php echo $info["Username"]?>'s Place Number: 1" style="width: 300px;">
                        <br><br>
                        <label>Description:</label><br>
                        <textarea type="text" name="description" style="width: 300px; height: 50px;"></textarea>
                        <br><br>
                        <label>Genre:</label><br>
                        <select name="genre" style="width: 100apx;">
                            <option value="all">All</option>
                        </select>
                    
                    <br><br>
                    <a class="btn-medium btn-primary" style="float:left;" onclick="__doPostBack()">Create Place</a>
                    <a class="btn-medium btn-negative" onclick="window.close();" style="margin-left: 15px;">Cancel</a>
                </div>
                <div id="Access">
                    <div id="playerAccess" class="default-hidden" style="display: block;">
					<div class="headline">
					   <h2>Access</h2>
					</div>
                    <br><br>
                    <div id="devices">
                        <label>Playable Devices (Visual):</label><br>
                        <input type="checkbox" name="devices" value="Computer" checked=""><label>Computer</label><br>
                        <input type="checkbox" name="devices" value="Phone" checked=""><label>Phone</label><br>
                        <input type="checkbox" name="devices" value="Tablet" checked=""><label>Tablet</label><br>
                    </div>
                    <br><br>
                    <div id="servertype">
                        <label>Place Type (Visual):</label>
                        <ul class="nav nav-pills">
                            <li class="active"><a>Game Place</a></li>
                            <li class=""><a>Personal Server</a></li>
                        </ul>
                    </div>


                    <div id="options">
                        <br>
                        <label class="form-label" for="NumPlayers">Number of Players:</label>
                        <br>
                        <select class="form-select" id="NumPlayers" name="NumPlayers">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>  
                            <option selected="selected">6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>
                            <option>13</option>
                            <option>14</option>
                            <option>15</option>
                            <option>16</option>
                            <option>17</option>
                            <option>18</option>
                            <option>19</option>
                            <option>20</option>
                            <option>21</option>
                            <option>22</option>
                            <option>23</option>
                            <option>24</option>
                            <option>25</option>
                            <option>26</option>
                            <option>27</option>
                            <option>28</option>
                            <option>29</option>
                            <option>30</option>
                            <option>31</option>
                            <option>32</option>
                            <option>33</option>
                            <option>34</option>
                            <option>35</option>
                            <option>36</option>
                            <option>37</option>
                            <option>38</option>
                            <option>39</option>
                            <option>40</option>
                            <option>41</option>
                            <option>42</option>
                            <option>43</option>
                            <option>44</option>
                            <option>45</option>
                            <option>46</option>
                            <option>47</option>
                            <option>48</option>
                            <option>49</option>
                            <option>50</option>
                        </select>
                            <br><br>
                        <label class="form-label" for="Access">Access (Visual):</label>
                        <br>
                        <select class="form-select" id="Access" name="Access">
                            <option selected="selected">Everyone</option>
                            <option>Friends</option>
                            <option>No One</option>
                        </select>
                        <img class="TipsyImg tooltip-bottom h2-tooltip place-access-tooltip" src="/images/65cb6e4009a00247ca02800047aafb87.png" data-toggle="tooltip" alt="To restrict who may access this place, first you must disable private servers and not sell experience access." data-original-title="To restrict who may access this place, first you must disable private servers and not sell experience access." original-title="" style="display: none;">
                        <span class="field-validation-valid" data-valmsg-for="Access" data-valmsg-replace="true"></span>
                        <div style="clear:both;"></div>
                    </div>
                    <br><br><br>
                    <a class="btn-medium btn-primary" style="float:left;" onclick="__doPostBack()">Create Place</a>
                    <a class="btn-medium btn-negative" onclick="window.close();" style="margin-left: 15px;">Cancel</a>
                    </div>
                </div>
                <div id="AdvancedSettings">
                    
                    <div id="buttonRow" class="actionButtons">
                        <a class="btn-medium btn-primary" style="float:left;" onclick="__doPostBack()">Create Place</a>
                        <a class="btn-medium btn-negative" onclick="window.close();" style="margin-left: 15px;">Cancel</a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="Footer" class="footer-container">
    <div class="FooterNav">
        <a href="http://www.watrbx.wtf/info/Privacy.aspx">Privacy Policy</a>
        &nbsp;|&nbsp;
        <a href="http://corp.watrbx.wtf/advertise-on-roblox" class="roblox-interstitial">Advertise with Us</a>
        &nbsp;|&nbsp;
        <a href="http://corp.watrbx.wtf/press" class="roblox-interstitial">Press</a>
        &nbsp;|&nbsp;
        <a href="http://corp.watrbx.wtf/contact-us" class="roblox-interstitial">Contact Us</a>
        &nbsp;|&nbsp;
            <a href="http://corp.watrbx.wtf/about" class="roblox-interstitial">About Us</a>
&nbsp;|&nbsp;        <a href="http://blog.watrbx.wtf">Blog</a>
        &nbsp;|&nbsp;
            <a href="http://corp.watrbx.wtf/careers" class="roblox-interstitial">Jobs</a>
&nbsp;|&nbsp;        <a href="http://corp.watrbx.wtf/parents" class="roblox-interstitial">Parents</a>
    </div>
    <div class="legal">
            <div class="left">
                <div id="a15b1695-1a5a-49a9-94f0-9cd25ae6c3b2">
    <a href="//privacy.truste.com/privacy-seal/Roblox-Corporation/validation?rid=2428aa2a-f278-4b6d-9095-98c4a2954215" title="TRUSTe Children privacy certification" target="_blank">
        <img style="border: none" src="/images/seal.png" width="133" height="45" alt="TRUSTe Children privacy certification">
    </a>
</div>
            </div>
            <div class="right">
                <p class="Legalese">
    ROBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of <a href="http://corp.watrbx.wtf/" ref="footer-smallabout" class="roblox-interstitial">ROBLOX Corporation</a>, ©2015. Patents pending.
    ROBLOX is not sponsored, authorized or endorsed by any producer of plastic building bricks, including The LEGO Group, MEGA Brands, and K'Nex, and no resemblance to the products of these companies is intended.
    Use of this site signifies your acceptance of the <a href="http://www.watrbx.wtf/info/terms-of-service" ref="footer-terms">Terms and Conditions</a>.
</p>
            </div>
        <div class="clear"></div>
    </div>

</div>                </div>
            </div></form> 
         
     
 

    <div class="ConfirmationModal modalPopup unifiedModal smallModal" data-modal-handle="confirmation" style="display:none;">
    <a class="genericmodal-close ImageButton closeBtnCircle_20h"></a>
    <div class="Title"></div>
    <div class="GenericModalBody">
        <div class="TopBody">
            <div class="ImageContainer roblox-item-image" data-image-size="small" data-no-overlays="" data-no-click="">
                <img class="GenericModalImage" alt="generic image">
            </div>
            <div class="Message"></div>
        </div>
        <div class="ConfirmationModalButtonContainer GenericModalButtonContainer">
            <a href="" id="roblox-confirm-btn"><span></span></a>
            <a href="" id="roblox-decline-btn"><span></span></a>
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
    <div id="page-heartbeat-event-data-model" class="hidden" data-page-heartbeat-event-intervals="[2,8,20,60]">
</div>


    <script type="text/javascript">function urchinTracker() {}</script>


<div id="PlaceLauncherStatusPanel" style="display:none;width:300px" data-new-plugin-events-enabled="True" data-event-stream-for-plugin-enabled="True" data-event-stream-for-protocol-enabled="True" data-is-protocol-handler-launch-enabled="True" data-is-user-logged-in="True" data-os-name="Windows" data-protocol-name-for-client="roblox-player" data-protocol-name-for-studio="watrbx-studio" data-protocol-url-includes-launchtime="true" data-protocol-detection-enabled="true">
    <div class="modalPopup blueAndWhite PlaceLauncherModal" style="min-height: 160px">
        <div id="Spinner" class="Spinner" style="padding:20px 0;">
            <img src="http://images.rbxcdn.com/e998fb4c03e8c2e30792f2f3436e9416.gif" height="32" width="32" alt="Progress">
        </div>
        <div id="status" style="min-height:40px;text-align:center;margin:5px 20px">
            <div id="Starting" class="PlaceLauncherStatus MadStatusStarting" style="display:block">
                Starting Roblox...
            </div>
            <div id="Waiting" class="PlaceLauncherStatus MadStatusField">Connecting to Players...</div>
            <div id="StatusBackBuffer" class="PlaceLauncherStatus PlaceLauncherStatusBackBuffer MadStatusBackBuffer"></div>
        </div>
        <div style="text-align:center;margin-top:1em">
            <input type="button" class="Button CancelPlaceLauncherButton translate" value="Cancel">
        </div>
    </div>
</div>
<div id="ProtocolHandlerStartingDialog" style="display:none;">
    <div class="modalPopup ph-modal-popup">
        <div class="ph-modal-header">

        </div>
        <div class="ph-logo-row">
            <img src="/images/Logo/logo_R.svg" width="90" height="90" alt="R">
        </div>
        <div class="ph-areyouinstalleddialog-content">
            <p class="larger-font-size">
                ROBLOX is now loading. Get ready to play!
            </p>
            <div class="ph-startingdialog-spinner-row">
                <img src="http://images.rbxcdn.com/4bed93c91f909002b1f17f05c0ce13d1.gif" width="82" height="24">
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
            <img src="/images/Logo/logo_R.svg" width="90" height="90" alt="R">
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
                <a href="https://en.help.watrbx.wtf/hc/en-us/articles/204473560" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>
<div id="ProtocolHandlerClickAlwaysAllowed" class="ph-clickalwaysallowed" style="display:none;">
    <p class="larger-font-size">
        <span class="rbx-icon-moreinfo"></span>
        Check <b>Remember my choice</b> and click <img src="http://images.rbxcdn.com/7c8d7a39b4335931221857cca2b5430b.png" alt="Launch Application">  in the dialog box above to join games faster in the future!
    </p>
</div>


    <div id="videoPrerollPanel" style="display:none">
        <div id="videoPrerollTitleDiv">
            Gameplay sponsored by:
        </div>
        <div id="content">
            <video id="contentElement" style="width:0; height:0;">
        </video></div>
        <div id="videoPrerollMainDiv"></div>
        <div id="videoPrerollCompanionAd">
            
                <script type="text/javascript">
                    googletag.cmd.push(function () {
                        googletag.defineSlot('/1015347/VideoPrerollUnder13', [300, 250], 'videoPrerollCompanionAd')
                            .addService(googletag.companionAds());
                        googletag.enableServices();
                        googletag.display('videoPrerollCompanionAd');
                    });
                </script>
        </div>
        <div id="videoPrerollLoadingDiv">
            Loading <span id="videoPrerollLoadingPercent">0%</span> - <span id="videoPrerollMadStatus" class="MadStatusField">Starting game...</span><span id="videoPrerollMadStatusBackBuffer" class="MadStatusBackBuffer"></span>
            <div id="videoPrerollLoadingBar">
                <div id="videoPrerollLoadingBarCompleted">
                </div>
            </div>
        </div>
        <div id="videoPrerollJoinBC">
            <span>Get more with Builders Club!</span>
            <a href="https://www.watrbx.wtf/premium/membership?ctx=preroll" target="_blank" class="btn-medium btn-primary" id="videoPrerollJoinBCButton">Join Builders Club</a>
        </div>
    </div>   
        <script type="text/javascript" src="//imasdk.googleapis.com/js/sdkloader/ima3.js"></script>
    <script type="text/javascript">
        $(function () {
            var videoPreRollDFP = Roblox.VideoPreRollDFP;
            if (videoPreRollDFP) {
                var customTargeting = Roblox.VideoPreRollDFP.customTargeting;
                videoPreRollDFP.showVideoPreRoll = true;
                videoPreRollDFP.loadingBarMaxTime = 33000;
                videoPreRollDFP.videoLoadingTimeout = 11000;
                videoPreRollDFP.videoPlayingTimeout = 41000;
                videoPreRollDFP.videoLogNote = "";
                videoPreRollDFP.logsEnabled = true;
                videoPreRollDFP.excludedPlaceIds = "32373412";
                videoPreRollDFP.adUnit = "/1015347/VideoPrerollUnder13";
                videoPreRollDFP.adTime = 15;
                videoPreRollDFP.isSwfPreloaderEnabled = true;
                videoPreRollDFP.isPrerollShownEveryXMinutesEnabled = true;
                customTargeting.userAge = "9";
                customTargeting.userGender = "Male";
                customTargeting.gameGenres = "";
                customTargeting.environment = "Production";
                customTargeting.adTime = "15";
                customTargeting.PLVU = false;
                $(videoPreRollDFP.checkEligibility);
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
                <a href="http://www.watrbx.wtf/?returnUrl=http%3A%2F%2Fwww.watrbx.wtf%2Fmy%2Fmessages%2F"><div class="RevisedCharacterSelectSignup"></div></a>
                <a class="HaveAccount" href="https://www.watrbx.wtf/newlogin?returnUrl=http%3A%2F%2Fwww.watrbx.wtf%2Fmy%2Fmessages%2F">I have an account</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function checkRobloxInstall() {
             return RobloxLaunch.CheckRobloxInstall('/install/download.aspx');
    }

</script>

    <div id="InstallationInstructions" style="display:none;">
        <div class="ph-installinstructions">
            <div class="ph-modal-header">
                <span class="rbx-icon-close simplemodal-close"></span>
                <h3>Thanks for playing ROBLOX</h3>
            </div>
            <div class="ph-installinstructions-body">
                    <div class="ph-install-step ph-installinstructions-step1-of4">
                        <h1>1</h1>
                        <p class="larger-font-size">Click RobloxPlayerLauncher.exe to run the ROBLOX installer, which just downloaded via your web browser.</p>
                        <img width="230" height="180" src="http://images.rbxcdn.com/8b0052e4ff81d8e14f19faff2a22fcf7.png">
                    </div>
                    <div class="ph-install-step ph-installinstructions-step2-of4">
                        <h1>2</h1>
                        <p class="larger-font-size">Click <strong>Run</strong> when prompted by your computer to begin the installation process.</p>
                        <img width="230" height="180" src="http://images.rbxcdn.com/4a3f96d30df0f7879abde4ed837446c6.png">
                    </div>
                    <div class="ph-install-step ph-installinstructions-step3-of4">
                        <h1>3</h1>
                        <p class="larger-font-size">Click <strong>Ok</strong> once you've successfully installed ROBLOX.</p>
                        <img width="230" height="180" src="http://images.rbxcdn.com/6e23e4971ee146e719fb1abcb1d67d59.png">
                    </div>
                    <div class="ph-install-step ph-installinstructions-step4-of4">
                        <h1>4</h1>
                        <p class="larger-font-size">After installation, click <strong>Play</strong> below to join the action!</p>
                        <div class="VisitButton VisitButtonContinuePH">
                            <a class="btn rbx-btn-primary-lg disabled">Play</a>
                        </div>
                    </div>
            </div>
            <div class="rbx-font-sm rbx-text-notes">
                The ROBLOX installer should download shortly. If it doesn’t, <a href="#" onclick="Roblox.ProtocolHandlerClientInterface.startDownload(); return false;">start the download now.</a>
            </div>
        </div>
    </div>
    <div class="InstallInstructionsImage" data-modalwidth="970" style="display:none;"></div>



<div id="pluginObjDiv" style="height:1px;width:1px;visibility:hidden;position: absolute;top: 0;"></div>
<iframe id="downloadInstallerIFrame" style="visibility:hidden;height:0;width:1px;position:absolute"></iframe>

<script type="text/javascript" src="/js/6b9fed5e91a508780b95c302464d62ef.js.gzip"></script>

<script type="text/javascript">
    Roblox.Client._skip = null;
    Roblox.Client._CLSID = '76D50904-6780-4c8b-8986-1A7EE0B1716D';
    Roblox.Client._installHost = 'setup.watrbx.wtf';
    Roblox.Client.ImplementsProxy = true;
    Roblox.Client._silentModeEnabled = true;
    Roblox.Client._bringAppToFrontEnabled = false;
    Roblox.Client._currentPluginVersion = '';
    Roblox.Client._eventStreamLoggingEnabled = true;

        
        Roblox.Client._installSuccess = function() {
            if(GoogleAnalyticsEvents){
                GoogleAnalyticsEvents.ViewVirtual('InstallSuccess');
                GoogleAnalyticsEvents.FireEvent(['Plugin','Install Success']);
                if (Roblox.Client._eventStreamLoggingEnabled && typeof Roblox.GamePlayEvents != "undefined") {
                    Roblox.GamePlayEvents.SendInstallSuccess(Roblox.Client._launchMode, play_placeId);
                }
            }
        }
        
            
        if ((window.chrome || window.safari) && window.location.hash == '#chromeInstall') {
            window.location.hash = '';
            var continuation = '(' + $.cookie('chromeInstall') + ')';
            play_placeId = $.cookie('chromeInstallPlaceId');
            Roblox.GamePlayEvents.lastContext = $.cookie('chromeInstallLaunchMode');
            $.cookie('chromeInstallPlaceId', null);
            $.cookie('chromeInstallLaunchMode', null);
            $.cookie('chromeInstall', null);
            RobloxLaunch._GoogleAnalyticsCallback = function() { var isInsideRobloxIDE = 'website'; if (Roblox && Roblox.Client && Roblox.Client.isIDE && Roblox.Client.isIDE()) { isInsideRobloxIDE = 'Studio'; };GoogleAnalyticsEvents.FireEvent(['Plugin Location', 'Launch Attempt', isInsideRobloxIDE]);GoogleAnalyticsEvents.FireEvent(['Plugin', 'Launch Attempt', 'Play']);EventTracker.fireEvent('GameLaunchAttempt_Win32', 'GameLaunchAttempt_Win32_Plugin'); if (typeof Roblox.GamePlayEvents != 'undefined') { Roblox.GamePlayEvents.SendClientStartAttempt(null, play_placeId); }  }; 
            Roblox.Client.ResumeTimer(eval(continuation));
        }
        
</script>

    <div id="usernotifications-data-model" class="hidden" data-notificationsdomain="https://notifications.watrbx.wtf/" data-notificationstestinterval="5000" data-notificationsmaxconnectiontime="43200000">
</div>
    <script type="text/javascript">
        var Roblox = Roblox || {};
        Roblox.ChatTemplates = {
            ChatBarTemplate: "chat-bar",
            AbuseReportTemplate: "chat-abuse-report",
            DialogTemplate: "chat-dialog",
            FriendsSelectionTemplate: "chat-friends-selection",
            GroupDialogTemplate: "chat-group-dialog",
            NewGroupTemplate: "chat-new-group",
            DialogMinimizeTemplate: "chat-dialog-minimize"
        };
        Roblox.Chat = {
            SoundFile: "http://www.watrbx.wtf/Chat/sound/chatsound.mp3"
        };
        Roblox.Party = {};
        Roblox.Party.SetGoogleAnalyticsCallback = function () {
            RobloxLaunch._GoogleAnalyticsCallback = function() { var isInsideRobloxIDE = 'website'; if (Roblox && Roblox.Client && Roblox.Client.isIDE && Roblox.Client.isIDE()) { isInsideRobloxIDE = 'Studio'; };GoogleAnalyticsEvents.FireEvent(['Plugin Location', 'Launch Attempt', isInsideRobloxIDE]);GoogleAnalyticsEvents.FireEvent(['Plugin', 'Launch Attempt', 'Play']);EventTracker.fireEvent('GameLaunchAttempt_Win32', 'GameLaunchAttempt_Win32_Plugin'); if (typeof Roblox.GamePlayEvents != 'undefined') { Roblox.GamePlayEvents.SendClientStartAttempt(null, play_placeId); }  }; 
        };

    </script>


<div id="chat-container" class="chat chat-container ng-scope collapsed" ng-modules="robloxApp, chat" ng-controller="chatController" ng-class="{'collapsed': chatLibrary.chatLayout.collapsed}">
    <!--chatLibrary.deviceType === deviceType.TABLET && ,
                'tablet-inapp': chatLibrary.tabletInApp-->
<div id="chat-data-model" class="hidden ng-isolate-scope" chat-data="" chat-view-model="chatViewModel" chat-library="chatLibrary" data-userid="1649" data-domain="watrbx.wtf" data-gamespagelink="http://www.watrbx.wtf/games" data-chatdomain="https://watrbx.wtf" data-numberofmembersforpartychrome="6" data-avatarheadshotsmultigetlimit="100" data-userpresencemultigetlimit="100" data-intervalofchangetitleforpartychrome="500" data-spinner="http://images.rbxcdn.com/4bed93c91f909002b1f17f05c0ce13d1.gif" data-notificationsdomain="https://notifications.watrbx.wtf/" data-devicetype="Computer" data-inapp="false" data-smallerchatenabled="true" data-cleanpartyfromconversationenabled="false">
</div>
    <div id="chat-main" class="chat-main chat-main-empty" ng-class="{'chat-main-empty': chatLibrary.chatLayout.chatLandingEnabled}" chat-bar="">
        <div id="chat-header" class="chat-windows-header chat-header">
            <div class="chat-header-label" ng-click="toggleChatContainer()">
                <span class="rbx-font-bold chat-header-title">Chat &amp; Party</span>
            </div>
            <div class="chat-header-action">
                <span class="rbx-notification-red ng-binding ng-hide" ng-show="chatLibrary.chatLayout.collapsed &amp;&amp; chatViewModel.conversationCount &gt; 0"></span>
                <span id="chat-group-create" class="rbx-icon-chat-group-create ng-hide" ng-hide="chatLibrary.chatLayout.collapsed || chatLibrary.chatLayout.errorMaskEnable || chatLibrary.chatLayout.chatLandingEnabled || chatLibrary.chatLayout.pageDataLoading" ng-click="launchDialog(newGroup.layoutId)" data-toggle="tooltip" title="" data-original-title="Add at least 2 people to create chat group"></span>
            </div>
        </div>
        <!-- ngIf: !chatLibrary.chatLayout.chatLandingEnabled -->
        <div id="chat-disconnect" class="chat-disconnect" ng-show="chatLibrary.chatLayout.errorMaskEnable || chatLibrary.chatLayout.pageDataLoading">
            <p ng-show="chatLibrary.chatLayout.errorMaskEnable" class="ng-hide">Trying to connect ...</p>
            <img ng-src="http://images.rbxcdn.com/4bed93c91f909002b1f17f05c0ce13d1.gif" alt="loading ..." src="http://images.rbxcdn.com/4bed93c91f909002b1f17f05c0ce13d1.gif">
        </div>
        <!-- ngIf: chatLibrary.chatLayout.chatLandingEnabled --><div id="chat-empty-list" class="chat-disconnect ng-scope" ng-hide="chatLibrary.chatLayout.errorMaskEnable" ng-if="chatLibrary.chatLayout.chatLandingEnabled">
            <span class="rbx-icon-chat-friends"></span>
            <p class="rbx-lead">Make friends to start chatting and partying!</p>
        </div><!-- end ngIf: chatLibrary.chatLayout.chatLandingEnabled -->
    </div>
    <script type="text/ng-template" id="chat-bar">
    <div id="chat-main" class="chat-main"
         ng-class="{'chat-main-empty': chatLibrary.chatLayout.chatLandingEnabled}" ng-cloak>
        <div id="chat-header"
             class="chat-windows-header chat-header">
            <div class="chat-header-label"
                 ng-click="toggleChatContainer()">
                <span class="rbx-font-bold chat-header-title">Chat & Party</span>
            </div>
            <div class="chat-header-action">
                <span class="rbx-notification-red"
                      ng-show="chatLibrary.chatLayout.collapsed && chatViewModel.conversationCount > 0"
                      ng-cloak>{{chatViewModel.conversationCount}}</span>
                <span id="chat-group-create"
                      class="rbx-icon-chat-group-create"
                      ng-hide="chatLibrary.chatLayout.collapsed || chatLibrary.chatLayout.errorMaskEnable || chatLibrary.chatLayout.chatLandingEnabled || chatLibrary.chatLayout.pageDataLoading"
                      ng-click="launchDialog(newGroup.layoutId)"
                      data-toggle="tooltip"
                      title="Add at least 2 people to create chat group"
                      ng-cloak></span>
            </div>
        </div>
        <div id="chat-body"
             class="chat-body"
             ng-hide="chatLibrary.chatLayout.errorMaskEnable || chatLibrary.chatLayout.pageDataLoading"
             ng-if="!chatLibrary.chatLayout.chatLandingEnabled">
            <div class="chat-search"
                 ng-class="{'chat-search-focus': chatLibrary.chatLayout.searchFocus}">
                <input type="text"
                       placeholder="Search"
                       class="chat-search-input"
                       ng-model="chatViewModel.searchTerm"
                       ng-focus="chatLibrary.chatLayout.searchFocus = true" />
                <span class="rbx-icon-chat-search"></span>
                <span class="rbx-icon-chat-cancel-search"
                      ng-click="cancelSearch()"></span>
            </div>
            <button id="chat-group-create-btn"
                    type="button"
                    class="btn rbx-btn-control-xs"
                    ng-click="launchDialog(newGroup.layoutId)"
                    title="Add at least 2 people to create chat group"
                    ng-cloak>
                <span>Create Chat Group</span>
            </button>
            <div id="chat-friend-list" class="rbx-scrollbar chat-friend-list" lazy-load>
                <ul id="chat-friends" class="chat-friends">
                    <li ng-repeat="chatUser in chatUserDict | orderList: chatLibrary.chatLayoutIds | filter : {name: chatViewModel.searchTerm}"
                        class="chat-friend">
                        <div ng-click="launchDialog(chatUser.layoutId)"
                             ng-if="chatUser.dialogType === dialogType.CHAT && chatUser.isConversation"
                             class="chat-friend-container">
                            <div class="chat-friend-avatar">
                                <img ng-src="{{chatLibrary.friendsDict[chatUser.displayUserId].AvatarThumb.Url}}"
                                     class="chat-avatar"
                                     thumbnail="chatLibrary.friendsDict[chatUser.displayUserId].AvatarThumb"
                                     image-retry
                                     ng-if="chatUser.isConversation">
                                <div class="chat-friend-status" ng-class="userPresenceTypes[chatLibrary.friendsDict[chatUser.displayUserId].UserPresenceType]['className']"></div>
                            </div>
                            <div class="chat-friend-info">
                                <span class="rbx-text-overflow chat-friend-name" ng-if="chatUser.isConversation">{{chatUser.name}}</span>
                                <span class="rbx-font-sm chat-friend-message"
                                      ng-class="{'rbx-font-bold': chatUser.HasUnreadMessages}"
                                      ng-bind-html="chatUser.DisplayMessage.Content"
                                      ng-if="chatUser.DisplayMessage"></span>
                            </div>
                        </div>

                        <div ng-click="launchDialog(chatUser.layoutId)"
                             ng-if="chatUser.dialogType === dialogType.GROUPCHAT && chatUser.isConversation"
                             class="chat-friend-container chat-friend-groups">
                            <div class="chat-friend-avatar">
                                <ul class="chat-avatar-groups">
                                    <li ng-repeat="userId in chatUser.userIds | limitTo : 4"
                                        class="chat-avatar">
                                        <img ng-src="{{chatLibrary.friendsDict[userId].AvatarThumb.Url}}"
                                             thumbnail="chatLibrary.friendsDict[userId].AvatarThumb"
                                             image-retry>
                                    </li>
                                </ul>
                            </div>
                            <div class="chat-friend-info">
                                <span class="rbx-text-overflow chat-friend-name">{{chatUser.name}}</span>
                                <span class="rbx-font-sm chat-friend-message"
                                      ng-class="{'rbx-font-bold': chatUser.HasUnreadMessages}"
                                      ng-bind-html="chatUser.DisplayMessage.Content"
                                      ng-if="chatUser.DisplayMessage"></span>
                            </div>
                        </div>

                        <div ng-click="launchDialog(chatUser.layoutId)"
                             ng-if="chatUser.dialogType === dialogType.PARTY && chatUser.isConversation"
                             class="chat-friend-container">
                            <div class="chat-friend-avatar chat-party-avatar">
                                <span class="rbx-icon-chat-party-avatar"></span>
                            </div>
                            <div class="chat-friend-info">
                                <span class="rbx-text-overflow chat-friend-name">{{chatUser.name}}</span>
                                <span class="rbx-font-sm chat-friend-message"
                                      ng-class="{'rbx-font-bold': chatUser.HasUnreadMessages}"
                                      ng-bind-html="chatUser.DisplayMessage.Content"
                                      ng-if="chatUser.DisplayMessage"></span>
                            </div>
                        </div>
                        <div ng-click="launchDialog(chatUser.layoutId)"
                             ng-if="chatUser.dialogType === dialogType.PENDINGPARTY  && chatUser.isConversation"
                             class="chat-friend-container chat-pending-party">
                            <div class="chat-friend-avatar">
                                <ul class="chat-avatar-groups">
                                    <li ng-repeat="userId in chatUser.userIds | limitTo : 3"
                                        class="chat-avatar">
                                        <img ng-src="{{chatLibrary.friendsDict[userId].AvatarThumb.Url}}"
                                             thumbnail="chatLibrary.friendsDict[userId].AvatarThumb"
                                             image-retry>
                                    </li>
                                    <li class="chat-avatar-party-icon">
                                        <span class="rbx-icon-chat-party-avatar"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="chat-friend-info">
                                <span class="rbx-text-overflow chat-friend-name">{{chatUser.name}}</span>
                                <span class="rbx-font-sm chat-friend-message party-pending-msg"
                                      ng-if="chatUser.incomingPartyInvite">{{chatUser.pendingPartyMsg}}</span>
                                <span class="rbx-font-sm chat-friend-message"
                                      ng-class="{'rbx-font-bold': chatUser.HasUnreadMessages}"
                                      ng-bind-html="chatUser.DisplayMessage.Content"
                                      ng-if="chatUser.DisplayMessage && !chatUser.incomingPartyInvite"></span>
                            </div>
                        </div>
                        <div ng-click="launchDialog(chatUser.layoutId)"
                             ng-if="!chatUser.isConversation"
                             class="chat-friend-container">
                            <div class="chat-friend-avatar">
                                <img ng-src="{{chatUser.AvatarThumb.Url}}" class="chat-avatar"
                                     thumbnail="chatUser.AvatarThumb"
                                     image-retry>
                                <div class="chat-friend-status" ng-class="userPresenceTypes[chatUser.UserPresenceType]['className']"></div>

                            </div>
                            <div class="chat-friend-info">
                                <span class="rbx-text-overflow chat-friend-name">{{chatUser.name}}</span>
                                <span class="rbx-font-xs chat-friend-message">{{userPresenceTypes[chatUser.UserPresenceType].title}}</span>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="chat-loading loading-bottom"
                     ng-show="chatLibrary.chatLayout.isChatLoading">
                    <img ng-src="{{chatLibrary.spinner}}" alt="loading ...">
                </div>
            </div>
        </div>
        <div id="chat-disconnect"
             class="chat-disconnect"
             ng-show="chatLibrary.chatLayout.errorMaskEnable || chatLibrary.chatLayout.pageDataLoading"
             ng-cloak>
            <p ng-show="chatLibrary.chatLayout.errorMaskEnable">Trying to connect ...</p>
            <img ng-src="{{chatLibrary.spinner}}" alt="loading ...">
        </div>
        <div id="chat-empty-list"
             class="chat-disconnect"
             ng-hide="chatLibrary.chatLayout.errorMaskEnable"
             ng-if="chatLibrary.chatLayout.chatLandingEnabled">
            <span class="rbx-icon-chat-friends"></span>
            <p class="rbx-lead">Make friends to start chatting and partying!</p>
        </div>
    </div>
</script>
    <div id="dialogs" class="dialogs ng-scope" ng-controller="dialogsController">
        <!-- ngRepeat: chatLayoutId in chatLibrary.layoutIdList -->
        <script type="text/ng-template" id="chat-abuse-report">
    <div class="dialog-report-container"
         ng-show="dialogLayout.isConfirmationOn">
        <div class="dialog-report-content">
            <h4>Continue to report?</h4>
            <button id="chat-abuse-report-btn"
                    class="rbx-btn-primary-xs"
                    ng-click="abuseReport(null, true)">
                Report
            </button>

            <button class="rbx-btn-control-xs"
                    ng-click="dialogLayout.isConfirmationOn = false">
                Cancel
            </button>
        </div>
    </div>
</script>

        <script type="text/ng-template" id="chat-friends-selection">
    <div class="chat-friends-container">
        <div class="chat-search"
             ng-class="{'group-select-container' : dialogData.selectedUserIds.length > 0}"
             group-select>
            <div class="group-select">
                <ul class="group-select-friends">
                    <li class="rbx-font-sm group-select-friend"
                        ng-repeat="userId in dialogData.selectedUserIds">
                        {{dialogData.selectedUsersDict[userId].Username}}
                        <span class="rbx-icon-chat-cancel-search group-select-cancel" ng-click="selectFriends(userId)"></span>
                    </li>
                </ul>
                <input type="text"
                       placeholder="Search"
                       class="chat-search-input"
                       focus-me="chatLibrary.inApp ? false: true"
                       ng-model="dialogData.searchTerm" />
            </div>
            <button id="friends-selection-btn"
                    class="rbx-btn-secondary-xs friends-invite-btn"
                    ng-disabled="dialogLayout.inviteBtnDisabled"
                    ng-click="sendInvite()">
                Invite
            </button>
        </div>

        <div id="scrollbar_friend_{{dialogData.dialogType}}_{{dialogData.layoutId}}" class="rbx-scrollbar chat-friend-list"
             friends-lazy-load>
            <ul class="chat-friends">
                <li ng-repeat="friend in chatLibrary.friendsDict | orderList: dialogData.friendIds  | filter: {Username: dialogData.searchTerm}"
                    class="chat-friend">
                    <div class="chat-friend-container chat-friend-select"
                         ng-click="selectFriends(friend.Id)">
                        <div class="chat-friend-avatar">
                            <img ng-src="{{friend.AvatarThumb.Url}}"
                                 thumbnail="friend.AvatarThumb"
                                 image-retry
                                 class="chat-avatar">
                            <div class="chat-friend-status" ng-class="userPresenceTypes[friend.UserPresenceType]['className']"></div>
                        </div>
                        <div class="chat-friend-info">
                            <span class="chat-friend-name">{{friend.Username}}</span>
                            <span class="rbx-font-sm chat-friend-message">{{userPresenceTypes[friend.UserPresenceType].title}}</span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</script>
        <script type="text/ng-template" id="chat-dialog">
    <div id="dialog-container" class="dialog-container"
         ng-class="{'group-has-banner': dialogData.isPartyExisted || dialogData.partyInGame,
                    'dialog-party': dialogData.dialogType == dialogType.PARTY,
                    'collapsed': dialogLayout.collapsed && chatLibrary.deviceType === deviceType.COMPUTER,
                    'active': dialogLayout.active && !dialogLayout.hasFocus}"
         ng-controller="dialogController"
         ng-focus="focusDialog()">
        <div class="dialog-main">
            <div class="chat-windows-header dialog-header">
                <div class="chat-header-label">
                    <span class="rbx-icon-chat-party-label"
                          ng-show="dialogData.dialogType == dialogType.PARTY"
                          title="Party"
                          ng-cloak></span>
                    <span id="chat-title"
                          class="rbx-font-bold chat-header-title dialog-header-title" 
                          ng-click="toggleDialogContainer()" 
                          ng-if="dialogData.dialogType != dialogType.PARTY">
                        <a class="rbx-font-bold"
                           ng-click="linkToProfile($event)"
                           ng-href="{{dialogData.nameLink}}">{{dialogData.name}}</a>
                    </span>
                    <span id="party-title"
                          class="rbx-font-bold chat-header-title dialog-header-title"
                          ng-click="toggleDialogContainer()" 
                          ng-if="dialogData.dialogType == dialogType.PARTY">{{dialogData.name}}
                    </span>
                </div>
                <div class="chat-header-action"
                     chat-setting>
                    <span class="rbx-icon-chat-close"
                          ng-click="closeDialog(dialogData.layoutId)"
                          data-toggle="tooltip"
                          title="Close"></span>
                    <span class="rbx-icon-chat-setting"
                          data-toggle="popover"
                          data-bind="group-settings-{{dialogData.Id}}"
                          ng-hide="(dialogLayout.collapsed && chatLibrary.deviceType === deviceType.COMPUTER) || chatLibrary.chatLayout.errorMaskEnable"></span>
                    <div class="rbx-popover-content" data-toggle="group-settings-{{dialogData.Id}}">
                        <ul class="rbx-dropdown-menu" role="menu">
                            <li>
                                <a id="abuse-report"
                                   ng-click="abuseReport(dialogData.userIds[0], false)">Report Abuse</a>
                            </li>
                            <li>
                                <a id="leave-group"
                                   ng-click="leaveGroup()"
                                   ng-if="dialogData.dialogType === dialogType.PARTY">Leave Party</a>
                            </li>
                        </ul>
                    </div>
                    <span id="create-party"
                          class="rbx-icon-chat-party-label"
                          ng-click="sendInvite(dialogData.layoutId)"
                          data-toggle="tooltip"
                          title="Play Together"
                          ng-hide="dialogData.party || (dialogLayout.collapsed && chatLibrary.deviceType === deviceType.COMPUTER)  || chatLibrary.chatLayout.errorMaskEnable"></span>
                </div>
            </div>

            <div id="dialog-member-header"
                 class="dialog-member-header"
                 ng-show="dialogData.isPartyExisted && !dialogData.partyInGame">
                <ul class="group-members">
                    <li class="group-member"
                        title="{{chatLibrary.friendsDict[userId].Username}}{{dialogData.membersDict[userId].statusTooltip}}"
                        ng-repeat="userId in dialogData.userIds">
                        <img ng-src="{{chatLibrary.friendsDict[userId].AvatarThumb.Url}}"
                             thumbnail="chatLibrary.friendsDict[userId].AvatarThumb"
                             image-retry
                             alt="{{chatLibrary.friendsDict[userId].Username}}"
                             class="group-member-avatar"
                             ng-class="{'group-leader': dialogData.membersDict[userId].memberStatus === memberStatus.LEADER,
                                        'group-pending':dialogData.membersDict[userId].memberStatus === memberStatus.PENDING}">
                    </li>
                </ul>
                <a id="find-game"
                   class="rbx-btn-primary-xs"
                   ng-show="dialogData.dialogType === dialogType.PARTY && dialogData.party.LeaderUser.Id === chatLibrary.userId"
                   ng-href="{{chatLibrary.gamesPageLink}}">Find Game</a>
                <a id="join-party"
                   class="rbx-btn-control-xs"
                   ng-hide="dialogData.dialogType === dialogType.PARTY || !dialogData.party"
                   ng-click="joinParty()">
                    Join Party
                </a>
            </div>

            <div id="party-ingame-header"
                 class="party-ingame-header"
                 ng-show="dialogData.dialogType === dialogType.PARTY && dialogData.isPartyExisted && dialogData.partyInGame">
                <img ng-src="{{dialogData.placeThumbnail.Url}}"
                     thumbnail="dialogData.placeThumbnail" image-retry
                     class="party-ingame-thumbnail">
                <div class="party-ingame-label"
                     ng-class="{'party-ingame-member': chatLibrary.userId !== dialogData.party.LeaderUser.Id}">
                    <span class="rbx-font-sm">Playing</span>
                    <span class="rbx-font-sm rbx-font-bold party-ingame-name">{{dialogData.party.GameName}}</span>
                </div>
                <a id="join-game"
                   class="rbx-btn-control-xs"
                   ng-hide="chatLibrary.userId === dialogData.party.LeaderUser.Id"
                   ng-click="joinGame()">
                    Join Game
                </a>
            </div>
            <div id="scrollbar_{{dialogData.dialogType}}_{{dialogData.layoutId}}"
                 class="rbx-scrollbar dialog-body"
                 dialog-lazy-load>
                <ul class="dialog-messages">
                    <li class="dialog-message-container"
                        ng-repeat="message in dialogData.ChatMessages | reverse"
                        ng-class="{'message-inbound': message.SenderUserId != chatLibrary.userId && !message.isSystemMessage,
                                    'system-message': message.isSystemMessage}">
                        <div class="rbx-font-sm dialog-message dialog-triangle dialog-message-content"
                             ng-bind-html="message.Content" 
                             ng-hide="message.isSystemMessage"></div>
                        <div class="rbx-font-xs dialog-sending" ng-show="message.sendingMessage">Sending...</div>
                        <div class="rbx-font-xs rbx-text-danger dialog-sending" ng-show="message.sendMessageHasError"
                             ng-bind="message.error || 'Error'"></div>
                        <span class="system-message-content"
                              ng-show="message.isSystemMessage"
                              ng-bind-html="message.Content"></span>
                    </li>
                </ul>
            </div>
            <div class="chat-loading loading-top"
                 ng-show="dialogLayout.isChatLoading">
                <img ng-src="{{chatLibrary.spinner}}" alt="loading ...">
            </div>
            <div class="dialog-input-container">
                <textarea id="dialog-input"
                          msd-elastic
                          focus-me="{{dialogLayout.focusMeEnabled}}"
                          ng-focus="toggleDialogFocusStatus(true)"
                          ng-blur="toggleDialogFocusStatus(false)"
                          placeholder="Send a message ..."
                          ng-model="dialogData.messageForSend"
                          key-press-enter="sendMessage()"
                          class="dialog-input"
                          maxlength="{{dialogLayout.limitCharacterCount}}"
                          ng-disabled="chatLibrary.chatLayout.errorMaskEnable"></textarea>
            </div>

            <div abuse-report></div>
        </div>
    </div>
</script>
        <script type="text/ng-template" id="chat-group-dialog">
    <div class="dialog-container group-dialog"
         ng-class="{'group-has-banner': dialogData.isPartyExisted || dialogData.partyInGame,
                    'dialog-party': dialogData.dialogType == dialogType.PARTY,
                    'collapsed': dialogLayout.collapsed && chatLibrary.deviceType === deviceType.COMPUTER,
                    'active': dialogLayout.active && !dialogLayout.hasFocus}"
         ng-controller="dialogController">
        <div class="dialog-main" 
             ng-hide="dialogLayout.isConfirmationOn || dialogLayout.lookUpMembers || dialogData.addMoreFriends">
            <div class="chat-windows-header dialog-header">
                <div class="chat-header-label">
                    <span class="rbx-icon-chat-party-label"
                          ng-show="dialogData.dialogType == dialogType.PARTY"
                          title="Party"
                          ng-cloak></span>
                    <span class="rbx-icon-chat-group-label"
                          ng-show="dialogData.dialogType != dialogType.PARTY"
                          title="Group Chat"
                          ng-cloak></span>
                    <span id="party-title"
                          class="rbx-font-bold dialog-header-title"
                          title="{{dialogData.partyName}}"
                          ng-click="toggleDialogContainer()" ng-if="dialogData.dialogType == dialogType.PARTY">{{dialogData.partyName}}</span>
                    <span id="group-chat-title"
                          class="rbx-font-bold dialog-header-title"
                          title="{{dialogData.groupName}}"
                          ng-click="toggleDialogContainer()" ng-if="dialogData.dialogType != dialogType.PARTY">{{dialogData.groupName}}</span>
                </div>
                <div class="chat-header-action"
                     chat-setting>
                    <span class="rbx-icon-chat-close"
                          ng-click="closeDialog(dialogData.layoutId)"
                          data-toggle="tooltip"
                          title="Close"></span>
                    <span class="rbx-icon-chat-setting"
                          data-toggle="popover"
                          data-bind="group-settings-{{dialogData.Id}}"
                          ng-hide="(dialogLayout.collapsed && chatLibrary.deviceType === deviceType.COMPUTER) || chatLibrary.chatLayout.errorMaskEnable"></span>
                    <div class="rbx-popover-content" data-toggle="group-settings-{{dialogData.Id}}">
                        <ul class="rbx-dropdown-menu" role="menu">
                            <li><a id="add-friends" ng-click="addFriends()">Add Friends</a></li>
                            <li><a id="view-participants" ng-click="viewParticipants()">View Participants</a></li>
                            <li>
                                <a id="leave-group" ng-click="leaveGroup()" ng-bind="dialogData.dialogType === dialogType.PARTY ? 'Leave Party' : 'Leave Group'"></a>
                            </li>
                        </ul>
                    </div>
                    <span id="create-party"
                          class="rbx-icon-chat-party-label"
                          ng-click="sendInvite(dialogData.layoutId)"
                          data-toggle="tooltip"
                          title="Play Together"
                          ng-hide="dialogData.party || (dialogLayout.collapsed && chatLibrary.deviceType === deviceType.COMPUTER) || chatLibrary.chatLayout.errorMaskEnable"></span>
                </div>
            </div>
            <div id="dialog-member-header"
                 class="dialog-member-header"
                 ng-show="dialogData.isPartyExisted && (!dialogData.partyInGame || dialogData.dialogType === dialogType.PENDINGPARTY)">
                <ul class="group-members">
                    <li class="group-member"
                        title="{{chatLibrary.friendsDict[userId].Username}}{{dialogData.membersDict[userId].statusTooltip}}"
                        ng-repeat="userId in dialogData.userIds | limitTo: dialogLayout.limitMemberDisplay">
                        <img ng-src="{{chatLibrary.friendsDict[userId].AvatarThumb.Url}}"
                             thumbnail="chatLibrary.friendsDict[userId].AvatarThumb"
                             image-retry
                             alt="{{chatLibrary.friendsDict[userId].Username}}"
                             class="group-member-avatar"
                             ng-class="{'group-leader': dialogData.membersDict[userId].memberStatus === memberStatus.LEADER,
                                        'group-pending':dialogData.membersDict[userId].memberStatus === memberStatus.PENDING}">
                    </li>
                    <li ng-show="dialogData.userIds.length === (dialogLayout.limitMemberDisplay + 1)"
                        title="{{chatLibrary.friendsDict[dialogData.userIds[dialogLayout.limitMemberDisplay]].Username}}{{dialogData.membersDict[dialogData.userIds[dialogLayout.limitMemberDisplay]].statusTooltip}}"
                        class="group-member">
                        <img ng-src="{{chatLibrary.friendsDict[dialogData.userIds[dialogLayout.limitMemberDisplay]].AvatarThumb.Url}}"
                             thumbnail="chatLibrary.friendsDict[dialogData.userIds[dialogLayout.limitMemberDisplay]].AvatarThumb"
                             image-retry
                             alt="{{chatLibrary.friendsDict[dialogData.userIds[dialogLayout.limitMemberDisplay]].Username}}"
                             class="group-member-avatar"
                             ng-class="{'group-pending': dialogData.membersDict[dialogData.userIds[dialogLayout.limitMemberDisplay]].memberStatus === memberStatus.PENDING}">
                    </li>
                    <li ng-show="dialogData.userIds.length > (dialogLayout.limitMemberDisplay + 1)"
                        ng-click="dialogLayout.lookUpMembers = !dialogLayout.lookUpMembers"
                        class="group-member group-member-plus"
                        ng-cloak>+{{dialogData.userIds.length - dialogLayout.limitMemberDisplay}}</li>
                </ul>
                <a id="find-game"
                   class="rbx-btn-primary-xs"
                   ng-show="dialogData.dialogType === dialogType.PARTY && dialogData.party.LeaderUser.Id === chatLibrary.userId && !chatLibrary.inApp"
                   ng-href="{{chatLibrary.gamesPageLink}}">Find Game</a>
                <a id="join-party"
                   class="rbx-btn-control-xs"
                   ng-hide="dialogData.dialogType === dialogType.PARTY || !dialogData.party"
                   ng-click="joinParty()">
                    Join Party
                </a>
            </div>

            <div id="party-ingame-header"
                 class="party-ingame-header"
                 ng-show="dialogData.dialogType === dialogType.PARTY && dialogData.isPartyExisted && dialogData.partyInGame">
                <img ng-src="{{dialogData.placeThumbnail.Url}}"
                     thumbnail="dialogData.placeThumbnail" image-retry
                     class="party-ingame-thumbnail">
                <div class="party-ingame-label"
                     ng-class="{'party-ingame-member': chatLibrary.userId !== dialogData.party.LeaderUser.Id}">
                    <span class="rbx-font-sm">Playing</span>
                    <span class="rbx-font-sm rbx-font-bold party-ingame-name">{{dialogData.party.GameName}}</span>
                </div>
                <a id="join-game"
                   class="rbx-btn-control-xs"
                   ng-hide="chatLibrary.userId === dialogData.party.LeaderUser.Id"
                   ng-click="joinGame()">
                    Join Game
                </a>
            </div>
            <div id="scrollbar_{{dialogData.dialogType}}_{{dialogData.layoutId}}"
                 class="rbx-scrollbar dialog-body"
                 dialog-lazy-load>
                <ul class="dialog-messages">
                    <li class="dialog-message-container"
                        ng-repeat="message in dialogData.ChatMessages | reverse"
                        ng-class="{'message-inbound': message.SenderUserId != chatLibrary.userId && !message.isSystemMessage,
                                    'system-message': message.isSystemMessage}">
                        <a ng-href="{{chatLibrary.friendsDict[message.SenderUserId].UserProfileLink}}"
                           ng-hide="message.isSystemMessage">
                            <img ng-if="message.SenderUserId != chatLibrary.userId"
                                 ng-src="{{chatLibrary.friendsDict[message.SenderUserId].AvatarThumb.Url}}"
                                 thumbnail="chatLibrary.friendsDict[message.SenderUserId].AvatarThumb"
                                 image-retry
                                 class="dialog-message-avatar">
                        </a>
                        <div class="dialog-message dialog-triangle" 
                             ng-hide="message.isSystemMessage">
                            <span ng-if="chatLibrary.friendsDict[message.SenderUserId] && message.SenderUserId != chatLibrary.userId"
                                  class="rbx-font-xs dialog-message-author">{{chatLibrary.friendsDict[message.SenderUserId].Username}}</span>
                            <span class="rbx-font-sm dialog-message-content" ng-bind-html="message.Content"></span>
                        </div>
                        <div class="rbx-font-xs dialog-sending" ng-show="message.sendingMessage">Sending...</div>
                        <div class="rbx-font-xs rbx-text-danger dialog-sending" ng-show="message.sendMessageHasError"
                             ng-bind="message.error || 'Error'"></div>
                        <span class="system-message-content"
                              ng-show="message.isSystemMessage"
                              ng-bind-html="message.Content"></span>
                    </li>
                </ul>
            </div>
            <div class="chat-loading loading-top"
                 ng-show="dialogLayout.isChatLoading">
                <img ng-src="{{chatLibrary.spinner}}" alt="loading ...">
            </div>
            <div class="dialog-input-container">
                <textarea msd-elastic
                          focus-me="{{dialogLayout.focusMeEnabled}}"
                          ng-focus="toggleDialogFocusStatus(true)"
                          ng-blur="toggleDialogFocusStatus(false)"
                          placeholder="Send a message ..."
                          ng-model="dialogData.messageForSend"
                          key-press-enter="sendMessage()"
                          class="dialog-input"
                          maxlength="{{dialogLayout.limitCharacterCount}}"
                          ng-disabled="chatLibrary.chatLayout.errorMaskEnable"></textarea>
            </div>
        </div>
        <div class="group-members-container"
             ng-show="dialogLayout.lookUpMembers">
            <div class="chat-windows-header">
                <span class="rbx-icon-chat-arrow-left"
                      ng-click="viewParticipants()"></span>
                <span class="rbx-font-bold">Participants</span>
            </div>
            <div id="group-members" class="rbx-scrollbar chat-friend-list"
                <ul class="chat-friends">
                    <li ng-repeat="userId in dialogData.userIds"
                        class="chat-friend">
                        <div class="chat-friend-container">
                            <div class="chat-friend-avatar">
                                <img ng-src="{{chatLibrary.friendsDict[userId].AvatarThumb.Url}}"
                                     thumbnail="chatLibrary.friendsDict[userId].AvatarThumb"
                                     image-retry
                                     class="chat-avatar">
                                <div class="chat-friend-status" ng-class="userPresenceTypes[chatLibrary.friendsDict[userId].UserPresenceType]['className']"></div>
                            </div>
                            <div class="chat-friend-info">
                                <span class="rbx-text-overflow chat-friend-name">{{chatLibrary.friendsDict[userId].Username}}</span>
                                <span class="rbx-font-sm rbx-text-notes" ng-show="dialogData.party && dialogData.membersDict[userId].memberStatus == memberStatus.LEADER">Leader</span>
                                <span class="rbx-font-sm rbx-text-notes" ng-show="dialogData.party && dialogData.membersDict[userId].memberStatus == memberStatus.MEMBER">In party</span>
                            </div>
                            <div class="group-member-action">
                                <span ng-if="chatLibrary.userId != userId && chatLibrary.userId === dialogData.party.LeaderUser.Id && dialogData.dialogType == dialogType.PARTY"
                                      class="rbx-icon-chat-remove"
                                      ng-click="removeMember(userId)"></span>
                                <span ng-if="chatLibrary.userId != userId && chatLibrary.userId === dialogData.InitiatorUser.Id && dialogData.dialogType != dialogType.PARTY"
                                      class="rbx-icon-chat-remove"
                                      ng-click="removeMember(userId)"></span>
                                <span class="rbx-icon-chat-report-person"
                                      ng-if="chatLibrary.userId != userId"
                                      ng-click="abuseReport(userId, false)"></span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div abuse-report></div>
        <div class="group-friends-container"
             ng-show="dialogData.addMoreFriends">
            <div class="chat-windows-header">
                <div class="chat-header-label">
                    <span class="rbx-icon-chat-arrow-left"
                          ng-click="dialogData.addMoreFriends = false"></span>
                    <span class="rbx-font-bold">Add Friends</span>
                    <span class="rbx-font-bold"
                          ng-class="{'group-overload': dialogLayout.isMembersOverloaded}">
                        ({{(dialogData.numberOfSelected)}}/{{chatLibrary.quotaOfPartyMembers}})
                    </span>
                </div>
            </div>
            <div friends-selection></div>
        </div>
    </div>
</script>

        <!-- ngIf: newGroup --><div dialog="" id="newGroup" dialog-data="newGroup" chat-library="chatLibrary" close-dialog="closeDialog('newGroup')" send-invite="sendInvite(newGroup.layoutId)" ng-if="newGroup" class="ng-scope ng-isolate-scope"></div><!-- end ngIf: newGroup -->
        <script type="text/ng-template" id="chat-new-group">
    <div class="dialog-container group-create-container"
         ng-controller="friendsController">
        <div class="chat-windows-header">
            <div class="chat-header-title">
                <span class="rbx-font-bold">{{dialogLayout.title}}</span>
                <span class="rbx-font-bold"
                      ng-class="{'group-overload': dialogLayout.isMembersOverloaded}">
                    ({{(dialogData.numberOfSelected)}}/{{chatLibrary.quotaOfPartyMembers}})
                </span>
            </div>
            <div class="chat-header-action">
                <span class="rbx-icon-chat-close"
                      ng-click="closeDialog(dialogData.layoutId)"
                      data-toggle="tooltip"
                      title="Close"></span>
            </div>
        </div>
        <div friends-selection></div>
    </div>
</script>
        <script type="text/ng-template" id="chat-dialog-minimize">
    <div id="dialogs-minimize-container"
         class="dialogs-minimize-container"
         ng-show="hasMinimizedDialogs"
         data-toggle="popover"
         data-bind="dialogs">
        <span class="rbx-icon-chat-minimize"></span>
        <span class="minimize-count">{{chatLibrary.minimizedDialogIdList.length}}</span>
        <div class="rbx-popover-content" data-toggle="dialogs">
            <ul class="rbx-dropdown-menu minimize-list" role="menu">
                <li ng-repeat="dialogLayoutId in chatLibrary.minimizedDialogIdList"
                    class="minimize-item"
                    id="{{dialogLayoutId}}"
                    minimize-item>
                    <a class="rbx-text-overflow minimize-title">
                        <span>
                            {{chatLibrary.minimizedDialogData[dialogLayoutId].name}}
                        </span>
                    </a>
                    <span class="rbx-icon-chat-cancel-search minimize-close"></span>
                </li>
            </ul>
        </div>
    </div>
</script>


        <div id="dialogs-minimize" class="dialogs-minimize ng-isolate-scope" dialog-minimize="" chat-library="chatLibrary">
    <div id="dialogs-minimize-container" class="dialogs-minimize-container ng-hide" ng-show="hasMinimizedDialogs" data-toggle="popover" data-bind="dialogs" data-original-title="" title="">
        <span class="rbx-icon-chat-minimize"></span>
        <span class="minimize-count ng-binding">0</span>
        <div class="rbx-popover-content" data-toggle="dialogs">
            <ul class="rbx-dropdown-menu minimize-list" role="menu">
                <!-- ngRepeat: dialogLayoutId in chatLibrary.minimizedDialogIdList -->
            </ul>
        </div>
    </div>
</div>
    </div>

    <script type="text/javascript">
        $(function () {
            // Because of placeLauncher.js, has to add this stupid global "play_placeId"
            $(document).on('Roblox.Chat.PartyInGame', function (event, args) {
                play_placeId = args.placeId;
            });
        });
    </script>

</div>

    <script type="text/javascript">
        $(function () {
            Roblox.CookieUpgrader.domain = 'watrbx.wtf';
            Roblox.CookieUpgrader.upgrade("GuestData", { expires: Roblox.CookieUpgrader.thirtyYearsFromNow });
            Roblox.CookieUpgrader.upgrade("RBXSource", { expires: function (cookie) { return Roblox.CookieUpgrader.getExpirationFromCookieValue("rbx_acquisition_time", cookie); } });
            Roblox.CookieUpgrader.upgrade("RBXViralAcquisition", { expires: function (cookie) { return Roblox.CookieUpgrader.getExpirationFromCookieValue("time", cookie); } });
                
                Roblox.CookieUpgrader.upgrade("RBXMarketing", { expires: Roblox.CookieUpgrader.thirtyYearsFromNow });
                
                            
                Roblox.CookieUpgrader.upgrade("RBXSessionTracker", { expires: Roblox.CookieUpgrader.fourHoursFromNow });
                
                    });
    </script>

    <script>
        var _comscore = _comscore || [];
        _comscore.push({ c1: "2", c2: "6035605", c3: "", c4: "", c15: "" });

        (function() {
            var s = document.createElement("script"), el = document.getElementsByTagName("script")[0];
            s.async = true;
            s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
            el.parentNode.insertBefore(s, el);
        })();
    </script>
    <noscript>
        <img src="http://b.scorecardresearch.com/p?c1=2&c2=&c3=&c4=&c5=&c6=&c15=&cv=2.0&cj=1"/>
    </noscript>



<iframe src="https://www.google.com/recaptcha/api2/aframe" width="0" height="0" style="display: none;"></iframe></body></html>