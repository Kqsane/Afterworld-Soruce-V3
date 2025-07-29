<?php
// user information
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	exit("In order to access this page, you must log in");
}
exit(header("Location: /home"));
$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
echo("<h1>Home page</h1>");
echo("<div></div>Username: ". $info["Username"]);
echo("<div></div>UserID: ". $info["UserId"]);
echo("<div></div>Registered in: ". gmdate("Y-m-d\TH:i:s\Z", $info["JoinData"]));
echo("<div></div>Description: ". $info["Description"]);
?>