<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

header('Content-type:image/png');

$errimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/not-approved.png");
$penimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/pending.png");
$default = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/img/DefaultPlaceIcon.jpg");


$request = ($_GET['request'] ?? die(json_encode(["message" => "Unable to process request."])));
$id = (int)($_GET['id'] ?? die(json_encode(["message" => "Cannot process request at this time."])));

function fetchAsset($pdo, $query, $id, $requestType)
{
    $AssetFetch = $pdo->prepare($query);
    $AssetFetch->execute([':id' => $id, ':request' => $requestType]);
    return $AssetFetch->fetch(PDO::FETCH_ASSOC);
}

function displayImage($imagePath, $fallbackImage)
{
    echo file_exists($imagePath) ? file_get_contents($imagePath) : $fallbackImage;
}

switch ($request) {
    case "place":
        $query = "SELECT * FROM templates WHERE approved = '1' AND id = :id AND itemtype = :request";
        $assetResults = fetchAsset($pdo, $query, $id, $request);
    
        if ($assetResults) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/Tools/template/" . $id . ".jpg";
    
            if (file_exists($imagePath)) {
                echo file_get_contents($imagePath);
            } else {
                echo $penimg;
            }
        } else {
            echo $errimg;
        }
        break;
    

        case "game":
            echo $default;
            break;
        

    case "model":
        $query = "SELECT * FROM asset WHERE approved = '1' AND id = :id AND itemtype = :request";
        $modelResults = fetchAsset($pdo, $query, $id, $request);

        if ($modelResults) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/Tools/ModelUserCreated/" . $id . ".png";
            displayImage($imagePath, $penimg);
        } else {
            echo $errimg;
        }
        break;

    default:
        echo $errimg;
        break;
}
?>
