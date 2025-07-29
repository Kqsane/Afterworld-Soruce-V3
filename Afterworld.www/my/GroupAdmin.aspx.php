<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    header("Location: /newlogin");
    exit;
}

$cookie = $_COOKIE['_ROBLOSECURITY'];
$stmt = $pdo->prepare("SELECT UserID, Username FROM users WHERE ROBLOSECURITY = ?");
$stmt->execute([$cookie]);
$loggedInUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loggedInUser) {
    header("Location: /newlogin");
    exit;
}

$loggedInUserId = $loggedInUser['UserID'];
$groupId = isset($_GET['gid']) ? (int)$_GET['gid'] : 0;

if ($groupId <= 0) {
    header("Location: http://devopstest1.aftwld.xyz/RobloxDefaultErrorPage.aspx?mode=&code=404");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM `groups` WHERE GID = ?");
$stmt->execute([$groupId]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    header("Location: http://devopstest1.aftwld.xyz/RobloxDefaultErrorPage.aspx?mode=&code=404");
    exit;
}

$groupName = htmlspecialchars($group['Name']);
$groupOwnerId = (int)$group['OwnerID'];

$stmt = $pdo->prepare("SELECT GroupRole FROM `GroupJoin` WHERE GID = ? AND UserID = ?");
$stmt->execute([$groupId, $loggedInUserId]);
$userGroupJoin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userGroupJoin) {
    die("You are not a member of this group.");
}

$currentRoleId = (int)$userGroupJoin['GroupRole'];

if ($currentRoleId === 255) {
    $rolePermissions = range(1, 15);
} else {
    $stmt = $pdo->prepare("SELECT RolePermissions FROM GroupRoles WHERE GID = ? AND RoleID = ?");
    $stmt->execute([$groupId, $currentRoleId]);
    $roleData = $stmt->fetch(PDO::FETCH_ASSOC);
    $rolePermissions = $roleData ? json_decode($roleData['RolePermissions'], true) : [];
}

if (!is_array($rolePermissions)) $rolePermissions = [];

