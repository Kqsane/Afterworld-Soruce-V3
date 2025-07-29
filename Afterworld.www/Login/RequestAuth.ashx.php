<?php 
include ($_SERVER['DOCUMENT_ROOT'].'/config/main.php');
$robloauth = $_COOKIE['_ROBLOSECURITY'] ?? "nil";
$ticket = getuserinfo($robloauth);
if ($ticket) {
	$logged = true;
	$usrname = $ticket['Username'];
	$randguestid = urlencode($robloauth);
	echo "https://devopstest1.aftwld.xyz/Login/Negotiate.ashx?suggest=$randguestid";
}else{
	http_response_code(401);
	exit();
}
?>