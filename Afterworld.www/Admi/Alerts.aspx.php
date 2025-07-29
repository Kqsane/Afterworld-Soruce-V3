<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$success = false;
$error = '';
$adminLevel = 0;
$currentPage = basename($_SERVER['PHP_SELF']);
if (!empty($_COOKIE['_ROBLOSECURITY'])) {
    $row = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    $adminLevel = isset($row['isAdmin']) ? (int)$row['isAdmin'] : 0;
}
if ($adminLevel === 0) {
    header('Location: /NewLogin');
    exit();
}
if(!$row){
	exit('Couldnt load information');
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/Admi/SideBar.php';
if ($adminLevel < AdminLevel::DEV) {
    echo <<<HTML
<div class="main-content"><br>
    <div class="header">
        <h1>You do not have permission to use this.</h1>
    </div>
</div>
</body>
</html>
HTML;
exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($adminLevel < AdminLevel::DEV) {
        http_response_code(403);
        echo '403 Forbidden: Unauthorized';
        exit();
    }
    $createdAt = time();
    if (isset($_POST['submit_alert'])) {
        $content = trim($_POST['alert-content'] ?? '');
        $color = $_POST['alert_color'] ?? '';
        if ($content === '' || $color === '') {
            $error = 'Please enter both content and select a color.';
        } elseif (strlen($content) > 255) {
            $error = 'Alert content cannot exceed 255 characters.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO alerts (content, color, CreatedAt) VALUES (:content, :color, :createdAt)");
            $success = $stmt->execute([
                ':content' => $content,
                ':color' => $color,
                ':createdAt' => $createdAt
            ]);
            if (!$success) $error = 'Failed to insert alert.';
        }
    } elseif (isset($_POST['remove_alert'])) {
        $stmt = $pdo->prepare("INSERT INTO alerts (content, color, CreatedAt) VALUES ('', NULL, :createdAt)");
        $success = $stmt->execute([':createdAt' => $createdAt]);
        if (!$success) $error = 'Failed to remove alert.';
    }
}
?>
<div class="main-content"><br>
    <div class="header">
        <h1>Site-Wide Alerts</h1>
    </div>
    <br>
    <form method="POST">
        <button type="submit" name="remove_alert">Remove Alert</button>
    </form>
    <form method="POST">
        <div class="form-group">
            <label for="alert-content">Alert Content (Max 255 chars to prevent abuse):</label>
            <textarea name="alert-content" id="alert-content" maxlength="255" required></textarea>
        </div>
        <div class="form-group">
            <label>Color:</label>
            <?php
            $colors = ['red', 'yellow', 'orange', 'blue', 'black', 'pink', 'purple', 'green'];
            foreach ($colors as $c) {
                echo "<div><input type='radio' name='alert_color' value='$c' required>" . ucfirst($c) . "</div>";
            }
            ?>
        </div>
        <button type="submit" name="submit_alert">Submit</button><br>
    </form>

    <?php if ($success): ?>
        <div class="hidden2" style="display:block">
            <span style="color: green; margin-top: 16px;">✔ Action completed successfully.</span>
        </div>
    <?php elseif ($error): ?>
        <div class="hidden2" style="display:block">
            <span style="color: red; margin-top: 16px;">✖ <?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>
</div>
</body>
</html>