if (!in_array(6, $rolePermissions) && $currentRoleId !== 255) {
    die("You do not have permission to access the members tab.");
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$membersPerPage = 12;
$offset = ($page - 1) * $membersPerPage;

$stmt = $pdo->prepare("SELECT RoleID, RoleName FROM GroupRoles WHERE GID = ? ORDER BY RoleID ASC");
$stmt->execute([$groupId]);
$allRoles = $stmt->fetchAll(PDO::FETCH_ASSOC);
$roleIds = array_column($allRoles, 'RoleID');

if (!$roleIds) {
    die("No roles found for this group.");
}

$inQuery = implode(',', array_fill(0, count($roleIds), '?'));
$params = array_merge([$groupId], $roleIds);
$stmt = $pdo->prepare("SELECT GroupRole, COUNT(*) AS cnt FROM GroupJoin WHERE GID = ? AND GroupRole IN ($inQuery) GROUP BY GroupRole");
$stmt->execute($params);
$countsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

$roleCounts = [];
foreach ($countsRaw as $row) {
    $roleCounts[(int)$row['GroupRole']] = (int)$row['cnt'];
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM GroupJoin WHERE GID = ? AND GroupRole < ?");
$stmt->execute([$groupId, $currentRoleId]);
$totalMembers = (int)$stmt->fetchColumn();
$totalPages = max(1, ceil($totalMembers / $membersPerPage));
if ($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $membersPerPage;

$stmt = $pdo->prepare("
    SELECT u.UserID, u.Username, gj.GroupRole 
    FROM GroupJoin gj
    JOIN users u ON u.UserID = gj.UserID
    WHERE gj.GID = :gid AND gj.GroupRole < :currentRole
    ORDER BY gj.id DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':gid', $groupId, PDO::PARAM_INT);
$stmt->bindValue(':currentRole', $currentRoleId, PDO::PARAM_INT);
$stmt->bindValue(':limit', $membersPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getRoleName($roleId, $allRoles) {
    foreach ($allRoles as $role) {
        if ((int)$role['RoleID'] === (int)$roleId) return htmlspecialchars($role['RoleName']);
    }
    return 'Unknown';
}

function getUserHeadshot($userId) {
    return "http://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId={$userId}";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Group Admin - Members</title>
    <link rel="stylesheet" href="https://devopstest1.aftwld.xyz/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css">
    <link rel="stylesheet" href="https://devopstest1.aftwld.xyz/CSS/Base/CSS/page___6d7bcbdfd9dfa4d697c4e627e71f4fc1_m.css">
    <link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css">
    <link rel="stylesheet" href="/CSS/Pages/Legacy/Navigation.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/BuildPage.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/Develop.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/DropDownMenus.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/StudioWidget.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/Upload.css">
<style>
/* Legacy CSS file - Please do not copy */
/* header - less margin-bottom */
.BuildPageHeaderTopLvl {
  margin-bottom: 7px;
  display: block; }

.BuildPageContent {
  float: left; }

#build-page {
  border-spacing: 0;
  border-collapse: collapse; }

#build-page td {
  padding: 0; }

/* left side menu begins */
{
  width: 148px;
  vertical-align: top;
  height: 738px;
  /* same as the skyscraper ad */ }

#build-page #MyCreationsTab td.menu-area {
  padding-top: 10px; }

a.tab-item {
  display: block;
  padding: 5px 10px;
  color: Black !important;
  font-size: 16px; }

a.tab-item:hover {
  text-decoration: none;
  background-color: #EFEFEF; }

/* in addition to a.tab-item */
a.tab-item-selected {
  border-top: 1px solid #CCCCCC;
  border-bottom: 1px solid #CCCCCC;
  border-left: 1px solid #CCCCCC;
  background-color: #EFEFEF;
  padding: 4px 9px;
  /* 1px less to account for the border */
  font-weight: bold; }

/* left side menu ends */
#build-page td.content-area {
  width: 622px;
  padding: 9px 10px 10px 10px !important;
  vertical-align: top;
  border-right: 1px solid #cccccc; }

#build-page td.no-ads {
  border-right: none; }

#build-page .separator {
  background-color: #EFEFEF;
  margin: 10px 0;
  height: 1px; }

/** stuff in the header begins */
#build-page table.section-header {
  margin-bottom: 26px;
  width: 622px;
  border-spacing: 0; }

#build-page table.section-header td {
  vertical-align: top;
  height: 45px; }

#build-page table.section-header label.sort-label {
  font-size: 14px;
  font-weight: 600; }

#build-page table.section-header input[type=checkbox] {
  vertical-align: middle;
  position: relative;
  bottom: 1px;
  margin-right: 4px;
  margin-left: 5px; }

#build-page table.section-header label.checkbox-label {
  float: initial;
  color: Black;
  font-size: 12px; }

td.content-title {
  white-space: nowrap; }

div.sorts-and-filters .checkbox {
  padding-top: 2px;
  white-space: nowrap;
  padding-right: 6px; }

#build-page table.section-header div.sorts-and-filters {
  text-align: right;
  position: relative;
  top: 16px;
  padding-left: 20px;
  *left: 20px; }

#build-page table.section-header div.creation-context-filters-and-sorts {
  text-align: right;
  position: relative;
  top: 14px;
  padding-left: 20px;
  *left: 20px; }

div.creation-context-filters-and-sorts .checkbox {
  padding-top: 2px;
  white-space: nowrap;
  padding-right: 6px; }

#build-page .show-active-places-only .checkbox-label {
  padding-left: 0px; }

#build-page div.creation-context-breadcrumb {
  margin-top: 6px; }

#build-page div.buildpage-loading-container {
  text-align: center; }

.active-only-checkbox {
  float: right;
  vertical-align: middle; }

.active-only-checkbox > input[type='checkbox'] {
  margin-top: 0px; }

