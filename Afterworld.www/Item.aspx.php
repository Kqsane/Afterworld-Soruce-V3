<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
    header("Location: /newlogin");
    exit();
}

if (isset($_GET["id"])) {
    $FindGames = $pdo->prepare('SELECT * FROM assets WHERE AssetID = :gid');
    $FindGames->execute(['gid' => (int)$_GET["id"]]);
    $row = $FindGames->fetch(PDO::FETCH_ASSOC);
    if ($row['AssetType'] === 9) {
        header("Location: /games/".$row['AssetID']."/".ucwords(str_replace(" ", "-", $row['Name'])));
    }
    $Find1 = $pdo->prepare('SELECT * FROM users WHERE UserId=:uid');
    $Find1->execute(['uid'=>$row['CreatorID']]);
    $creator = $Find1->fetch(PDO::FETCH_ASSOC);
}

if (!$row) {
    exit("item doesnt exist");
}

function returnName($assetType){
    global $asset_types;
    return $asset_types[$assetType] ?? "Unknown";
}

function timeAgo($timestamp) {
    $time = time() - $timestamp;
    $units = [
        31536000 => 'year',
        2592000  => 'month',
        604800   => 'week',
        86400    => 'day',
        3600     => 'hour',
        60       => 'minute',
        1        => 'second'
    ];
    foreach ($units as $unitSeconds => $unitName) {
        if ($time >= $unitSeconds) {
            $numUnits = floor($time / $unitSeconds);
            return $numUnits . ' ' . $unitName . ($numUnits > 1 ? 's' : '') . ' ago';
        }
    }
    return 'Just now';
}

