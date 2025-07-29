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
?>
<link rel='stylesheet' href='/CSS/Base/CSS/leanbase___e457f3b30a24742f0b81021a7cb26907_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___0513ca5a00c9bdedff82380744b7def6_m.css' />
<link href="https://web.archive.org/web/20150602221635cs_/http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,600,700" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://web.archive.org/web/20150602221635cs_/http://www.roblox.com/CSS/Base/CSS/FetchCSS?path=page___49f048892333bead6ea13d9c9e10881d_m.css">


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
<div class="wrap no-gutter-ads" data-gutter-ads-enabled="false">
<!-- LEFT NAV MENU -->
    <div class="container-main   ">
            <script type="text/javascript">
                if (top.location != self.location) {
                    top.location = self.location.href;
                }
            </script>
        <noscript><div class="SystemAlert"><div class="rbx-alert-info" role="alert">Please enable Javascript to use all the features on this site.</div></div></noscript>
        <div class="content  ">

                                    

<li id="StudioSplashScreen" data-authentication-url="https://www.roblox.com/Login/Negotiate.ashx">
    <div class="header-bg-container">
        <div class="header-bg">
            <div class="header-container">
                <img class="studio-icon" src="http://images.rbxcdn.com/1a10c788eebfe2e15d618eb38d5133ad.png">
                <h3>WE ARE MAKING STUFF BETTER</h3>
                <p>
                    Our developers are working on the site to get the features and experience that you had back in your childhood!
                    <br>
                    This project started in Jan 2025 and is still in operation
                </p>
            </div>
        </div>
    </div>
    <div class="content-container">
        <ul class="blurbs-container">
            <li class="section gear">
                <p class="rbx-article-title">The Community</p>
                <p>Our community is real nice and not fucked up defo.</p>
                <img src="http://images.rbxcdn.com/b5a31363778e6d95036d684a71b3eebe.png">
            </li>
            <li class="section friends">
                <p class="rbx-article-title">Reach Millions of Players</p>
                <p>We bring players to you from our giant, loyal, and growing community in no time. No need to go find your players, we have millions right here.</p>
                <img src="http://images.rbxcdn.com/610d1e9e62b0fe55c2590b410d5ab3c9.png">
            </li>
            <li class="section money">
                <p class="rbx-article-title">Control Your Monetization</p>
                <p>You have full control of your in-game monetization. And we pay you for traffic to your game. Top developers are pacing to earn over $250,000 a year from our <a href="/web/20150602221635/http://www.roblox.com/develop/developer-exchange">DevEx</a> program.</p>
                <img src="http://images.rbxcdn.com/41cc0c5852485b140c12bc46a982e319.png">
            </li>
            <div style="clear:both;"></div>
        </ul>
        <div class="links-container wide">
            <div class="resources-title">Resources</div>
            <a class="rbx-btn-icon-community-sm" href="https://web.archive.org/web/20150602221635/http://developer.roblox.com/forum/index"><span class="rbx-icon-community"></span> <span>Dev Community</span></a>
            <a class="rbx-btn-icon-page-sm" href="https://web.archive.org/web/20150602221635/http://wiki.roblox.com/"><span class="rbx-icon-page"></span> <span>ROBLOX Wiki</span></a>
            <a class="rbx-btn-icon-play-sm" href="https://web.archive.org/web/20150602221635/http://youtu.be/SkMLcBPXHUA?list=PLuEQ5BB-Z1PLiLI_As4MC3SMS5Gwbwl40"><span class="rbx-icon-play"></span> <span>ROBLOX University</span></a>
            <div style="clear:both;"></div>
        </div>

        <div class="links-container narrow">
            <div class="resources-title">Resources</div>
            <a class="rbx-btn-icon-play-sm" href="https://web.archive.org/web/20150602221635/http://youtu.be/SkMLcBPXHUA?list=PLuEQ5BB-Z1PLiLI_As4MC3SMS5Gwbwl40"><span class="rbx-icon-play"></span> <span>ROBLOX University</span></a>
            <a class="rbx-btn-icon-page-sm" href="https://web.archive.org/web/20150602221635/http://wiki.roblox.com/"><span class="rbx-icon-page"></span> <span>ROBLOX Wiki</span></a>
            <a class="rbx-btn-icon-community-sm" href="https://web.archive.org/web/20150602221635/http://developer.roblox.com/forum/index"><span class="rbx-icon-community"></span> <span>Dev Community</span></a>
            <div style="clear:both;"></div>
        </div>
    </div>

            
        </li></div>
            </div> 


<div id="fb-root"></div>
<script>
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//web.archive.org/web/20150602221635/http://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0&appId=e58f2110adf82c2c00e6ae41c665510c";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<footer class="container-footer">
    <div class="footer">
        <ul class="row footer-links">
            <li class="col-xs-4 col-sm-2 footer-link"><a href="//web.archive.org/web/20150602221635/http://corp.roblox.com/" class="roblox-interstitial" target="_blank"><h2>About Us</h2></a></li>
            <li class="col-xs-4 col-sm-2 footer-link"><a href="//web.archive.org/web/20150602221635/http://corp.roblox.com/jobs" class="roblox-interstitial" target="_blank"><h2>Jobs</h2></a></li>
            <li class="col-xs-4 col-sm-2 footer-link"><a href="//web.archive.org/web/20150602221635/http://blog.roblox.com/" class="roblox-interstitial" target="_blank"><h2>Blog</h2></a></li>
            <li class="col-xs-4 col-sm-2 footer-link"><a href="/web/20150602221635/http://www.roblox.com/Info/Privacy.aspx" target="_blank"><h2>Privacy</h2></a></li>
            <li class="col-xs-4 col-sm-2 footer-link"><a href="//web.archive.org/web/20150602221635/http://corp.roblox.com/parents" class="roblox-interstitial" target="_blank"><h2>Parents</h2></a></li>
            <li class="col-xs-4 col-sm-2 footer-link"><a href="//web.archive.org/web/20150602221635/http://en.help.roblox.com/" class="roblox-interstitial" target="_blank"><h2>Help</h2></a></li>
        </ul>
        <p class="footer-note">
            ROBLOX, "Online Building Toy", characters, logos, names, and all related indicia are trademarks of <a target="_blank" href="//web.archive.org/web/20150602221635/http://corp.roblox.com/" class="rbx-link roblox-interstitial">ROBLOX Corporation</a>, ©2015. Patents pending. ROBLOX is not sponsored, authorized or endorsed by any producer of plastic building bricks, including The LEGO Group, MEGA Brands, and K'Nex, and no resemblance to the products of these companies is intended. Use of this site signifies your acceptance of the <a href="/web/20150602221635/http://www.roblox.com/info/terms-of-service" target="_blank" class="rbx-link">Terms and Conditions</a>.
        </p>
        
    </div>
</footer>


<script src="https://web.archive.org/web/20150602221635js_/https://apis.google.com/js/platform.js" gapi_processed="true"></script></div>