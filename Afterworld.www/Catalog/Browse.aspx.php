<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
static $categories = [
	0 => "Featured",
	1 => "All",
	2 => "Collectibles",
	3 => "Clothing",
	4 => "Body Parts",
	5 => "Gears"
];
static $subcategories = [
	1 => "",
	2 => "Collectibles",
	3 => "Clothing",
	4 => "Body Parts",
	5 => "Gears",
	9 => "Hats",
	10 => "Faces",
	11 => "Packages",
	12 => "Shirts",
	13 => "T-Shirts",
	14 => "Pants",
	15 => "Heads",
];
static $Gears = [
	1 => "Melee Weapon",
	2 => "Ranged Weapon",
	3 => "Explosive",
	4 => "Power Up",
	5 => "Navigation Enhancer",
	6 => "Musical Instrument",
	7 => "Social Item",
	8 => "Building Tool",
	9 => "Personal Transport"
];
$subcategory_assetType = [
	1 => [2,8,11,12,17,18,27,28,29,30,31],
    2 => [19],
    3 => [11, 12, 2],
    4 => [17, 27, 28, 29, 30, 31],
    5 => [19],
    9 => [8],
    10 => [18],
    11 => [32],
    12 => [11],
    13 => [2],
    14 => [12],
    15 => [17]
];
function getAssetTypesForSubcategory($subcategoryId) {
    global $subcategory_assetType, $asset_types;
    if (!isset($subcategory_assetType[$subcategoryId])) {
        return null;
    }
    $type_ids = $subcategory_assetType[$subcategoryId];
    return array_intersect_key($asset_types, array_flip($type_ids));
}
function returnVal($array, $value){
	return $array[$value] ?? null;
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
$context = isset($_GET['CatalogContext']) ? intval($_GET['CatalogContext']) : null;
$subcategory = isset($_GET['Subcategory']) ? intval($_GET['Subcategory']) : 1;
$category = isset($_GET['Category']) ? intval($_GET['Category']) : 0;
$sorttype = isset($_GET['SortType']) ? intval($_GET['SortType']) : 0;
$page = isset($_GET['PageNumber']) ? intval($_GET['PageNumber']) : 1;
$legendexpanded = ($_GET['LegendExpanded'] !== "false");
$keyword = isset($_GET['Keyword']) ? $_GET['Keyword'] : null;
?>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<!-- MachineID: WEB4 -->
<head id="ctl00_Head1"><meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true" /><title>
	Avatar Items, Virtual Avatars, Virtual Goods
</title>
<link rel='stylesheet' href='/CSS/Base/CSS/main___993aee760ede694623860ea25c58778a_m.css' />

<link rel='stylesheet' href='/CSS/Base/CSS/page___4cc4d60cfbc37ae3dcc62d001949d60c_m.css' />
<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="Content-Language" content="en-us" /><meta name="author" content="ROBLOX Corporation" /><meta id="ctl00_metadescription" name="description" content="User-generated MMO gaming site for kids, teens, and adults. Players architect their own worlds. Builders create free online games that simulate the real world. Create and play amazing 3D games. An online gaming cloud and distributed physics engine." /><meta id="ctl00_metakeywords" name="keywords" content="free games, online games, building games, virtual worlds, free mmo, gaming cloud, physics engine" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        
            _gaq.push(['b._setCustomVar', 1, 'Visitor', 'Anonymous', 2]);
            _gaq.push(['b._trackPageview']);    
        
        
        

		_gaq.push(['c._setAccount', 'UA-26810151-2']);
		_gaq.push(['c._setDomainName', 'roblox.com']);

		(function() {
			var ga = document.createElement('script');
			ga.type = 'text/javascript';
			ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(ga, s);
		})();

	</script>
<div id="roblox-linkify" data-enabled="true" data-regex="(https?\:\/\/)?(?:www\.)?([a-z0-9\-]{2,}\.)*((m|de|www|web|api|blog|wiki|help|corp|polls|bloxcon|developer)\.roblox\.com|robloxlabs\.com)((\/[A-Za-z0-9-+&amp;@#\/%?=~_|!:,.;]*)|(\b|\s))" data-regex-flags="gm"></div><script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>
<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js'></script>
<script type='text/javascript'>window.Sys || document.write("<script type='text/javascript' src='/js/Microsoft/MicrosoftAjax.js'><\/script>")</script>
<script type='text/javascript' src='/rbxcdn_js/38f35adc88a79cc297f7a01a4bdcac00.js'></script>
<script type='text/javascript'>Roblox.config.externalResources = [];Roblox.config.paths['Pages.Catalog'] = '/rbxcdn_js/a2ff3787d1fd8d3c2492b5f5c5ec70b6.js';Roblox.config.paths['Pages.CatalogShared'] = '/rbxcdn_js/4eb48eec34ca711d5a7b08a4291ac753.js';Roblox.config.paths['Pages.Messages'] = '/rbxcdn_js/e8cbac58ab4f0d8d4c707700c9f97630.js';Roblox.config.paths['Resources.Messages'] = '/rbxcdn_js/fb9cb43a34372a004b06425a1c69c9c4.js';Roblox.config.paths['Widgets.AvatarImage'] = '/rbxcdn_js/bbaeb48f3312bad4626e00c90746ffc0.js';Roblox.config.paths['Widgets.DropdownMenu'] = '/rbxcdn_js/7b436bae917789c0b84f40fdebd25d97.js';Roblox.config.paths['Widgets.GroupImage'] = '/rbxcdn_js/33d82b98045d49ec5a1f635d14cc7010.js';Roblox.config.paths['Widgets.HierarchicalDropdown'] = '/rbxcdn_js/fbb86cf0752d23f389f983419d3085b4.js';Roblox.config.paths['Widgets.ItemImage'] = '/rbxcdn_js/838ec9c8067ba6fd6793a8bdbdb48a5c.js';Roblox.config.paths['Widgets.PlaceImage'] = '/rbxcdn_js/f2697119678d0851cfaa6c2270a727ed.js';Roblox.config.paths['Widgets.SurveyModal'] = '/rbxcdn_js/d6e979598c460090eafb6d38231159f6.js';</script><script type="text/javascript">
    $(function () {
        Roblox.JSErrorTracker.initialize({ 'suppressConsoleError': true});
    });
</script><script type='text/javascript' src='/rbxcdn_js/0a831cc3b392802c34a4c6644d804957.js'></script>

    <script type="text/javascript">
function Roblox_Catalog_Top_728x90_RTP(estimate){rtp['/1015347/Roblox_Catalog_Top_728x90'] = rp_valuation.estimate;}
var rtp = rtp || {};
oz_api="valuation";oz_site="9874/18868";oz_zone="58960";oz_ad_slot_size="728x90";oz_callback=Roblox_Catalog_Top_728x90_RTP;
</script><script type="text/javascript" src="http://tap-cdn.rubiconproject.com/partner/scripts/rubicon/dorothy.js?pc=9874/18868"></script><script>

    googletag.cmd.push(function() {
        Roblox = Roblox || {};
        Roblox.AdsHelper = Roblox.AdsHelper || {};
        Roblox.AdsHelper.slots = [];
        Roblox.AdsHelper.slots = Roblox.AdsHelper.slots || []; Roblox.AdsHelper.slots.push({slot:googletag.defineSlot("/1015347/Roblox_Catalog_Top_728x90", [728, 90], "3336353337393239").addService(googletag.pubads()), id: "3336353337393239", path: "/1015347/Roblox_Catalog_Top_728x90"});

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
                                                googletag.pubads().enableSingleRequest();
        googletag.pubads().collapseEmptyDivs();
        googletag.enableServices();
    });
    </script>  
</head>
<body class="">

    <script type="text/javascript">Roblox.XsrfToken.setToken('BV8Ijy5Vbh26');</script>
 
    <script type="text/javascript">
        if (top.location != self.location) {
            top.location = self.location.href;
        }
    </script>
  
<style type="text/css">
    
</style>
<form name="aspnetForm" method="post" action="/catalog/browse.aspx?CatalogContext=1&amp;Subcategory=9&amp;SortType=0&amp;SortAggregation=3&amp;SortCurrency=0&amp;LegendExpanded=true&amp;Category=0" id="aspnetForm" class="nav-container no-gutter-ads">
<div>
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="ap1bhLWLl7PN+sp+uGa87hUi6FwAFj9yymG+A3m5eFKV0ML/3PE8OdOdza9XgjGCzvSKj2ou0H4pOIigaAU75oq+S3WgmqpBFkl4LwOatG+RgGMk9YZ2f1cKunZfN+9Qt02WGZ1R6CJ5qpLaDTtp7V3YenqyZx6SJXYpEMB8YoOvPbTLUkCTqlsXBVUIuUKAqZMqiCQU+OJZDdNGlCZWAB4mhnWS3eFaCKGwYEs6yAx4UbzuCdxMZPF2To6xh8T7Uq0Uolig6dELy8Qtl870NvifbahLzJpOzqH7Ug95IfdTvXfNljQ1E1LdA+1YwbekWV6tuYje91yiYsRy9PqIN7kvP8PJxo0EEVxR0eKStJXwrBgmUkhaPPJAdjv4y1IQPdr8WIiLqQ+XDkRi7iY131arv5I=" />
</div>


<script src="/ScriptResource.axd?d=NL2IQOXspKls8GK6thvORmOFYD95dCEO-roTaV1fubyGbc0LK-jD_ZMDWzrNWOnW0EoMeCIXyXKBGZ_v84JcsoZnSBCWj1Jq_ERFCY1w38BB1-SkN7FHnbxD5w18DPXdMQens8tMd22k_ZlKkS4iVqNP-DZZ0fiOf-xaMadxOcecDF19XZlJyaWry0oYN92z8CsAqzDAysg1RZBBOiLlUaZ-5RGbzI5MvrgVEqOTSLI_etHvZhivDY2OaAoZpjd8zY_0GnztbvIflrzjz11Xv36yNhu_rVRdAacIETqEZV2zfraTm0g7lCplLVKlWRGJq659cK90HtjQV_XOlgwiCbn5PzOeG7puGD8QbuMlzAWEt9pww19Pada7Wrp-jiS4IVw00c-7yEjPd1vZ7Go9fqZu0LfkzsBehdbKVZRC_4o9wIAM0" type="text/javascript"></script>
<div>

	<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="C6D44BB4" />
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

		
    <div id="AdvertisingLeaderboard"  class = "top-ad-728">
        
<div style="width: 728px; " class="adp-gpt-container">
    <span id='3336353337393239' class="GPTAd banner" data-js-adtype="gptAd">
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
            if (typeof RobloxAds == "undefined" || typeof RobloxAds.showAdCallback == "undefined" || RobloxAds.showAdCallback("Roblox_Catalog_Top_728x90")) {
                googletag.display("3336353337393239");
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
                    
    

<style type="text/css">
    #Body {
        padding: 5px;
    }
</style>
<div id="catalog" data-empty-search-enabled="true" style="font-size: 12px;">
<?php
$passedamount = 0;

$types = getAssetTypesForSubcategory($subcategory);
$typeIds = array_keys($types); // Get the asset type IDs only

$passedamount = 0;
$types = getAssetTypesForSubcategory($subcategory);
$typeIds = array_keys($types); // Just the IDs
$assets = [];
$maxCount = 0;

if (!empty($typeIds)) {
    $placeholders = [];
    $params = [];

    foreach ($typeIds as $index => $id) {
        $key = ":type$index";
        $placeholders[] = $key;
        $params[$key] = (int)$id; // Ensure integer safety
    }

    $sql = 'SELECT * FROM assets WHERE AssetType IN (' . implode(', ', $placeholders) . ') AND isPrivate != 1';

    if (!empty($keyword) && trim($keyword) !== "") {
        $sql .= ' AND Name LIKE :keyword';
        $params[':keyword'] = '%' . $keyword . '%';
    }elseif(returnVal($categories, $category) === "Featured"){
		$sql .= ' ORDER BY AssetID, Sales DESC LIMIT 42'; // featured
	}else{
		$sql .= ' ORDER BY RAND() LIMIT 42';
	}
	$offset = ($page - 1) * 42;
	if($page >= 2){
	$sql .= " OFFSET $offset";
	}
	//echo $sql;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $assets = $stmt->fetchAll();

    // Count query (more efficient using COUNT(*))
    $countSql = 'SELECT COUNT(*) FROM assets WHERE AssetType IN (' . implode(', ', $placeholders) . ') AND isPrivate != 1';

    if (!empty($keyword) && trim($keyword) !== "") {
        $countSql .= ' AND Name LIKE :keyword';
    }
	$count = count($assets) * $page;
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $maxCount = $countStmt->fetchColumn();
	
}
?>
<div class="header" style="height:60px;">
        <div style="float:left;">
            <h1><a href="/catalog/" id="CatalogLink">Catalog</a></h1>
        </div>
    <div class="CatalogSearchBar">
        <input id="keywordTextbox" name="name" type="text" class="translate text-box text-box-small" <?php if(!empty($keyword)){ echo'value="'.htmlspecialchars($keyword).'"';} ?> />
        <div style="height:23px;border:1px solid #a7a7a7;padding:2px 2px 0px 2px;margin-right:6px;float:left;position:relative">
            <!--[if IE7]>
                <div style="height:19px;width:131px;position:absolute;top:2px;left:2px;border:1px solid white"></div>
                <div style="height:19px;width:15px;position:absolute;top:2px;right:2px;border:1px solid #aaa"></div>
            <![endif]-->
            <select id="categoriesForKeyword" style="">
                    <option value="1">All Categories</option>
                    <option value="0"selected=selected>Featured</option>
                    <option value="2">Collectibles</option>
                    <option value="3">Clothing</option>
                    <option value="4">Body Parts</option>
                    <option value="5">Gear</option>
            </select>
        </div>
        <a id="submitSearchButton" href="#" class="btn-control btn-control-large top-level">Search</a>
    </div>
</div>


    <div class="left-nav-menu divider-right">



    <div class="browseDropdownHeader"></div>

<div id="dropdown" class="browsedropdownbrowsedropdown roblox-hierarchicaldropdown">
    <ul id="dropdownUl" class="clearfix">

            <li class="subcategories" data-delay="never">
                <a href="#category=featured" class="assetTypeFilter" data-category="0">Featured</a>
                <ul class="slideOut" style="top:-1px;">
                    <li class="slideHeader"><span>Featured Types</span></li>
                        <li><a href="#category=featured" class="assetTypeFilter" data-types="0" data-category="0">All Featured Items</a></li>    
                        <li><a href="#category=featured" class="assetTypeFilter" data-types="9" data-category="0">Featured Hats</a></li>    
                        <li><a href="#category=featured" class="assetTypeFilter" data-types="5" data-category="0">Featured Gear</a></li>    
                        <li><a href="#category=featured" class="assetTypeFilter" data-types="10" data-category="0">Featured Faces</a></li>    
                        <li><a href="#category=featured" class="assetTypeFilter" data-types="11" data-category="0">Featured Packages</a></li>    
                </ul>
            </li>
        
            <li class="subcategories"><a href="#category=collectibles" class="assetTypeFilter collectiblesLink" data-category="2">Collectibles</a>
                <ul class="slideOut" style="top:-32px;">
                    <li class="slideHeader"><span>Collectible Types</span></li>
                        <li><a href="#category=collectibles" class="assetTypeFilter" data-types="2" data-category="2">All Collectibles</a></li>    
                        <li><a href="#category=collectibles" class="assetTypeFilter" data-types="10" data-category="2">Collectible Faces</a></li>    
                        <li><a href="#category=collectibles" class="assetTypeFilter" data-types="9" data-category="2">Collectible Hats</a></li>    
                        <li><a href="#category=collectibles" class="assetTypeFilter" data-types="5" data-category="2">Collectible Gear</a></li>    
                </ul>
            </li>

            <li class="slideHeader DropdownDivider divider-bottom" data-delay="ignore"></li>

            <li data-delay="always">
                <a href="#category=all" class="assetTypeFilter" data-category="1">All Categories</a>
            </li>
        
            <li class="subcategories">
                <a href="#category=clothing" class="assetTypeFilter" data-category="3">Clothing</a>
                <ul class="slideOut" style="top:-97px;">
                    <li class="slideHeader"><span>Clothing Types</span></li>
                        <li><a href="#" class="assetTypeFilter" data-types="3" data-category="3">All Clothing</a></li>    
                        <li><a href="#" class="assetTypeFilter" data-types="9" data-category="3">Hats</a></li>    
                        <li><a href="#" class="assetTypeFilter" data-types="12" data-category="3">Shirts</a></li>    
                        <li><a href="#" class="assetTypeFilter" data-types="13" data-category="3">T-Shirts</a></li>    
                        <li><a href="#" class="assetTypeFilter" data-types="14" data-category="3">Pants</a></li>    
                        <li><a href="#" class="assetTypeFilter" data-types="11" data-category="3">Packages</a></li>    
                </ul>
            </li>
        
            <li class="subcategories"><a href="#category=bodyparts" class="assetTypeFilter" data-category="4">Body Parts</a>
                <ul class="slideOut" style="top:-128px;">
                    <li class="slideHeader"><span>Body Part Types</span></li>
                        <li><a href="#category=bodyparts" class="assetTypeFilter" data-types="4" data-category="4">All Body Parts</a></li>    
                        <li><a href="#category=bodyparts" class="assetTypeFilter" data-types="15" data-category="4">Heads</a></li>    
                        <li><a href="#category=bodyparts" class="assetTypeFilter" data-types="10" data-category="4">Faces</a></li>    
                        <li><a href="#category=bodyparts" class="assetTypeFilter" data-types="11" data-category="4">Packages</a></li>    
                </ul>
            </li>
        
            <li class="subcategories"><a href="#category=gear" class="assetTypeFilter" data-category="5">Gear</a>
                <ul class="slideOut" style="top:-159px; width:auto;" style="border-right:0px;">
                    <div>
                        <li class="slideHeader"><span>Gear Categories</span></li>
                            <li><a href="#geartype=All Gear" class="gearFilter" data-category="5" data-types="All">All Gear</a></li>
                            <li><a href="#geartype=Melee Weapon" class="gearFilter" data-category="5" data-types="1">Melee Weapon</a></li>
                            <li><a href="#geartype=Ranged Weapon" class="gearFilter" data-category="5" data-types="2">Ranged Weapon</a></li>
                            <li><a href="#geartype=Explosive" class="gearFilter" data-category="5" data-types="3">Explosive</a></li>
                            <li><a href="#geartype=Power Up" class="gearFilter" data-category="5" data-types="4">Power Up</a></li>
                            <li><a href="#geartype=Navigation Enhancer" class="gearFilter" data-category="5" data-types="5">Navigation Enhancer</a></li>
                            <li><a href="#geartype=Musical Instrument" class="gearFilter" data-category="5" data-types="6">Musical Instrument</a></li>
                    </div>
                    <div id="gearSecondColumn">
                            <li><a href="#geartype=Social Item" class="gearFilter" data-category="5" data-types="7">Social Item</a></li>
                            <li><a href="#geartype=Building Tool" class="gearFilter" data-category="5" data-types="8">Building Tool</a></li>
                            <li><a href="#geartype=Personal Transport" class="gearFilter" data-category="5" data-types="9">Personal Transport</a></li>

                    </div>
                </ul>
            </li>
        
                            </ul>
</div>
            <div style="padding-top:20px;">
                <h2>Filters</h2>
            </div>
            <div style="margin-left:5px">

                <div class="filter-title">Featured Type</div>
                <ul>
                        <li><a href="#subcategory=All Featured Items" class="assetTypeFilter" data-types="0">All Featured Items</a></li>
                        <li><a href="#subcategory=Featured Hats" class="assetTypeFilter selected" data-types="9">Featured Hats</a></li>
                        <li><a href="#subcategory=Featured Gear" class="assetTypeFilter" data-types="5">Featured Gear</a></li>
                        <li><a href="#subcategory=Featured Faces" class="assetTypeFilter" data-types="10">Featured Faces</a></li>
                        <li><a href="#subcategory=Featured Packages" class="assetTypeFilter" data-types="11">Featured Packages</a></li>
                </ul>
        
        
                <div class="filter-title">Genre</div>
                <ul id="genresUl">
                    <li><a href="#" id="AllGenresLink" onclick="Roblox.Pages.Catalog.ClearGenres();" class="selected">All Genres</a></li>
                        <li>
                            <input type="checkbox" id="genre-13" class="genreFilter" data-genreId="13" />
                            <label for="genre-13">Building</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-5" class="genreFilter" data-genreId="5" />
                            <label for="genre-5">Horror</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-1" class="genreFilter" data-genreId="1" />
                            <label for="genre-1">Town and City</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-11" class="genreFilter" data-genreId="11" />
                            <label for="genre-11">Military</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-9" class="genreFilter" data-genreId="9" />
                            <label for="genre-9">Comedy</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-2" class="genreFilter" data-genreId="2" />
                            <label for="genre-2">Medieval</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-7" class="genreFilter" data-genreId="7" />
                            <label for="genre-7">Adventure</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-3" class="genreFilter" data-genreId="3" />
                            <label for="genre-3">Sci-Fi</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-6" class="genreFilter" data-genreId="6" />
                            <label for="genre-6">Naval</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-14" class="genreFilter" data-genreId="14" />
                            <label for="genre-14">FPS</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-15" class="genreFilter" data-genreId="15" />
                            <label for="genre-15">RPG</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-8" class="genreFilter" data-genreId="8" />
                            <label for="genre-8">Sports</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-4" class="genreFilter" data-genreId="4" />
                            <label for="genre-4">Fighting</label>
                        </li>
                        <li>
                            <input type="checkbox" id="genre-10" class="genreFilter" data-genreId="10" />
                            <label for="genre-10">Western</label>
                        </li>
                </ul>

        
        
                <div class="filter-title">Currency / Price</div>
                <ul class="separatorForLegend" style="border:0">
                    <li><a href="#price=0" class="priceFilter selected" data-currencytype="0">All Currency</a></li>
                    <li><a href="#price=1" class="priceFilter" data-currencytype="1">Robux</a></li>
                    <li><a href="#price=2" class="priceFilter" data-currencytype="2">Tickets</a></li>
                                    <li class="NotForSale">
                        <input type="checkbox" id="includeNotForSaleCheckbox" value="true" />
                        <label for="includeNotForSaleCheckbox">Show unavailable items</label>
                    </li>
                </ul>
            </div>
<div id="legend" class="divider-top">
    <div class="header expanded" id="legendheader">
        <h3>Legend</h3>
    </div>
    <div id="legendcontent" style="overflow: hidden; ">
        <img src="http://images.rbxcdn.com/4fc3a98692c7ea4d17207f1630885f68.png" style="margin-left: -13px" />
        <div class="legendText"><b>Builders Club Only</b><br/>
        Only purchasable by Builders Club members.</div>

        <img src="http://images.rbxcdn.com/793dc1fd7562307165231ca2b960b19a.png" style="margin-left: -13px" />
        <div class="legendText"><b>Limited Items</b><br/>
        Owners of these discontinued items can re-sell them to other users at any price.</div>
        
        <img src="http://images.rbxcdn.com/d649b9c54a08dcfa76131d123e7d8acc.png" style="margin-left: -13px" />
        <div class="legendText"><b>Limited Unique Items</b><br/>
        A limited supply originally sold by ROBLOX. Each unit is labeled with a serial number. Once sold out, owners can re-sell them to other users.
        </div>
    </div>
</div>                               </div>

    <div class="right-content divider-left">

<a href="#breadcrumbs=category" class="breadCrumbFilter bolded" data-filter="category"><?php echo returnVal($categories, $category);?></a>
 &#187; <a href="#breadcrumbs=subcategory" class="breadCrumbFilter selected" data-filter="subcategory"><?php if(empty($keyword)){ echo returnVal($categories, $category) ." ". returnVal($subcategories, $subcategory); }else{ echo '"'.htmlspecialchars($keyword).'"';}?></a>
        <div id="secondRow">
            <div style="float:left;padding-top:5px">

                    <span>Showing <span class="notranslate"><?php echo $page; ?></span> - <span class="notranslate"><?php echo"$count</span> of <span class=\"notranslate\">$maxCount</span> results"?></span>
            </div>

            <div id="SortOptions">
                Sort by: 
                    <select id="SortMain">
                            <option value="0" selected=selected >Relevance</option>
                            <option value="1">Most Favorited</option>
                            <option value="2">Bestselling</option>
                            <option value="3">Recently updated</option>
                            <option value="5">Price (High to Low)</option>
                            <option value="4">Price (Low to High)</option>
                    </select>
                    <select id="SortAggregation" style=display:none >
                            <option value="3" selected=selected >All Time</option>
                            <option value="1">Past week</option>
                            <option value="0">Past day</option>
                    </select>
                    <select id="SortCurrency" style=display:none >
                            <option value="0" selected=selected >in Robux</option>
                            <option value="1">in Tickets</option>
                    </select>
            </div>

            <div style="clear:both"></div>
        </div>

<?php
if(count($assets) <= 1){
	$noresult = "<p>No Results found";
	if(!empty($keyword)){
		$x = htmlspecialchars($keyword);
		$noresult .= " for \"<b>{$x}</b>\"";
	}
	$noresult .= ".</p>";
	echo $noresult;
}
foreach ($assets as $asset) {
    $id = htmlspecialchars($asset['AssetID']);
    $name = htmlspecialchars($asset['Name']);
    $price = htmlspecialchars($asset['RobuxPrice']);
    $creatorID = htmlspecialchars($asset['CreatorID']);
    $sales = number_format($asset['Sales']);
    $favorited = number_format($asset['Favorites']);
	
    $updated = htmlspecialchars(timeAgo($asset['Updated_At'])); // Format this as needed
		$stmt = $pdo->prepare('SELECT * FROM users where UserId = :uid');
		$stmt->execute(['uid' => $asset['CreatorID']]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
$created_by = $user['Username'];
		// Generate a URL-friendly version of the name
    $urlName = urlencode(str_replace(' ', '-', $name));
    $link = "/$urlName-item?id=$id";
$price_tag = "<div class='robux-price'><span class='robux notranslate'>$price</span></div>";
if($asset['RobuxPrice'] == 0 && $asset['TixPrice'] == 0){
	$price_tag = '<b>Free</b>';
}elseif($asset['TixPrice'] > 0 && $asset['RobuxPrice'] == 0){
	$price_tag = "<div class='ticket-price'><span class='tickets notranslate'>{$asset['TixPrice']}</span></div>";
}
        // Small catalog items
        echo "
        <div class='CatalogItemOuter SmallOuter'>
        <div class='SmallCatalogItemView SmallView'>
        <div class='CatalogItemInner SmallInner'>    
            <div class='roblox-item-image image-small' data-item-id='$id' data-image-size='small'>
			<div class=\"item-image-wrapper\">
                <a href=\"$link\">
                    <img title=\"$name\" alt=\"$name\" class=\"original-image \" src=\"/Thumbs/Asset.ashx?assetId=$id&width=100&height=100\">
                                                                            </a>
            </div></div>
            
            <div id='textDisplay'>
            <div class='CatalogItemName notranslate'><a class='name notranslate' href='$link' title='$name'>$name</a></div>
                    $price_tag
            </div>
            <div class='CatalogHoverContent'>
                <div><span class='CatalogItemInfoLabel'>Creator:</span> <span class='HoverInfo notranslate'><a href='/User.aspx?ID=$creatorID'>$created_by</a></span></div>
                <div><span class='CatalogItemInfoLabel'>Updated:</span> <span class='HoverInfo'>$updated</span></div>
                <div><span class='CatalogItemInfoLabel'>Sales:</span> <span class='HoverInfo notranslate'>$sales</span></div>
                <div><span class='CatalogItemInfoLabel'>Favorited:</span> <span class='HoverInfo'>$favorited times</span></div>
            </div>
        </div>
        </div>    
        </div>";
}
?>

<?php if(ceil($maxCount/42) >= 2): ?>
            <div class="PagingContainerDivTop">
                <span class="pager previous" id="pagingprevious"></span>
                <span class="page text">
                    Page <input class="Paging_Input translate" type="text" value="<?php echo $page; ?>"/> of <?php echo ceil($maxCount/42); ?>
                    <span class="paging_pagenums_container"></span>
                </span>
                <span class="pager next" id="pagingnext"></span>
            </div>
        <?php endif; ?>
    
    </div>
    <div style="clear:both;padding-top:20px"></div>
</div>

<div id="AddToGearInstructionsPanel" class="PurchaseModal">
    <div id="simplemodal-close" class="simplemodal-close">
        <a></a>
    </div>
    <div class="titleBar" style="text-align: center">
        Add Gear to Your Game
    </div>
    <div class="PurchaseModalBody">
        <div class="PurchaseModalMessage">
            <div class="PromoteModalErrorMessage errorStatusBar"></div>
            <div class="PurchaseModalMessageText">
                <span>
                    <img src="/images/img-addgear-screenshot.jpg"/>
                </span>
                <br/>
                To add gear to your game, find an item in the catalog and click the "Add to Game" button. The item will automatically be allowed in game, and you'll receive a commission on every copy sold from your game page. (You can only add gear that's for sale.)
            </div>
        </div>
        <div class="PurchaseModalButtonContainer">
            <div class="ImageButton btn-blue-ok-sharp simplemodal-close" ></div>
        </div>
        <div class="PurchaseModalFooter footnote"></div>
    </div>
</div>

<script type="text/javascript">
    Roblox.require('Pages.Catalog', function (catalog) {
        catalog.init({"Subcategory":<?php echo $subcategory; ?>,"Category":<?php echo $category; ?>,"CurrencyType":0,"SortType":<?php echo $sorttype; ?>,"SortAggregation":3,"SortCurrency":0,"Gears":null,"Genres":null,"CatalogContext":1,"Keyword":<?php echo !empty($keyword) ? $keyword : "null"; ?>,"PageNumber":<?php echo $page; ?>,"CreatorID":0,"PxMin":0,"PxMax":0,"IncludeNotForSale":false,"LegendExpanded":true,"ResultsPerPage":42}, <?php echo ceil($maxCount/42); ?>);
    });
    
    $('.Paging_Input').val('<?php echo $page; /* WTF ROBLOX??? */ ?>'); /* what?! party.js overwrites paging_input on any pageback */
    
    $(function () {
        if (window.location.search.indexOf('&showInstructions=true') > -1) {
            var modalProperties = { escClose: true, opacity: 80, overlayCss: { backgroundColor: "#000"} };
            $('#AddToGearInstructionsPanel').modal(modalProperties);
        }
    });

        Roblox.CatalogValues = Roblox.CatalogValues || {};
        Roblox.CatalogValues.CatalogContext = 1;
</script>

<!--[if IE]>
    <script type="text/javascript">
        $(function () {
            $('.CatalogItemInner').live('mouseenter', function () {
                $(this).parents('.SmallCatalogItemView').css('z-index', '6');
            });
            $('.CatalogItemInner').live('mouseleave', function () {
                $(this).parents('.SmallCatalogItemView').css('z-index', '1');
            });
        });
    </script>
<![endif]-->


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

<script type='text/javascript' src='/rbxcdn_js/e3a913253f6cb8d64dc84ed62f8d02a7.js'></script>

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


<div id="PlaceLauncherStatusPanel" style="display:none;width:300px"
     data-is-protocol-handler-launch-enabled="False"
     data-is-user-logged-in="False"
     data-protocol-name-for-client="roblox-player"
     data-protocol-name-for-studio="roblox-studio">
    <div class="modalPopup blueAndWhite PlaceLauncherModal" style="min-height: 160px">
        <div id="Spinner" class="Spinner" style="margin:0 1em 1em 0; padding:20px 0;">
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
                <a href="https://en.help.roblox.com/hc/en-us/articles/203313020-ROBLOX-Launcher-Plugin" class="rbx-link" target="_blank">Click here for help</a>
            </div>

        </div>
    </div>
</div>


<script type='text/javascript' src='/rbxcdn_js/4801536fe25bc7963df710cfc4b85f25.js'></script>
 
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

</body>                
</html>
