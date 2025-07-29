<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

$username = $_POST['userName'] ?? '';
$password = $_POST['password'] ?? '';
$passwordC = $_POST['passwordConfirm'] ?? '';
$captcha = $_REQUEST['cf-turnstile-response'] ?? '';
$gender = $_POST['gender'] ?? '';

if (empty($username) || empty($password) || empty($passwordC) || empty($gender) || empty($captcha)) {
    exit("An error occurred: Fields were not filled.");
}

$skey = '1x0000000000000000000000000000000AA';
$url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
$ip = $_SERVER['REMOTE_ADDR'];
$data = [
    'secret' => $skey,
    'response' => $captcha,
    'remoteip' => $ip
];
$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded",
        'method' => 'POST',
        'content' => http_build_query($data)
    ]
];
$stream = stream_context_create($options);
$result = file_get_contents($url, false, $stream);
$response = json_decode($result);

if (empty($response->success)) {
    http_response_code(403);
    exit("An error occurred: You must fill the captcha in order to register.");
}

if ($password !== $passwordC) {
    exit("An error occurred: Passwords do not match.");
}

$stmt = $pdo->prepare("
    SELECT 1 FROM users 
    WHERE LOWER(username) = LOWER(:username) 
       OR JSON_CONTAINS(LOWER(JSON_EXTRACT(previousUsernames, '$[*]')), JSON_QUOTE(LOWER(:username)))
");
$stmt->execute(['username' => $username]);
if ($stmt->fetch()) {
    exit("An error occurred: Username already taken.");
}

$functionload = signup($username, $password, $gender);
if ($functionload === true) {
    header("Location: /landing/activation");
    exit();
} else {
    exit("An unknown error occurred when registering your account.");
}
?>