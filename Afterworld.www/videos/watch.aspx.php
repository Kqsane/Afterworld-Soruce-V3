<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

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

$userinfo = $info;
$userId = $userinfo['UserId'] ?? null;

$commentError = '';
$videoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($videoId <= 0) {
    echo "Invalid video ID.";
    exit;
}

if ($userId) {
    $stmt = $pdo->prepare('SELECT Membership, isAdmin FROM users WHERE UserId = :userId LIMIT 1');
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $adminLevel = (int)($row['isAdmin'] ?? 0);
    $isAdmin = in_array($adminLevel, [2, 3, 4, 5]) ? 1 : 0;
} else {
    $isAdmin = 0;
}

$stmt = $pdo->prepare("
    SELECT UserId, Username, JoinData, isAdmin, Membership 
    FROM users 
    WHERE UserId = ?
");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$viewCookie = 'viewed_' . $videoId;
if (!isset($_COOKIE[$viewCookie])) {
    $stmt = $pdo->prepare("UPDATE videos SET views = COALESCE(views, 0) + 1 WHERE id = ?");
    $stmt->execute([$videoId]);
    setcookie($viewCookie, '1', time() + 3600);
}

$stmt = $pdo->prepare("
    SELECT v.*, u.UserId AS uploader_id, u.Username 
    FROM videos v 
    JOIN users u ON u.UserId = v.uploaderId 
    WHERE v.id = ?
");
$stmt->execute([$videoId]);
$video = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$video) {
    echo "Video not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postComment'])) {
    $comment = trim($_POST['comment'] ?? '');
    if (!$userId) {
        $commentError = "You must be logged in to comment.";
    } elseif (empty($comment)) {
        $commentError = "Comment cannot be empty.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO videocomments (video_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$videoId, $userId, $comment]);
        header("Location: watch.aspx?id=" . $videoId);
        exit;
    }
}

$stmt = $pdo->prepare("
    SELECT c.*, u.Username 
    FROM videocomments c 
    JOIN users u ON c.user_id = u.UserId 
    WHERE c.video_id = ? 
    ORDER BY c.created_at DESC
");
$stmt->execute([$videoId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_end_flush();
$stmt = $pdo->prepare("
    SELECT v.id, v.title, v.views, v.uploadedAt, v.uploaderId, u.Username
    FROM videos v
    JOIN users u ON u.UserId = v.uploaderId
    WHERE v.id != ?
    ORDER BY v.uploadedAt DESC
    LIMIT 6
");
$stmt->execute([$videoId]);
$recommendedVideos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$verifiedUsers = ['ROBLOX', 'Chloe', 'xQc'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>AFTERWORLD Videos - <?= htmlspecialchars($video['title']) ?></title>
    
    <style>
    /* Comment and the page styling reminder dont touch please */
    @import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Source Sans Pro', sans-serif;
    }

    .video-hub-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 16px;
        margin: 20px 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        font-family: "Segoe UI", sans-serif;
    }

    .hub-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .channel-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .channel-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .channel-meta {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .channel-name {
        font-weight: bold;
        color: #333;
        text-decoration: none;
    }

    .subscribe-btn, .share-btn {
        background-color: #e62117;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        cursor: pointer;
    }

    .share-btn {
        background-color: #ccc;
        color: #222;
    }

    .subscribe-btn:hover {
        background-color: #cc1f14;
    }
    .share-btn:hover {
        background-color: #aaa;
    }

    .video-title {
        display: block;
        font-size: 18px;
        font-weight: 600;
        margin: 8px 0;
        color: #111;
        text-decoration: none;
    }

    .video-meta-box {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }

    .video-description {
        font-size: 14px;
        color: #333;
        line-height: 1.4;
    }

    .see-more {
        color: #065fd4;
        text-decoration: none;
        margin-left: 6px;
        cursor: pointer;
    }

    .watch-layout {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        gap: 30px;
    }

    .video-content {
        flex: 2;
        min-width: 300px;
        max-width: 854px;
    }

    .recommendations-section {
        flex: 1;
        min-width: 280px;
        max-width: 400px;
        max-height: 600px;
        overflow-y: auto;
        padding-right: 8px;
    }

    @media (max-width: 900px) {
        .watch-layout {
            flex-direction: column;
        }

        .recommendations-section {
            max-height: unset;
            padding-right: 0;
        }
    }

    video {
        width: 100%;
        max-width: 100%;
        aspect-ratio: 16 / 9;
        border-radius: 12px;
    }

    /* COMMENTS SECTION, DONT TOUCH PLZ VERY FRAGILE */

    .comments-section {
        margin-top: 30px;
        font-family: 'Source Sans Pro', sans-serif;
        width: 100%;
    }

    .section-title {
        font-size: 22px;
        margin-bottom: 15px;
        border-bottom: 2px solid #ccc;
        padding-bottom: 5px;
    }

    .comment-form {
        margin-bottom: 20px;
    }

    .comment-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        resize: vertical;
        font-family: 'Source Sans Pro', sans-serif;
    }

    .comment-submit {
        margin-top: 10px;
        background-color: #065fd4;
        color: white;
        border: none;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
    }

    .comment-submit:hover {
        background-color: #044a9c;
    }

    .comment-error {
        color: red;
        margin-bottom: 10px;
    }

    .login-reminder {
        color: #777;
        margin-bottom: 15px;
    }

    .comment-divider {
        margin: 25px 0;
        border: none;
        border-top: 1px solid #ccc;
    }

    .comment-card {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 10px;
        border: 1px solid #ddd;
        gap: 10px;
    }

    .comment-avatar img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-body {
        flex: 1;
    }

    .comment-header {
        margin-bottom: 8px;
        font-weight: bold;
    }

    .comment-user {
        font-weight: bold;
        color: #333;
        text-decoration: none;
        margin-right: 10px;
    }

    .comment-date {
        font-size: 12px;
        color: #999;
    }

    .comment-text {
        font-size: 14px;
        color: #222;
        line-height: 1.5;
        white-space: pre-wrap;
        margin-top: 4px;
    }

    .no-comments {
        color: #555;
        font-style: italic;
    }
    </style>

    <link rel='stylesheet' href='/CSS/Base/CSS/main___3cf17678f5d36ad2b9b72dd3439177be_m.css' />
    <link rel='stylesheet' href='/videos/forumpages.css' />
    <link rel='stylesheet' href='/videos/default.css' />
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
                    <a id="ctl00_cphRoblox_ThreadView1_ctl00_NewThreadLinkTop" class="btn-control btn-control-medium verified-email-act" href="/videos/default.aspx">
                &lt; Go Back
            </a>
<div id="Body" style="width: 100%; max-width: 1200px; margin: 0 auto;">

  <div class="watch-layout">
    <div class="video-content">
      <video controls>
        <source src="/uploads/videos/<?= htmlspecialchars($video['filename']) ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>

      <div class="video-hub-card" style="background: #fff; border: 1px solid #ddd; border-radius: 12px; padding: 16px; margin: 40px 0 0 0;">
        <div class="hub-header">
          <div class="channel-info">
            <img src="/Thumbs/Avatar.ashx?userId=<?= (int)$video['uploader_id'] ?>" alt="Avatar" class="channel-avatar">
            <div class="channel-meta">
              <a href="/User.aspx?id=<?= (int)$video['uploader_id'] ?>" class="channel-name">
                <?= htmlspecialchars($video['Username']) ?> 
              </a>
            </div>
          </div>
          <input class="social-media-bar-copy-to-clipboard text-box text-box-large" type="text" value="https://devopstest1.aftwld.xyz/videos/watch.aspx?id=<?= (int)$video['id'] ?>" readonly="true">
        </div>

        <a class="video-title">
          <?= htmlspecialchars($video['title']) ?>
        </a>

        <div class="video-meta-box">
          <?= (int)$video['views'] ?> views • <?= date("F j, Y", strtotime($video['uploadedAt'])) ?>
        </div>

        <div class="video-description">
          <?php
            $desc = trim(strip_tags($video['description'] ?? ''));
            $wordCount = str_word_count($desc);
            if ($wordCount > 10) {
              $shortDesc = implode(' ', array_slice(explode(' ', $desc), 0, 10));
              echo htmlspecialchars($shortDesc) . '... <a href="#" class="see-more">see more</a>';
            } elseif ($desc !== '') {
              echo htmlspecialchars($desc);
            } else {
              echo "No description found.";
            }
          ?>
        </div>
      </div>

      <div class="comments-section">
        <h3 class="section-title">Comments</h3>

        <?php if ($userinfo): ?>
          <form method="POST" class="comment-form">
            <?php if ($commentError): ?>
              <p class="comment-error"><?= htmlspecialchars($commentError) ?></p>
            <?php endif; ?>
            <textarea name="comment" class="comment-input" rows="4" placeholder="Write your comment..." required></textarea>
            <button type="submit" name="postComment" class="comment-submit">Post Comment</button>
          </form>
        <?php else: ?>
          <p class="login-reminder">You must be logged in to post comments.</p>
        <?php endif; ?>

        <hr class="comment-divider">

        <?php if (count($comments) > 0): ?>
          <?php foreach ($comments as $comment): ?>
            <div class="comment-card">
              <div class="comment-avatar">
                <a href="/User.aspx?id=<?= (int)$comment['user_id'] ?>">
                  <img src="/Thumbs/Avatar.ashx?userId=<?= (int)$comment['user_id'] ?>" alt="Avatar">
                </a>
              </div>
              <div class="comment-body">
                <div class="comment-header">
                  <a href="/User.aspx?id=<?= (int)$comment['user_id'] ?>" class="comment-user"><?= htmlspecialchars($comment['Username']) ?></a>
                  <span class="comment-date"><?= date("F j, Y, g:i a", strtotime($comment['created_at'])) ?></span>
                </div>
                <div class="comment-text"><?= nl2br(htmlspecialchars($comment['comment'])) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="no-comments">No comments yet.</p>
        <?php endif; ?>
      </div>
    </div>

    <div class="recommendations-section">
      <h3 class="section-title">Recommended Videos</h3>
      <?php foreach ($recommendedVideos as $rec): ?>
        <div class="video-list-card" style="display: flex; gap: 10px; margin-bottom: 15px;">
          <div class="video-thumb" style="width: 120px; position: relative;">
            <img alt="Video Thumbnail" src="https://placehold.co/" style="width: 100%; border-radius: 8px;" />
            <span class="video-duration" style="position: absolute; bottom: 4px; right: 6px; background: rgba(0,0,0,0.6); color: white; padding: 2px 6px; font-size: 12px; border-radius: 3px;">N/A</span>
          </div>
          <div class="video-info" style="flex: 1;">
            <a href="/videos/watch.aspx?id=<?= (int)$rec['id'] ?>" class="video-title" style="font-weight: bold; font-size: 14px; color: #111; text-decoration: none;">
              <?= htmlspecialchars($rec['title']) ?>
            </a>
            <div class="video-meta" style="font-size: 12px; color: #666;">
              <?= (int)$rec['views'] ?> views • <?= date('F j, Y', strtotime($rec['uploadedAt'])) ?>
            </div>
            <div class="video-channel" style="display: flex; align-items: center; gap: 5px; margin-top: 4px;">
              <img class="channel-avatar" src="/Thumbs/Avatar.ashx?userId=<?= (int)$rec['uploaderId'] ?>&x=200&y=200&mode=headshot" alt="Uploader Avatar" style="width: 20px; height: 20px; border-radius: 50%;" />
              <a href="/User.aspx?id=<?= (int)$rec['uploaderId'] ?>" style="font-size: 12px; color: #222; text-decoration: none;">
                <?= htmlspecialchars($rec['Username']) ?>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
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
</div>

</body>
</html>
