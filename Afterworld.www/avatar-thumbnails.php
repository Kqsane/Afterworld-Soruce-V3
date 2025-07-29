<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
header("content-type: text/javascript");
$json_callback = $_GET['jsoncallback'];
$params = json_decode(urldecode($_GET['params']), true);
$finaljsCallback = $json_callback."(";
$jointed_requests = [];
foreach ($params as $userRequest){
	$FindGames = $pdo->prepare('select * from users where UserId = :gid');
    $FindGames->execute(['gid' =>(int) $userRequest["userId"]]);
    $row = $FindGames->fetch(PDO::FETCH_ASSOC);
	$args = "";
	$bcOverlayUrl = null;
	$membership = (int)$row['Membership'];
if ($membership === 1) {
    $bcOverlayUrl = 'https://devopstest1.aftwld.xyz/images/BC.png';
} elseif ($membership === 2) {
    $bcOverlayUrl=  'https://devopstest1.aftwld.xyz/images/TBC.png';
} elseif ($membership === 3) {
    $bcOverlayUrl= 'https://devopstest1.aftwld.xyz/images/OBC.png';
}
	if($userRequest['imageSize'] == "small"){
		$args = "&x=100&y=100";
	}
	$constructor = [
		"id" => $row['UserId'],
		"name" => $row['Username'],
		"url" => "/user.aspx?id=".$row['UserId'],
		"thumbnailFinal" => true,
		"thumbnailUrl" => "https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=".$row['UserId'].$args,
		"bcOverlayUrl" => $bcOverlayUrl
	];
	$jointed_requests[] = $constructor;
}
$finaljsCallback .= json_encode($jointed_requests).")";
exit($finaljsCallback);