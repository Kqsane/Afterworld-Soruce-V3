<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
include_once __DIR__ . '/MeshConverter.php';
function convertToMesh2(array $parsed): string {
    $vertices = $parsed['vertices'];
    $faces = $parsed['faces'];

    $lines = [];
    $lines[] = 'version 2.00';
    $lines[] = (string) count($faces);

    foreach ($faces as $face) {
        foreach ($face as $index) {
            $v = $vertices[$index];
            $lines[] = sprintf(
                "[%f,%f,%f][%f,%f,%f][%f,%f]",
                $v->x, $v->y, $v->z,
                $v->nx, $v->ny, $v->nz,
                $v->u, $v->v
            );
        }
    }
    return implode("\n", $lines);
}

$id = $_GET['id'] ?? $_GET['ID'] ?? null;
if (!$id || !preg_match('/^\d+$/', $id)) {
    http_response_code(400);
    die("Invalid or missing asset ID.");
}

$cache_dir = __DIR__ . "/cache";
$assetFile = "$cache_dir/$id.asset";
$metaFile  = "$cache_dir/$id.meta.json";

if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}

// Serve from cache
if (file_exists($assetFile)) {
	if(file_exists($metaFile)){
    $meta = json_decode(file_get_contents($metaFile), true);
    }
	$mime = $meta['content_type'] ?? 'application/octet-stream';
    header("Content-Type: $mime");
    readfile($assetFile);
    exit;
}

// Step 1: Get CDN URL via AssetDelivery v2
$assetUrl = "https://assetdelivery.roblox.com/v2/assetId/$id";
if(isset($_GET['version'])){
	$version = (int) $_GET['version'];
	$assetUrl = "https://assetdelivery.roblox.com/v2/assetId/$id/version/$version";
}
$ch = curl_init($assetUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "User-Agent: Roblox/WinInetRobloxApp/0.648.1.6480783",
        "Cookie: .ROBLOSECURITY=" . ROBLOSECURITY
    ],
	CURLOPT_CAINFO => __DIR__ . '/cacert.pem', // <--- SSL fix
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$response) {
    http_response_code(502);
    die("Failed to contact AssetDelivery v2.");
}

$data = json_decode($response, true);
$cdnUrl = $data['locations'][0]['location'] ?? null;

if (!$cdnUrl) {
    http_response_code(404);
    die("CDN location not found for asset. $response");
}
// Step 2: Download and decompress asset from CDN
$ch = curl_init($cdnUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_ENCODING => "", // auto-decompress
    CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_CAINFO => __DIR__ . '/cacert.pem', // <--- SSL fix
    CURLOPT_TIMEOUT => 10
]);

$raw = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($raw, 0, $headerSize);
$body = substr($raw, $headerSize);
$body = str_replace('class="Accessory"', 'class="Hat"', $body);
$body = str_replace('roblox.com', 'aftwld.xyz', $body);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: 'application/octet-stream';
curl_close($ch);

// Update MIME if you want, or keep original (processing won't change mime usually)
file_put_contents($assetFile, $body);
file_put_contents($metaFile, json_encode(['content_type' => $contentType]));

// Step 4: Serve processed content
header("Content-Type: $contentType");
echo $body;
exit;
?>
