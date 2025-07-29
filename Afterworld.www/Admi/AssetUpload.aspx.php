<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
$adminLevel  = 0;
$success = false;
$error = null;
$message = null;
$currentPage = basename($_SERVER['PHP_SELF']);
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	header("Location: /NewLogin");
}
	$GetUser = $pdo->prepare('SELECT * FROM users WHERE ROBLOSECURITY = :token');
    $GetUser->execute(['token' => $_COOKIE["_ROBLOSECURITY"]]);
    $row = $GetUser->fetch(PDO::FETCH_ASSOC);
if (!empty($_COOKIE['_ROBLOSECURITY'])) {
    $row = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    $userId = isset($row['UserId']) ? (int)$row['UserId'] : (isset($row['UserID']) ? (int)$row['UserID'] : (isset($row['id']) ? (int)$row['id']: 1));
    $adminLevel = isset($row['isAdmin']) ? (int)$row['isAdmin'] : 0;
} else {
    $userId = 1;
}
if ($adminLevel === 0) {
    header('Location: /NewLogin');
    exit();
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/Admi/SideBar.php';
if ($adminLevel < AdminLevel::ASSET_MOD) {
    echo <<<HTML
<div class="main-content"><br>
    <div class="header">
        <h1>You do not have permission to use this.</h1>
    </div>
</div>
</body>
</html>
HTML;
exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($adminLevel < AdminLevel::ASSET_MOD) {
        http_response_code(403);
        echo 'Unauthorized';
        exit();
    }
	
	if (!isset($_POST['creatorType']) || ($_POST['creatorType'] !== 'roblox' && $_POST['creatorType'] !== 'user')) {
		die("You must choose a valid creator.");
	}
	
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
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/asset/cache/";
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
        'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId, // Or a system bot ID
        'type' => $meta['AssetTypeId'] ?? 0,
    ]);
$name = htmlspecialchars($meta['Name']);
$urlName = urlencode(str_replace(' ', '-', $name));
$link = "/$urlName-item?id=$newAssetId";
    echo "Successfully migrated Roblox asset ID $robloxAssetId into local ID <a href='".$link."'>$newAssetId</a>.";
}
else{
$target_dir = $_SERVER['DOCUMENT_ROOT'] . "/asset/cache/";
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
   $message = "File too large. Limit is 10MB.";
   $success = false;
}

$fileData = file_get_contents($fileTmpPath);
if ($fileData === false) {
   $message = "Failed to read uploaded file.";
   $success = false;
}

if (file_put_contents($target_file, $fileData) === false) {
   $message = "Failed to save file.";
   $success = false;
}

// Insert asset metadata into DB
if($_POST['assettype'] == 9){
$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, ClientYear, isSubPlace, UniverseID, MaxPlayers) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, :client, 0, :uid, :max)');
$insert->execute([
    'name' => $_POST['name'] ?? 'Untitled',
    'desc' => $_POST['description'] ?? '',
    'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
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
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
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
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
		'typee' => intval($_POST['assettype'])
	]);
}elseif($_POST['assettype'] == 11){// shirt
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, isPrivate) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, 1)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => '',
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
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
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
		'typee' => intval($_POST['assettype'])
	]);
}elseif($_POST['assettype'] == 12){// pants
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, isPrivate) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, 1)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => '',
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
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
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
		'typee' => intval($_POST['assettype'])
	]);
}elseif($_POST['assettype'] == 18){// face
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType, isPrivate) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee, 1)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => '',
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
		'typee' => 1
	]);
	$rbxm = '<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://devopstest1.aftwld.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="Decal" referent="RBXA3B3A4E933B843D2AFCB02A9E62372D8">
		<Properties>
			<token name="Face">5</token>
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
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
		'typee' => intval($_POST['assettype'])
	]);
}else{
	$insert = $pdo->prepare('INSERT INTO assets (Name, Description, CreatorID, Created_At, Updated_At, AssetType) VALUES (:name, :desc, :creator, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), :typee)');
	$insert->execute([
		'name' => $_POST['name'] ?? 'Untitled',
		'desc' => $_POST['description'] ?? '',
		'creator' => ($_POST['creatorType'] === 'roblox') ? 1 : $userId,
		'typee' => intval($_POST['assettype'])
	]);
}
$name = htmlspecialchars($_POST['name']);
$urlName = urlencode(str_replace(' ', '-', $name));
$link = "/$urlName-item?id=$assetid";
$message = "Uploaded " . htmlspecialchars($fileInfo['name']) . " as asset ID: <a href='".$link."'>$assetid</a>";
$success = true;

}
}
?>
<div class="main-content">
    <div class="header"><h1>Asset Uploader</h1></div><br>
    <form action="/Admi/AssetUpload.aspx" method="post" enctype="multipart/form-data">
        <label>Select a file:</label><br>
        <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br><br>
        <label for="assettype">Asset Type:</label><br>
        <select id="assettype" name="assettype" onchange="togglePlaceOptions()">
            <option value="1">Image</option>
            <option value="2">TShirt</option>
            <option value="3">Audio</option>
            <option value="4">Mesh</option>
            <option value="8">Hat</option>
            <option value="9">Place</option>
            <option value="11">Shirt</option>
            <option value="12">Pants</option>
            <option value="17">Head</option>
            <option value="18">Face</option>
            <option value="19">Gear</option>
            <option value="24">Animation</option>
        </select><br><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>
		<label for="creatorType">Upload As:</label><br>
		<select id="creatorType" name="creatorType" required>
			<option value="" disabled selected>Select one</option>
			<option value="roblox">ROBLOX Account</option>
			<option value="user">Currently Logged In User</option>
		</select><br><br>
        <div class="placeOnly" style="display:none;">
            <label for="client">Client Year:</label><br>
            <select id="client" name="client">
                <option value="2015">2015</option>
                <option value="2016">2016</option>
            </select><br><br>
            <label for="maxplrs">Max Players (100):</label><br>
            <input type="number" id="maxplrs" name="maxplrs" min="1" max="100"><br>
            <p>This thing is still in development, just set the max players.</p><br>
        </div>
        <button type="submit" name="submit">Upload</button>
    </form><br><br>
    <form action="/Admi/AssetUpload.aspx" method="post">
        <div class="header"><h1>Asset Migrator (WIP)</h1></div><br>
        <label for="aid">Asset ID:</label><br>
        <input type="text" id="aid" name="aid"><br><br>
        <button type="submit" name="submit">Migrate</button>
    </form>
	<?php if ($success): ?>
    <div class="hidden2" style="display:block">
        <span style="color: green; margin-top: 16px;">✔ <?= $message ?></span>
    </div>
    <?php elseif ($error): ?>
    <div class="hidden2" style="display:block">
        <span style="color: red; margin-top: 16px;">✖ <?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>
</div>
<script>
function togglePlaceOptions() {
    const assetType = document.getElementById('assettype').value;
    const placeOnly = document.querySelector('.placeOnly');
    placeOnly.style.display = assetType === '9' ? 'block' : 'none';
}
</script>
</body>
</html>
