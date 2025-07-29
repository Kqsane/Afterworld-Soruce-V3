<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$videoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($videoId <= 0) {
    echo "Invalid video ID.";
    exit;
}
$stmt = $pdo->prepare("
    SELECT *
    FROM video_archive
    WHERE id = ?
");
$stmt->execute([$videoId]);
$video = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$video) {
    echo "Video not found.";
    exit;
}
$stmt = $pdo->prepare("
    SELECT *
    FROM videocomments_archive
    WHERE video_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$videoId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <title>AFTERWORLD Video Archive - <?= htmlspecialchars($video['title']) ?></title>
<link rel='stylesheet' href='/CSS/Base/CSS/main___3cf17678f5d36ad2b9b72dd3439177be_m.css' />
<link rel='stylesheet' href='/videos/forumpages.css' />
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
        <video controls style="width: 100%; max-width: 854px; aspect-ratio: 16 / 9; border-radius: 12px;">
    <source src="/uploads/videos/<?= htmlspecialchars($video['filename']) ?>" type="video/mp4">
    Your browser does not support the video tag.
</video>
        <h2 style="font-size: 22px; margin-top: 15px;">
            <?= htmlspecialchars($video['title']) ?>
        </h2>
        <div class="meta" style="font-size: 14px; color: #555;">
            Uploaded by <strong><?= htmlspecialchars($video['uploaderUsername']) ?></strong></a> |
            <?= (int)$video['views'] ?> views |
            Uploaded on <?= date("F j, Y", strtotime($video['uploadedAt'])) ?>
        </div>
	 <?php if (!empty($video) && !empty($video['description'])): ?>
	 <p style="font-size: 16px; margin-top: 5px;">
        <?= htmlspecialchars($video['description']) ?>
     </p>
	 <?php else: ?>
	 <p style="font-size: 16px; margin-top: 5px;">
        No description found.
     </p>
	 <?php endif; ?>
    </div>
    <div class="comments-section" style="flex: 1; min-width: 300px;">
        <h3 class="section-title">Comments</h3>
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
                            <strong class="comment-user"><?= htmlspecialchars($comment['username']) ?></strong>
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