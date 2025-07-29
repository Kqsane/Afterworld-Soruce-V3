<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header("Content-Type: application/json");
file_put_contents(__DIR__."/debug.txt", print_r($_GET, true));
function removeEverythingBefore($in, $before) {
    $pos = strpos($in, $before);
    return $pos !== FALSE
        ? substr($in, $pos + strlen($before), strlen($in))
        : "";
}

function removeEverythingAfter($in, $before) {
    $pos = strpos($in, $before);
    return $pos !== FALSE
        ? substr($in, $pos - strlen($before), strlen($in))
        : "";
}
$requiredParams = ["placeId", "scope", "type"];
$missingParams = [];

foreach ($requiredParams as $param) {
    if (!isset($_GET[$param])) {
        $missingParams[] = $param;
    }
}

if (!empty($missingParams)) {
    exit(json_encode(["error" => "Missing parameters: " . implode(", ", $missingParams)]));
}
		$values=[];
		$input = file_get_contents('php://input');
		$qkeys = explode("&",substr($input, 1));
		$tempTable = array();
		foreach($qkeys as &$val){
			$after = substr($val, 0, strpos($val, "="));
			$tempTable[$after]=removeEverythingBefore($val,"=");
		}
		$qkeys = $tempTable;
		$tempTable = null;
		
		if(isset($qkeys['qkeys[0].key'])&&isset($qkeys['qkeys[0].target'])){
			$key = (string)urldecode($qkeys['qkeys[0].key']);
			$pid = (int)$_GET["placeId"];
			$scope = (string)urldecode($_GET["scope"]);
			$type = (string)urldecode($_GET["type"]);
			$target = (string)urldecode($qkeys['qkeys[0].target']);
			
			$stmt = $pdo->prepare("SELECT UniverseID FROM assets WHERE AssetID = :assetId AND AssetType = 9");
			$stmt->bindParam(':assetId', $pid);
			$stmt->execute();
			$pid = $stmt->fetchColumn();
			
			$stmt = $pdo->prepare("SELECT * FROM `datastores` WHERE `universeId`=:pid AND `scope`=:scope AND `type`=:type AND `key`=:key AND `target`=:target");
			$stmt->bindParam(':key', $key, PDO::PARAM_STR); 
			$stmt->bindParam(':pid', $pid, PDO::PARAM_INT); 
			$stmt->bindParam(':scope', $scope, PDO::PARAM_STR); 
			$stmt->bindParam(':type', $type, PDO::PARAM_STR); 
			$stmt->bindParam(':target', $target, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as &$data){
				array_push($values,array("Value"=>$data["value"],"Scope"=>$data["scope"],"Key"=>$data["key"],"Target"=>$data["target"]));
			}
			exit(json_encode(["data"=>$values], JSON_NUMERIC_CHECK));
		}
?>