.show-archive-checkbox {
  float: right;
  vertical-align: middle; }

.show-archive-checkbox > input[type='checkbox'] {
  margin-top: 0px; }

/** stuff in the header ends */
/* item listings start */
#build-page .item-table {
  border-spacing: 0;
  border-collapse: collapse; }

#build-page .item-table td {
  padding: 0;
  vertical-align: top; }

#build-page .item-table .image-col {
  width: 80px;
  overflow: hidden; }

#build-page .item-table .name-col {
  padding-top: 11px;
  width: 300px; }

#build-page .item-table .name-col a.title {
  display: block;
  margin-bottom: 2px;
  overflow: hidden; }

#build-page .item-table .stats-col-games {
  width: 140px;
  vertical-align: middle; }

#build-page .item-table .stats-col {
  width: 198px;
  vertical-align: middle; }

#build-page .item-table .edit-col {
  vertical-align: middle;
  padding-right: 10px; }

#build-page .item-table .menu-col {
  vertical-align: middle;
  text-align: right; }

/* item listings end */
/* other items on the list area start */
#build-page a[class^='load-more-'] {
  display: block;
  width: 100px;
  margin: 20px auto 0 auto; }

/* other items on the list area end */
/* "Gear" drop down button begins */
#build-page .gear-button,
#build-page .gear-button-hover,
#build-page .gear-button-open {
  background: url(/images/BuildPage/btn-gear_sprite_27px.png) no-repeat;
  display: inline-block;
  width: 40px;
  height: 27px;
  border: none;
  position: relative;
  margin-right: 10px; }

#build-page .gear-hover {
  background-position: 0 -27px; }

#build-page .gear-open {
  background-position: 0 -54px;
  z-index: 100; }

#build-page .gear-button {
  left: -1px; }

#build-page .gear-button-wrapper {
  top: 5px;
  width: 38px;
  background-color: #FFFFFF;
  border-left: 1px solid;
  border-right: 1px solid;
  border-color: white;
  height: 37px;
  position: relative;
  margin-right: 5px; }

/* gear drop down button ends */
#build-page a.item-image img {
  width: 69px; }

#build-page a.game-image img {
  width: 70px; }

#build-page a.item-image img,
#build-page a.game-image img {
  height: 69px;
  display: block;
  border: none; }

#build-page .totals-label {
  line-height: 17px;
  font-size: 13px; }

#build-page .aside-text {
  margin-left: 10px;
  font-size: 13px; }

#build-page .aside-text,
#build-page .totals-label {
  color: #999; }

/* number of sales or visits */
#build-page .item-table .totals-label span {
  color: black;
  margin-left: 2px; }

/* active/inactive links and date start */
#build-page .details-table {
  border-spacing: 0;
  border-collapse: collapse; }

#build-page .details-table td.activate-cell {
  width: 115px; }

#build-page .details-table td.hide-activate-cell {
  display: none; }

#build-page .details-table td.ad-activate-cell {
  width: 115px; }

#build-page .details-table td {
  font-size: 13px; }

#build-page .details-table td > span {
  margin-right: 5px;
  color: #999; }

#build-page a.place-active,
#build-page a.place-inactive, span.inactive-image {
  padding-left: 18px;
  font-size: 13px;
  background-image: url("/images/BuildPage/ico-game_privacy.png");
  background-repeat: no-repeat;
  display: block;
  height: 15px; }

#build-page a.place-active:hover {
  background-position: 0 -15px;
  text-decoration: none; }

#build-page a.place-inactive, span.inactive-image {
  background-position: 0 -30px; }

#build-page span.creator-app {
  color: #888;
  font-size: 12px; }

span.inactive-image {
  display: inline-block; }

#build-page a.place-inactive:hover {
  background-position: 0 -45px;
  text-decoration: none; }

/* active/inactive links and date end */
/*** building games box on right ****/
.build-games-container {
  text-align: left;
  padding-top: 12px;
  padding-left: 3px; }

