<?php
session_start();
$correct_password = 'aftwld_81927351_maintenance_password';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_password = $_POST['ctl00$cphRoblox$Textbox1'] ?? '';

    if (strtoupper($entered_password) === strtoupper($correct_password)) {
        $_SESSION['maintenance_bypass'] = true;
        header('Location: /');
        exit;
    } else {
        $error = "Incorrect password.";
    }
}
?>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true"><title>
	Afterworld - Site Offline
</title>
<link rel="stylesheet" href="https://web.archive.org/web/20150622041514cs_/http://www.roblox.com/CSS/Base/CSS/FetchCSS?path=main___3cf17678f5d36ad2b9b72dd3439177be_m.css">

<link rel="stylesheet" href="https://web.archive.org/web/20150622041514cs_/http://www.roblox.com/CSS/Base/CSS/FetchCSS?path=page___7e179435245cf7fea087ef729d75722c_m.css">
<link rel="icon" type="https://devopstest1.aftwld.xyz/favicon.ico"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="author" content="ROBLOX Corporation"><meta name="description" content="User-generated MMO gaming site for kids, teens, and adults. Players architect their own worlds. Builders create free online games that simulate the real world. Create and play amazing 3D games. An online gaming cloud and distributed physics engine."><meta name="keywords" content="free games, online games, building games, virtual worlds, free mmo, gaming cloud, physics engine"><meta name="robots" content="all"><meta name="viewport" content="width=device-width, initial-scale=1">
<!-- BEGIN WAYBACK TOOLBAR INSERT -->
<script>__wm.rw(0);</script>
</head>
<body class=" cookie-constraint-body">
<!-- END WAYBACK TOOLBAR INSERT -->
 <div id="roblox-linkify" data-enabled="true" data-regex="(https?\:\/\/)?(?:www\.)?([a-z0-9\-]{2,}\.)*((m|de|www|web|api|blog|wiki|help|corp|polls|bloxcon|developer)\.roblox\.com|robloxlabs\.com)((\/[A-Za-z0-9-+&amp;@#\/%?=~_|!:,.;]*)|(\b|\s))" data-regex-flags="gm"></div><script type="text/javascript" src="//web.archive.org/web/20150622041514js_/http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
<script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
<script type="text/javascript" src="//web.archive.org/web/20150622041514js_/http://ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>
<script type="text/javascript" src="//web.archive.org/web/20150622041514js_/http://ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js"></script>
<script type="text/javascript">window.Sys || document.write("<script type='text/javascript' src='/js/Microsoft/MicrosoftAjax.js'><\/script>")</script>
<script type="text/javascript" src="https://web.archive.org/web/20150622041514js_/http://js.rbxcdn.com/fb8d9efef14c71a37801e41741592a21.js"></script>
<script type="text/javascript">
    $(function () {
        Roblox.JSErrorTracker.initialize({ 'suppressConsoleError': true});
    });
</script>
<style type="text/css"> 
		.notification2 {
			width: auto;
			height: auto;
			padding: 12px 20px;
			margin: 0;
			background-color: #f68802;
			color: #fff;
		}

</style>
	<script type="text/javascript">
	    $(function () {
	        $('.tooltip').tipsy();
	        $('.tooltip-top').tipsy({ gravity: 's' });
	        $('.tooltip-right').tipsy({ gravity: 'w' });
	        $('.tooltip-left').tipsy({ gravity: 'e' });
	        $('.tooltip-bottom').tipsy({ gravity: 'n' });
	        $('.tooltip-right-html').tipsy({ gravity: 'w', html: true, delayOut: 1000 });
	        $('.tooltip-left-html').tipsy({ gravity: 'e', html: true, delayOut: 1000 });
	    });
    </script>


    <form name="aspnetForm" method="post" action="https://devopstest1.aftwld.xyz/offline" id="aspnetForm" class="nav-container no-gutter-ads">
