<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

if (!isset($_COOKIE['_ROBLOSECURITY'])) {
    header('Location: /newlogin');
    exit();
}

$info = getuserinfo($_COOKIE['_ROBLOSECURITY']);
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

$uploadError = '';
$uploadSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $file = $_FILES['video'] ?? null;

    if (empty($title) || !$file || empty($file['name'])) {
        $uploadError = 'Please enter a title and select a video file.';
    } else {
        $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        if (!in_array($file['type'], $allowedTypes)) {
            $uploadError = 'Unsupported video format. Allowed: mp4, webm, ogg.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $uploadError = 'Upload failed. Error code: ' . $file['error'];
        } else {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/videos/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safeName = uniqid('vid_', true) . '.' . $ext;
            $destination = $uploadDir . $safeName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $stmt = $pdo->prepare("INSERT INTO videos (title, description, filename, uploaderId, uploadedAt) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$title, $description, $safeName, $userId]);

                $uploadSuccess = 'Video uploaded successfully!';
            } else {
                $uploadError = 'Failed to save the uploaded video.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AFTERWORLD Videos - Upload</title>
    <link rel='stylesheet' href='/CSS/Base/CSS/main___3cf17678f5d36ad2b9b72dd3439177be_m.css' />
    <link rel='stylesheet' href='/videos/forumpages.css' />
    <link rel='stylesheet' href='/CSS/Base/CSS/page___b3be82695b3ef2061fcc71f48ca60b85_m.css' />
    <link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css">
    <link rel="stylesheet" href="/CSS/Base/CSS/page___53eeb36e90466af109423d4e236a59bd_m.css">
    <script src="/js/jquery/jquery-1.7.2.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
    <script>window.jQuery || document.write("<script src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js"></script>
    <script>window.jQuery || document.write("<script src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,600,700" rel="stylesheet">
    <script src="https://js.rbxcdn.com/fbb8598e3acc13fe8b4d8c1c5b676f2e.js.gzip"></script>
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
<div id="navContent" class="nav-content nav-no-left" style="margin-left: 0px; width: 100%;">
    <div class="nav-content-inner">
        <div class="container-main">
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
                        <div id="Body" style="width:970px;">
                            <h1>Upload a Video</h1>
                            <small>gonna work on a much newer design soon</small><br>
                            <small>DO NOT UPLOAD LONG VIDEOS, our server isn’t powerful as YouTube...</small>

                            <?php if ($uploadError): ?>
                                <p style="color: red;"><?= htmlspecialchars($uploadError) ?></p>
                            <?php elseif ($uploadSuccess): ?>
                                <p style="color: green;"><?= htmlspecialchars($uploadSuccess) ?></p>
                            <?php endif; ?>

                            <form method="POST" enctype="multipart/form-data">
                                <label for="title">Video Title:</label><br>
                                <input type="text" name="title" required><br><br>

                                <label for="description">Description:</label><br>
                                <textarea name="description" class="comment-input" rows="4" placeholder="Write something about your video..."></textarea><br><br>

                                <label for="video">Select Video File:</label><br>
                                <input type="file" name="video" accept="video/*" required><br><br>

                                <button type="submit">Upload Video</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