.build-games-container ul {
  margin-top: 20px;
  list-style: none;
  padding: 0;
  margin-top: 11px;
  margin-bottom: 17px; }

.build-games-container li {
  margin-bottom: 14px; }

.build-drop-down {
  *margin-left: -141px;
  *margin-top: 34px; }

/* Ad Page stuff */
.run-ad-buttons {
  float: right;
  margin-left: 10px; }

.bid-amount-box {
  text-align: left;
  float: right;
  margin-left: 10px; }

.group-fund-box {
  float: right;
  margin-top: 1px; }

.bid-amount-text {
  padding-top: 5px;
  margin-right: 5px; }

#upload-iframe {
  width: 100%;
  height: 150px;
  margin-left: 10px; }

#upload-iframe.place-specific-assets {
  height: 230px; }

#upload-plugin-iframe {
  width: 100%;
  height: 220px;
  margin-left: 10px; }

#universe-create-container label {
  min-width: 100px;
  display: inline-block; }

.universe-list-container {
  margin-top: 20px;
  padding-top: 20px; }

.universe-create-container {
  margin-bottom: 30px;
  margin-left: 20px;
  margin-top: 10px; }

#universe-create-container label[for=description] {
  vertical-align: top; }

#universe-create-container .submit {
  margin-top: 5px; }

#universe-create-container .loading-image {
  padding-top: 9px;
  padding-bottom: 8px; }

#universe-create-container .error {
  color: red;
  display: inline-block;
  vertical-align: top; }

.item-universe {
  text-overflow: ellipsis;
  width: 400px;
  display: block;
  overflow: hidden; }

#build-page .item-table .universe-name-col {
  vertical-align: middle;
  width: 457px; }

#build-page .item-table .universename-context-col {
  padding-top: 28px;
  width: 400px; }

#universe-create-container .form-row {
  margin-bottom: 10px; }

.root-place-selector {
  width: 200px; }

.content-area .create-new-button {
  margin-bottom: 10px; }

#build-page .spacer {
  height: 15px;
  width: 10px; }

#build-page .groups-dropdown-container {
  margin-bottom: 15px; }

#build-page .groups-dropdown-container select {
  width: 135px; }

#build-page .option select {
  min-width: 150px; }

#build-page .item-template {
  display: none; }

</style>
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
<div id="navContent" class="nav-content"><div class="nav-content-inner">
<div id="MasterContainer">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">Roblox.FixedUI.gutterAdsEnabled=false;</script>
<div id="Container"></div>
<div id="AdvertisingLeaderboard">
    <iframe allowtransparency="true" frameborder="0" height="110" scrolling="no" src="/userads/1/" width="728" data-js-adtype="iframead"></iframe>
</div>
<noscript><div class="SystemAlert"><div class="SystemAlertText">Please enable Javascript to use all the features on this site.</div></div></noscript>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>
<div id="BodyWrapper">
<div id="RepositionBody">
<div id="Body" style="width:970px;">
<div class="card br-none mt-4 border-0">
<div class="row">
<div class="col-6">
<h3><?php echo $groupName; ?></h3>
<p class="fw-600 mb-0"><span class="header_labelName__yunFT">Owned by: </span><a class="fst-italic" href="/users/<?php echo $groupOwnerId; ?>/profile"><?php
$stmt = $pdo->prepare("SELECT Username FROM users WHERE UserID = ?");
$stmt->execute([$groupOwnerId]);
$owner = $stmt->fetch(PDO::FETCH_ASSOC);
echo htmlspecialchars($owner['Username'] ?? 'Unknown');
?></a></p>
<p class="fw-600 mb-0"><span class="header_labelName__yunFT">Group Funds: </span><div class="image-0-2-101"></div><span class="amount-0-2-103"><?php echo $group['Funds'] ?? '0'; ?></span><span class="ms-2"><span class="image-0-2-105"></span><span class="text-0-2-104">0</span></span></p>
</div>
<div class="col-12"><div class="header_line__k3dJe pt-3 mb-2"></div></div>
</div>
<div class="row">
<div class="col-2">
<div class="row mt-4 pe-0 row-0-2-41">
<div class="col-12 pe-0 me-0">
<a href="?gid=<?php echo $groupId; ?>&tab=members" class="tab-item tab-item-selected">Members</a>
<a href="?gid=<?php echo $groupId; ?>&tab=info" class="tab-item">Group Info</a>
<a href="?gid=<?php echo $groupId; ?>&tab=settings" class="tab-item">Settings</a>
<a href="?gid=<?php echo $groupId; ?>&tab=relationships" class="tab-item">Relationships</a>
<a href="?gid=<?php echo $groupId; ?>&tab=roles" class="tab-item">Roles</a>
<a href="?gid=<?php echo $groupId; ?>&tab=revenue" class="tab-item">Revenue</a>
<a href="?gid=<?php echo $groupId; ?>&tab=payouts" class="tab-item">Payouts</a>
                                                                                                            </div>
