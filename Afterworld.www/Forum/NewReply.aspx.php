<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    header("Location: /newlogin");
    exit();
}

$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if (!$info || !isset($info['UserId'])) {
    logout();
    header("Location: /");
    exit();
}

$userId = $info['UserId'];
$postId = isset($_GET['PostID']) ? (int)$_GET['PostID'] : 0;

if ($postId <= 0) {
    echo "Invalid post ID.";
    exit();
}

$stmt = $pdo->prepare("SELECT f.*, u.Username FROM forums AS f LEFT JOIN users AS u ON f.UserId = u.UserId WHERE f.id = ?");
$stmt->execute([$postId]);
$originalPost = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$originalPost) {
    echo "Thread not found.";
    exit();
}

$originalSubject = htmlspecialchars($originalPost['Subject']);
$originalUser = htmlspecialchars($originalPost['Username'] ?? 'User');
$originalDate = date("F j, Y, g:i a", $originalPost['PostedAt']);
$originalContent = nl2br(htmlspecialchars($originalPost['Content'] ?? 'No content provided.'));
$replySubject = "Re: " . $originalSubject;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    $replyText = trim($_POST['reply_text']);

    if (!empty($replyText)) {
        $stmt = $pdo->prepare("INSERT INTO forum_replies (PostId, UserId, Subject, Content, PostedAt) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $postId,
            $userId,
            $replySubject,
            $replyText,
            time()
        ]);

        header("Location: /Forum/ShowPost.aspx?PostID=$postId");
        exit();
    } else {
        $error = "Reply cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $replySubject ?></title>
<link rel='stylesheet' href='/CSS/Base/CSS/main___52c69b42777a376ab8c76204ed8e75e2_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___c7d63abcc3de510b8a7b8ab6d435f9b6_m.css' />
<link rel='stylesheet' href='/Forum/skins/default/style/default.css' />
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

<div style="padding: 20px; max-width: 800px; margin: auto;">

    <h2><?= $replySubject ?></h2>

    <div style="border: 1px solid #ccc; padding: 15px; background-color: #f9f9f9; margin-bottom: 20px;">
        <h3 style="margin: 0;">
            <a href="/Forum/ShowPost.aspx?PostID=<?= $postId ?>" style="text-decoration: none; color: #2578bb;">
                <?= $originalSubject ?>
            </a>
        </h3>
        <p style="font-size: 14px; color: gray;">Posted by <?= $originalUser ?> on <?= $originalDate ?></p>
        <div style="margin-top: 10px; font-size: 15px;">
            <?= $originalContent ?>
        </div>
    </div>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label style="font-weight: bold;">Subject:</label><br>
        <input type="text" value="<?= $replySubject ?>" readonly style="width: 100%; background: #f0f0f0; border: 1px solid #ccc;"><br><br>

        <label style="font-weight: bold;">Reply:</label><br>
        <textarea name="reply_text" rows="10" cols="80" placeholder="Write your reply here..." required></textarea><br><br>

        <button type="submit" name="reply">Post Reply</button>
        <a href="/Forum/ShowPost.aspx?PostID=<?= $postId ?>" style="margin-left: 10px;">Cancel</a>
    </form>
</div>
</body>
</html>
