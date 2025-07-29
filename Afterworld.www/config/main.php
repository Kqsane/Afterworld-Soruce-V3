<?php
session_start();

foreach (glob(__DIR__ . "/classes/Rcc/*.php") as $filename)
{
    require_once $filename;
}

include_once __DIR__ . "/miscfunctions.php";

foreach (glob(__DIR__ . "/classes/*.php") as $filename)
{
    require_once $filename;
}

$asset_types = [
    1 => "Image",
    2 => "TShirt",
    3 => "Audio",
    4 => "Mesh",
    5 => "Lua",
    8 => "Hat",
    9 => "Place",
    10 => "Model",
    11 => "Shirt",
    12 => "Pants",
    13 => "Decal",
    17 => "Head",
    18 => "Face",
    19 => "Gear",
    21 => "Badge",
    24 => "Animation",
    27 => "Torso",
    28 => "RightArm",
    29 => "LeftArm",
    30 => "LeftLeg",
    31 => "RightLeg",
    32 => "Package",
    34 => "GamePass",
    38 => "Plugin",
    40 => "MeshPart",
    41 => "Hair",
    42 => "FaceAccessory",
    43 => "NeckAccessory",
    44 => "ShoulderAccessory",
    45 => "FrontAccessory",
    46 => "BackAccessory",
    47 => "WaistAccessory",
    48 => "ClimbAnimation",
    49 => "DeathAnimation",
    50 => "FallAnimation",
    51 => "IdleAnimation",
    52 => "JumpAnimation",
    53 => "RunAnimation",
    54 => "SwimAnimation",
    55 => "WalkAnimation",
    56 => "PoseAnimation"
];
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=aftwld",
        "root",
        "AFTWLD$92EUKSQB39321KS",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    header('Content-Type: application/json');
    exit(
        json_encode([
            'error' => true,
            'message' => 'Database Error',
            'debug' => $e->getMessage()
        ])
    );
}
define('ROBLOSECURITY','_|WARNING:-DO-NOT-SHARE-THIS.--Sharing-this-will-allow-someone-to-log-in-as-you-and-to-steal-your-ROBUX-and-items.|_CAEaAhAB.6DAD21F2D83F7514EFD5F49DA53F70DFD582D0BBC9282B67112E334AFBAB2CBD4172ED393343037DE10EC7BEFCBCFBCD1CCE6383DE74C3FBA8B0D05C2A77AFCDEA380BD9F04E3860C600996A53D0585457D1FFA6C2D51E862B6B3106914F74366EEBFA69BB10783FD48F50FC17EAE0F2715FED14D228623D9C0F839650DFF5D95D5BB0B396289F1E08E3F30566662122A157403E50829D68B8710646E52E9FAF98C6A76C124F5D1354E4D7B1B4D2912E2D97B4CC30DDEB0A9B16BC9C6CCB000B509B32E4A3835019C4799FC6957A7C8449296F91E30C1563E8E5092A973702EDF89A5F004E3CFC36156133091A01A5834A3F15A237A6F9D099B7C1D615F9FB03A19191627AFAC96AF0100AE39C2122D03F32762DE4A81B46AE837E5C5F011A0C1E70101D8454DBF46CBE1A23F05738478CB539FF29B3202ECE6D5730EEBB366EE28D29FDC843543781812289EEA8F222D77A613CFE59910BBF491295346A8DD7E1A2C7BCBACB56174C74B3D75FFD15213BFBFE4C78583FF4A29C65C99C66D50B8F247BD9890511ED2D7A51402A9FC6B08498DCBE93570701E801A125EA45196FBE63A8765963A9E1C189B227D527CDB33AFCBFAF5FCA38B90249D6F225CBF9ABFCBD07C3F1F5DA974EA3C563F7EF2EBB2105EEF92C0DCE9E9BCF7D01C98A633DD4D6C94CC7B3A642A0203F78EF7409A736AF2977E2BF65848A3620E33B30BC0831269ADDB5DA989B2DDC20FA5CE61499F3C718736FFA0B8F504E95EB4010C2856262AE1DE07689DF44FA06E70E55BC4C471068ACE1922E06B408745B535A5876B0B186772392C2FC6EA2AC4900B5A4409708E6681A0BB8AD41EA9A4AAA289EE2C4495F468CA265C838927FF2AF35DD775AF5EB587A38796C3B419FABD72760516BE353BC3FB67ADCCD7F2568064F23A0038C541569BE9F07A5B5A0798EBBE7A763C9927195D0439B0BFFA88FDD1D3784ECB2F4AFAA070A0B2AC61339E7787C54C0811EEB2EB5AFE0386B86D9508627BC0241BD9CB742CDDD165865354459EA3319A9B3D8DE7D202E88E3146B99853C5EE976EFA420B6BA4ABD57115D43F6B93523F0AC4E3F9ABA14ACAA3B4E23F7DF4664A84E18');
$test_HTTP_proxy_headers = array(
	//'HTTP_VIA',
	'VIA',
	'Proxy-Connection',
	//'HTTP_X_FORWARDED_FOR',  
	'HTTP_FORWARDED_FOR',
	'HTTP_X_FORWARDED',
	'HTTP_FORWARDED',
	'HTTP_CLIENT_IP',
	'HTTP_FORWARDED_FOR_IP',
	'X-PROXY-ID',
	'MT-PROXY-ID',
	'X-TINYPROXY',
	'X_FORWARDED_FOR',
	'FORWARDED_FOR',
	'X_FORWARDED',
	'FORWARDED',
	'CLIENT-IP',
	'CLIENT_IP',
	'PROXY-AGENT',
	'HTTP_X_CLUSTER_CLIENT_IP',
	'FORWARDED_FOR_IP',
	'HTTP_PROXY_CONNECTION');
	
	foreach($test_HTTP_proxy_headers as $header){
		if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
			exit("Please disable your proxy connection! ". $header);
		}
	}
