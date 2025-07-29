<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';

$SortFilter = $_GET["SortFilter"] ?? 1;
$Keyword = $_GET["Keyword"] ?? "";
$StartRows = $_GET["StartRows"] ?? 0;
$MaxRows = $_GET["MaxRows"] ?? 14;
$Genre = $_GET["GenreID"] ?? 1;
$stmt = $pdo->query('SELECT * FROM assets WHERE AssetType = 9 order by players DESC')->fetchAll();
if ($Keyword !== ""){
    if (trim($Keyword) == ""){
        die;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetType = 9 AND isSubPlace = 0 AND isPrivate = 0 AND Name LIKE :keyword LIMIT :startRow,:maxRows");
    $Keyword = "%$Keyword%";
    $stmt->bindParam(':keyword', $Keyword, PDO::PARAM_STR);
    $stmt->bindParam(":startRow", $StartRows, PDO::PARAM_INT);
    $stmt->bindParam(":maxRows", $MaxRows, PDO::PARAM_INT);
    $stmt->execute();
}else{
    switch ($SortFilter)
    {
		case 0:
            $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetType = 9 AND isSubPlace = 0 AND isPrivate = 0 ORDER BY Visits DESC LIMIT :startRow,:maxRows");
            $stmt->bindParam(":startRow", $StartRows, PDO::PARAM_INT);
            $stmt->bindParam(":maxRows", $MaxRows, PDO::PARAM_INT);
            $stmt->execute();
            break;
        case 1:
            $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetType = 9 AND isSubPlace = 0 AND isPrivate = 0 ORDER BY players DESC LIMIT :startRow,:maxRows");
            $stmt->bindParam(":startRow", $StartRows, PDO::PARAM_INT);
            $stmt->bindParam(":maxRows", $MaxRows, PDO::PARAM_INT);
            $stmt->execute();
            break;

        default:
           
            $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetType = 9 AND isSubPlace = 0 AND isPrivate = 0 ORDER BY players DESC LIMIT :startRow,:maxRows");
            $stmt->bindParam(":startRow", $StartRows, PDO::PARAM_INT);
            $stmt->bindParam(":maxRows", $MaxRows, PDO::PARAM_INT);
            $stmt->execute();
            
            break;
    }
}
	
foreach($stmt as $game) {
	if(!$game['isSubPlace']){
		$stmt = $pdo->prepare('SELECT * FROM users where UserId = :uid');
		$stmt->execute(['uid' => $game['CreatorID']]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
echo '<div class="game-item">
<div class="always-shown">
    <a class="game-item-anchor" rel="external" href="https://devopstest1.aftwld.xyz/games/'.$game['AssetID'].'/'.ucwords(str_replace(" ", "-", $game['Name'])).'">
        <span class=""><img class="game-item-image" src="https://devopstest1.aftwld.xyz/Thumbs/Asset.ashx?assetId='.$game["AssetID"].'"/></span>
        <div class="game-name notranslate">
            '.htmlspecialchars($game['Name']).'  
        </div>
    </a>
    <span class="player-count deemphasized notranslate">
        '.intval($game['players']).' players online - <b style="color: green;">'.$game['ClientYear'].'</b>
    </span>
	<div>
	</div>
    <span class="online-player roblox-player-text" style="float: left"></span>

    <div class="show-on-hover-only deemphasized hidden">
        <div class="creator-name notranslate">
            by <a href="/User.aspx?id='.$game['CreatorID'].'">'.htmlspecialchars($user['Username']).'</a>
        </div>


    </div>
</div>

<div class="hover-shown deemphasized ">
    <div class="snap-bottom snap-left">
        <div>
            played <span class="roblox-plays-count notranslate">'.intval($game['Visits']).'</span> times
        </div>
    
            <div class=" game-thumbs-up-down notranslate">
                <span class="tiny-thumbs-up"/>0 &nbsp;  |  &nbsp; 0<span class="tiny-thumbs-down"/>
            </div>
    </div>

    <div class="snap-bottom snap-right">
    </div>
</div>
</div>';
}
}
?>