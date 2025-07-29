<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
header('Content-Type: application/json');
$input = json_decode(file_get_contents("php://input"), true);
$transactionTypes = [
1 => "purchase",
2 => "sale",
3 => "affiliatesale",
4 => "grouppayout"
];
$transactionType = $input["transactiontype"] ?? null;
$startIndex = intval($input["startindex"] ?? 0);
$pageSize = 10;
$userId = isset($_COOKIE['_ROBLOSECURITY']) ? getuserinfo($_COOKIE['_ROBLOSECURITY'])['UserId'] : null;
if(!array_flip($transactionTypes)[$transactionType]){
	exit;
}
if(empty($userId)){
	exit;
}

$stmt = $pdo->prepare("
    SELECT * FROM transactions
    WHERE userId = :userId
	AND reason = :reason
    ORDER BY robux DESC
    LIMIT :startIndex, :pageSize
");
$stmt->bindValue(":userId", $userId, PDO::PARAM_INT);
$stmt->bindValue(":startIndex", $startIndex, PDO::PARAM_INT);
$stmt->bindValue(":reason", array_flip($transactionTypes)[$transactionType], PDO::PARAM_INT);
$stmt->bindValue(":pageSize", $pageSize, PDO::PARAM_INT);
$stmt->execute();

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $assetstmt = $pdo->prepare("SELECT * FROM assets WHERE AssetID = :id");
    $assetstmt->execute(['id' => $row['assetId']]);
    $asset = $assetstmt->fetch(PDO::FETCH_ASSOC);
	if(!$asset){
		continue;
	}
$reasonCode = $row['reason'];
$memberinfo = null;

if ($reasonCode == 2) { // Sale, show buyer
    $buyerQuery = $pdo->prepare("SELECT userId FROM transactions WHERE assetId = :aid AND reason = 1 ORDER BY transactionDate DESC LIMIT 1");
    $buyerQuery->execute(['aid' => $row['assetId']]);
    $buyer = $buyerQuery->fetch(PDO::FETCH_ASSOC);

    if ($buyer) {
        $userstmt = $pdo->prepare("SELECT Username, UserId FROM users WHERE UserId = :id");
        $userstmt->execute(['id' => $buyer['userId']]);
        $memberinfo = $userstmt->fetch(PDO::FETCH_ASSOC);
    }
} else { // Purchase, show asset creator
    $creatorstmt = $pdo->prepare("SELECT * FROM users WHERE UserId = :id");
    $creatorstmt->execute(['id' => $asset['CreatorID']]);
    $memberinfo = $creatorstmt->fetch(PDO::FETCH_ASSOC);
}

    $isTix = $row['tix'] > 0;
    $currencyValue = $isTix ? $row['tix'] : $row['robux'];
    $currencyClass = $currencyValue >= 0 ? "positive" : "negative";

    $amountSpan = $isTix
        ? "<span class='tickets notranslate'>{$currencyValue}</span>"
        : "<span class='robux notranslate'>{$currencyValue}</span>";
	if($row['robux'] < 1 && $row['tix'] < 1){
		 $amountSpan = "<span class='notranslate'>{$currencyValue}</span>";
	}

    $data[] = json_encode([
        "Date" => date("m/d/Y", $row['transactionDate']),
        "Member" => $memberinfo['Username'],
        "Member_ID" => $memberinfo['UserId'],
        "MemberIsGroup" => "False",
        "Group_ID" => "",
        "Description" => $row["reason"],
        "Amount" => $amountSpan,
        "Amount_Class" => $currencyClass,
        "Item_Name" => htmlspecialchars($asset['Name']),
        "Item_Url" => "",
        "EventDate" => date("m/d/Y", $row['transactionDate'])
    ]);
}


$countStmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE userId = :userId");
$countStmt->execute([":userId" => $userId]);
$totalCount = $countStmt->fetchColumn();

$response = [
    "StartIndex" => $startIndex + $pageSize,
    "TotalCount" => $totalCount,
    "Data" => $data
];
echo json_encode(["d" => json_encode($response)]);
