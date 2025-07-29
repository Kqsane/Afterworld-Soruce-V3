<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php'; 
if (!getuserinfo($_COOKIE['_ROBLOSECURITY']) && !isset($_COOKIE['_ROBLOSECURITY'])){
		echo "Not authenticated";
		http_response_code(403);
	}else{
		echo getuserinfo($_COOKIE['_ROBLOSECURITY'])['UserId'];
	}