function doesUserOwnAsset($id, $userId = null){ 
    global $pdo;
    $stmt = $pdo->prepare("SELECT userId FROM inventory WHERE userId = ? AND assetId = ?");
    $stmt->bindParam(1, $userId, PDO::PARAM_INT);
    $stmt->bindParam(2, $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount() >= 1;
}

$user = getuserinfo($_COOKIE['_ROBLOSECURITY']);
if ($user) {
    $ownership = boolval(doesUserOwnAsset($_GET["id"], $user['UserId']));
}
?>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<!-- MachineID: WEB345 -->
<head id="ctl00_Head1"><meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true" /><title>
	<?php echo htmlspecialchars($row['Name']).", a ".returnName($row['AssetType'])." by ".$creator['Username']." - AFTERWORLD (updated ".gmdate("d/m/Y H:i:s ", $row['Updated_At']).")"; ?>
</title>
    
    
    <script type="text/javascript" src="http://cdn.gigya.com/js/gigya.js?apiKey=3_OsvmtBbTg6S_EUbwTPtbbmoihFY5ON6v6hbVrTbuqpBs7SyF_LQaJwtwKJ60sY1p"></script>
    
<link rel='stylesheet' href='/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css' />

<link rel='stylesheet' href='/CSS/Base/CSS/page___53eeb36e90466af109423d4e236a59bd_m.css' />
<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="Content-Language" content="en-us" /><meta name="author" content="AFTERWORLD" /><meta id="ctl00_metadescription" name="description" content="<?php echo htmlspecialchars($row['Name']).", a ".returnName($row['AssetType'])." by ".$creator['Username']." - AFTERWORLD (updated ".gmdate("d/m/Y H:i:s ", $row['Updated_At'])."): ".htmlspecialchars($row['Description']); ?>" /><meta id="ctl00_metakeywords" name="keywords" content="virtual good <?php echo htmlspecialchars($row['Name']); ?>, a Hat by ROBLOX - ROBLOX (updated 4/18/2015 12:07:27 PM) items, ROBLOX <?php echo htmlspecialchars($row['Name']) ?>, a Hat by ROBLOX - ROBLOX (updated 4/18/2015 12:07:27 PM)" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
	    	<script type="text/javascript">

        var _gaq = _gaq || [];

		    _gaq.push(['_setAccount', 'UA-11419793-1']);
		    _gaq.push(['_setCampSourceKey', 'rbx_source']);
		    _gaq.push(['_setCampMediumKey', 'rbx_medium']);
		    _gaq.push(['_setCampContentKey', 'rbx_campaign']);
		        _gaq.push(['_setDomainName', 'aftwld.xyz']);
		_gaq.push(['b._setAccount', 'UA-486632-1']);
		_gaq.push(['b._setCampSourceKey', 'rbx_source']);
		_gaq.push(['b._setCampMediumKey', 'rbx_medium']);
		_gaq.push(['b._setCampContentKey', 'rbx_campaign']);

		_gaq.push(['b._setDomainName', 'aftwld.xyz']);
        
            _gaq.push(['b._setCustomVar', 1, 'Visitor', 'Anonymous', 2]);
            _gaq.push(['b._trackPageview']);    
        
        
        

		_gaq.push(['c._setAccount', 'UA-26810151-2']);
		_gaq.push(['c._setDomainName', 'aftwld.xyz']);

		(function() {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();

	</script>
<div id="roblox-linkify" data-enabled="true" data-regex="(https?\:\/\/)?(?:www\.)?([a-z0-9\-]{2,}\.)*((m|de|www|web|api|blog|wiki|help|corp|polls|bloxcon|developer)\.roblox\.com|robloxlabs\.com)((\/[A-Za-z0-9-+&amp;@#\/%?=~_|!:,.;]*)|(\b|\s))" data-regex-flags="gm"></div><script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>
<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js'></script>
<script type='text/javascript'>window.Sys || document.write("<script type='text/javascript' src='/js/Microsoft/MicrosoftAjax.js'><\/script>")</script>
<script type='text/javascript' src='/rbxcdn_js/50cb8c7590b75499925be4825ab1fb8f.js'></script>
<script type='text/javascript'>Roblox.config.externalResources = [];Roblox.config.paths['Pages.Catalog'] = '/rbxcdn_js/a2ff3787d1fd8d3c2492b5f5c5ec70b6.js';Roblox.config.paths['Pages.CatalogShared'] = '/rbxcdn_js/4eb48eec34ca711d5a7b08a4291ac753.js';Roblox.config.paths['Pages.Messages'] = '/rbxcdn_js/e8cbac58ab4f0d8d4c707700c9f97630.js';Roblox.config.paths['Resources.Messages'] = '/rbxcdn_js/fb9cb43a34372a004b06425a1c69c9c4.js';Roblox.config.paths['Widgets.AvatarImage'] = '/rbxcdn_js/bbaeb48f3312bad4626e00c90746ffc0.js';Roblox.config.paths['Widgets.DropdownMenu'] = '/rbxcdn_js/7b436bae917789c0b84f40fdebd25d97.js';Roblox.config.paths['Widgets.GroupImage'] = '/rbxcdn_js/33d82b98045d49ec5a1f635d14cc7010.js';Roblox.config.paths['Widgets.HierarchicalDropdown'] = '/rbxcdn_js/fbb86cf0752d23f389f983419d3085b4.js';Roblox.config.paths['Widgets.ItemImage'] = '/rbxcdn_js/838ec9c8067ba6fd6793a8bdbdb48a5c.js';Roblox.config.paths['Widgets.PlaceImage'] = '/rbxcdn_js/f2697119678d0851cfaa6c2270a727ed.js';Roblox.config.paths['Widgets.SurveyModal'] = '/rbxcdn_js/d6e979598c460090eafb6d38231159f6.js';</script><script type="text/javascript">
    $(function () {
        Roblox.JSErrorTracker.initialize({ 'suppressConsoleError': true});
    });
</script><script type='text/javascript' src='/rbxcdn_js/db95b7bf9a4587f82d242e5a2fc3fc30.js'></script>

    <script type="text/javascript">
function Roblox_Item_Top_728x90_RTP(estimate){rtp['/1015347/Roblox_Item_Top_728x90'] = rp_valuation.estimate;}
var rtp = rtp || {};
oz_api="valuation";oz_site="9874/18868";oz_zone="58960";oz_ad_slot_size="728x90";oz_callback=Roblox_Item_Top_728x90_RTP;
</script><script type="text/javascript" src="http://tap-cdn.rubiconproject.com/partner/scripts/rubicon/dorothy.js?pc=9874/18868"></script><script>

function Roblox_Item_Right_160x600_RTP(estimate){rtp['/1015347/Roblox_Item_Right_160x600'] = rp_valuation.estimate;}
var rtp = rtp || {};
oz_api="valuation";oz_site="9874/18868";oz_zone="58960";oz_ad_slot_size="160x600";oz_callback=Roblox_Item_Right_160x600_RTP;
</script><script type="text/javascript" src="http://tap-cdn.rubiconproject.com/partner/scripts/rubicon/dorothy.js?pc=9874/18868"></script><script>

    googletag.cmd.push(function() {
        Roblox = Roblox || {};
        Roblox.AdsHelper = Roblox.AdsHelper || {};
        Roblox.AdsHelper.slots = [];
        Roblox.AdsHelper.slots = Roblox.AdsHelper.slots || []; Roblox.AdsHelper.slots.push({slot:googletag.defineSlot("/1015347/Roblox_Item_Top_728x90", [728, 90], "3133333934333635").addService(googletag.pubads()), id: "3133333934333635", path: "/1015347/Roblox_Item_Top_728x90"});
Roblox.AdsHelper.slots = Roblox.AdsHelper.slots || []; Roblox.AdsHelper.slots.push({slot:googletag.defineSlot("/1015347/Roblox_Item_Right_160x600", [160, 600], "3632323431393436").addService(googletag.pubads()), id: "3632323431393436", path: "/1015347/Roblox_Item_Right_160x600"});

        for (var key in Roblox.AdsHelper.slots) {
            var slot = Roblox.AdsHelper.slots[key].slot;
            var id = Roblox.AdsHelper.slots[key].id;
            var path = Roblox.AdsHelper.slots[key].path;

                     slot.setTargeting('pos', path);
                                             slot.setTargeting('tier', rtp[path].tier);
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

        googletag.pubads().setTargeting("Age", "Unknown");
                    googletag.pubads().setTargeting("Env",  "Production");
                    googletag.pubads().setTargeting("AssetID", "<?php echo htmlspecialchars($row['AssetID']); ?>");
                                        googletag.pubads().enableSingleRequest();
        googletag.pubads().collapseEmptyDivs();
        googletag.enableServices();
    });
    </script>  
<link rel='canonical' href='https://devopstest1.aftwld.xyz/item.aspx?id=<?php echo htmlspecialchars($row['AssetID']); ?>' /><meta property="og:type" content="product" /><meta property="og:site_name" content="AFTERWORLD" /><meta property="og:url" content="https://devopstest1.aftwld.xyz/item.aspx?id=<?php echo $row['AssetID']; ?>" /><meta property="og:title" content="<?php echo htmlspecialchars($row['Name']); ?>" /><meta property="og:description" content="<?php echo htmlspecialchars($row['Description']); ?>" /><meta property="og:image" content="/Thumbs/Asset.ashx?assetId=<?php echo htmlspecialchars($row['AssetID']); ?>" /><meta property="og:image:width" content="320" /><meta property="og:image:height" content="320" /><meta property="fb:app_id" content="190191627665278" /><meta name="twitter:card" content="product" /><meta name="twitter:site" content="@ROBLOX" /><meta name="twitter:label1" content="Item Type" /><meta name="twitter:data1" content="ROBLOX <?php echo returnName($row['AssetType']); ?>" /><meta name="twitter:label2" content="Creator" /><meta name="twitter:data2" content="<?php echo htmlspecialchars($creator['Username']); ?>" /><meta name="twitter:app:country" content="US" /><meta name="twitter:app:name:iphone" content="ROBLOX Mobile" /><meta name="twitter:app:id:iphone" content="431946152" /><meta name="twitter:app:name:ipad" content="ROBLOX Mobile" /><meta name="twitter:app:id:ipad" content="431946152" /><meta name="twitter:app:name:googleplay" content="ROBLOX" /><meta name="twitter:app:id:googleplay" content="com.roblox.client" /></head>
<body class="">

    <script type="text/javascript">Roblox.XsrfToken.setToken('bswL7bPPy6YL');</script>
 
    <script type="text/javascript">
        if (top.location != self.location) {
            top.location = self.location.href;
        }
    </script>
  
<style type="text/css">
    
</style>
<form name="aspnetForm" method="post" action="/Recycled-Cardboard-Swordpack-item?id=<?php echo htmlspecialchars($row['AssetID']); ?>" id="aspnetForm" class="nav-container no-gutter-ads">
<div>
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="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" />
</div>


<script src="/ScriptResource.axd?d=OgIn8Dj4mHXZrkJIOj_4yhDpjcFUEl5eAdNoNlfxwmxKxgsFicuBkvC5O-suv0jwzhcPYr5otEhb4HtLZ2TZqxZj2MmdhpzuEhgeC5HaoIpjoq8Zfftx84WAKCg2VC_fdq5dMiYF8ONp_2dmEQTvSc4jDn7gQQdtYzI7oqd39cay8Lh3josiwDXV_PY1x1lvWJJfZjfuwQKbdb4VxR-g5FMyUYDhiXNVQ8pOh8QX-5HWzYlSkWCVBxsYWHoJPgPK3SeDlSTuLQGscWT7mr2cw7zAVP-jwaJ0qb7rofRMk6rJRBqEUI3jR2DHe-fRJRuXUmob_XgzSyKGtBOGBhaG_SAobiEyBxW39QEAZR5R6eDVzjGe6Nh3l6a0l4IPZDcX3b63SGp2QRo9d8mJG_6GBRgXju6scM3QFiymqYT6eCCqT_SZ0" type="text/javascript"></script>
<div>

	<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="6AB857A9" />
	<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="m2b+bNgcFnpV4PpMjvpE7rzg4kC+7Tz/3beygz71q0EjABJPxxPXhDWuboadLE9+Aw4ebneswKhpt1Iqqi6mknfGsnhuqBeWhwP+NsOTW05bl2Bb" />
</div>
    <div id="fb-root">
    </div>
    
    
         
    
    
    

<?php
  include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php';
?>  
        <div id="navContent" class="nav-content"><div class="nav-content-inner">
    <div id="MasterContainer" >
        

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



        <script type="text/javascript">Roblox.FixedUI.gutterAdsEnabled=false;</script>

        

        <div id="Container">
            
            
        </div>

		
    <div id="AdvertisingLeaderboard"  class = " top-ad-728 ">
        
<div style="width: 728px; " class="adp-gpt-container">
    <span id='3133333934333635' class="GPTAd banner" data-js-adtype="gptAd">
    </span>
        <div class="ad-annotations " style="width: 728px">
            <span class="ad-identification">
                Advertisement
                    <span> - </span>
                    <a href="" class="UpsellAdButton" title="Click to learn how to remove ads!">Why am I seeing ads?</a>
            </span>
                <a class="BadAdButton" href="/Ads/ReportAd.aspx" title="click to report an offensive ad">Report</a>
        </div>
    <script type="text/javascript">
        googletag.cmd.push(function () {
            if (typeof RobloxAds == "undefined" || typeof RobloxAds.showAdCallback == "undefined" || RobloxAds.showAdCallback("Roblox_Item_Top_728x90")) {
                googletag.display("3133333934333635");
            }
        });
    </script>
</div>
    </div>

        
        
        <noscript><div class="SystemAlert"><div class="SystemAlertText">Please enable Javascript to use all the features on this site.</div></div></noscript>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>
        
        
        
        
        <div id="BodyWrapper">
            
            <div id="RepositionBody">
                <div id="Body" style='width:970px;'>
                    
    <div id="ItemContainer" class="text ">
        <div>
            <div id="ctl00_cphRoblox_GearDropDown" class="SetList ItemOptions <?php if(!isset($ownership) || !$ownership){echo "invisible";} ?>" data-isdropdownhidden="<?php if(!isset($ownership) || !$ownership){echo "True";} ?>" data-isitemlimited="False" data-isitemresellable="False">
                <a href="#" class="btn-dropdown">
                    <img src="/rbxcdn_img/ea51d75440715fc531fc3ad281c722f3.png" />
                </a>
                <div class="clear"></div>
                <div class="SetListDropDown">
                    <div class="SetListDropDownList invisible">
                        <div class="menu invisible">
                            
                            
                            
                            
                            <div id="ctl00_cphRoblox_ItemOwnershipPanel">
	
                                <a id="ctl00_cphRoblox_btnDelete" class="invisible" href="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl00$cphRoblox$btnDelete&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, true))">Delete from My Stuff</a>
                            
</div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="notranslate" data-se="item-name">
                <?php echo htmlspecialchars($row['Name']); ?>
            </h1>
            <h3>
                <?php echo "AFTERWORLD ".returnName($row['AssetType']); ?>
                
            </h3>
        </div>
        <div id="Item">
            <div id="Details">
                
                        <div id="assetContainer">
                            <div id="Thumbnail">
                                
                                

<div id="AssetThumbnail" class="thumbnail-holder"  data-3d-thumbs-enabled data-url="/thumbnail/asset?assetId=<?php echo htmlspecialchars($row['AssetID']); ?>&amp;thumbnailFormatId=254&amp;width=320&amp;height=320" style="width:320px; height:320px;">
    <span class="thumbnail-span" data-3d-url="/asset-thumbnail-3d/json?assetId=<?php echo htmlspecialchars($row['AssetID']); ?>"  data-js-files='/rbxcdn_js/2cdabe2b5b7eb87399a8e9f18dd7ea05.js' ><img  class='' src='<?php echo "https://devopstest1.aftwld.xyz/Thumbs/Asset.ashx?assetId=".$row['AssetID']; ?>&width=320&height=320' /></span>
	<?php
	$limitedType = htmlspecialchars($row['Limited']);

	if ($limitedType == 1) {
		echo '<img id="ctl00_cphRoblox_ItemLimitedOverlay" alt="Limited" style="position:absolute;bottom:0px;margin-left:-28px;z-index:1;left:0px;" src="/images/ecf6b4f4789665e0e4f45d202fa740c7.png">';
	} elseif ($limitedType == 2) {
		echo '<img id="ctl00_cphRoblox_ItemLimitedOverlay" alt="Limited Unique" style="position:absolute;bottom:0px;margin-left:-28px;z-index:1;left:0px;" src="/images/e98dda3d70d864320c1adea9053ff52e.png">';
	}
	?>
    <span class="enable-three-dee btn-control btn-control-small"></span>
</div>
                                
                                <img src="/images/NewTag.png" id="ctl00_cphRoblox_ItemNewOverlay" alt="New" style="position: absolute; top: 0px; right: 0px;" />
								
								
                            </div>
                            <span id="ThumbnailText"></span>
                        </div>
                    
                <div id="Summary">
                    <div class="SummaryDetails">
                        <div id="Creator" class="Creator">
                            <div class="Avatar">
                                
                                <a id="ctl00_cphRoblox_AvatarImage" class=" notranslate" class=" notranslate" title="<?php echo htmlspecialchars($creator['Username']); ?>" href="/User.aspx?ID=<?php echo htmlspecialchars($row['CreatorID']); ?>" style="display:inline-block;height:70px;width:70px;cursor:pointer;"><img <?php echo'src="/Thumbs/Avatar.ashx?userId='.$creator['UserId'].'"'; ?> height="70" width="70" border="0" onerror="return Roblox.Controls.Image.OnError(this)" alt="<?php echo htmlspecialchars($creator['Username']); ?>" class=" notranslate" /><img src="/Thumbs/BCOverlay.ashx?Username=<?php echo htmlspecialchars($creator['Username']); ?>" class="bcOverlay" align="left" style="position:relative;top:-19px;" /></a>
                            </div>
                        </div>
                        <div class="item-detail">
                            <span class="stat-label notranslate">Creator:</span>
                            <a id="ctl00_cphRoblox_CreatorHyperLink" class="stat notranslate" href="/User.aspx?ID=<?php echo htmlspecialchars($row['CreatorID']); ?>"><?php echo htmlspecialchars($creator['Username']); ?></a>
                            
                            <div>
                                <span class="stat-label">Created:</span>
                                <span class="stat">
                                   <?php echo gmdate("d/m/Y", $row['Created_At']); ?>
                                </span>
                            </div>
                            <div id="LastUpdate">
                                <span class="stat-label">Updated:</span>
                                <span class="stat">
                                    <?php echo timeAgo($row['Updated_At']); ?>
                                </span>
                                </div>
                                
                                 
                        </div>
                        <div id="ctl00_cphRoblox_DescriptionPanel" class="DescriptionPanel notranslate">
	
                            <pre class="Description Full text"> <?php echo htmlspecialchars($row['Description']) ?> </pre>
                            <pre class="Description body text"><span class="description-content"><?php echo htmlspecialchars($row['Description']) ?></span><span class="description-more-container"></span></pre>
                        
</div>
                        <div class="ReportAbuse">
                            <div id="ctl00_cphRoblox_AbuseReportButton1_AbuseReportPanel" class="ReportAbuse">
	
    <span class="AbuseIcon"><a id="ctl00_cphRoblox_AbuseReportButton1_ReportAbuseIconHyperLink" href="abusereport/asset?id=<?php echo htmlspecialchars($row['AssetID']); ?>&amp;RedirectUrl=http%3a%2f%2f194.62.248.75:34533%2fitem.aspx%3fseoname%3dRecycled-Cardboard-Swordpack%26id%3d<?php echo htmlspecialchars($row['AssetID']); ?>"><img src="images/abuse.PNG?v=2" alt="Report Abuse" style="border-width:0px;" /></a></span>
    <span class="AbuseButton"><a id="ctl00_cphRoblox_AbuseReportButton1_ReportAbuseTextHyperLink" href="abusereport/asset?id=<?php echo htmlspecialchars($row['AssetID']); ?>&amp;RedirectUrl=http%3a%2f%2f194.62.248.75:34533%2fitem.aspx%3fseoname%3dRecycled-Cardboard-Swordpack%26id%3d<?php echo htmlspecialchars($row['AssetID']); ?>">Report Abuse</a></span>

</div>
                        </div>
                        
                        
                        
                        
                        
                        <div class="GearGenreContainer divider-top">
                            <div id="GenresDiv">
                                <div id="ctl00_cphRoblox_Genres">
	
                                    <div class="stat-label">
                                        Genres:</div>
                                    <div class="GenreInfo stat">
                                        

<div>
    
            
        
            
        
            
        
            <div id="ctl00_cphRoblox_GenresDisplayTest_AssetGenreRepeater_ctl03_AssetGenreRepeaterPanel" class="AssetGenreRepeater_Container">
		
                <div class="GamesInfoIcon All"></div>
                <div><a href="/games">All</a></div>
            
	</div>
        
            
        
            
        
            
        
            
        
            
        
            
        
            
        
            
        
            
        
    <div style="clear:both;"></div>
</div>
                                    </div>
                                
</div>
                            </div>
                            
                            <div class="clear"></div>
                        </div>
                        <div class="PluginMessageContainer" style="display: none;">
                            <p>
                                <span class="status-confirm">A newer version is available.</span>
                            </p>
                        </div>
                    </div>
                    <div class="BuyPriceBoxContainer">
                        <div class="BuyPriceBox">
                            
                            
                            
                            
                            
                            <div id="<?php if($row['isPrivate'] == 1){ echo "ctl00_cphRoblox_NotForPurchasePanel"; }else{ echo"ctl00_cphRoblox_RobuxPurchasePanel"; }?>">
                                <?php if($row['isPrivate'] == 0 && (!isset($ownership) || !$ownership)): ?>
								<div id="RobuxPurchase">
                                    <div class="calloutParent">
									
                                        Price: <span class="robux<?php if($row['RobuxPrice'] < 1){ echo "-text"; } ?> " data-se="<?php  if($row['RobuxPrice'] < 1){ echo "item-free"; }else{ echo "item-priceinrobux"; } ?>">
                                            <?php if($row['RobuxPrice'] < 1){ echo "FREE"; }else{ echo $row['RobuxPrice']; } ?>
                                        </span>
                                        
                                    </div>
                                    <div id="BuyWithRobux">
                                        <div data-expected-currency="1" data-asset-type="<?php echo returnName($row['AssetType']); ?>" class="btn-primary btn-medium PurchaseButton " data-se="item-buyforrobux" data-item-name="<?php echo htmlspecialchars($row['Name']); ?>" data-item-id="<?php echo htmlspecialchars($row['AssetID']); ?>" data-expected-price="<?php echo htmlspecialchars($row['RobuxPrice']); ?>" data-product-id="<?php echo $row['AssetID'] ?>" data-expected-seller-id="<?php echo $row["CreatorID"]; ?>" data-bc-requirement="0" data-seller-name="<?php echo htmlspecialchars($creator['Username']); ?>">
                                             <?php if($row['RobuxPrice'] < 1){ echo "Take Now"; }else{ echo "Buy with R$"; } ?>
                                             <span class="btn-text">Buy with R$</span>
                                        </div>
                                        
                                    </div>
                                </div>
								<?php elseif(isset($ownership) && $ownership === true): ?>
								<div class="calloutParent">
                                        Price: <span class="robux " data-se="item-priceinrobux">
                                            <?php echo $row['RobuxPrice']; ?>
                                        </span>
                                        
                                    </div>
								<a class="btn-primary roblox-buy-now btn-medium btn-disabled-primary" original-title="You already own this item." href="javascript: return false;">Buy Now  <span class="btn-text">Buy Now</span></a>
								<?php elseif($row['isPrivate'] == 1): ?>
								<a class="btn-primary roblox-buy-now btn-medium btn-disabled-primary" original-title="This item is no longer for sale." href="javascript: return false;">Buy Now  <span class="btn-text">Buy Now</span></a>
								<?php endif; ?>
                                <div class="clear"></div>
                            </div>
                            
                            
                            
                            
                            <div class="clear">
                            </div>
                            <div class="footnote">
	                            
                                
                                <div id="ctl00_cphRoblox_Sold">
                                    (<span data-se="item-numbersold"><?php echo $row['Sales'] ?></span> 
                                    Sold)
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <span>
                                <span class="FavoriteStar" data-se="item-numberfavorited">
                                    531 
                                </span>
                                
                        </span>
                        
                        <div class="SocialMediaBar divider-top">
                               <p class="catchy-title ">
                                    <span>Share with your friends</span>
                                    <span class="info-tool-tip tooltip" title="Share ROBLOX with your friends and earn ROBUX every time they make a purchase.">&nbsp;</span>
                               </p>
                                <div id="social-media-bar-target"></div>
                            <script>
                                var ua = new gigya.socialize.UserAction();
                                ua.setLinkBack("https://devopstest1.aftwld.xyz/item.aspx?id=<?php echo htmlspecialchars($row['AssetID']); ?>");
                                ua.setTitle("ROBLOX: <?php echo htmlspecialchars($row['Name']); ?>");

                                var shareButtons = [
                                    {
                                        'provider': "Facebook",
                                        'enableCount': "true",
                                        'iconImgUp': "/rbxcdn_img/4799659a1367d6c6e235b5986cb9b6b9.png"
                                    },
                                    {
                                        'provider': "Twitter",
                                        'enableCount': "true",
                                        'iconImgUp': "/rbxcdn_img/d75e7a07fd4db793d79060cc5976cb29.png"
                                    },
                                    {
                                        'provider': "Googleplus",
                                        'enableCount': "true",
                                        'iconImgUp': "/rbxcdn_img/ee4b20b19bbaac5eb7c5e2c46a750c5c.png"
                                    }
                                ];

                                var params = {
                                    userAction: ua,
                                    shareButtons: shareButtons,
                                    containerID: "social-media-bar-target",
                                    deviceType: "auto",
                                    iconsOnly: "true",
                                    buttonWithCountTemplate: "<div class='social-button-template'><img src='$iconImg' class='social-button-icon-img' onclick='$onClick'><div class='social-button-counter'>$count</div></div>",
                                    buttonTemplate: "<div class='social-button-template'><img src='$iconImg' class='social-button-icon-img' onclick='$onClick'><div class='social-button-counter'>-</div></div>",
                                    showEmailButton: false
                                }
                                gigya.socialize.showShareBarUI(params);
                            </script>
                                <input class="social-media-bar-copy-to-clipboard text-box text-box-large" type="text" value="https://devopstest1.aftwld.xyz/item.aspx?id=<?php echo htmlspecialchars($row['AssetID']); ?>" readonly="true" />
                                <script>
                                    $(function () {
                                        $(".social-media-bar-copy-to-clipboard")
                                            .on("click", function () {
                                                $(".social-media-bar-copy-to-clipboard").select();
                                            });
                                    });
                                </script>
                        </div>
                        
                    </div>
                    <div class="clear"></div>
                    <div class="SocialMediaContainer">
                        
                    </div>
                </div>
                
                
                <div class="clear"></div>
            </div>
            
            <div class="PrivateSales divider-top invisible" >
                <h2>Private Sales</h2>
                <div id="UserSalesTab" >
                    
                    
                            <div class="empty">
                                Sorry, no one is privately selling this item at the moment.
                            </div>
                        
                    <div class="pgItemsForResale">
                        <span id="ctl00_cphRoblox_pgItemsForResale"><a disabled="disabled">First</a>&nbsp;<a disabled="disabled">Previous</a>&nbsp;<a disabled="disabled">Next</a>&nbsp;<a disabled="disabled">Last</a>&nbsp;</span>
                    </div>
                </div>
                
                
                <div class="clear"></div>
            </div>
            <div id="Tabs">
                <ul id="TabHeader" class="WhiteSquareTabsContainer">
                      
                            <li id="RecommendationsTabHeader" contentid="RecommendationsTab" class="SquareTabGray ItemTabs selected">
                                                <span><a id="RecommendationsLink" href="#RecommendationsTab">
                                                    Recommendations</a></span></li>
                      
                      <li id="CommentaryTabHeader" contentid="CommentaryTab" class="SquareTabGray ItemTabs ">
                                                <span><a id="CommentaryLink" href="#CommentaryTab">
                                                    Commentary</a></span></li>
                </ul>
                <div class="StandardPanelContainer">
                    <div id="RecommendationsTab" class="StandardPanelWhite TabContent selected">
                        

    <div class="AssetRecommenderContainer">
    <table id="ctl00_cphRoblox_AssetRec_dlAssets" cellspacing="0" align="Center" border="0" style="height:175px;width:800px;border-collapse:collapse;">
	<tr>
	<?php
	$assets = $pdo->query("SELECT * FROM assets WHERE AssetType IN (2,8,11,12,17,18,27,28,29,30,31) and isPrivate = 0 ORDER BY RAND() LIMIT 10")->fetchAll();
	$count = 0;
	foreach($assets as $asset){
		if($count == 0 || $count == 5): echo "<tr>"; endif;
	
		$id = htmlspecialchars($asset['AssetID']);
    $name = htmlspecialchars($asset['Name']);
    $creatorID = htmlspecialchars($asset['CreatorID']);
	
    $updated = htmlspecialchars(timeAgo($asset['Updated_At'])); // Format this as needed
		$stmt = $pdo->prepare('SELECT * FROM users where UserId = :uid');
		$stmt->execute(['uid' => $asset['CreatorID']]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
$created_by = $user['Username'];
		// Generate a URL-friendly version of the name
    $urlName = urlencode(str_replace(' ', '-', $name));
    $link = "/$urlName-item?id=$id";
	echo'<td>
            <div class="PortraitDiv" style="width: 140px;overflow: hidden;margin:auto;" visible="True" data-se="recommended-items-0">
                <div class="AssetThumbnail">
                    <a id="ctl00_cphRoblox_AssetRec_dlAssets_ctl00_AssetThumbnailHyperLink" class=" notranslate" title="'.$name.'" class=" notranslate" href="'.$link.'" style="display:inline-block;height:110px;width:110px;cursor:pointer;"><img src="/Thumbs/Asset.ashx?assetId='.$id.'&width=110&height=110" height="110" width="110" border="0" onerror="return Roblox.Controls.Image.OnError(this)" alt="'.$name.'" class="notranslate" /></a>
                </div>
                <div class="AssetDetails">
                    <div class="AssetName noTranslate">
                        <a id="ctl00_cphRoblox_AssetRec_dlAssets_ctl00_AssetNameHyperLinkPortrait" href="'.$link.'">'.$name.'</a>
                    </div>
                    <div class="AssetCreator">
                        <span class="stat-label">Creator:</span> <span class="Detail stat"><a id="ctl00_cphRoblox_AssetRec_dlAssets_ctl00_CreatorHyperLinkPortrait" class="notranslate" href="User.aspx?ID='.$creatorID.'">'.$created_by.'</a></span>
                    </div>
                </div>
            </div>
        </td>';
		if($count == 4 || $count == 9): echo "</tr>"; endif;
		$count++;
	}
	?>
	</tr>
</table>
    
</div>

<script type="text/javascript">
    $(function () {
        var itemNames = $('.PortraitDiv .AssetDetails .AssetName a');
        $.each(itemNames, function (index) {
            var elem = $(itemNames[index]);
            elem.html(fitStringToWidthSafe(elem.html(), 200));
        });
        var userNames = $('.PortraitDiv .AssetDetails .AssetCreator .Detail a');
        $.each(userNames, function (index) {
            var elem = $(userNames[index]);
            elem.html(fitStringToWidthSafe(elem.html(), 70));
        });
    });
</script>

                    </div>
                    <div id="CommentaryTab" class="StandardPanelWhite TabContent " >
                        <div id="ctl00_cphRoblox_CommentsPane_CommentsUpdatePanel">
	
        <div id="AjaxCommentsPaneData"></div>

        <div class="AjaxCommentsContainer">
		<?php if(isset($_COOKIE['_ROBLOSECURITY']) && getuserinfo($_COOKIE['_ROBLOSECURITY'])): $userinfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);?>
  <div id="ctl00_cphRoblox_TabbedInfo_CommentaryTab_CommentsPane_Div1" class="PostACommentContainer divider-bottom">
      <div class="Commenter">
        <div class="Avatar"> 
          <a id="ctl00_cphRoblox_TabbedInfo_CommentaryTab_CommentsPane_AvatarImage" class=" notranslate" title="<?=$userinfo['Username']?>" href="/User.aspx?ID=<?=$userinfo['UserId']?>" style="display:inline-block;height:100px;width:100px;cursor:pointer;">
		  <div style="position: relative; width: 100px; height: 100px;">
    <img src="/Thumbs/Avatar.ashx?userId=<?= $userinfo['UserId'] ?>" 
         height="100" width="100" border="0" 
         onerror="return Roblox.Controls.Image.OnError(this)" 
         alt="<?= $userinfo['Username'] ?>" class="notranslate">
		<img src="/Thumbs/BCOverlay.ashx?Username=<?=$userinfo['Username']?>" style="position: absolute; left: 0; bottom: 0;" alt="OBC"/>
</div>

          </a>
        </div>
      </div>
	  <div class="centered-error-container">
        <span id="commentPaneErrorMessage" class="status-error" style="display:none;"></span>
      </div>
      <div id="ctl00_cphRoblox_TabbedInfo_CommentaryTab_CommentsPane_PostAComment" class="PostAComment">
        <div class="CommentText">
          <textarea name="ctl00$cphRoblox$TabbedInfo$CommentaryTab$CommentsPane$NewCommentTextBox" id="ctl00_cphRoblox_TabbedInfo_CommentaryTab_CommentsPane_NewCommentTextBox" class="MultilineTextBox hint-text text" rows="5" style="margin-bottom: 0px">Write a comment!</textarea>
          <div class="Buttons">
            <div id="ctl00_cphRoblox_TabbedInfo_CommentaryTab_CommentsPane_BlueCommentBtn" class="BlueCommentBtn btn-neutral btn-small roblox-comment-button">Comment <span class="btn-text">Comment</span>
            </div>
            <p id="CharsRemaining" class="hint-text"></p>
            <div style="clear:both;"></div>
          </div>
        </div>
      </div>
      <div style="clear:both;"></div>
    </div>
<?php endif; ?>
            <div class="Comments" data-asset-id="<?php echo htmlspecialchars($row['AssetID']); ?>"></div>
            
            <div class="CommentsItemTemplate">
                    <div class="Comment text">
                        <div class="Commenter">
                            <div class="Avatar" data-user-id="%CommentAuthorID" data-image-size="small">
                            </div>
                        </div>
                        <div class="PostContainer">
                            <div class="Post">
                                <div class="Audit">
                                    <span class="ByLine footnote"><div class="UserOwnsAsset" title="User has this item" alt="User has this item" style="display:none;"></div>Posted %CommentCreated ago by <a href="/user.aspx?id=%CommentAuthorID">%CommentAuthor</a></span>
                                    <div class="ReportAbuse">
                                        <span class="AbuseButton">
                                            <a href="/abusereport/comment?id=%CommentID&amp;redirectUrl=%PageURL">Report Abuse</a>
                                        </span>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                                <div class="Content">
                                    %CommentContent
                                </div>
                                <div id="Actions" class="Actions" >
                                    <a data-comment-id="%CommentID" class="DeleteCommentButton">Delete Comment</a>
                                </div>
                            </div>
                            <div class="PostBottom"></div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                </div>
        </div>

</div>

<script type="text/javascript">
    Roblox.CommentsPane.Resources = {
        //<sl:translate>
        defaultMessage:         'Write a comment!',
        noCommentsFound:		'No comments found.',
        moreComments:			'More comments',
        sorrySomethingWentWrong:'Sorry, something went wrong.',
        charactersRemaining:	' characters remaining',
        emailVerifiedABTitle:	'Verify Your Email',
        emailVerifiedABMessage: "You must verify your email before you can comment. You can verify your email on the <a href='/my/account?confirmemail=1'>Account</a> page.",
        linksNotAllowedTitle:   'Links Not Allowed',
        linksNotAllowedMessage: 'Comments should be about the item or place on which you are commenting. Links are not permitted.',
        accept:					'Verify',
        decline:				'Cancel',
        tooManyCharacters:		'Too many characters!',
        tooManyNewlines:		'Too many newlines!'
        //</sl:translate>
       };

       Roblox.CommentsPane.Limits =
       [	{ limit: '10'
            , character: "\n"
            , message: Roblox.CommentsPane.Resources.tooManyNewlines
            }
       ,	{ limit: '200'
            , character: undefined
            , message: Roblox.CommentsPane.Resources.tooManyCharacters
            }
       ];

       Roblox.CommentsPane.FilterIsEnabled = true;
       Roblox.CommentsPane.FilterRegex = "(([a-zA-Z0-9-]+\\.[a-zA-Z]{2,4}[:\\#/\?]+)|([a-zA-Z0-9]\\.[a-zA-Z0-9-]+\\.[a-zA-Z]{2,4}))";
       Roblox.CommentsPane.FilterCleanExistingComments = false;

    Roblox.CommentsPane.initialize();
</script>

                    </div>
                </div>
            </div>
            
            <div id="FreeGames">
                <div class='SEOLinksContainer'><span><b>Other free games and items:</b></span><ul class='freegames'><?php
//				
$assets = $pdo->query("SELECT * FROM assets WHERE isPrivate = 0 ORDER BY RAND() LIMIT 6")->fetchAll();
	$count = 0;
	foreach($assets as $asset){
	
		$id = htmlspecialchars($asset['AssetID']);
    $name = htmlspecialchars($asset['Name']);
    $creatorID = htmlspecialchars($asset['CreatorID']);
	
    $updated = htmlspecialchars(timeAgo($asset['Updated_At'])); // Format this as needed
		$stmt = $pdo->prepare('SELECT * FROM users where UserId = :uid');
		$stmt->execute(['uid' => $asset['CreatorID']]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
$created_by = $user['Username'];
		// Generate a URL-friendly version of the name
    $urlName = urlencode(str_replace(' ', '-', $name));
    $link = "/$urlName-item?id=$id";
	echo"<li><a class='notranslate' href='$link' title='Free Games: $name'>$name</a></li>";
		$count++;
	}
	?>
				</ul></div></div>
        </div>
        <div class="Ads_WideSkyscraper">
            
<div style="width: 160px; " class="adp-gpt-container">
    <span id='3632323431393436' class="GPTAd skyscraper" data-js-adtype="gptAd">
    </span>
        <div class="ad-annotations " style="width: 160px">
            <span class="ad-identification">
                Advertisement
            </span>
                <a class="BadAdButton" href="/Ads/ReportAd.aspx" title="click to report an offensive ad">Report</a>
        </div>
    <script type="text/javascript">
        googletag.cmd.push(function () {
            if (typeof RobloxAds == "undefined" || typeof RobloxAds.showAdCallback == "undefined" || RobloxAds.showAdCallback("Roblox_Item_Right_160x600")) {
                googletag.display("3632323431393436");
            }
        });
    </script>
</div>
        </div>
        <div class="clear">
        </div>
    </div>
    
    
    

<div id="ItemPurchaseAjaxData"
        data-authenticateduser-isnull="<?php if(getuserinfo($_COOKIE['_ROBLOSECURITY'])){echo "False";}else{echo"True";} ?>"
        data-user-balance-robux="<?php if(getuserinfo($_COOKIE['_ROBLOSECURITY'])){echo getuserinfo($_COOKIE['_ROBLOSECURITY'])['Robux'];}else{echo"0";} ?>"
        data-user-balance-tickets="0"
        data-user-bc="0"
        data-continueshopping-url="/Catalog"
        data-imageurl="<?php echo "https://devopstest1.aftwld.xyz/Thumbs/Asset.ashx?assetId=".$row['AssetID']; ?>" 
        data-alerturl="/rbxcdn_img/cbb24e0c0f1fb97381a065bd1e056fcb.png"
        data-builderscluburl="/rbxcdn_img/ae345c0d59b00329758518edc104d573.png"></div>

    <div id="ProcessingView" style="display:none">
        <div class="ProcessingModalBody">
            <p style="margin:0px"><img src='/rbxcdn_img/ec4e85b0c4396cf753a06fade0a8d8af.gif' alt="Processing..." /></p>
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
            continueShoppingText : "Continue Shopping",
            customizeCharacterText : "Customize Character",
            orText : "or",
            rentText : "rent",
            accessText: "access to "
        }
    //</sl:translate>
    </script>


    

    

    <div id="ctl00_cphRoblox_CreateSetPanelDiv" class="createSetPanelPopup">
	
        
    
</div>
    
     

<div class="GenericModal modalPopup unifiedModal smallModal" style="display:none;">
    <div class="Title"></div>
    <div class="GenericModalBody">
        <div>
            <div class="ImageContainer roblox-item-image"  data-image-size="small" data-no-overlays data-no-click>
                <img class="GenericModalImage" alt="generic image" />
            </div>
            <div class="Message"></div>  
            <div style="clear:both"></div>
        </div>
        <div class="GenericModalButtonContainer">
            <a class="ImageButton btn-neutral btn-large roblox-ok" >OK<span class="btn-text">OK</span></a> 
        </div>  
    </div>
</div>

    

<div id="BCOnlyModal" class="modalPopup unifiedModal smallModal" style="display:none;">
 	<div style="margin:4px 0px;">
        <span>Builders Club Only</span>
    </div>
    <div class="simplemodal-close">
        <a class="ImageButton closeBtnCircle_20h" style="margin-left:400px;"></a>
    </div>
    <div class="unifiedModalContent" style="padding-top:5px; margin-bottom: 3px; margin-left: 3px; margin-right: 3px">
        <div class="ImageContainer" >
            <img class="GenericModalImage BCModalImage" alt="Builder's Club" src="/rbxcdn_img/ae345c0d59b00329758518edc104d573.png" />
            <div id="BCMessageDiv" class="BCMessage Message">
                You need  to buy this item!
            </div>
        </div>
        <div style="clear:both;"></div>
        <div style="clear:both;"></div>
        <div class="GenericModalButtonContainer" style="padding-bottom: 13px">
            <div style="text-align:center">
                <a id="BClink" href="/Upgrades/BuildersClubMemberships.aspx" class="btn-primary btn-large">Upgrade Now</a>
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
        $('#NULL').click(function () {
            showBCOnlyModal("BCOnlyModal");
            return false;
        });
    });
</script>
 

<div class="GenericModal modalPopup unifiedModal smallModal" style="display:none;">
    <div class="Title"></div>
    <div class="GenericModalBody">
        <div>
            <div class="ImageContainer roblox-item-image"  data-image-size="small" data-no-overlays data-no-click>
                <img class="GenericModalImage" alt="generic image" />
            </div>
            <div class="Message"></div>  
            <div style="clear:both"></div>
        </div>
        <div class="GenericModalButtonContainer">
            <a class="ImageButton btn-neutral btn-large roblox-ok" >OK<span class="btn-text">OK</span></a> 
        </div>  
    </div>
</div>


    <div id="InstallingPluginView" class="processing-view" style="display:none">
        <div class="ProcessingModalBody">
            <p style="margin:0px"><img src='/rbxcdn_img/ec4e85b0c4396cf753a06fade0a8d8af.gif' alt="Installing Plugin..." /></p>
            <p class="processing-text" style="margin:7px 0px">Installing Plugin...</p>
        </div>
    </div>
    <div id="UpdatingPluginView" class="processing-view" style="display:none">
        <div class="ProcessingModalBody">
            <p style="margin:0px"><img src='/rbxcdn_img/ec4e85b0c4396cf753a06fade0a8d8af.gif' alt="Updating Plugin..." /></p>
            <p class="processing-text" style="margin:7px 0px">Updating Plugin...</p>
        </div>
    </div>
    
    <script type="text/javascript">
    Roblox.Item = Roblox.Item || {};

    Roblox.Item.Resources = {
        //<sl:translate>
        DisableBadgeTitle: 'Disable Badge'
        , DisableBadgeMessage: 'Are you sure you want to disable this Badge?'
        , assetGrantedModalTitle: "This item is now yours"
        , assetGrantedModalMessage: "You just got this item courtesy of our sponsor."
        //</sl:translate>
    };
</script><script type="text/javascript">
    Roblox.Plugins = Roblox.Plugins || {};

    Roblox.Plugins.Resources = {
        //<sl:translate>
        errorTitle: "Error Installing Plugin",
        errorBody: "There was a problem installing this plugin. Please try again later.",
        successTitle: "Plugin Installed",
        successBody: " has been successfully installed! Please open a new window to begin using this plugin.",
        ok: "OK",
        reinstall: "Reinstall",
        updateErrorTitle: "Error Updating Plugin",
        updateErrorBody: "There was a problem updating this plugin. Please try again later.",
        updateSuccessTitle: "Plugin Update",
        updateSuccessBody: " has been successfully updated! Please open a new window for the changes to take effect.",
        updateText: "Update",
        //</sl:translate>
        alertImageUrl: '/images/Icons/img-alert.png'
    };
</script>

    <script type="text/javascript">
        Roblox.Item = Roblox.Item || {};
        
        Roblox.Item.ShowAssetGrantedModal = false;
        Roblox.Item.ForwardToUrl = "";
        

        $(function() {
            var commentsLoaded = false;

            //Tabs
            function SwitchTabs(nextTabElem) {
                $('.WhiteSquareTabsContainer .selected,  .TabContent.selected').removeClass('selected');
                nextTabElem.addClass('selected');
                $('#' + nextTabElem.attr('contentid')).addClass('selected');

                var label = $.trim(nextTabElem.attr('contentid'));
                if(label == "CommentaryTab" && !commentsLoaded) {
                    Roblox.CommentsPane.getComments(0);
                    commentsLoaded = true;
                    if(Roblox.SuperSafePrivacyMode != undefined) {
                        Roblox.SuperSafePrivacyMode.initModals();
                    }
                    return false;
                }
            }
            
            $('.WhiteSquareTabsContainer li').bind('click', function (event) {
                event.preventDefault();
                SwitchTabs($(this));
            });
        
            
            function confirmDelete() {
                Roblox.GenericConfirmation.open({
                    //<sl:translate>
                    titleText: "Delete Item",
                    bodyContent: "Are you sure you want to permanently DELETE this item from your inventory?",
                    //</sl:translate>
                    onAccept: function () {
                        javascript: __doPostBack('ctl00$cphRoblox$btnDelete', '');
                    },
                    acceptColor: Roblox.GenericConfirmation.blue,
                    //<sl:translate>
                    acceptText: "OK"
                    //</sl:translate>
                });
            }

            function confirmSubmit() {
                Roblox.GenericConfirmation.open({
                    //<sl:translate>
                    titleText: "Create New Badge Giver",
                    bodyContent: "This will add a new badge giver model to your inventory. Are you sure you want to do this?",
                    //</sl:translate>
                    onAccept: function () {
                        window.location.href = $('#ctl00_cphRoblox_btnSubmit').attr('href');
                    },
                    acceptColor: Roblox.GenericConfirmation.blue,
                    //<sl:translate>
                    acceptText: "OK"
                    //</sl:translate>
                });
            }

            $('#ctl00_cphRoblox_btnDelete').click(function() {
                confirmDelete();
                return false;
            });

            $('div.Ownership input').click(function() {
                confirmSubmit();
                return false;
            });

            modalProperties = { escClose: true, opacity: 80, overlayCss: { backgroundColor: "#000"} };
        
            // Code for Modal Popups and Plugin initialization
            
            $(".btn-disabled-primary").removeClass("Button").tipsy({ gravity: 's' }).attr("href", "javascript: return false;");
        });
        function ModalClose(popup) {
            $.modal.close('.' + popup);
        }
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
        <a href="http://corp.aftwld.xyz/advertise-on-roblox" class="roblox-interstitial">Advertise with Us</a>
        &nbsp;|&nbsp;
        <a href="http://corp.aftwld.xyz/press" class="roblox-interstitial">Press</a>
        &nbsp;|&nbsp;
        <a href="http://corp.aftwld.xyz/contact-us" class="roblox-interstitial">Contact Us</a>
        &nbsp;|&nbsp;
        <a href="http://corp.aftwld.xyz/about" class="roblox-interstitial">About Us</a>
        &nbsp;|&nbsp;
        <a href="http://blog.aftwld.xyz" class="roblox-interstitial">Blog</a>
        &nbsp;|&nbsp;
        <a href="http://corp.aftwld.xyz/careers" class="roblox-interstitial">Jobs</a>
        &nbsp;|&nbsp;
        <a href="http://corp.aftwld.xyz/parents" class="roblox-interstitial">Parents</a>
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
    ROBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of <a href="http://corp.aftwld.xyz/" ref="footer-smallabout" class="roblox-interstitial">ROBLOX Corporation</a>, ©2015. Patents pending.
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
    

    

<script type="text/javascript">
//<![CDATA[
Roblox.Controls.Image.ErrorUrl = "https://devopstest1.aftwld.xyz/Analytics/BadHtmlImage.ashx";//]]>
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

<script type='text/javascript' src='/rbxcdn_js/33dd14d8b93a9bb9a6fea4240114cbf7.js'></script>

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
            <img src="/rbxcdn_img/e998fb4c03e8c2e30792f2f3436e9416.gif" height="32" width="32" alt="Progress" />
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
                <img src="/rbxcdn_img/4bed93c91f909002b1f17f05c0ce13d1.gif" width="82" height="24" />
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
                <a href="https://en.help.aftwld.xyz/hc/en-us/articles/204473560" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>
<div id="ProtocolHandlerClickAlwaysAllowed" class="ph-clickalwaysallowed" style="display:none;">
    <p class="larger-font-size">
        <span class="rbx-icon-moreinfo"></span>
        Check <b>Remember my choice</b> and click <img src="/rbxcdn_img/7c8d7a39b4335931221857cca2b5430b.png" alt="Launch Application" />  in the dialog box above to join games faster in the future!
    </p>
</div>


<script type='text/javascript' src='/rbxcdn_js/e59cc9c921c25a5cd61d18f0a7fd5ac8.js'></script>
 
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
                    Roblox.EventStream.InitializeEventStream("null", "8", "http://public.ecs.aftwld.xyz/www/e.png");
                }
            });
        </script>
    
</body>                
</html>
