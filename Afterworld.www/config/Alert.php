<?php
$stmt = $pdo->prepare("SELECT content, color FROM alerts ORDER BY id DESC LIMIT 1");
$stmt->execute();
$alert = $stmt->fetch(PDO::FETCH_ASSOC);
if ($alert && !empty(trim($alert['content']))) {
    $safeContent = htmlspecialchars($alert['content']);
    $safeColor = htmlspecialchars($alert['color']);
	echo '<div class="SystemAlert" style="background-color: red;"><div class="SystemAlertText">-- DEVELOPMENT SITE -- Some things may break randomly at any time -- DEVELOPMENT SITE --</div></div>';
    echo '<div class="SystemAlert" style="background-color: ' . $safeColor . ';"><div class="SystemAlertText">' . $safeContent . '</div></div>';
}