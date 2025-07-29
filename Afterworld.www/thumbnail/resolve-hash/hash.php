<?php
//expected result for the resolve hash: {"Url":"http://t6.rbxcdn.com/ad0550ba8fe100c8463cee57f79b7737"}
$hash = $_GET['md5'] ?? "";
function isValidMd5($md5 ='')
{
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}
if(!isValidMd5($hash)){
	exit;
}
exit(json_encode(["Url" => "https://aftwld.xyz/3dcache/" . $hash . ".txt"]));