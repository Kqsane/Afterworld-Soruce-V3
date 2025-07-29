<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    header("Location: /");
    exit;
}

$user = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if (!$user || !isset($user['UserId'])) {
    header("Location: /");
    exit;
}

$userId = (int)$user['UserId'];

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$assetTypeTabs = [
    17 => "Heads",
    18 => "Faces",
    19 => "Gear",
    8 => "Hats",
    2 => "T-Shirts",
    11 => "Shirts",
    12 => "Pants",
    7 => "Decals",
    1 => "Images",
    20 => "Models",
    4 => "Meshes",
    21 => "Plugins",
    24 => "Animations",
    9 => "Places",
    10 => "Game Passes",
    3 => "Audio",
    25 => "Badges",
    26 => "Left Arms",
    27 => "Right Arms",
    28 => "Left Legs",
    29 => "Right Legs",
    30 => "Torsos",
    31 => "Packages"
];

$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$itemsPerPage = 18;

$stmt = $pdo->prepare("SELECT assetId, assetType FROM inventory WHERE userId = :userId ORDER BY assetType, purchasedWhen DESC");
$stmt->execute(['userId' => $userId]);
$inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

$assetsByType = [];
foreach ($inventory as $item) {
    $assetsByType[$item['assetType']][] = $item['assetId'];
}

