<?php
header("Content-Type: application/json");

include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$assetId = isset($_GET['assetId']) ? intval($_GET['assetId']) : 0;
$startIndex = isset($_GET['startindex']) ? intval($_GET['startindex']) : 0;
$userid = getuserinfo($_COOKIE["_ROBLOSECURITY"])["UserId"] ?? -1;
$rqtype =  isset($_GET['rqtype']) ? intval($_GET['rqtype']) : null;

 function ownasset($asset) {
	 global $userid;
	 global $pdo;
	 $stmt = $pdo->prepare("
    SELECT *
    FROM assets 
    WHERE AssetID = :assetId
");
$stmt->execute(["assetId"=>$asset]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$info || $info["CreatorID"] !== $userid){ return false;}
return true;
 }

$maxRows = 10;
$stmt = $pdo->prepare("
    SELECT id, Posted_At, Author_Id, Text 
    FROM comments 
    WHERE AssetID = :assetId 
    ORDER BY Posted_At DESC 
    LIMIT :startIndex, :maxRows
");
$stmt->bindValue(':assetId', $assetId, PDO::PARAM_INT);
$stmt->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
$stmt->bindValue(':maxRows', $maxRows, PDO::PARAM_INT);
$stmt->execute();

$comments = [];


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $authorId = (int)$row['Author_Id'];
    $user = getuserinfo($authorId);

    $comments[] = [
        "Id" => (int)$row['id'],
        "PostedDate" => date("M j, Y | g:i A", $row['Posted_At']),
        "AuthorName" => $user['Username'] ?? 'Unknown',
        "AuthorId" => $authorId,
        "Text" => ($row['Text']),
        "ShowAuthorOwnsAsset" => ownasset($assetId) ?? false, 
        "AuthorThumbnail" => [
            "AssetId" => 0,
            "AssetHash" => null,
            "AssetTypeId" => 0,
            "Url" => "https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=" . $authorId ."&x=75&y=75",
            "IsFinal" => true
        ]
    ];
}

$response = [
    "IsUserModerator" => ownasset($assetId) ?? false,
    "Comments" => $comments,
    "MaxRows" => $maxRows
];

echo json_encode($response);
