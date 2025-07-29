<?php
$aid = isset($_GET['aid']) ? htmlspecialchars($_GET['aid']) : '';
$wd = isset($_GET['wd']) ? intval($_GET['wd']) : 75;
$ht = isset($_GET['ht']) ? intval($_GET['ht']) : 75;

$wd = ($wd > 0) ? $wd : 75;
$ht = ($ht > 0) ? $ht : 75;

if ($aid !== '' && ctype_alnum($aid)) {
    $url = "https://thumbnails.roblox.com/v1/assets?assetIds={$aid}&returnPolicy=PlaceHolder&size={$wd}x{$ht}&format=Png&isCircular=false";
    $response = file_get_contents($url);
    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data['data'][0]['imageUrl'])) {
            $imageUrl = htmlspecialchars($data['data'][0]['imageUrl']);
            if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                header('Content-Type: image/png');
                readfile($imageUrl);
            } else {
                echo 'invalid image url';
            }
        } else {
            echo 'url not found in returned data';
        }
    } else {
        echo 'unable to fetch data';
    }
} else {
    header('Content-Type: image/jpeg');
    readfile('./notfound.jpg');
}
?>
