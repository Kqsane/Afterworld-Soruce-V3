<?php
header("content-type: application/json");
$id = isset($_GET['userId']) ? intval($_GET['userId']) : null;

    $url = "https://devopstest1.aftwld.xyz/avatar-thumbnail-3d/?userId=" . $id;

    $json = [
        "Url" => $url,
        "Final" => true
    ];

    die(json_encode($json, JSON_UNESCAPED_SLASHES));
?>
