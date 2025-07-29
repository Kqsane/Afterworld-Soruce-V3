<?php
// a code handling every single function for the user authentication
function login(string $username,string $password) {
	global $pdo;
	$GetUser = $pdo->prepare('SELECT * FROM users WHERE Username = :username');
	$GetUser->execute(['username' => $username]);
	$row = $GetUser->fetch(PDO::FETCH_ASSOC);
	if(!$row){
		return false;
	}
	if(password_verify($password, $row["Password"])){
		setcookie(".ROBLOSECURITY",$row['ROBLOSECURITY'],time() + (10 * 365 * 24 * 60 * 60), "/",  ".aftwld.xyz");
		return true;
	}else{
		return false;
	}
}
function signup(string $username,string $password,string $gender) {
	global $pdo;
	exit("signup is currently disabled at this moment. join our discord server to know when its open!");
	$swearWords = loadSwearWords($_SERVER['DOCUMENT_ROOT'] . '/config/includes/swearwords.txt');
	$username = trim($username);
	if (containsSwearWords($username, $swearWords)) {
		exit('Swear words arent allowed buddy.');
	}
	if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username)){
		exit("Usage of specials characters are not allowed, and if you thought you could register a account with the characters, you're a certified idiot");
	}
	if($gender == "male"){
		$gender = 0;
	}elseif($gender == "female"){
		$gender = 1;
	}else{
		exit("Invalid gender, please specify your gender");
	}
	$GetUser = $pdo->prepare('SELECT * FROM users WHERE Username = :username');
	$GetUser->execute(['username' => $username]);
	$row = $GetUser->fetch(PDO::FETCH_ASSOC);
	if($row){
		exit("The username you tried to register already has been taken, boohoo");
	}
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	$timetaken = time(); // Unix Time
	$password = password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
	$token = hash('sha256', htmlspecialchars($username). $password . $timetaken . generateRandomString()); // roblosecurity
	$insertQuery = $pdo->prepare(
		"INSERT INTO users (Username, Description, isAdmin, JoinData, Password, Gender, ROBLOSECURITY, IsBanned, CharApp, activated) 
		VALUES (:username, :description, :isAdmin, :jointime, :password, :gender, :token, :isBanned, '', 0)"
	);
	$a = "Hi, i'm a new robloxian!";
	$b = 0;
	$insertQuery->bindParam(':username', $username);
	$insertQuery->bindParam(':description', $a);
	$insertQuery->bindParam(':isAdmin', $b);
	$insertQuery->bindParam(':jointime', $timetaken);
	$insertQuery->bindParam(':password', $password);
	$insertQuery->bindParam(':gender', $gender);
	$insertQuery->bindParam(':token', $token);
	$insertQuery->bindParam(':isBanned', $b);
	
	$insertSuccess = $insertQuery->execute();
	if ($insertSuccess) {
		setcookie(".ROBLOSECURITY",$token,time() + (10 * 365 * 24 * 60 * 60), "/",  ".aftwld.xyz");
		return true;
	} else {
		return false;
	}
}
function roblosecurityauth($auth){
	global $pdo;
	$GetUser = $pdo->prepare('SELECT * FROM users WHERE ROBLOSECURITY = :auth');
	$GetUser->execute(['auth' => $auth]);
	$row = $GetUser->fetch(PDO::FETCH_ASSOC);
	if(!$row){
		return false;
	}
	setcookie(".ROBLOSECURITY",$row['ROBLOSECURITY'],time() + (10 * 365 * 24 * 60 * 60), "/",  ".aftwld.xyz");
	return true;
}
function logout(){
	unset($_COOKIE["_ROBLOSECURITY"]);
	setcookie(".ROBLOSECURITY","",-1, "/", ".aftwld.xyz");
	return true;
}
function getuserinfo($auth){
	global $pdo;
	$GetUser = $pdo->prepare('SELECT * FROM users WHERE ROBLOSECURITY = :auth');
	if(is_int($auth)){
		$GetUser = $pdo->prepare('SELECT * FROM users WHERE UserId = :auth');
	}
	$GetUser->execute(['auth' => $auth]);
	$row = $GetUser->fetch(PDO::FETCH_ASSOC);
	if(!$row){
		return false;
	}
	return $row;
}
?>