<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_GET["placeid"])){
	exit("Cannot launch the game without the required arguments!");
}

$FindGames = $pdo->prepare('select * from assets where AssetID = :gid AND AssetType = 9');
$FindGames->execute(['gid' =>(int) $_GET["placeid"]]);
$row = $FindGames->fetch(PDO::FETCH_ASSOC);
if(!$row)
{
  exit("place doesnt exist");
}
function isIOS() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    return preg_match('/iPhone|iPad|iPod/i', $userAgent);
}

if (isIOS()) {
    $urilaunch = "robloxmobile2015E://Game/PlaceLauncher.ashx?request=RequestGame&placeId=" . $_GET["placeid"];
    exit("<a href='$urilaunch'>Click me to Join!</a>");
}
	$launcher_prefix = "aftwld-player";
	if($row['ClientYear'] === 2016){
		$launcher_prefix = "aftwld-16plyr";
	}
if(isset($_COOKIE["_ROBLOSECURITY"])){
	$urilaunch = "{$launcher_prefix}-aftwld:1+launchmode:play+gameinfo:".$_COOKIE["_ROBLOSECURITY"]."+launchtime:".time()."+placelauncherurl:http%3A%2F%2F194.62.248.75:34533%2FGame%2FPlaceLauncher.ashx%3FplaceId%3D".$_GET["placeid"]."%26useTokendatabase%3Dtrue+browsertrackerid:1";

	header("Location: ". $urilaunch);
}else{
	$urilaunch = "{$launcher_prefix}-aftwld:1+launchmode:play+gameinfo:Guest-1".rand(10000, 999999999)."+launchtime:".time()."+placelauncherurl:http%3A%2F%2F194.62.248.75:34533%2FGame%2FPlaceLauncher.ashx%3FplaceId%3D".$_GET["placeid"]."+browsertrackerid:1";
	header("Location: ". $urilaunch);
}
?>