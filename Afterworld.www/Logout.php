<?php
// user information
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	exit("You already logged out!");
}
		$logout_action = logout();
		if($logout_action){
			echo "You sucessfully logged out. You're going to be shortly bought back to the login/signup page";
			sleep(3);
			exit(header("Location: /"));
		}