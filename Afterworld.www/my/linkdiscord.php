<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$clientId = '1393323068352495666';
$clientSecret = 'wDargneEh_VkI_RG1rgUNLEGZmwywnp2';
$redirectUri = 'https://devopstest1.aftwld.xyz/my/linkdiscord';
if (!isset($_COOKIE['_ROBLOSECURITY'])) {
    header("Location: /newlogin");
    exit;
}
$securityToken = $_COOKIE['_ROBLOSECURITY'];
$stmt = $pdo->prepare("SELECT UserId FROM users WHERE ROBLOSECURITY = ? LIMIT 1");
$stmt->execute([$securityToken]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header("Location: /newlogin");
    exit;
}
$userId = $user['UserId'];
if (!isset($_GET['code'])) {
    exit('No Discord code provided.');
}
$code = $_GET['code'];
$tokenResponse = file_get_contents('https://discord.com/api/oauth2/token', false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded",
        'content' => http_build_query([
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'scope' => 'identify'
        ])
    ]
]));
$token = json_decode($tokenResponse, true);
if (empty($token['access_token'])) {
    exit('Failed to retrieve Discord access token.');
}
$userResponse = file_get_contents('https://discord.com/api/users/@me', false, stream_context_create([
    'http' => [
        'header' => "Authorization: Bearer {$token['access_token']}"
    ]
]));
$discordUser = json_decode($userResponse, true);
if (empty($discordUser['id'])) {
    exit('Failed to retrieve Discord user info.');
}
$discordId = $discordUser['id'];
$stmt = $pdo->prepare("UPDATE users SET discord_id = ? WHERE UserId = ?");
$stmt->execute([$discordId, $userId]);
header("Location: /my/account");
exit;