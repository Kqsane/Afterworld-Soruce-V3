<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');

$userId = isset($_COOKIE['_ROBLOSECURITY']) ? getuserinfo($_COOKIE['_ROBLOSECURITY'])['UserId'] : null;
if (empty($userId)) {
	echo json_encode(['error' => 'unauthorized']);
	exit;
}

$summary = [
    "R_SaleOfGoods" => 0,
    "R_GroupPayouts" => 0,
    "R_affiliatesale" => 0,	
    "R_PendingSales" => 0,		
    "CurrencyPurchase" => 0,			
    "BCStipend" => 0,				
    "BCStipendBonus" => 0,					
    "PromotedPageConversionRevenue" => 0,						
    "GamePageConversionRevenue" => 0,							
    "R_Total" => 0									
];
$reasonMap = [			
1 => "CurrencyPurchase",
2 => "R_SaleOfGoods",
3 => "R_affiliatesale",
4 => "R_GroupPayouts"
];
// Fetch all transaction sums by reason
$stmt = $pdo->prepare("SELECT reason, SUM(robux) as total FROM transactions WHERE userId = :userId    GROUP BY reason");
$stmt->execute(['userId' => $userId]);

$total = 0;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$reasonId = (int)$row['reason'];
	$amount = (int)$row['total'];
	if($reasonId !== 1){
		 $total += $amount;
	}else{
		$total -= $amount;
	}
	if (isset($reasonMap[$reasonId])) {
		$key = $reasonMap[$reasonId];
		$summary[$key] += $amount;
	}
}

$summary["R_Total"] = $total;

echo json_encode(["d"=> json_encode($summary)]);