<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	header("Location: /newlogin");
}
	$GetUser = $pdo->prepare('SELECT * FROM users WHERE ROBLOSECURITY = :token');
    $GetUser->execute(['token' => $_COOKIE["_ROBLOSECURITY"]]);
    $row = $GetUser->fetch(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['aid']) && preg_match('/^\d+$/', $_POST['aid'])) {
    $robloxAssetId = intval($_POST['aid']);

    // Step 1: Fetch metadata from your productinfo proxy
    $metaUrl = "https://aftwld.xyz/marketplace/productinfo.php?assetId=" . $robloxAssetId;
	$urlcall = @file_get_contents($metaUrl);
    $meta = json_decode($urlcall, true);

    if (!$meta || !isset($meta['Name'])) {
        die("Failed to fetch valid metadata for asset ID $robloxAssetId. response $urlcall");
    }

    // Step 2: Download asset content using authenticated .ROBLOSECURITY
	if($meta["AssetTypeId"] == 41 || $meta["AssetTypeId"] == 42 || $meta["AssetTypeId"] == 43 || $meta["AssetTypeId"] == 44 || $meta["AssetTypeId"] == 45 || $meta["AssetTypeId"] == 46 || $meta["AssetTypeId"] == 47){ $meta["AssetTypeId"] = 8;}
    $assetUrl = "https://assetdelivery.roblox.com/v2/assetId/$robloxAssetId";
	if($meta["AssetTypeId"] == 8 || $meta["AssetTypeId"] == 41 || $meta["AssetTypeId"] == 27 ||$meta["AssetTypeId"] == 28 ||$meta["AssetTypeId"] == 29 ||$meta["AssetTypeId"] == 30 ||$meta["AssetTypeId"] == 31){
		$assetUrl = "https://assetdelivery.roblox.com/v2/assetId/$robloxAssetId/version/1";
	}
	$ch = curl_init($assetUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "User-Agent: RobloxStudio/WinInet",
            "Cookie: .ROBLOSECURITY=" . ROBLOSECURITY
        ],
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $assetData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$assetData) {
        die("Failed to download asset content. Error code $httpCode");
    }
$data = json_decode($assetData, true);
$cdnUrl = $data['locations'][0]['location'] ?? null;

if (!$cdnUrl) {
    http_response_code(404);
    die("CDN location not found for asset. response: $assetData");
}
// Step 2: Download and decompress asset from CDN
$ch = curl_init($cdnUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_ENCODING => "", // auto-decompress
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 10,
	CURLOPT_SSL_VERIFYPEER => false,
]);