$selectedCat = isset($_GET['cat']) && is_numeric($_GET['cat']) ? (int)$_GET['cat'] : 8;
if (!isset($assetTypeTabs[$selectedCat])) {
    $selectedCat = 8;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true"/>
	<meta http-equiv="Content-Language" content="en-us"/>
	<title>My Inventory - AFTERWORLD</title>
	<link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css"/>
	<link rel="stylesheet" href="/CSS/Base/CSS/page___6d7bcbdfd9dfa4d697c4e627e71f4fc1_m.css"/>
	<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico"/>
	<meta name="author" content="ROBLOX Corporation"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta id="ctl00_metakeywords" name="keywords" content="free games, online games, building games, virtual worlds, free mmo, gaming cloud, physics engine"/>
</head>
<body class="">
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
	<div id="navContent" class="nav-content">
		<div class="nav-content-inner">
			<div id="MasterContainer">
				<noscript>
					<div class="SystemAlert">
						<div class="SystemAlertText">Please enable Javascript to use all the features on this site.</div>
					</div>
				</noscript>
				<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
				<div id="BodyWrapper">
					<div id="RepositionBody">
						<div id="Body" style="width:970px; padding: 10px;">
							<div id="UserContainer">
								<div id="UserAssetsPane" style="border-top: 1px solid #ccc;">
									<div id="ctl00_cphRoblox_rbxUserAssetsPane_upUserAssetsPane">
										<h2 class="title" style="width:970px; display:block;"><span>My Inventory</span></h2>
										<div id="UserAssets">
											<div id="AssetsMenu" class="divider-right">
												<?php foreach ($assetTypeTabs as $typeId => $typeName): ?>
													<div id="AssetCategorySelectorPanel_<?= $typeId ?>" class="verticaltab<?= ($typeId === $selectedCat ? " selected" : "") ?>">
														<a href="?page=1&cat=<?= $typeId ?>"><?= htmlspecialchars($typeName) ?></a>
													</div>
												<?php endforeach; ?>
											</div>
											<div id="AssetsContent">
												<?php
												$cat = $selectedCat;
												if (!isset($assetsByType[$cat]) || count($assetsByType[$cat]) === 0):
												?>
													<div id="AssetContent_<?= $cat ?>">
														<p>No items in this category.</p>
													</div>
												<?php else: ?>
													<?php
													$assetIds = $assetsByType[$cat];
													$totalItems = count($assetIds);
													$totalPages = (int)ceil($totalItems / $itemsPerPage);
													if ($page > $totalPages) $page = $totalPages;
													if ($page < 1) $page = 1;
													$offset = ($page - 1) * $itemsPerPage;
													$pageAssetIds = array_slice($assetIds, $offset, $itemsPerPage);
													$placeholders = implode(',', array_fill(0, count($pageAssetIds), '?'));
													$stmt2 = $pdo->prepare("SELECT AssetID, Name, Limited FROM assets WHERE AssetID IN ($placeholders)");
													$stmt2->execute($pageAssetIds);
													$assetsInfoRaw = $stmt2->fetchAll(PDO::FETCH_ASSOC);
													$assetsInfo = [];
													foreach ($assetsInfoRaw as $asset) {
														$assetsInfo[$asset['AssetID']] = $asset;
													}
													?>
													<div id="AssetContent_<?= $cat ?>">
														<table cellspacing="0" border="0">
															<tbody>
															<?php
															for ($row = 0; $row < 3; $row++):
																echo "<tr>";
																for ($col = 0; $col < 6; $col++):
																	$index = $row * 6 + $col;
																	if (!isset($pageAssetIds[$index])) {
																		echo "<td></td>";
																		continue;
																	}
																	$assetId = $pageAssetIds[$index];
																	if (!isset($assetsInfo[$assetId])) {
																		echo "<td></td>";
																		continue;
																	}
																	$asset = $assetsInfo[$assetId];
																	$name = htmlspecialchars($asset['Name']);
																	$assetUrlName = str_replace(' ', '-', $name);
																	$limited = (int)$asset['Limited'];
																	$thumbUrl = "/Thumbs/Asset.ashx?assetId={$assetId}&width=100&height=100";
															?>
																	<td class="Asset" style="padding:5px;">
																		<div>
																			<div class="AssetThumbnail">
																				<a title="<?= $name ?>" href="/<?= $assetUrlName ?>-item?id=<?= $assetId ?>" style="display:inline-block; height:110px; width:110px; cursor:pointer;">
																					<img src="<?= $thumbUrl ?>" alt="<?= $name ?>" height="110" width="110" border="0" onerror="this.src='/images/placeholder.png';" />
																				</a>
																				<?php if ($limited === 1): ?>
																					<div style="position:relative;left:-21px;top:-19px;">
																						<img src="/images/assetIcons/limited.png" alt="Limited" />
																					</div>
																				<?php elseif ($limited === 2): ?>
																					<div style="position:relative;left:-12px;top:-19px;">
																						<img src="/images/assetIcons/limitedunique.png" alt="Limited Unique" />
																					</div>
																				<?php endif; ?>
																			</div>
																		</div>
																		<div class="AssetDetails" style="width:110px;">
																			<div class="AssetName" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
																				<a href="/<?= $assetUrlName ?>-item?id=<?= $assetId ?>"><?= $name ?></a>
																			</div>
																		</div>
																	</td>
															<?php
																endfor;
																echo "</tr>";
															endfor;
															?>
															</tbody>
														</table>
														<div class="FooterPager" style="width: 780px; margin-top:10px;">
															<?php
															$prevDisabled = $page <= 1 ? "disabled" : "";
															$nextDisabled = $page >= $totalPages ? "disabled" : "";
															$prevPage = max(1, $page - 1);
															$nextPage = min($totalPages, $page + 1);
															?>
															<?php if ($prevDisabled === "disabled"): ?>
																<span class="pager disabled">Prev</span>
															<?php else: ?>
																<a class="pager" href="?page=<?= $prevPage ?>&cat=<?= $cat ?>">Prev</a>
															<?php endif; ?>
															<span style="padding: 5px 10px;">Page <?= $page ?> of <?= $totalPages ?></span>
															<?php if ($nextDisabled === "disabled"): ?>
																<span class="pager disabled">Next</span>
															<?php else: ?>
																<a class="pager" href="?page=<?= $nextPage ?>&cat=<?= $cat ?>">Next</a>
															<?php endif; ?>
														</div>
													</div>
												<?php endif; ?>
											</div>
											<div style="clear: both;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>