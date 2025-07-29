<?php
$client = $_GET["c"] ?? 15;
$client_version = "version";
switch($client){
	case 16:
	$client_version = "2016ver";
	break;
	case 14:
	$client_version = "2014ver";
	break;
}
$setup_version = file_get_contents("http://setup.aftwld.xyz/$client_version"); // fetch da version
if(!empty($setup_version)){
	header("Location: http://setup.aftwld.xyz/$setup_version-Roblox.exe");
	exit;
}
exit("Couldn't fetch version");