if(isset($_COOKIE["_ROBLOSECURITY"])){
	$check = roblosecurityauth($_COOKIE["_ROBLOSECURITY"]);
	if($check == false){
		logout();
	}else{
		$userinfo = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
		if(!$userinfo["activated"] && $_SERVER['PHP_SELF'] != "/landing/activation.php"){
			header("Location : /landing/activation");
		}
	}
}

function formatRobux($number) {
    if ($number >= 1000000000000) {
        return floor($number / 1000000000000) . 'T';
    } elseif ($number >= 1000000000) {
        return floor($number / 1000000000) . 'B';
    } elseif ($number >= 1000000) {
        return floor($number / 1000000) . 'M';
    } elseif ($number >= 1000) {
        return floor($number / 1000) . 'K';
    }
    return $number;
}

// same with the tickets
function formatTix($number) {
    if ($number >= 1000000000000) {
        return floor($number / 1000000000000) . 'T';
    } elseif ($number >= 1000000000) {
        return floor($number / 1000000000) . 'B';
    } elseif ($number >= 1000000) {
        return floor($number / 1000000) . 'M';
    } elseif ($number >= 1000) {
        return floor($number / 1000) . 'K';
    }
    return $number;
}

$maintenance_mode = false;
$whitelisted_ips = ['127.0.0.1'];
$current_path = @parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (isset($_SESSION['maintenance_bypass']) && $_SESSION['maintenance_bypass'] === true) {
    return;
}

$excluded_paths = [
    '/offline',
    '/wafjsdogjiofhjeoiahjaeodifkijhmaeidkofjmdafj.php',
    '/asset/'
];

if (
    $maintenance_mode &&
    !in_array($current_path, $excluded_paths) &&
    !in_array($_SERVER['REMOTE_ADDR'], $whitelisted_ips)
) {
    header('Location: /offline');
    exit;
}

function getMembershipBadge($membership) {
    switch ((int)$membership) {
        case 1:
            return 'rbx-icon-bc';
        case 2:
            return 'rbx-icon-tbc';
        case 3:
            return 'rbx-icon-obc';
        default:
            return 'rbx-icon-nbc';
    }
}

$theme = $_COOKIE['.AFTWLDTHEME'] ?? null;
$auth = $_COOKIE['_ROBLOSECURITY'] ?? null;
if ($theme === 'obc' && $auth) {
    $info = getuserinfo($auth);
    if (!empty($info['UserId'])) {
        try {
            $stmt = $pdo->prepare("SELECT Memberships FROM users WHERE UserId = ?");
            $stmt->execute([$info['UserId']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && (int)$user['Memberships'] === 3) {
                echo '<link rel="stylesheet" type="text/css" href="/CSS/Base/CSS/OBCTheme.css">';
            }
        } catch (PDOException $e) {
            error_log("OBC theme load failed: " . $e->getMessage());
        }
    }
}

