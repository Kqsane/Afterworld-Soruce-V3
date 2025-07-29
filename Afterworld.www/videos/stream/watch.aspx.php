<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/classes/twitchHelpers.php';

if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    header("Location: /newlogin");
    exit();
}

$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if (!$info) {
    logout();
    header("Location: /");
    exit();
}

$userId = $info['UserId'] ?? null;
$streamId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($streamId <= 0) {
    echo "Invalid stream ID.";
    exit();
}

$stmt = $pdo->prepare("SELECT s.*, u.Username AS streamerUsername FROM stream s JOIN users u ON s.streamer_id = u.UserId WHERE s.id = ?");
$stmt->execute([$streamId]);
$stream = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$stream) {
    echo "Stream not found.";
    exit();
}

$commentError = '';
$channelName = getTwitchDisplayName($stream['stream_url']);
$streamDescription = getTwitchStreamDescription($stream['stream_url'], $stream['description']);
$stream['live_title']   = getTwitchStreamTitle($stream['stream_url'], $stream['title']);
$stream['viewer_count'] = getLiveViewers($stream['stream_url']);
$stream['started_at']   = getTwitchStartTime($stream['stream_url']);
$stream['display_name'] = getTwitchDisplayName($stream['stream_url']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>AFTERWORLD Streams - <?= htmlspecialchars($stream['live_title']) ?></title>
<link rel='stylesheet' href='/CSS/Base/CSS/main___3cf17678f5d36ad2b9b72dd3439177be_m.css' />
<link rel='stylesheet' href='/videos/stream/streampages.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___b3be82695b3ef2061fcc71f48ca60b85_m.css' />
<link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css">
<link rel="stylesheet" href="/CSS/Base/CSS/page___53eeb36e90466af109423d4e236a59bd_m.css">
<script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>
   <script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>




    
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,600,700" rel="stylesheet" type="text/css">
    <script type='text/javascript' src='https://js.rbxcdn.com/fbb8598e3acc13fe8b4d8c1c5b676f2e.js.gzip'></script>
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>

<div id="navContent" class="nav-content nav-no-left" style="margin-left: 0px; width: 100%;">
    <div class="nav-content-inner">
        <div class="container-main">
            <script type="text/javascript">
                if (top.location !== self.location) {
                    top.location = self.location.href;
                }
            </script>
            <noscript>
                <div class="SystemAlert">
                    <div class="rbx-alert-info" role="alert">
                        Please enable Javascript to use all the features on this site.
                    </div>
                </div>
            </noscript>
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>

            <div class="container pt-5">
                <div class="content">
                    <div id="RepositionBody">
                        <div id="Body" style="width: 100%; max-width: 1200px; margin: 0 auto;">
<div class="watch-layout" style="display: flex; gap: 30px; align-items: flex-start; flex-wrap: wrap;">
    <div class="video-content" style="flex: 2; min-width: 640px;">
        <iframe style="width: 100%; max-width: 854px; aspect-ratio: 16 / 9; border-radius: 12px;" src="https://player.twitch.tv/?channel=<?= htmlspecialchars($channelName) ?>&parent=<?= $_SERVER['HTTP_HOST'] ?>">
    Your browser does not support streams.
</iframe>
        <h2 style="font-size: 22px; margin-top: 15px;">
            <?= htmlspecialchars($stream['live_title']) ?>
        </h2>
        <div class="meta" style="font-size: 14px; color: #555;">
            Uploaded by <a href="<?= htmlspecialchars($stream['stream_url']) ?>"><strong><?= htmlspecialchars($stream['display_name']) ?></strong></a> |
            <?= htmlspecialchars(getLiveViewers($stream['stream_url'])) ?> |
            Started at <?= htmlspecialchars(getTwitchStartTime($stream['stream_url'] ?? 'N/A')) ?>
        </div>
	 <?php if (!empty($streamDescription)): ?>
	 <p style="font-size: 16px; margin-top: 5px;">
        <?= htmlspecialchars($streamDescription) ?>
     </p>
	 <?php else: ?>
	 <p style="font-size: 16px; margin-top: 5px;">
        No description found.
     </p>
	 <?php endif; ?>
    </div>
    <div class="comments-section" style="flex: 1; min-width: 300px;">
        <h3 class="section-title">Chat</h3>
		<div id="chat-box" style="background: #f9f9f9; border: 1px solid #ccc; height: 450px; overflow-y: auto; padding: 10px; border-radius: 6px;">
		    <div class="chat" id="chat-messages">
			</div>
		</div>
        <?php if ($userinfo): ?>
            <form method="POST" class="comment-form">
                <?php if ($commentError): ?>
                    <p class="comment-error"><?= htmlspecialchars($commentError) ?></p>
                <?php endif; ?>
            </form>
        <?php else: ?>
            <p class="login-reminder">You must be logged in to post comments.</p>
        <?php endif; ?>

        <hr class="comment-divider">
		<form id="chat-form" method="POST" onsubmit="return false;">
		    <textarea name="message" id="chat-input" class="comment-input" placeholder="Type something..." required style="width: 250px;"></textarea>
		    <button id="chat-send" class="comment-submit">Send</button>
		</form>
    </div>
</div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

	
	
<script>
let chatPolling = null;
const streamId = <?= htmlspecialchars($stream['id']) ?>;

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function sendChatMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message) return;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/API/stream/postchat.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(`stream_id=${streamId}&message=${encodeURIComponent(message)}`);
    xhr.onload = function () {
        if (xhr.status === 200) {
            input.value = '';
        }
    };
}

function fetchChatMessages() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/API/stream/fetchchat.php?stream_id=" + streamId);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
            const chatBox = document.getElementById('chat-messages');
            chatBox.innerHTML = '';

            for (const msg of data) {
                let label = 'User';
                let color = '';

                if (msg.user_id == 1) { label = 'System'; color = '#ff05b0'; }
                else if (msg.user_id == 7) { label = 'Owner'; color = '#ffcb05'; }
                else if (msg.IsAdmin == 5) { label = 'Developer'; color = '#ff9d05'; }
                else if (msg.IsAdmin == 4) { label = 'Head Admin'; color = '#cd0000'; }
                else if (msg.IsAdmin == 3) { label = 'Admin'; color = '#ff0000'; }
                else if (msg.IsAdmin == 2) { label = 'Moderator'; color = '#055a99'; }
                else if (msg.IsAdmin == 1) { label = 'Asset Uploader'; color = '#05992d'; }
                else if (msg.streamer_id == msg.user_id) { label = 'Stream Owner'; color = '#ff055f'; }

                const p = document.createElement('p');
                p.innerHTML = `<span style="color:${color}; font-weight: bold;">[${label}] ${msg.username}:</span> `;

                if (msg.user_id == 1) {
                    p.innerHTML += escapeHtml(msg.message);
                } else {
                    p.innerHTML += escapeHtml(msg.message);
                }

                chatBox.appendChild(p);
            }

            const box = document.getElementById('chat-box');
            box.scrollTop = box.scrollHeight;
        }
    };
    xhr.send();
}

document.getElementById('chat-send').addEventListener('click', sendChatMessage);
chatPolling = setInterval(fetchChatMessages, 1000);
fetchChatMessages();
</script>


<script type="text/javascript" src="/js/emoji.js"></script>



</body>
</html>