<?php
// user information
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	exit("You already logged out!");
}
		$logout_action = logout();
		if($logout_action){
			echo '{"success": true}';
		}