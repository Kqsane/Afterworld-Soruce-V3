<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php'; 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <title>EaglerCraft - AFTERWORLD</title>
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" />
    <link rel='stylesheet' href='/CSS/Base/CSS/main___d7f658c4695ed776947e7d072c17ef0f_m.css' />
    <link rel='stylesheet' href='/CSS/Base/CSS/page___bf085a0aa25ce4df4c0be2fa1dc7e79a_m.css' />
    <style>
        #grid-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
        }
        #ErrorPage img {
            position: relative;
            z-index: 1;
        }
        iframe {
            width: 900px;
            height: 600px;
            margin-top: 20px;
            border: 2px solid #000;
        }
        .audio-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }
        .audio-controls input[type=range] {
            flex-grow: 1;
        }
    </style>
</head>
<body>
<canvas id="grid-background"></canvas>
<audio id="player" src="/yes.mp3"></audio>
<div class="audio-controls">
    <button onclick="player.play()">Play</button>
    <button onclick="player.pause()">Pause</button>
    <button onclick="player.currentTime = 0">Rewind</button>
    <input type="range" id="seekbar" value="0" max="100">
</div>
<script>
const player = document.getElementById('player');
const seekbar = document.getElementById('seekbar');

player.addEventListener('timeupdate', () => {
    seekbar.value = (player.currentTime / player.duration) * 100 || 0;
});

seekbar.addEventListener('input', () => {
    player.currentTime = (seekbar.value / 100) * player.duration;
});
</script>
<script>
setTimeout(() => {
    const canvas = document.getElementById('grid-background');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    let hexSize = 40;
    let hexHeight = Math.sqrt(3) * hexSize;
    let hexWidth = 2 * hexSize;
    let vertDist = hexHeight;
    let horizDist = 3 / 4 * hexWidth;
    let offset = 0;

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.strokeStyle = 'rgba(0,0,0,0.08)';
        offset += 0.3;

        for (let y = 0; y < canvas.height + hexHeight; y += vertDist) {
            for (let x = 0; x < canvas.width + hexWidth; x += horizDist) {
                let offsetY = (Math.floor(x / horizDist) % 2) * (hexHeight / 2);
                drawHex(x + Math.sin((y + offset) * 0.01) * 2, y + offsetY);
            }
        }
        requestAnimationFrame(draw);
    }

    function drawHex(x, y) {
        ctx.beginPath();
        for (let i = 0; i < 6; i++) {
            let angle = Math.PI / 3 * i;
            let px = x + hexSize * Math.cos(angle);
            let py = y + hexSize * Math.sin(angle);
            if (i === 0) ctx.moveTo(px, py);
            else ctx.lineTo(px, py);
        }
        ctx.closePath();
        ctx.stroke();
    }

    draw();
}, 7000);
</script>
<div class="nav-container no-gutter-ads">
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
<div id="navContent" class="nav-content ">
    <div class="nav-content-inner">
        <div id="MasterContainer">
            <div class="container-main">
                <div id="Body" class="simple-body">
                    <div id="ErrorPage">
                        <h1><span id="ctl00_cphRoblox_ErrorTitle">EaglerCraft</span></h1>
                        <img src="https://images.steamusercontent.com/ugc/772849313853007793/82A7CE28D00F6488CF9E65883B6D16E3C62A62E9/?imw=5000&imh=5000&ima=fit&impolicy=Letterbox&imcolor=%23000000&letterbox=false" id="ctl00_cphRoblox_ErrorImage" alt="Alert" class="ErrorAlert">
                        <iframe src="https://eaglercraft.com/mc/1.12.2/" allowfullscreen></iframe>
                        <div class="divideTitleAndBackButtons">&nbsp;</div>
                        <div class="CenterNavigationButtonsForFloat">
                           <a class="btn-small btn-neutral" title="Go to Previous Page Button" onclick="history.back();return false;" href="#">Go to Previous Page</a>
                           <a class="btn-neutral btn-small" title="Return Home" href="/">Return Home</a>
                           <div style="clear:both"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
