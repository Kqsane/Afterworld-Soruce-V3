<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

if (!isset($_COOKIE['_ROBLOSECURITY'])) {
    header("Location: /newlogin");
    exit();
}

$info = getuserinfo($_COOKIE['_ROBLOSECURITY']);
$userId = $info['UserId'] ?? null;

$stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE UserId = ?");
$stmt->execute([$userId]);
$isAdmin = (int)($stmt->fetchColumn() ?? 0);

if (!in_array($isAdmin, [2, 3, 4, 5])) {
    exit("Unauthorized");
}

$postId = isset($_GET['PostID']) ? (int)$_GET['PostID'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $stmt = $pdo->prepare("UPDATE forums SET Subject = ?, Message = ? WHERE id = ?");
    $stmt->execute([$subject, $message, $postId]);
    header("Location: /Forum/ShowPost.aspx?PostID=$postId");
    exit();
}

$stmt = $pdo->prepare("SELECT Subject, Message FROM forums WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<link rel='stylesheet' href='/CSS/Base/CSS/main___3cf17678f5d36ad2b9b72dd3439177be_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___b3be82695b3ef2061fcc71f48ca60b85_m.css' />
<link rel='stylesheet' href='/Forum/skins/default/style/default.css' />
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
<div style="padding: 20px; max-width: 800px; margin: auto;">
<h2>Edit Post</h2>
<form method="post">
    <label>Subject:</label><br>
    <input type="text" name="subject" value="<?= htmlspecialchars($post['Subject']) ?>" required><br><br>
    <label>Message:</label><br>
    <textarea name="message" rows="10" cols="70" required><?= htmlspecialchars($post['Message']) ?></textarea><br><br>
    <button type="submit">Save</button>
</form>
</div>
