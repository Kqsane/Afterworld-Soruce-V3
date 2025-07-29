<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/miscfunctions.php';
header("Content-Type: application/json");
$username = $_GET["username"];
/*
REFERENCE DATA:
1: Already taken
2: Can't Use (Roblox)

*/
$swearWords = loadSwearWords($_SERVER['DOCUMENT_ROOT'] . '/config/includes/swearwords.txt');
if (containsSwearWords($username, $swearWords)) {
    exit('{"data": 2}');
}
// Securely prepare the SQL query
$FindUser = $pdo->prepare('SELECT * FROM users WHERE Username = :username');
$FindUser->execute(['username' => htmlspecialchars($username)]);
$row = $FindUser->fetch(PDO::FETCH_ASSOC);

// Check if the row was retrieved
if ($row) {
   exit('{"data": 1}');
}
exit('{"data": 0}');
?>
