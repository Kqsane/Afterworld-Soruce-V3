<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=aftwld", "root", "AFTWLD$92EUKSQB39321KS", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit("Database connection error.");
}

use TrashBlx\Core\SoapUtils;
$soapUtils = new SoapUtils();

$id = isset($_GET['assetId']) ? intval($_GET['assetId']) : null;
$targetW = isset($_GET['width']) ? intval($_GET['width']) : null;
$targetH = isset($_GET['height']) ? intval($_GET['height']) : null;

$basePath = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/RenderedAssets/';
$imagePath = "{$basePath}{$id}.png";
$fallbackPath = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/placeholder.png';

if (!$id) {
    http_response_code(400);
    exit("No asset ID provided.");
}

$stmt = $pdo->prepare("SELECT AssetID, AssetType FROM assets WHERE AssetID = :id");
$stmt->execute(['id' => $id]);
$asset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asset) {
    $imagePath = $fallbackPath;
} elseif ($asset['AssetType'] == 1) {
    header("Location: /asset/?id=$id");
    exit;
} elseif ($asset['AssetType'] == 2) {
    $xmlContent = @file_get_contents("https://devopstest1.aftwld.xyz/asset?id=$id");

    if ($xmlContent) {
        $dom = new DOMDocument();
        @$dom->loadXML($xmlContent);
        $contentTags = $dom->getElementsByTagName("Content");

        if ($contentTags->length > 0) {
            $imgFile = trim($contentTags[0]->nodeValue);

            if (str_contains($imgFile, "rbxassetid://")) {
                $assetId = intval(str_replace("rbxassetid://", "", $imgFile));
                $imgFile = "https://devopstest1.aftwld.xyz/asset?id=" . $assetId;
            } elseif (str_contains($imgFile, "roblox.com")) {
                $imgFile = str_replace("roblox.com", "aftwld.xyz", $imgFile);
            }

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $imgFile,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CAINFO => $_SERVER['DOCUMENT_ROOT'] . '/asset/cacert.pem',
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => 10,
            ]);
            $data = curl_exec($ch);
            curl_close($ch);

            if ($data !== false) {
                $overlay = @imagecreatefromstring($data);
                $background = @imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . "/Thumbs/tshirt.png");

                if ($overlay && $background) {
                    $canvas = imagecreatetruecolor(420, 420);
                    imagealphablending($canvas, false);
                    imagesavealpha($canvas, true);
                    $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                    imagefill($canvas, 0, 0, $transparent);

                    imagecopyresampled($canvas, $background, 0, 0, 0, 0, 420, 420, imagesx($background), imagesy($background));

                    $boxX = $boxY = 84;
                    $boxW = $boxH = 420 - 2 * $boxX;
                    imagecopyresampled($canvas, $overlay, $boxX, $boxY, 0, 0, $boxW, $boxH, imagesx($overlay), imagesy($overlay));

                    header("Content-Type: image/png");
                    if (!$targetW || !$targetH) {
                        imagepng($canvas);
                    } else {
                        $dst = imagecreatetruecolor($targetW, $targetH);
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                        imagecopyresampled($dst, $canvas, 0, 0, 0, 0, $targetW, $targetH, 420, 420);
                        imagepng($dst);
                        imagedestroy($dst);
                    }

                    imagedestroy($canvas);
                    imagedestroy($overlay);
                    imagedestroy($background);
                    exit;
                }
            }
        }
    }
}

if (!file_exists($imagePath) || !is_readable($imagePath)) {
    $render = $soapUtils->renderAsset($id, $asset['AssetType'] ?? 0);
    if ($render) {
        $decrypted = base64_decode($render[0]);
        file_put_contents($imagePath, $decrypted);
    }
}

if (!file_exists($imagePath) || !is_readable($imagePath)) {
    $imagePath = $fallbackPath;
}

$src = @imagecreatefrompng($imagePath);
if (!$src) {
    $src = @imagecreatefrompng($fallbackPath);
}

if (!$targetW || !$targetH) {
    header("Content-Type: image/png");
    imagepng($src);
    imagedestroy($src);
    exit;
}

$dst = imagecreatetruecolor($targetW, $targetH);
imagealphablending($dst, false);
imagesavealpha($dst, true);
imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetW, $targetH, imagesx($src), imagesy($src));

header("Content-Type: image/png");
imagepng($dst);
imagedestroy($src);
imagedestroy($dst);
exit;