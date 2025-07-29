<?php
header("content-type: text/xml");
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
if(!isset($_GET["userId"])){
	exit('<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://devopstest1.aftwld.xyz/roblox.xsd" version="4">
<External>null</External>
<External>nil</External>
<Item class="BodyColors">
<Properties>
<int name="HeadColor">1001</int>
<int name="LeftArmColor">1003</int>
<int name="LeftLegColor">1003</int>
<string name="Name">Body Colors</string>
<int name="RightArmColor">1003</int>
<int name="RightLegColor">1003</int>
<int name="TorsoColor">199</int>
<bool name="archivable">true</bool>
</Properties>
</Item>
</roblox>');
}
$uID = $_GET["userId"];
$getuserinfo = $pdo->prepare('SELECT * FROM users WHERE UserId = :userId');
$getuserinfo->execute(['userId' => $uID]);
$row = $getuserinfo->fetch(PDO::FETCH_ASSOC);
if (!$getuserinfo) {
    print_r($pdo->errorInfo());
	exit("");
}
$bodycolor = json_decode($row["BodyColors"], true);
exit('<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://devopstest1.aftwld.xyz/roblox.xsd" version="4">
<External>null</External>
<External>nil</External>
<Item class="BodyColors">
<Properties>
<int name="HeadColor">'.$bodycolor["Head"].'</int>
<int name="LeftArmColor">'.$bodycolor["Left Arm"].'</int>
<int name="LeftLegColor">'.$bodycolor["Left Leg"].'</int>
<string name="Name">Body Colors</string>
<int name="RightArmColor">'.$bodycolor["Right Arm"].'</int>
<int name="RightLegColor">'.$bodycolor["Right Leg"].'</int>
<int name="TorsoColor">'.$bodycolor["Torso"].'</int>
<bool name="archivable">true</bool>
</Properties>
</Item>
</roblox>');
?>