$raw = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($raw, 0, $headerSize);
$assetData = substr($raw, $headerSize);
$assetData = str_replace('class="Accessory"', 'class="Hat"', $assetData);
$assetData = str_replace('roblox.com', 'aftwld.xyz', $assetData);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: 'application/octet-stream';
curl_close($ch);
    // Step 3: Determine local ID
    $FindGames = $pdo->query('SELECT * FROM assets ORDER BY AssetID DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
    $newAssetId = intval($FindGames['AssetID']) + 1;

    // Step 4: Save content to disk
    $target_dir = __DIR__ . "/asset/cache/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
    $target_file = $target_dir . $newAssetId . ".asset";

    if (!file_put_contents($target_file, $assetData)) {
        die("Failed to save asset content.");
    }

    // Step 5: Insert into DB
    $stmt = $pdo->prepare("INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :type)");
    $stmt->execute([
        'name' => $meta['Name'],
        'desc' => $meta['Description'] ?? 'Imported from Roblox',
        'creator' => $row['UserId'], // Or a system bot ID
        'type' => $meta['AssetTypeId'] ?? 0,
    ]);
$name = htmlspecialchars($meta['Name']);
$urlName = urlencode(str_replace(' ', '-', $name));
$link = "/$urlName-item?id=$newAssetId";
    echo "Successfully migrated Roblox asset ID $robloxAssetId into local ID <a href='".$link."'>$newAssetId</a>.";
}
else{
$target_dir = __DIR__ . "/asset/cache/";
$FindGames = $pdo->query('SELECT * FROM assets ORDER BY AssetID DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
$assetid = intval($FindGames['AssetID']) + 1;
$target_file = $target_dir . $assetid . ".asset";

$uploadField = 'fileToUpload';
$uploadMaxSize = 10 * 1024 * 1024; // 10 MB

if (!isset($_FILES[$uploadField])) {
    echo "No file uploaded.";
    return;
}

$errorCode = $_FILES[$uploadField]['error'];
if ($errorCode !== UPLOAD_ERR_OK) {
    $uploadErrors = [
        UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
    ];
    
    $message = $uploadErrors[$errorCode] ?? 'Unknown upload error.';
    echo "Upload failed with error: $message (code $errorCode)";
    return;
}

$fileInfo = $_FILES[$uploadField];
$fileTmpPath = $fileInfo['tmp_name'];
$fileSize = $fileInfo['size'];

if ($fileSize > $uploadMaxSize) {
    echo "File too large. Limit is 10MB.";
    return;
}

$fileData = file_get_contents($fileTmpPath);
if ($fileData === false) {
    echo "Failed to read uploaded file.";
    return;
}

if (file_put_contents($target_file, $fileData) === false) {
    echo "Failed to save file.";
    return;
}

// Insert asset metadata into DB
if($_POST['assettype'] == 9){
$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, ClientYear, isSubPlace, UniverseID, MaxPlayers) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, :client, 0, :uid, :max)');
$insert->execute([
    'name' => $_POST['name'] ?? 'Untitled',
    'desc' => $_POST['description'] ?? '',
    'creator' => $row['UserId'],
    'typee' => intval($_POST['assettype']),
	"client" => intval($_POST['client']),
	"uid" => $assetid,
	"max" => intval($_POST['maxplrs'])
]);
}elseif($_POST['assettype'] == 2){// tee shirt/shirtgraphics
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, isPrivate) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, 1)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => '',
		'creator' => $row['UserId'],
		'typee' => 1
	]);
	$rbxm = '
	<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://devopstest1.aftwld.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="ShirtGraphic" referent="RBX19E754CD96E745689EC0F9D166944CE4">
		<Properties>
			<string name="Name">Clothing</string>
			<Content name="Graphic"><url>https://devopstest1.aftwld.xyz/asset?id='.$assetid.'</url></Content>
		</Properties>
	</Item>
</roblox>
	';
	$assetid = $assetid + 1;
	$target_file = $target_dir . $assetid . ".asset";
	file_put_contents($target_file, $rbxm);
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => $_POST['description'] ?? '',
		'creator' => $row['UserId'],
		'typee' => intval($_POST['assettype'])
	]);
}elseif($_POST['assettype'] == 11){// shirt
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, isPrivate) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, 1)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => '',
		'creator' => $row['UserId'],
		'typee' => 1
	]);
	$rbxm = '
	<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://devopstest1.aftwld.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="Shirt" referent="RBX19E754CD96E745689EC0F9D166944CE4">
		<Properties>
			<string name="Name">Clothing</string>
			<Content name="ShirtTemplate"><url>https://devopstest1.aftwld.xyz/asset?id='.$assetid.'</url></Content>
		</Properties>
	</Item>
</roblox>
	';
	$assetid = $assetid + 1;
	$target_file = $target_dir . $assetid . ".asset";
	file_put_contents($target_file, $rbxm);
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => $_POST['description'] ?? '',
		'creator' => $row['UserId'],
		'typee' => intval($_POST['assettype'])
	]);
}elseif($_POST['assettype'] == 12){// pants
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, isPrivate) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, 1)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => '',
		'creator' => $row['UserId'],
		'typee' => 1
	]);
	$rbxm = '
	<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://devopstest1.aftwld.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="Pants" referent="RBX19E754CD96E745689EC0F9D166944CE4">
		<Properties>
			<string name="Name">Clothing</string>
			<Content name="PantsTemplate"><url>https://devopstest1.aftwld.xyz/asset?id='.$assetid.'</url></Content>
		</Properties>
	</Item>
