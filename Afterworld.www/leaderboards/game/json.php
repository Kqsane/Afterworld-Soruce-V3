<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header("content-type: application/json");
$json = [];
	
$universeId = (int)$_GET["distributorTargetId"];
$timeFilter = (int)$_GET["timeFilter"];
$targetType = (int)$_GET["targetType"];
	

try {
	if (!isset($_GET["startIndex"]) || !isset($_GET["max"]))
		throw new Exception("Invalid request");
	
	$startIndex = (int)$_GET["startIndex"];
	$rank = $startIndex;
	$max = (int)$_GET["max"];
	
	if ($max > 20)
		throw new Exception("Invalid request");
	
	
	switch ($timeFilter)
	{
		case 0:
			$currentDate = date("Y-m-d");
			$stmt = $pdo->prepare("SELECT userId, SUM(points) AS total_points FROM pointshistory WHERE DATE(FROM_UNIXTIME(time)) = :currentDate AND universeId = :universeId GROUP BY userId ORDER BY total_points DESC LIMIT :startIndex, :max");
			$stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
			$stmt->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
			$stmt->bindParam(':max', $max, PDO::PARAM_INT);
			$stmt->bindParam(':universeId', $universeId);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			foreach ($result as $row){
				$rank++;
				
				$userJson = [ 
					'Rank' => $rank,
					'DisplayRank' => number_format($rank),
					'FullRank' => null,
					'WasRankTruncated' => false,
					'Name' => getuserinfo($row["userId"])['Username'] ?? "Content Deleted",
					'UserId' => $row["userId"],
					'TargetId' => $row["userId"],
					'ProfileUri' => "https://devopstest1.aftwld.xyz/Users.aspx?ID=".$row["userId"],
					'ClanName' => null, 
					'ClanUri' => null,
					'Points' => $row["total_points"],
					'DisplayPoints' => number_format($row["total_points"]),
					'FullPoints' => null,
					'WasPointsTruncated' => false,
					'ClanEmblemID' => 0,
					'UserImageUri' => "https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=" . $row["userId"] ."&x=75&y=75",
					'UserImageFinal' => true,
					'ClanImageUri' => "",
					'ClanImageFinal' => false
				];
				
				$json[] = $userJson;
			};
		break;
		
		case 1:
			$secondsPerDay = 24 * 60 * 60; // 24 hours * 60 minutes * 60 seconds
			$currentTimestamp = time();
			$currentDayOfWeek = date('w', $currentTimestamp);
			$daysSinceMonday = ($currentDayOfWeek + 6) % 7; // Number of days to subtract to reach Monday
			$startOfWeekTimestamp = $currentTimestamp - ($daysSinceMonday * $secondsPerDay);
			$startOfWeekDate = date('Y-m-d', $startOfWeekTimestamp);
			$stmt = $pdo->prepare("SELECT userId, SUM(points) AS total_points FROM pointshistory WHERE DATE(FROM_UNIXTIME(time)) >= :startOfWeekDate AND universeId = :universeId GROUP BY userId ORDER BY total_points DESC LIMIT :startIndex, :max");
			$stmt->bindParam(':startOfWeekDate', $startOfWeekDate, PDO::PARAM_STR);
			$stmt->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
			$stmt->bindParam(':max', $max, PDO::PARAM_INT);
			$stmt->bindParam(':universeId', $universeId);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			foreach ($result as $row){
				$rank++;
				
				$userJson = [ 
					'Rank' => $rank,
					'DisplayRank' => number_format($rank),
					'FullRank' => null,
					'WasRankTruncated' => false,
					'Name' => getuserinfo($row["userId"])['Username'] ?? "Content Deleted",
					'UserId' => $row["userId"],
					'TargetId' => $row["userId"],
					'ProfileUri' => "https://devopstest1.aftwld.xyz/Users.aspx?ID=".$row["userId"],
					'ClanName' => null, 
					'ClanUri' => null,
					'Points' => $row["total_points"],
					'DisplayPoints' => number_format($row["total_points"]),
					'FullPoints' => null,
					'WasPointsTruncated' => false,
					'ClanEmblemID' => 0,
					'UserImageUri' => "https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=" . $row["userId"] ."&x=75&y=75",
					'UserImageFinal' => true,
					'ClanImageUri' => "",
					'ClanImageFinal' => false
				];
					
				$json[] = $userJson;
			};
		break;
			
		case 2:
			$currentTimestamp = time();
			$startOfMonthTimestamp = strtotime('first day of this month 00:00:00', $currentTimestamp);
			$startOfMonthDate = date('Y-m-d', $startOfMonthTimestamp);
			$stmt = $pdo->prepare("SELECT userId, SUM(points) AS total_points FROM pointshistory WHERE DATE(FROM_UNIXTIME(time)) >= :startOfMonthDate AND universeId = :universeId GROUP BY userId ORDER BY total_points DESC LIMIT :startIndex, :max");
			$stmt->bindParam(':startOfMonthDate', $startOfMonthDate, PDO::PARAM_STR);
			$stmt->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
			$stmt->bindParam(':max', $max, PDO::PARAM_INT);
			$stmt->bindParam(':universeId', $universeId);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			foreach ($result as $row){
				$rank++;
				
				$userJson = [ 
					'Rank' => $rank,
					'DisplayRank' => number_format($rank),
					'FullRank' => null,
					'WasRankTruncated' => false,
					'Name' => getuserinfo($row["userId"])['Username'] ?? "Content Deleted",
					'UserId' => $row["userId"],
					'TargetId' => $row["userId"],
					'ProfileUri' => "https://devopstest1.aftwld.xyz/Users.aspx?ID=".$row["userId"],
					'ClanName' => null, 
					'ClanUri' => null,
					'Points' => $row["total_points"],
					'DisplayPoints' => number_format($row["total_points"]),
					'FullPoints' => null,
					'WasPointsTruncated' => false,
					'ClanEmblemID' => 0,
					'UserImageUri' => "https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=" . $row["userId"] ."&x=75&y=75",
					'UserImageFinal' => true,
					'ClanImageUri' => "",
					'ClanImageFinal' => false
				];
					
				$json[] = $userJson;
			};
		break;
				
		case 3:
			$stmt = $pdo->prepare("SELECT * FROM points WHERE universeId = :universeId ORDER BY allTimePoints DESC LIMIT :startIndex, :max");
			$stmt->bindParam(':startIndex', $startIndex, PDO::PARAM_INT);
			$stmt->bindParam(':max', $max, PDO::PARAM_INT);
			$stmt->bindParam(':universeId', $universeId);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row){
				$rank++;
				
				$userJson = [ 
					'Rank' => $rank,
					'DisplayRank' => number_format($rank),
					'FullRank' => null,
					'WasRankTruncated' => false,
					'Name' => getuserinfo($row["userId"])['Username'] ?? "Content Deleted",
					'UserId' => $row["userId"],
					'TargetId' => $row["userId"],
					'ProfileUri' => "https://devopstest1.aftwld.xyz/Users.aspx?ID=".$row["userId"],
					'ClanName' => null, 
					'ClanUri' => null,
					'Points' => $row["allTimePoints"],
					'DisplayPoints' => number_format($row["allTimePoints"]),
					'FullPoints' => null,
					'WasPointsTruncated' => false,
					'ClanEmblemID' => 0,
					'UserImageUri' => "https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=" . $row["userId"] ."&x=50&y=50",
					'UserImageFinal' => true,
					'ClanImageUri' => "",
					'ClanImageFinal' => false
				];
					
			$json[] = $userJson;
		}	
	break;
	}		
}
catch (Exception $e) {
	exit($e);
}
	
die(json_encode($json));
?>