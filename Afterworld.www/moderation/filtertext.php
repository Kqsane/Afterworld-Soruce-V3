<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/miscfunctions.php';
header("Content-Type: application/json");
$swearWords = loadSwearWords($_SERVER['DOCUMENT_ROOT'] . '/config/includes/swearwords.txt');
if (isset($_POST['text'])) {
    $text = $_POST['text'];

    $textog = replaceSwearWords($text, $swearWords);

    $return = json_encode([
        "success" => true,
        "data" => [
            "white" => $text,
            "black" => $textog
        ]
    ], JSON_UNESCAPED_SLASHES);

    echo $return;
}