<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

header("Content-Type: application/json");

$userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);

$response = [
    "Collection" => [
        [
            "Id" => 123456,
            "Subject" => "Hello!",
            "Body" => "<p>Welcome to Roblox!</p>",
            "Created" => "2025-06-01T12:34:56Z",
            "IsRead" => false,
            "Sender" => [
                "UserId" => 1,
                "UserName" => "ROBLOX"
            ],
            "SenderAbsoluteUrl" => "/user.aspx?id=1",
            "SenderThumbnail" => "/Thumbs/Avatar.ashx?userId=1",
            "Recipient" => [
                "UserId" => $userInfo['UserId'],
                "UserName" => $userInfo['Username']
            ],
            "RecipientAbsoluteUrl" => "/user.aspx?id=" . $userInfo['UserId'],
            "RecipientThumbnail" => "/Thumbs/Avatar.ashx?userId=" . $userInfo['UserId'],
            "IsReportAbuseDisplayed" => false,
            "AbuseReportAbsoluteUrl" => "/abusereport/message?id=123456"
        ]
    ],
    "Page" => 1,
    "PageSize" => 20,
    "TotalCollectionSize" => 1
];

echo json_encode($response);
?>