</roblox>
	';
	$assetid = $assetid + 1;
	$target_file = $target_dir . $assetid . ".asset";
	file_put_contents($target_file, $rbxm);
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => $_POST['description'] ?? '',
		'creator' => $row['UserId'],
		'typee' => intval($_POST['assettype'])
	]);
}elseif($_POST['assettype'] == 18){// face
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, isPrivate) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, 1)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => '',
		'creator' => $row['UserId'],
		'typee' => 1
	]);
	$rbxm = '<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://devopstest1.aftwld.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="Decal" referent="RBXA3B3A4E933B843D2AFCB02A9E62372D8">
		<Properties>
			<token name="Face">1</token>
			<string name="Name">face</string>
			<float name="Shiny">20</float>
			<float name="Specular">0</float>
			<Content name="Texture"><url>https://devopstest1.aftwld.xyz/asset?id='.$assetid.'</url></Content>
			<float name="Transparency">0</float>
		</Properties>
	</Item>
</roblox>';
	$assetid = $assetid + 1;
	$target_file = $target_dir . $assetid . ".asset";
	file_put_contents($target_file, $rbxm);
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => $_POST['description'] ?? '',
		'creator' => $row['UserId'],
		'typee' => intval($_POST['assettype'])
	]);
}else{
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => $_POST['description'] ?? '',
		'creator' => $row['UserId'],
		'typee' => intval($_POST['assettype'])
	]);
}
$name = htmlspecialchars($_POST['name']);
$urlName = urlencode(str_replace(' ', '-', $name));
$link = "/$urlName-item?id=$assetid";
echo "Uploaded " . htmlspecialchars($fileInfo['name']) . " as asset ID: <a href='".$link."'>$assetid</a>";

}
}
?>
<html xml:lang=en xmlns:fb=http://www.facebook.com/2008/fbml>
  <head data-machine-id=WEB907>
    <title>Develop - Afterworld</title>
    <meta http-equiv=X-UA-Compatible content="IE=edge,requiresActiveX=true">
    <meta charset=UTF-8>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name=author content="Roblox Corporation">
    <meta name=description content="Roblox is the world's largest social platform for play. We help power the imaginations of people around the world.">
    <meta name=keywords content="free games,online games,building games,virtual worlds,free mmo,gaming cloud,physics engine">
    <meta name=apple-itunes-app content="app-id=431946152">
    <meta name=google-site-verification content=KjufnQUaDv5nXJogvDMey4G-Kb7ceUVxTdzcMaP9pCY>
    <script type=application/ld+json>
      {
        "@context": "http://schema.org",
        "@type": "Organization",
        "name": "Roblox",
        "url": "https://devopstest1.aftwld.xyz/",
        "logo": "https://images.rbxcdn.com/1870e85fa867567576343eaf76fb841e.png",
        "sameAs": ["https://www.facebook.com/ROBLOX/", "https://twitter.com/roblox", "https://www.linkedin.com/company/147977", "https://www.instagram.com/roblox/", "https://www.youtube.com/user/roblox", "https://plus.google.com/+roblox", "https://www.twitch.tv/roblox"]
      }
    </script>
    <meta name=user-data data-userid=62022330 data-name=yoshi2692 data-isunder13=false>
    <link rel=canonical href=https://devopstest1.aftwld.xyz/develop>
