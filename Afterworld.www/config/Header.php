<style>
div.testingSitePanel{margin:5px auto 8px;width:870px;text-align:center;border:3px #FFE066 solid;padding:10px 5px;background:white;}
</style>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
// written by meditext,
// yk, roblox uses a really confusing system for their pages
// and i'll be attempting to recreate the very same slop
$userId = $info['UserId'] ?? null;

// Assume $userId is already set (logged-in user ID)
if (isset($_COOKIE['_ROBLOSECURITY'])) {
    $userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    if (is_array($userInfo) && isset($userInfo['UserId'])) {
        $userId = (int)$userInfo['UserId'];
        $stmt = $pdo->prepare('SELECT isAdmin FROM users WHERE UserId = :userId LIMIT 1');
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $adminRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $isAdmin = isset($adminRow['isAdmin']) ? (int)$adminRow['isAdmin'] : 0;
    }
}

$searchResult = $searchResult ?? null;
    if ($searchResult && isset($searchResult['UserId'])) {
        $stmt = $pdo->prepare("SELECT * FROM bans WHERE UserId = :userid ORDER BY ReviewedAt DESC LIMIT 1");
        $stmt->bindParam(':userid', $searchResult['UserId']);
        $stmt->execute();
        $banInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    }

if (isset($_COOKIE['_ROBLOSECURITY'])) {
    $userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    $currentPage = $_SERVER['PHP_SELF'];
    if (
        !empty($userInfo) &&
        isset($userInfo['IsBanned']) &&
        $userInfo['IsBanned'] == 1 &&
        stripos($currentPage, 'NotApproved.aspx') === false
    ) {
        header("Location: /Membership/NotApproved.aspx");
        exit();
    }
}


if(!isset($_COOKIE["_ROBLOSECURITY"])){
	echo '
	<script type="text/javascript" src="/js/emoji.js"></script>
	<script type="text/javascript" src="/rbxcdn_js/9715e76471ffacd5f6d9c24a5ab101ad.js"></script>
	<div id="header" class="navbar-fixed-top rbx-header" role="navigation">
    <div class="container-fluid">
        <div class="rbx-navbar-header">
            <div data-behavior="nav-notification" class="rbx-nav-collapse" onselectstart="return false;">
                            </div>
            <div class="navbar-header">
                <a class="navbar-brand" href="/Home"><span class="logo"></span></a>
            </div>
        </div>
        <ul class="nav rbx-navbar hidden-xs hidden-sm col-md-4 col-lg-3">
            <li>
                <a href="/games">Games</a>
            </li>
            <li>
                <a href="/catalog">Catalog</a>
            </li>
            <li>
                <a href="/develop">Develop</a>
            </li>
            <li>
                <a class="buy-robux" href="/upgrades/robux">ROBUX</a>
            </li>
        </ul><!--rbx-navbar-->
        <div id="navbar-universal-search" class="navbar-left rbx-navbar-search col-xs-5 col-sm-6 col-md-3" data-behavior="univeral-search" role="search">
            <div class="input-group rbx-input-group">

                <input id="navbar-search-input" class="form-control rbx-input-field" type="text" placeholder="Search" maxlength="120">
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
                        <a id="header-login" class="rbx-navbar-login" data-behavior="login" data-toggle="popover" data-bind="popover-login" data-viewport="#header" data-original-title="" title="">Log In</a>
                    </li>
                    <div id="iFrameLogin" class="rbx-popover-content" data-toggle="popover-login" role="menu">
                        <iframe class="rbx-navbar-login-iframe" src="https://devopstest1.aftwld.xyz/Login/iFrameLogin.aspx?loginRedirect=False&amp;parentUrl=https%3a%2f%2f194.62.248.75:34533%2fNewLogin" scrolling="no" frameborder="0" width="320"></iframe>
                    </div>
                    <li>
                        <a class="rbx-navbar-signup" href="/Login/NewAge.aspx">Sign Up</a>
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
                <a href="/games">Games</a>
            </li>
            <li>
                <a href="/catalog">Catalog</a>
            </li>
            <li>
                <a href="/develop">Develop</a>
            </li>
            <li>
                <a class="buy-robux" href="/upgrades/robux">ROBUX</a>
            </li>
        </ul><!--rbx-navbar-->
    </div>
</div>';
}else{
	$cookie = $_COOKIE["_ROBLOSECURITY"];
	$info = getuserinfo($cookie);
	echo '
	<script type="text/javascript" src="/js/emoji.js"></script>
	<script type="text/javascript" src="/rbxcdn_js/9715e76471ffacd5f6d9c24a5ab101ad.js"></script>
	<div id="header" class="navbar-fixed-top rbx-header" role="navigation">
    <div class="container-fluid">
        <div class="rbx-navbar-header">
            <div data-behavior="nav-notification" class="rbx-nav-collapse" onselectstart="return false;">
                    <span class="rbx-icon-nav-menu"></span>
                
                
                <div class="rbx-nav-notification hide rbx-font-xs"
                       title="0">
                    
                    
                </div>
                
                
            </div>
            <div class="navbar-header">
                <a class="navbar-brand" href="/"><span class="logo"></span></a>
            </div>
        </div>
        <ul class="nav rbx-navbar hidden-xs hidden-sm col-md-4 col-lg-3">
            <li>
                <a href="/games">Games</a>
            </li>
            <li>
                <a href="/catalog">Catalog</a>
            </li>
            <li>
                <a href="/develop">Develop</a>
            </li>
            <li>
                <a class="buy-robux" href="/upgrades/robux?ctx=nav">ROBUX</a>
            </li>
        </ul><!--rbx-navbar-->
        <div id="navbar-universal-search" class="navbar-left rbx-navbar-search col-xs-5 col-sm-6 col-md-3" data-behavior="univeral-search" role="search">
            <div class="input-group rbx-input-group">


                <input id="navbar-search-input" class="form-control rbx-input-field" type="text" placeholder="Search" maxlength="120" />
                <div class="input-group-btn rbx-input-group-btn">
                    <button id="navbar-search-btn" class="rbx-input-addon-btn" type="submit">
                        <span class="rbx-icon-nav-search"></span>
                    </button>
                </div>
            </div>
            <ul data-toggle="dropdown-menu" class="rbx-dropdown-menu" role="menu">
                <li class="rbx-navbar-search-option selected" data-searchurl="/search/users?keyword=">
                    <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in People</span>
                </li>
                        <li class="rbx-navbar-search-option" data-searchurl="/games/?Keyword=">
                            <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in Games</span>
                        </li>
                        <li class="rbx-navbar-search-option" data-searchurl="/catalog/browse.aspx?CatalogContext=1&amp;amp;Keyword=">
                            <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in Catalog</span>
                        </li>
                        <li class="rbx-navbar-search-option" data-searchurl="/groups/search.aspx?val=">
                            <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in Groups</span>
                        </li>
                        <li class="rbx-navbar-search-option" data-searchurl="/develop/library?CatalogContext=2&amp;amp;Category=6&amp;amp;Keyword=">
                            <span class="rbx-navbar-search-text">Search <span class="rbx-navbar-search-string"></span> in Library</span>
                        </li>
            </ul>
        </div><!--rbx-navbar-search-->
        <div class="navbar-right rbx-navbar-right col-xs-4 col-sm-3">


<ul class="nav navbar-right rbx-navbar-icon-group">
    <li>
        <a class="rbx-menu-item" data-toggle="popover" data-bind="popover-setting" data-viewport="#header">
            <span class="rbx-icon-nav-settings" id="nav-settings"></span>
            <span class="rbx-font-xs nav-setting-highlight hidden"></span>
        </a>
<div class="rbx-popover-content" data-toggle="popover-setting">
            <ul class="rbx-dropdown-menu" role="menu">
                <li>
                    <a class="rbx-menu-item" href="/my/account">
                        Settings
                </li>
                <li><a href="/Help/Builderman.aspx" target="_blank">Help</a></li>
                <li><a data-behavior="logout" data-bind="/authentication/logout">Logout</a></li>
            </ul>
        </div>
    </li>
    <li>
        <a class="rbx-menu-item" data-toggle="popover" data-bind="popover-tix" data-viewport="#header">
            <span class="rbx-icon-nav-tix" id="nav-tix"></span>
            <span class="rbx-text-navbar-right" id="nav-tix-amount">'.formatRobux($info['Tix']).'</span>
        </a>
        <div class="rbx-popover-content" data-toggle="popover-tix">
            <ul class="rbx-dropdown-menu" role="menu">
                <li><a href="/My/Money.aspx#/#Summary_tab" id="nav-tix-balance">'.$info['Tix'].' Tickets</a></li>
                <li><a href="/my/money.aspx?tab=TradeCurrency">Trade Currency</a></li>
            </ul>
        </div>
    </li>
    <li>
        <a id="nav-robux-icon" class="rbx-menu-item" data-toggle="popover" data-bind="popover-robux">
            <span class="rbx-icon-nav-robux" id="nav-robux"></span>
            <span class="rbx-text-navbar-right" id="nav-robux-amount">'.formatRobux($info['Robux']).'</span>
        </a>
        <div class="rbx-popover-content" data-toggle="popover-robux">
            <ul class="rbx-dropdown-menu" role="menu">
                <li><a href="/My/Money.aspx#/#Summary_tab" id="nav-robux-balance">'.number_format($info['Robux']).' ROBUX</a></li>
                <li><a href="/upgrades/robux?ctx=navpopover">Buy ROBUX</a></li>
            </ul>
        </div>
    </li>
    <li class="rbx-navbar-right-search" data-toggle="toggle-search">
        <a class="rbx-menu-icon">
            <span class="rbx-icon-nav-search-white"></span>
        </a>
    </li>
</ul>        </div><!-- navbar right-->
        <ul class="nav rbx-navbar hidden-md hidden-lg col-xs-12">
            <li>
                <a href="/games">Games</a>
            </li>
            <li>
                <a href="/catalog">Catalog</a>
            </li>
            <li>
                <a href="/develop">Develop</a>
            </li>
            <li>
                <a class="buy-robux" href="/upgrades/robux?ctx=nav">ROBUX</a>
            </li>
        </ul><!--rbx-navbar-->
    </div>
</div>


<!-- LEFT NAV MENU -->
    <div id="navigation" class="rbx-left-col" data-behavior="left-col">
        <ul>
            <li class="rbx-lead">
                <a href="/user.aspx">'.$info["Username"].'</a>
            </li>
            <li class="rbx-divider"></li>
        </ul>
        <div class="rbx-scrollbar" data-toggle="scrollbar" onselectstart="return false;">
            <ul>
                <li><a href="/home" id="nav-home"><span class="rbx-icon-nav-home"></span><span>Home</span></a></li>
                <li><a href="/User.aspx" id="nav-profile"><span class="rbx-icon-nav-profile"></span><span>Profile</span></a></li>
                <li>
                    <a href="/my/messages/#!/inbox" id="nav-message" data-count="0">
                        <span class="rbx-icon-nav-message"></span><span>Messages</span>
                        <span class="rbx-highlight" title="0"></span>
                    </a>
                </li>
                <li>
                    <a href="/friends.aspx" id="nav-friends" data-count="0">
                        <span class="rbx-icon-nav-friends"></span><span>Friends</span>
                        <span class="rbx-highlight" title="0"></span>
                    </a>
                </li>
                <li>
                    <a href="/My/Character.aspx" id="nav-character">
                        <span class="rbx-icon-nav-charactercustomizer"></span><span>Character</span>
                    </a>
                </li>
                <li>
                    <a href="/My/Stuff.aspx" id="nav-inventory">
                        <span class="rbx-icon-nav-inventory"></span><span>Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="/My/Money.aspx#/#TradeItems_tab" id="nav-trade">
                        <span class="rbx-icon-nav-trade"></span><span>Trade</span>
                    </a>
                </li>
                <li>
                   <a href="/My/Groups.aspx" id="nav-group">
                        <span class="rbx-icon-nav-group"></span><span>Groups</span>
                    </a>
                </li>
                <li>
                    <a href="/Forum/default.aspx" id="nav-forum">
                        <span class="rbx-icon-nav-forum"></span><span>Forum</span>
                    </a>
                </li>
                <li>
                    <a href="https://blog.aftwld.xyz/" id="nav-blog">
                        <span class="rbx-icon-nav-blog"></span><span>Blog</span>
                    </a>
                </li>
				<li>
                    <a href="/videos/default.aspx" id="nav-videos">
                        <span class="rbx-icon-nav-blog"></span><span>Videos</span>
                    </a>
                </li>
				<li>
                    <a href="/Admi/" id="nav-videos">
                        <span class="rbx-icon-nav-friends"></span>
						<span>Admin</span>
                    </a>
                </li>

                <li class="rbx-upgrade-now">
                    <a href="/Upgrades/BuildersClubMemberships.aspx?ctx=leftnav" class="rbx-btn-secondary-xs" id="upgrade-now-button">Upgrade Now</a>
                </li>
                <li class="rbx-text-notes rbx-font-bold rbx-font-sm">
                        Events
                    </li>
            </ul>
        </div>
    </div>
';
}

