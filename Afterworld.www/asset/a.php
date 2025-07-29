<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

$aid = (int)($_GET["id"] ?? $_GET["Id"]);
$classicify = [8, 17, 27, 28, 29, 30, 31, 32, 41, 42, 43, 44, 45, 46, 47];
$cacheFile = "./cached/" . $aid . ".bin";

if (file_exists($cacheFile)) {
    serveFile($cacheFile);
    exit;
}

function fetch_url($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Handle redirects
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore SSL errors (optional)
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout in seconds
    $cookie = "_|WARNING:-DO-NOT-SHARE-THIS.--Sharing-this-will-allow-someone-to-log-in-as-you-and-to-steal-your-ROBUX-and-items.|_ECE992751CDB64A06555DA53002FFE785FB3F9B7E1E6BD230D58B9C277970ACDEC79B74045703CEFA86444EADAB54B1141C003F37042C397BCF1788D291E19275E490C92C1050C4ACE321363C227ED33508F98B5078492AA697C995C682A2B2C8BF42F1FF938DA9C1469FF9B5E88689D24BD8A1F97E4D740D938621EBE2714A3EF1393EC60C0287EC96C2C6188931668F9EC6408964284049A108032793D54E7C4D37B9B21093A39B4598C1F22DA2B4A41BAD8B3B39D8224EF149A9D496AA965FFD54D02F2B4E8297FE0E0365D1D5164267CBF52162126A1D65535B8A58484B74E8E130E4EA62420AB6EDBBF07DD12D02DAB0250ADBD0D6331B5D5D31203F130907C7BA29B014C989C9AC03AEC76764E21B97B1F26C2C991D80C735D1CC0065B40ABD8E205C61EB383D148EB01B0D5A46A6D49E487D0F8260D56884BE71C40A926507D97EED3F32CF3DEC055E4852AAC3937D3109578166A972F6447AACD6EAD3E2BF78D5F69C67E1992940DA09786EB25FF24D8BAC7C0CA8521AF1703625E1C8F892B87BF8E0530FF1FD7154941345B21A74C5D823C1332ED4F634C312AA5F5D96E128F9A950C51D828AC95745F6602B4F5A7AA2786ADC5DA1F61667A14A1CE674C61AADB82EE8C395F871451A7180BD114FCF6B4075D607CF755352D3883F17F557042E4D7919F04BD6AD5B9819BFDEABB1EB9F885312793436FFBEA7F128893E4A37804C5F789A31C77E33C63B938C36A85F169766A5BC5C23B9B058C2CE4B7FFA16E81916825171454B34A56E6E311BBB29A73EF3A6EE144815C1445D2DAAA31F5CA11DE06916512C436AB9537AD8DBC29C88CE69A7717386EDD0BBB3CF6950FE93B3C064860338DFE097FAC40DBCF3831B8B944CD4480FB19DBA8F379310A086ECD9D27A36E28AA6E7C6358DCCCE24F754141CE8DA6F5D155F7729EDFE637571536D3A13D821AB92509DDB7F479C4ACF1120F2567122F3E2AB76BD2E574E6D9D4F997A2413C5AD6A839FF00557EDAA0E28D73FEF10918953A65AFE92E86EE22B4D803E151215CE1A2761068665B3D02D86581E6E18348DA043EF95EFF1BE4A7A583";
    $headers = [
        "Cookie: .ROBLOSECURITY=$cookie",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false || $httpCode !== 200) {
        file_put_contents("./logs.log", "Failed to fetch URL: $url - HTTP Code: $httpCode - Error: $error\n", FILE_APPEND);
        return false;
    }

    return $response;
}
$real = fetch_url("https://economy.aftwld.xyz/v2/assets/$aid/details");
if (!$real) {
    $asset = fetch_url("https://assetdelivery.aftwld.xyz/v1/asset/?id=$aid");
    if ($asset) {
        file_put_contents($cacheFile, $asset);
        serveFile($cacheFile);
        exit;
    } else {
        http_response_code(300);
        exit("Failed to retrieve asset.");
    }
}
$assetInfoRequest = json_decode($real, true);

if (isset($assetInfoRequest["AssetTypeId"]) && in_array($assetInfoRequest["AssetTypeId"], $classicify)) {
    $asset = fetch_url("https://assetdelivery.aftwld.xyz/v1/asset/?id=$aid&version=1");
} else {
    $asset = fetch_url("https://assetdelivery.aftwld.xyz/v1/asset/?id=$aid");
}
if ($asset) {
    file_put_contents($cacheFile, $asset);
    serveFile($cacheFile);
} else {
    http_response_code(404);
    exit("Asset not found.");
}
function serveFile($filePath) {
    if (!file_exists($filePath)) {
        http_response_code(404);
        exit("File not found.");
    }

    $mimeType = mime_content_type($filePath);
    if (!$mimeType) {
        $mimeType = "application/octet-stream"; // Fallback if MIME can't be detected
    }

    header("Content-Type: $mimeType");
    header("Content-Length: " . filesize($filePath));
    header("Content-Disposition: inline; filename=\"" . basename($filePath) . "\"");
    readfile($filePath);
    exit;
}
?>