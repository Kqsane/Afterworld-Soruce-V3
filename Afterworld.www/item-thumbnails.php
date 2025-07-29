<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
header("content-type: text/javascript");
$json_callback = $_GET['jsoncallback'];
$params = json_decode(urldecode($_GET['params']), true);
$finaljsCallback = $json_callback."(";
$jointed_requests = [];
foreach ($params as $assetRequest){
	$FindGames = $pdo->prepare('select * from assets where AssetID = :gid');
    $FindGames->execute(['gid' =>(int) $assetRequest["assetId"]]);
    $row = $FindGames->fetch(PDO::FETCH_ASSOC);
	$urlName = urlencode(str_replace(' ', '-', $row['Name']));
	$id = $row['AssetID'];
    $link = "/$urlName-item?id=$id";
	$args = "";
	if($assetRequest['imageSize'] == "small"){
		$args = "&height=100&width=100";
	}
	$constructor = [
		"id" => $row['AssetID'],
		"name" => $row['Name'],
		"url" => $link,
		"thumbnailFinal" => true,
		"thumbnailUrl" => "https://devopstest1.aftwld.xyz/Thumbs/Asset.ashx?assetId=".$row['AssetID'].$args,
		"bcOverlayUrl" => null,
		"limitedOverlayUrl" => null,
		"deadlineOverlayUrl" => null,
		"limitedAltText" => null,
		"newOverlayUrl" => null,
		"imageSize" => $assetRequest['imageSize'],
		"saleOverlayUrl" => null,
		"transparentBackground" => false
	];
	$jointed_requests[] = $constructor;
}
$finaljsCallback .= json_encode($jointed_requests).")";
exit($finaljsCallback);