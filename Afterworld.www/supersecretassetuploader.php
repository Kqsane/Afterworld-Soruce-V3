<title>Afterworld - Asset Uploader</title>
<link rel='stylesheet' href='/CSS/Base/CSS/leanbase___e457f3b30a24742f0b81021a7cb26907_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___0513ca5a00c9bdedff82380744b7def6_m.css' />

<script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>
   <script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>


<?php
// newuser was here lolo
require_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	header("Location: /NewLogin");
}
	$GetUser = $pdo->prepare('SELECT * FROM users WHERE ROBLOSECURITY = :token');
    $GetUser->execute(['token' => $_COOKIE["_ROBLOSECURITY"]]);
    $row = $GetUser->fetch(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['aid']) && preg_match('/^\d+$/', $_POST['aid'])) {
    $robloxAssetId = intval($_POST['aid']);
    // this is so odviously chatgepetee -random
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
<html>
<body>
<h1>Asset Uploader</h1>
<h2>Update: The Asset Uploader has moved to the admin panel! Please go there to contuine uploading assets.</h2>
<a href="/Admi/AssetUpload.aspx">Click here to go to the new page.</a>