<link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css"/>
<link rel="stylesheet" href="/CSS/Pages/Legacy/Navigation.css"/>
<link rel="stylesheet" href="/CSS/Pages/Build/BuildPage.css"/>
<link rel="stylesheet" href="/CSS/Pages/Build/Develop.css"/>
<link rel="stylesheet" href="/CSS/Pages/Build/DropDownMenus.css"/>
<link rel="stylesheet" href="/CSS/Pages/Build/StudioWidget.css"/>
<link rel="stylesheet" href="/CSS/Pages/Build/Upload.css"/>

    <script src=//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js></script>
  <body id=rbx-body data-performance-relative-value=0.005 data-internal-page-name=Create data-send-event-percentage=0>
    <div id=roblox-linkify data-enabled=true data-regex="(https?\:\/\/)?(?:www\.)?([a-z0-9\-]{2,}\.)*(((m|de|www|web|api|blog|wiki|help|corp|polls|bloxcon|developer|devforum|forum)\.roblox\.com|robloxlabs\.com)|(www\.shoproblox\.com))((\/[A-Za-z0-9-+&amp;@#\/%?=~_|!:,.;]*)|(\b|\s))" data-regex-flags=gm data-as-http-regex=((wiki|[^.]help|corp|polls|bloxcon|developer|devforum)\.roblox\.com|robloxlabs\.com)></div>
    <div id=image-retry-data data-image-retry-max-times=10 data-image-retry-timer=1500 data-ga-logging-percent=10></div>
    <div id=http-retry-data data-http-retry-max-timeout=0 data-http-retry-base-timeout=0 data-http-retry-max-times=5></div>
    <div class="nav-container no-gutter-ads">
<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php';
	?>
<style>
.build-col {vertical-align: middle;}
</style>
<div id="navContent" class="nav-content  nav-no-left" style="margin-left: 0px; width: 100%;">
        <div class="nav-content-inner">
            <div id="MasterContainer">
                    <script type="text/javascript">
                        if (top.location != self.location) {
                            top.location = self.location.href;
                        }
                    </script>


                <div>
                                                            <noscript><div class="SystemAlert"><div class="SystemAlertText">Please enable Javascript to use all the features on this site.</div></div></noscript>
                                                            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>
                    <div id="BodyWrapper" class="">
                        <div id="RepositionBody">
                            <div id="Body" style="width:970px">
                    <div>
				<div id="MyCreationsTab" class="tab-active">
					<div class="BuildPageContent" data-groupid="">
						<input id="assetTypeId" name="assetTypeId" type="hidden" value="9"> <input data-val="true" data-val-required="The IsTgaUploadEnabled field is required." id="isTgaUploadEnabled" name="isTgaUploadEnabled" type="hidden" value="True">
						<table id="build-page" data-asset-type-id="9" data-showcases-enabled="true" data-edit-opens-studio="True">
							<tbody>
								<tr>
									<td class="menu-area divider-right">

										<div id="StudioWidget">
											<div class="widget-name">
												<h3>ROBLOX Studio</h3>
											</div>
											<div class="content">
												<div id="LeftColumn">
													<div class="studio-icon"><img src="/Images/RobloxStudio.png"></div>
												</div>
												<div id="RightColumn">
													<ul>
														<li><a href="https://setup.aftwld.xyz/RobloxStudioLauncherBeta.exe" class="studio-launch" download="">Download</a></li>
														<li><a href="https://devforum.aftwld.xyz/">Forum</a></li>
														<li><a href="https://wiki.aftwld.xyz/">Wiki</a></li>
													</ul>
												</div>
											</div>
										</div>
									</td>
									<td class="content-area">
										<table class="section-header">
											<tbody>
												<tr>
													<td class="content-title">
														<div>
															<h2 class="header-text">Create Place</h2>
														</div>
													</td>
												
												</tr>
												<tr class="creation-context-breadcrumb" style="display:none">
													<td style="height:21px">
														<div class="breadCrumb creation-context-breadcrumb"><a href="#breadcrumbs=gamecontext" class="breadCrumbContext">Context</a> <span class="context-game-separator" style="display:none"> » </span> <a href="#breadcrumbs=game" class="breadCrumbGame" style="display:none">Game</a></div>
													</td>
												</tr>
											</tbody>
										</table>
										<div class="items-container games-container">
