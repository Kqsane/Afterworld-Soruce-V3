<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php'; 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="en-us" />
    <meta name="author" content="ROBLOX Corporation" />
    <meta name="description" content=":)" />
    <meta name="keywords" content="free games, online games, building games" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>secret</title>
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" />
    <link rel="stylesheet" href="/CSS/Base/CSS/main___d7f658c4695ed776947e7d072c17ef0f_m.css" />
    <link rel="stylesheet" href="/CSS/Base/CSS/page___bf085a0aa25ce4df4c0be2fa1dc7e79a_m.css" />
    <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #fff;
            text-align: center;
        }

        #MasterContainer {
            display: inline-block;
            text-align: left;
        }

        #Body {
            width: 970px;
            margin: 0 auto;
            float: none !important;
        }

        #RedeemContainer {
            width: 100%;
            overflow: hidden;
            margin-top: 30px;
        }

        #Instructions, #CodeInput {
            float: left;
            width: 48%;
            padding: 10px;
            box-sizing: border-box;
        }

        #CodeInput {
            text-align: left;
        }

        .btn-primary {
            cursor: pointer;
        }

        #eggContainer {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            background: black;
            z-index: 999999;
        }

        #eggContainer iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
<div id="fb-root"></div>
<div class="nav-container no-gutter-ads">
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
    <div id="navContent" class="nav-content">
        <div class="nav-content-inner">
            <div id="MasterContainer">
                <div id="BodyWrapper">
                    <div id="RepositionBody">
                        <div id="Body">
<h1><span>Unlock Hidden AFTERWORLD Easter Eggs!</span></h1>
<div id="RedeemContainer">
    <div id="Instructions">
        <div class="header">How to Discover</div>
        <div class="listitem">Got a secret AFTERWORLD code from events or giveaways?</div>
        <div class="listitem">Type your code on the right to unlock exclusive surprises and Easter eggs.</div>
        <div class="footnote">Some codes are rare and only active for a limited time - keep your eyes peeled!</div>
    </div>
    <div id="CodeInput">
        <div class="header">Enter Secret Code:</div>
        <input id="pin" type="text" placeholder="Type your secret here..." />
        <span class="btn-primary btn-small" onclick="alert('Nice try! Easter eggs unlock automatically with secret combos.')">Redeem</span>
        <img id="busy" src="http://images.rbxcdn.com/21e504e643e6c21e0c90e5a1b03325f9.gif" alt="Loading" />
    </div>
</div>                            </div>
                            <div style="clear:both"></div>
                        </div>
                    </div>
                </div>
                <div id="Footer" class="footer-container">
                    <div class="FooterNav">
                        <a href="#">Privacy Policy</a>&nbsp;|&nbsp;
                        <a href="#">Advertise</a>&nbsp;|&nbsp;
                        <a href="#">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="eggContainer"></div>

<script>
    let typed = "";

    document.addEventListener("keydown", function (e) {
        typed += e.key.toLowerCase();
        if (typed.length > 10) typed = typed.slice(-10); // limit buffer

        if (typed.includes("xqc")) {
            const eggContainer = document.getElementById("eggContainer");
            eggContainer.style.display = "block";
            eggContainer.innerHTML = `
                <iframe src="https://www.youtube.com/embed/i9BHVae7DEg?autoplay=1&rel=0&showinfo=0" allow="autoplay; fullscreen"></iframe>
            `;
        }
    });
</script>
</body>
</html>