<div>
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="QpCxJt8bKVtWnaz6lWeN8VnIPFEM/xIkZVHnBGAtc4zbmpc0IuXZS4geOdFbTQkmNC+wS6c/Tyy0k6g8WHbJzTt6BOOgCcEMVoWAGfIgyicgU7xxeW/v93diq9nTCPgJbfr3WSjUTMhw2zNlona1g57eHC6aWJmK9sRAwTHDaLricKd8aWHV719SrQC7+EOMinlgtTtxy/Zg6dX8r1HVuAP4h4U=">
</div>


<script src="https://web.archive.org/web/20150622041514js_/http://www.roblox.com/ScriptResource.axd?d=O3SMnVAjJL3yXSaeq76xuPg4uY15JZCrVx6SXs5m9DCjnKRSWXIj-__MSUSTdhXknsHX2jcp-8YBuWGBzuzQQV6SIq4eEv2IZTsCIyPCwIhKDSYV2zxgdnslFd_k0IC_S3bkxL2jbpIoN1scNzPSHWeKQqjooGiAIZmvFsfkvtSiyH5gUTVLXz9bcrKPtZevqQSCt_WzzN9esypwEqqRFT9uGy_F0zuxThZI0RPFYspNfPlF0" type="text/javascript"></script>


    
    
    
        <div id="navContent" class="nav-content">
        <div class="nav-content-inner">
<div class="content">
       <!-- <p class="notification2">
            <b>AFTERWORLD is currently on maintenance for an unknown period of time. We are working on new features to make your experience much more better.</b>
    </p> -->
    </div>
    <div id="Container">
        <div style="clear: both"></div>
        
        <div id="Body" class="simple-body">
            
    <div class="header">
        <img id="imgRobloxTeam" src="/grafik.png" alt="Afterworld Offline">
    </div>
    <div id="cookie-constraint-container" class="content">
        <p id="offlineTxt" class="notification">
            The site is currently on maintenance. <br> Check back later!
        </p>
        <div class="cookie-constraint-form">
            <input name="ctl00$cphRoblox$Textbox1" type="password" id="ctl00_cphRoblox_Textbox1" class="cookie-constraint-input">
            <input type="submit" name="ctl00$cphRoblox$Button1" value="A" id="ctl00_cphRoblox_Button1" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button2" value="F" id="ctl00_cphRoblox_Button2" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button3" value="T" id="ctl00_cphRoblox_Button3" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button4" value="E" id="ctl00_cphRoblox_Button4" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button5" value="R" id="ctl00_cphRoblox_Button5" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button6" value="W" id="ctl00_cphRoblox_Button6" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button7" value="O" id="ctl00_cphRoblox_Button6" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button8" value="R" id="ctl00_cphRoblox_Button6" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button9" value="L" id="ctl00_cphRoblox_Button6" class="cookie-constraint-btn">
            <input type="submit" name="ctl00$cphRoblox$Button10" value="D" id="ctl00_cphRoblox_Button6" class="cookie-constraint-btn">
        </div>
        <div id="offlineTxt" class="notification" style="display: inline;">
            <h1>Down time: </h1>
            <h1 id="demo" class="timer"></h1>
        </div>

    </div>

        </div>
        
    </div>
        </div></div>
      
    

<!-- <script type="text/javascript">
        window.window.setTimeout("window.location = 'https://aftwld.xyz/'", 30000);
    </script> -->

</form>
    <script type="text/javascript" src="https://web.archive.org/web/20150622041514js_/http://js.rbxcdn.com/78d2f9aab2c71251585c5fba37f90a3d.js"></script>
    
    <script>
var countDownDate = new Date("Jun 19, 2025 22:00:00").getTime();

var x = setInterval(function() {

  var now = new Date().getTime();

  var distance = countDownDate - now;

  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  document.getElementById("demo").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "UNKNOWN";
  }
}, 1000);
</script>




</body>
