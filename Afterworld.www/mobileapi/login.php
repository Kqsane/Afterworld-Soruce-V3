<?php
// this is a testing function, to the server being able to receive information and write the logs.
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header("Content-Type: application/json");
if(!isset($_POST)){
	exit(json_encode(["Status" => "Error", "Message" => "what"]));
}
// log
$log = fopen($_SERVER['DOCUMENT_ROOT'] . '/mobileapi/log.log', "w");
$rawPostData = file_get_contents('php://input');
$parsedPost = [];
parse_str($rawPostData, $parsedPost);
fwrite($log, $rawPostData);
$username = $parsedPost['username'] ?? "test";
$password = $parsedPost['password'] ?? "";
$GetUser = $pdo->prepare('SELECT * FROM users WHERE Username = :username');
$GetUser->execute(['username' => $username]);
$row = $GetUser->fetch(PDO::FETCH_ASSOC);
if(!$row){
	exit('{"Status": "Error", "Message": "Invalid username or password"}');
}
if(password_verify($password, $row["Password"])){
	setcookie(".ROBLOSECURITY",$row['ROBLOSECURITY'],time() + (10 * 365 * 24 * 60 * 60));
	$user = getuserinfo($row['ROBLOSECURITY']);
	if(!$user){
		exit('{"Status": "Error", "Message": "Error while loading user information"}');
	}
	$response = [
            "Status" => "OK",
            "UserInfo" => [
			"UserID" => $user['UserId'],
			"UserName" => $user['Username'],
			"RobuxBalance" => $user["Robux"],
			"TicketsBalance" => $user["Tix"],
			"IsAnyBuildersClubMember" => false,
			"ThumbnailUrl" => "https://devopstest1.aftwld.xyz/render.png"
		]
    ];

    echo json_encode($response);
}else{
	echo json_encode(["Status" => "Error", "Message" => "Invalid username or password"]);
}
?>