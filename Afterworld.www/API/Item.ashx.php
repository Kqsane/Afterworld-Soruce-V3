<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
header("content-type: application/json");
// functions
function doesUserOwnAsset($id, $userId = null){
	global $pdo;
        $stmt = $pdo->prepare("SELECT userId FROM inventory WHERE userId = ? and assetId = ?");
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() >= 1){
            return true;
        }else{
           return false;
		}
}

$json = [];

if ($_GET["rqtype"] !== "purchase"){
	die();
}
$userInfo = isset($_COOKIE['_ROBLOSECURITY']) ? getuserinfo($_COOKIE['_ROBLOSECURITY']) : null;
if (!$userInfo){
	http_response_code(500);
	$json["showDivID"] = "TransactionFailureView";
	$json["title"] = "Error";
	$json["errorMsg"] = "An error occured while processing this transaction. No ROBUX have been removed from your account. Please try again later.";
	die(json_encode($json));
}
if ($userInfo["IsBanned"] == 1 || $userInfo["activated"] == 0){
	http_response_code(500);
	$json["showDivID"] = "TransactionFailureView";
	$json["title"] = "Error";
	$json["errorMsg"] = "An error occured while processing this transaction. No ROBUX have been removed from your account. Please try again later.";
	die(json_encode($json));
}
$assetId = intval($_GET["productID"]) ?? 0;
$expectedPrice = (isset($_GET["expectedPrice"])) ? intval($_GET["expectedPrice"]) : die(json_encode($json));
$expectedSeller = (isset($_GET["expectedSellerID"])) ? intval($_GET["expectedSellerID"]) : die(json_encode($json));
$locationType = 0;
$locationId = 0;

$FindGames = $pdo->prepare('select * from assets where AssetID = :gid');
$FindGames->execute(['gid' =>(int) $assetId]);
$assetInfo = $FindGames->fetch(PDO::FETCH_ASSOC);

// No such asset linked to the productid, die..
if (!$assetInfo){
	http_response_code(500);
	$json["showDivID"] = "TransactionFailureView";
	$json["title"] = "Error";
	$json["errorMsg"] = "An error occured while processing this transaction. No ROBUX have been removed from your account. Please try again later.";
	die(json_encode($json));
}


if ($expectedSeller !== $assetInfo["CreatorID"]){
	http_response_code(500);
	$json["showDivID"] = "TransactionFailureView";
	$json["title"] = "Error";
	$json["errorMsg"] = "An error occured while processing this transaction. No ROBUX have been removed from your account. Please try again later.";
	die(json_encode($json));
}

	
if (doesUserOwnAsset($assetId, $userInfo['UserId'])){
	http_response_code(500);
	$json["showDivID"] = "TransactionFailureView";
	$json["title"] = "Error";
	$json["errorMsg"] = "You already own this item.";
	die(json_encode($json));
}

// We want to make sure that the price is the same as the expected so that we don't scam the user
if ($assetInfo["RobuxPrice"] !== $expectedPrice){
	http_response_code(500);
	$json["showDivID"] = "PriceChangedView";
	$json["expectedPrice"] = $expectedPrice;
	$json["currentPrice"] = $assetInfo["RobuxPrice"];
	$json["balanceAfterSale"] = $userInfo['Robux'] - $assetInfo["RobuxPrice"];
	die(json_encode($json));
}

if ($userInfo['Robux'] < $expectedPrice){
	http_response_code(500);
	$json["showDivID"] = "InsufficientFundsView";
	$json["currentCurrency"] = 1;
	$json["shortfallPrice"] = $expectedPrice - $userInfo['Robux'];
	die(json_encode($json));
}

try {
	$pdo->beginTransaction();
	$purchaseTime = time();
	
	$stmt = $pdo->prepare("UPDATE users SET Robux = Robux - :robux WHERE UserId = :userId");
	$stmt->bindParam(':robux', $expectedPrice);
	$stmt->bindParam(':userId', $userInfo['UserId']);
	$stmt->execute();
	
	$stmt = $pdo->prepare("INSERT INTO transactions (userId, assetId, reason, locationType, locationPlaceId, robux, tix, transactionDate) VALUES (:userId, :assetId, 1, :locationType, :locationPlaceId, :robux, 0, UNIX_TIMESTAMP())");
	$stmt->bindParam(':userId', $userInfo['UserId']);
	$stmt->bindParam(':assetId', $assetId, PDO::PARAM_INT);
	$stmt->bindParam(':locationType', $locationType);
	$stmt->bindParam(':locationPlaceId', $locationId);
	$stmt->bindParam(':robux', $expectedPrice);
	$stmt->execute();
	
	$stmt = $pdo->prepare("INSERT INTO inventory (userId, assetId, assetType, purchasedWhen) VALUES (:userId, :assetId, :assetType, :date)");
	$stmt->bindParam(':userId', $userInfo['UserId']);
	$stmt->bindParam(':assetId', $assetId, PDO::PARAM_INT);
	$stmt->bindParam(':assetType', $assetInfo["AssetType"]);
	$stmt->bindParam(':date', $purchaseTime);
	$stmt->execute();
	
	$stmt = $pdo->prepare("INSERT INTO transactions (userId, assetId, reason, locationType, locationPlaceId, robux, tix, transactionDate) VALUES (:sellerId, :assetId, 2, :locationType, :locationPlaceId, :robux, 0, UNIX_TIMESTAMP())");
	$stmt->bindParam(':sellerId', $assetInfo["CreatorID"]);
	$stmt->bindParam(':assetId', $assetId, PDO::PARAM_INT);
	$stmt->bindParam(':locationType', $locationType);
	$stmt->bindParam(':locationPlaceId', $locationId);
	$stmt->bindValue(':robux', $expectedPrice * 0.70);
	$stmt->execute();
	
	$stmt = $pdo->prepare("UPDATE users SET Robux = Robux + :robux WHERE UserId = :sellerId");
	$stmt->bindValue(':robux', $expectedPrice * 0.70);
	$stmt->bindParam(':sellerId', $assetInfo["CreatorID"]);
	$stmt->execute();
	
	$stmt = $pdo->prepare("UPDATE users SET Robux = Robux + :robux WHERE UserId = 1");
	$stmt->bindValue(':robux', $expectedPrice * 0.30);
	$stmt->execute();

	$stmt = $pdo->prepare("UPDATE assets SET Sales = Sales + 1 WHERE AssetID = :Asset");
	$stmt->bindValue(':Asset', $assetId);
	$stmt->execute();
	$pdo->commit();
	
	$json["TransactionVerb"] = "purchased";
	$json["AssetName"] = $assetInfo["Name"];
	$json["AssetType"] = $asset_types[$assetInfo["AssetType"]];
	$json["SellerName"] = getuserinfo(intval($assetInfo["CreatorID"]))["Username"];
	$json["Price"] = $assetInfo["RobuxPrice"];
}
catch (Exception $e){
	$pdo->rollback();
	http_response_code(500);
	$json["showDivID"] = "TransactionFailureView";
	$json["errorMsg"] = json_encode($e);
}

die(json_encode($json));
?>