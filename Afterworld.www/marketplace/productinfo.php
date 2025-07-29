<?php
$assetId = $_GET["assetId"] ?? 0;
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php'; 
header("Content-Type: application/json");
$FindUser = $pdo->prepare('select * from assets where AssetID = :cookie');
    $FindUser->execute(['cookie' => $assetId]);
    $user = $FindUser->fetch(PDO::FETCH_ASSOC);
	if(!$user) {
$url = 'https://economy.roblox.com/v2/assets/' . $assetId . '/details';

	$cookie_name = '.ROBLOSECURITY';
	$cookie_value = ROBLOSECURITY;

	$curl_options = [
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_HTTPHEADER => ['Cookie: ' . urlencode($cookie_name) . '=' . urlencode($cookie_value)],
		CURLOPT_SSL_VERIFYPEER => false
	];
	if (strpos($url, 'https://') === 0) {
		$curl_options[CURLOPT_COOKIE] = $cookie_name . '=' . $cookie_value . '; Secure';
	}

	$ch = curl_init();
	curl_setopt_array($ch, $curl_options);
	
	$response = curl_exec($ch);

	if ($response === false) {
		http_response_code(500);
		die(json_encode([ "errors" => [ [ 'code' => 500, 'message' => 'InternalServerError' ] ] ]));
	} else {
		
		die($response);
	}

	curl_close($ch);
}
$creator = getuserinfo($user["CreatorID"]);
$json = [];
$json["TargetId"] = $assetId;
$json["ProductType"] = "User Product";
$json["AssetId"] = $assetId;
$json["ProductId"] = $assetId;
$json["Name"] = $user["Name"];
$json["Description"] = $user["Description"];
$json["AssetTypeId"] = $user['AssetType'];
$json["Creator"] = [
	'Id' => $user["CreatorID"],
	'Name' => $creator["Username"],
	'CreatorType' => 0,
	'CreatorTargetId' => $user["CreatorID"]
];
$json["IconImageAssetId"] = null;
/*
$json["Created"] =
$json["Updated"] = 
*/
$json["PriceInRobux"] = null;
$json["PriceInTickets"] = null;
$json["Sales"] = 0;
$json["IsNew"] = false;
$json["IsForSale"] = false;
$json["IsPublicDomain"] = false;
$json["IsLimited"] = false;
$json["IsLimitedUnique"] = false;
$json["Remaining"] = null;
$json["MinimumMembershipLevel"] = 0;


die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));