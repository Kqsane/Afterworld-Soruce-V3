<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header("Content-Type: text/plain");
// log
function getRealIP() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
//$log = fopen($_SERVER['DOCUMENT_ROOT'] . '/joinlog.log', "w");
if(isset($_GET['suggest'])){
	//fwrite($log, "Begin to log ".$_GET['suggest']."\n");
	$userinfo = getuserinfo($_GET["suggest"]);
	if(!is_array($userinfo)){
		//fwrite($log, "Failed to authenticate user. Are you sure it's valid?");
		//fclose($log);
		http_response_code(401);
		exit();
	}
	$username = $userinfo['Username'];
	$password = $userinfo['Password'];
	$userid = $userinfo['UserId'];
	setcookie("username", $username, time() + (460800* 30));
	setcookie("password", $password, time() + (460800* 30));
	setcookie(".ROBLOSECURITY", $_GET["suggest"], time() + (460800* 30));
	//fwrite($log, "User has been sucessfully authenticated!\n");
	//fwrite($log, "Current set cookies: ".implode($_COOKIE)."\n");
	if(isset($_COOKIE["_ROBLOSECURITY"]) && !empty($_COOKIE["_ROBLOSECURITY"])){
		//fwrite($log, "Double check done\n");
		$clientIP = getRealIP();

$insertQuery = $pdo->prepare(
    "INSERT INTO auth_sessions (ROBLOSECURITY, ip_address) 
     VALUES (:token, :ip);"
);
$insertSuccess = $insertQuery->execute([
    'token' => htmlspecialchars($_GET["suggest"]),
    'ip'    => $clientIP
]);
			if ($insertSuccess) {
				$insertedId = $pdo->lastInsertId();
				//fwrite($log, "Set ROBLOSECURITY to the database. ID is ".$insertedId."\n");
			}
		
	}

	//fclose($log);
	exit($_GET['suggest']);
	
}else{
	//fwrite($log, "Failed to authenticate user. Are you sure you set ?suggest= correctly?");
	//fclose($log);
	exit(http_response_code(401));
}
?>