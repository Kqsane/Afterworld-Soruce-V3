<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

$id = isset($_GET['gid']) ? intval($_GET['gid']) : null;
$targetW = isset($_GET['x']) ? intval($_GET['x']) : null;
$targetH = isset($_GET['y']) ? intval($_GET['y']) : null;

$basePath  = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/GroupIcons/';
$imagePath = "{$basePath}{$id}.png";
if (!file_exists($imagePath)) {
    $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/Thumbs/placeholder.png';
}

// If no width/height, just serve the original file
if ($targetW === null || $targetH === null) {
    header('Content-Type: image/png');
    header('Content-Length: ' . filesize($imagePath));
    readfile($imagePath);
    exit;
}

// Load source
$src = imagecreatefrompng($imagePath);
if (!$src) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}

// Get original dimensions
$srcW = imagesx($src);
$srcH = imagesy($src);

// Create a new true‐color image at the requested size
$dst = imagecreatetruecolor($targetW, $targetH);

// Preserve transparency
imagealphablending($dst, false);
imagesavealpha($dst, true);

// Check if we're doing a headshot crop
$mode = isset($_GET['mode']) ? $_GET['mode'] : null;

if ($mode === 'headshot') {
    // Desired crop size
    $cropW = 200;
    $cropH = 200;

    // Allow negative offsets
    $offsetX = 75;
    $offsetY = -25;

    // Calculate source crop start
    $srcX = max(0, $offsetX);
    $srcY = max(0, $offsetY);

    // Calculate where to place the cropped area in the destination image
    $dstX = max(0, -$offsetX);
    $dstY = max(0, -$offsetY);

    // Calculate width/height of the copyable region
    $copyW = $cropW - abs($dstX);
    $copyH = $cropH - abs($dstY);

    // Prevent overflow
    $copyW = min($copyW, $srcW - $srcX);
    $copyH = min($copyH, $srcH - $srcY);

    // Fill destination with transparent background
    imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));

    imagecopyresampled(
        $dst,         // destination image
        $src,         // source image
        $dstX, $dstY, // where to place in dest
        $srcX, $srcY, // where to start in source
        $copyW, $copyH, // width and height of what to copy
        $copyW, $copyH  // same scaling (1:1)
    );
} else {
    // Full image resample (default)
    imagecopyresampled(
        $dst, $src,
        0, 0,
        0, 0,
        $targetW, $targetH,
        $srcW, $srcH
    );
}

// Output as PNG
header('Content-Type: image/png');
imagepng($dst);

// Cleanup
imagedestroy($src);
imagedestroy($dst);
exit;