<body>
<form action="/develop/createplace.php" method="post" enctype="multipart/form-data">
 Select a file:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <br/>
  <label for="name">Name:</label><br>
  <input type="text" id="name" name="name">
		<br></br>
		<label for="description">Description:</label><br></br>
		<textarea id="description" name="description" rows="4" cols="50"></textarea>
		<div class="placeOnly" style="display:block;">
		<label for="client">Client Year:</label>
		<select id="client" name="client">
			<option value="2015">2015</option>
		</select>
		<br></br>
		<label for="maxplrs">Max Players (100):</label>
		<input type="number" id="maxplrs" name="maxplrs" min="1" max="100" />
		<p>This thing is still in development, just set the max players.</p>
		<br></br>
		</div>
  <input type="submit" value="Upload File" name="submit">
</form>
					<script>if(typeof Roblox==="undefined"){Roblox={};}
						if(typeof Roblox.BuildPage==="undefined"){Roblox.BuildPage={};}
						Roblox.BuildPage.Resources={active:"Active",inactive:"Inactive",activatePlace:"Activate Place",editGame:"Edit Game",ok:"OK",robloxStudio:"ROBLOX Studio",openIn:"To edit this game, open to this page in ",placeInactive:"Deactivate Place",toBuileHere:"To build here, please activate this place by clicking the ",inactiveButton:"inactive button. ",createModel:"Create Model",toCreate:"To create models, please use ",makeActive:"Make Active",makeInactive:"Make Inactive",purchaseComplete:"Purchase Complete!",youHaveBid:"You have successfully bid ",confirmBid:"Confirm the Bid",placeBid:"Place Bid",cancel:"Cancel",errorOccurred:"Error Occurred",adDeleted:"Ad Deleted",theAdWasDeleted:"The Ad has been deleted.",confirmDelete:"Confirm Deletion",areYouSureDelete:"Are you sure you want to delete this Ad?",bidRejected:"Your bid was Rejected",bidRange:"Bid value must be a number between ",bidRange2:"Bid value must be a number greater than ",and:" and ",yourRejected:"Your bid was Rejected",estimatorExplanation:"This estimator uses data from ads run yesterday to guess how many impressions your ad will recieve.",estimatedImpressions:"Estimated Impressions ",makeAdBid:"Make Ad Bid",wouldYouLikeToBid:"Would you like to bid ",verify:"Verify",emailVerifiedTitle:"Verify Your Email",emailVerifiedMessage:"You must verify your email before you can work on your place. You can verify your email on the <a href='/my/account?confirmemail=1'>Account</a> page.",continueText:"Continue",profileRemoveTitle:"Remove from profile?",profileRemoveMessage:"This game is private and listed on your profile, do you wish to remove it?",profileAddTitle:"Add to profile?",profileAddMessage:"This game is public, but not listed on your profile, do you wish to add it?",deactivateTitle:"Deactivate Place",deactivateBody:"This will shut down any running games <br /><br />Do you still want to deactivate the place?",deactivateButton:"Deactivate",questionmarkImgUrl:"/images/Buttons/questionmark-12x12.png",activationRequestFailed:"Request to active the place. Please retry in a few minutes!",deactivationRequestFailed:"Request to deactivate the place failed. Please retry in a few minutes!",tooManyActiveMessage:"You have reached the maximum number of active places for your membership level. Deactivate one of your existing places before activating this one.",activeSlotsMessage:"{0} of {1} active slots used"};
					</script>
				</div>
			</div>
			<div id="AdPreviewModal" class="simplemodal-data" style="display:none">
				<div id="ConfirmationDialog" style="overflow:hidden">
					<div id="AdPreviewContainer" style="overflow:hidden"></div>
				</div>
			</div>
			<div id="clothing-upload-fun-captcha-container">
				<div id="clothing-upload-fun-captcha-backdrop"></div>
				<div id="clothing-upload-fun-captcha-modal"></div>
			</div>
			<div style="clear:both"></div>
		</div>