<?php
$assetId = $_GET['productId'];
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php'; 
header("Content-Type: application/json");
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
?>