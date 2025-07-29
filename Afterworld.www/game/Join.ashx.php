<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: text/plain");

$placeId = (int)$_GET["placeId"];
$FindJob = $pdo->prepare('SELECT * FROM jobs WHERE placeId = :placeId');
$FindJob->execute(['placeId' => $placeId]);

$FindGames = $pdo->prepare('SELECT * FROM assets WHERE AssetID = :placeId AND AssetType = 9');
$FindGames->execute(['placeId' => $placeId]);

$gamerow = $FindGames->fetch(PDO::FETCH_ASSOC);
$job = $FindJob->fetch(PDO::FETCH_ASSOC);

function getRealIP()
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

if (!$gamerow || !$job) {
    // print_r($pdo->errorInfo());
    exit("no jubs");
}

if (isset($_COOKIE["_ROBLOSECURITY"])) {
    $userinfo = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
} elseif (isset($_GET["UseTicket"])) {
    $ticket = $_GET["UseTicket"];
    $stmt = $pdo->prepare("SELECT roblosecurity FROM login_tickets WHERE ticket = ? AND expires_at > NOW()");
    $stmt->execute([$ticket]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $pdo->prepare("DELETE FROM login_tickets WHERE ticket = ?")->execute([$ticket]);
        $userinfo = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
        if (!is_array($userinfo)) {
            exit("invalid userinfo");
        }
        setcookie(".ROBLOSECURITY", $_COOKIE["_ROBLOSECURITY"], time()+86400, "/", "", false, true);
    } else {
        exit("invalid ticket");
    }
}

$membership = "None";
if (isset($userinfo)) {
    if ($userinfo["activated"] === 0) {
        exit;
    }
    if ($userinfo["Membership"] == 1) {
        $membership = "BuildersClub";
    } elseif ($userinfo["Membership"] == 2) {
        $membership = "TurboBuildersClub";
    } elseif ($userinfo["Membership"] == 3) {
        $membership = "OutrageousBuildersClub";
    }
    $charurl = "https://devopstest1.aftwld.xyz/asset/CharacterFetch.ashx?UserID=" . $userinfo["UserId"];
} else {
    //exit();
    $charurl = "https://devopstest1.aftwld.xyz/asset/CharacterFetch.ashx";
}

// Construct joinscript
$uID = $userinfo["UserId"] ?? rand(-9999, -1);
$uName = $userinfo['Username'] ?? "Guest " . rand(1, 9999);
$joinscript = [
    "ClientPort" => 0,
    "MachineAddress" => "2.56.246.161",
    "ServerPort" => $job['port'],
    "PingUrl" => "",
    "PingInterval" => 20,
    "UserName" => $uName,
    "SeleniumTestMode" => false,
    "UserId" => $uID,
    "SuperSafeChat" => false,
    "ClientTicket" => gameUtils::GenerateClientTicketv1($uID, $uName, $charurl, $job['jobid']),
    "GameId" => $job['jobid'],
    "PlaceId" => $placeId,
    "MeasurementUrl" => "", // No telemetry here :)
    "WaitingForCharacterGuid" => "26eb3e21-aa80-475b-a777-b43c3ea5f7d2",
    "BaseUrl" => "https://devopstest1.aftwld.xyz/",
    "ChatStyle" => "ClassicAndBubble",
    "VendorId" => "0",
    "ScreenShotInfo" => "",
    "VideoInfo" => "",
    "CreatorId" => $gamerow['CreatorID'],
    "CreatorTypeEnum" => "User",
    "MembershipType" => $membership,
    "AccountAge" => "3000000",
    "CookieStoreFirstTimePlayKey" => "rbx_evt_ftp",
    "CookieStoreFiveMinutePlayKey" => "rbx_evt_fmp",
    "CookieStoreEnabled" => true,
    "IsRobloxPlace" => true,
    "GenerateTeleportJoin" => false,
    "IsUnknownOrUnder13" => false,
    "SessionId" => "",
    "DataCenterId" => 0,
    "UniverseId" => $placeId,
    "BrowserTrackerId" => 0,
    "UsePortraitMode" => false,
    "FollowUserId" => 0,
    "CharacterAppearance" => $charurl,
];

$data = json_encode($joinscript, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
$pdo->prepare("UPDATE assets SET Visits = IFNULL(Visits, 0) + 1 WHERE AssetID = :id")->execute(["id" => $placeId]);
$pdo->prepare("UPDATE users SET Tix = Tix + 1 WHERE UserId = :id")->execute(["id" => $gamerow["CreatorID"]]);
if (isset($userId)) {
    $pdo->prepare("DELETE FROM recentlyplayed WHERE userId = :uid AND GameId = :gid")->execute(["uid" => $userId, "gid" => $placeId]);
    $pdo->prepare("INSERT INTO recentlyplayed (userId, GameId) VALUES (:uid, :gid)")->execute(["uid" => $userId, "gid" => $placeId]);
}

exit(gameUtils::signv1($data));