<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_GET["UserID"])){
	exit("https://devopstest1.aftwld.xyz/Asset/BodyColors.ashx");
}
$uID = (int)$_GET["UserID"];
$getuserinfo = $pdo->prepare('SELECT * FROM users WHERE UserId = :userId');
$getuserinfo->execute(['userId' => $uID]);
$row = $getuserinfo->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    print_r($pdo->errorInfo());
	exit("");
}

exit("https://devopstest1.aftwld.xyz/Asset/BodyColors.ashx?userId=$uID;".$row["CharApp"]);