</div>
</div>
<a class="buttons_legacyButton__vUgL2 w-100 mt-4 d-block text-center" href="/My/Groups.aspx">Back To My Groups</a>
</div>
<div class="col-10">
<div class="row mt-4"><div class="col-12"><h3>Members</h3>
<div>
<div class="d-inline-block"><input type="text" value=""></div>
<div class="d-inline-block ms-2">
<select>
<option value="Filter By Rank">Filter By Rank</option>
<?php foreach ($allRoles as $role): ?>
<option value="<?php echo (int)$role['RoleID']; ?>">
<?php echo htmlspecialchars($role['RoleName']) . ' (' . ($roleCounts[(int)$role['RoleID']] ?? 0) . ')'; ?>
</option>
<?php endforeach; ?>
</select>
</div>
<div class="d-inline-block ms-2"><button class="buttons_legacyButton__vUgL2">Search</button></div>
<div class="d-inline-block ms-2"><button class="buttons_legacyButton__vUgL2">Show All Members</button></div>
</div>
<div class="row"><div class="col-12 col-lg-12"><div class="row">
<?php foreach ($members as $member): ?>
<div class="col-2">
<div class="membersList_gearDropdown__DNqYf">
<div class="membersList_gear__ud0VI">
<div class="container-0-2-115">
<div class="box-0-2-107 ">
<div class="gearWrapper-0-2-110"><div class="gear-0-2-109"></div></div>
<div class="caretWrapper-0-2-111"><div class="caret-0-2-112">▼</div></div>
</div>
</div>
</div>
</div>
<div class="membersList_headshot__YvELA"><img class="image-0-2-116 undefined" alt="<?php echo htmlspecialchars($member['Username']); ?>" src="<?php echo getUserHeadshot($member['UserID']); ?>"></div>
<p class="mb-0 text-truncate"><a href="/users/<?php echo (int)$member['UserID']; ?>/profile"><?php echo htmlspecialchars($member['Username']); ?></a></p>
<select class="w-100 mt-2">
<?php foreach ($allRoles as $role): ?>
<option value="<?php echo (int)$role['RoleID']; ?>" <?php if ((int)$member['GroupRole'] === (int)$role['RoleID']) echo 'selected'; ?>>
<?php echo htmlspecialchars($role['RoleName']); ?>
</option>
<?php endforeach; ?>
</select>
</div>
<?php endforeach; ?>
</div></div></div>
<p class="text-center mt-4">
<?php if ($page > 1): ?>
<a class="ms-4 me-4" href="?gid=<?php echo $groupId; ?>&tab=members&page=<?php echo $page - 1; ?>">Previous</a>
<?php endif; ?>
<?php if ($page < $totalPages): ?>
<a class="ms-4 me-4" href="?gid=<?php echo $groupId; ?>&tab=members&page=<?php echo $page + 1; ?>">Next</a>
<?php endif; ?>
</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
