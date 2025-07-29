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

$stmt = $pdo->prepare("SELECT `UserID`, `Username` FROM `users` WHERE `ROBLOSECURITY` = ?");
$stmt->execute([$cookie]);
$loggedInUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loggedInUser) {
    header("Location: /newlogin");
    exit;
}

$loggedInUserId = $loggedInUser['UserID'];

$groupId = isset($_GET['gid']) ? (int)$_GET['gid'] : 0;
if ($groupId <= 0) {
    die("Invalid group ID.");
}

$stmt = $pdo->prepare("SELECT * FROM `groups` WHERE `GID` = ?");
$stmt->execute([$groupId]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    die("Group not found.");
}

$groupName = htmlspecialchars($group['Name']);
$groupDescription = nl2br(htmlspecialchars($group['Description']));
$groupCreatorId = $group['OwnerID'];

$stmt = $pdo->prepare("SELECT `Username` FROM `users` WHERE `UserID` = ?");
$stmt->execute([$groupCreatorId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$groupCreatorUsername = $user ? htmlspecialchars($user['Username']) : 'Unknown';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['JoinGroup'])) {
    $stmt = $pdo->prepare("SELECT * FROM `GroupJoin` WHERE `GID` = ? AND `UserID` = ?");
    $stmt->execute([$groupId, $loggedInUserId]);
    if (!$stmt->fetch()) {
        $isPublic = (int)$group['GroupPublic'] === 1;
        $status = $isPublic ? 'approved' : 'pending';
        $roleId = 1;
        $stmt = $pdo->prepare("INSERT INTO `GroupJoin` (`GID`, `UserID`, `Status`, `GroupRole`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$groupId, $loggedInUserId, $status, $roleId]);
    }
    header("Location: /Groups/group.aspx?gid=" . $groupId);
    exit;
}

$selectedRole = isset($_GET['role']) ? (int)$_GET['role'] : 1;
if ($selectedRole <= 0) $selectedRole = 1;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page <= 0) $page = 1;

$membersPerPage = 12;

$stmt = $pdo->prepare("SELECT * FROM `GroupRoles` WHERE `GID` = ? ORDER BY `RoleID` ASC");
$stmt->execute([$groupId]);
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$roleIds = array_column($roles, 'RoleID');
if (count($roleIds) === 0) {
    die("No roles found for this group.");
}
$inQuery = implode(',', array_fill(0, count($roleIds), '?'));
$params = array_merge([$groupId], $roleIds);
$sql = "SELECT `GroupRole`, COUNT(*) AS cnt FROM `GroupJoin` WHERE `GID` = ? AND `GroupRole` IN ($inQuery) GROUP BY `GroupRole`";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$countsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$roleCounts = [];
foreach ($countsRaw as $row) {
    $roleCounts[(int)$row['GroupRole']] = (int)$row['cnt'];
}

$roleExists = false;
foreach ($roles as $r) {
    if ((int)$r['RoleID'] === $selectedRole) {
        $roleExists = true;
        break;
    }
}
if (!$roleExists) {
    $selectedRole = (int)$roles[0]['RoleID'];
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM `GroupJoin` WHERE `GID` = ? AND `GroupRole` = ?");
$stmt->execute([$groupId, $selectedRole]);
$totalMembers = (int)$stmt->fetchColumn();
$totalPages = ceil($totalMembers / $membersPerPage);
if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}
$offset = ($page - 1) * $membersPerPage;

$members = [];

try {
    $sql = "
        SELECT u.UserID, u.Username
        FROM `GroupJoin` gj
        JOIN `users` u ON u.UserID = gj.UserID
        WHERE gj.GID = :gid AND gj.GroupRole = :role
        ORDER BY gj.id DESC
        LIMIT $membersPerPage OFFSET $offset
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':gid' => $groupId,
        ':role' => $selectedRole
    ]);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $members = [];
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM `GroupJoin` WHERE `GID` = ? AND `Status` = 'approved'");
$stmt->execute([$groupId]);
$memberCount = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT `Funds` FROM `groups` WHERE `GID` = ?");
$stmt->execute([$groupId]);
$fundsRow = $stmt->fetch(PDO::FETCH_ASSOC);

$funds = ["0", "0"];
if ($fundsRow && !empty($fundsRow['Funds'])) {
    $funds = json_decode($fundsRow['Funds'], true);
    if (!is_array($funds) || count($funds) < 2) {
        $funds = ["0", "0"];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['WallPost'])) {
    $text = trim($_POST['wall_text'] ?? '');
    if (!empty($text)) {
        $stmt = $pdo->prepare("INSERT INTO `GroupWall` (`GroupID`, `PosterID`, `Text`) VALUES (?, ?, ?)");
        $stmt->execute([$groupId, $loggedInUserId, htmlspecialchars($text)]);
    }
    header("Location: /Groups/group.aspx?gid=$groupId");
    exit;
}

$wallPage = isset($_GET['wallpage']) ? max((int)$_GET['wallpage'], 1) : 1;
$postsPerPage = 10;
$offset = ($wallPage - 1) * $postsPerPage;

$stmt = $pdo->prepare("
    SELECT gw.*, u.Username
    FROM `GroupWall` gw
    JOIN `users` u ON u.UserID = gw.PosterID
    WHERE gw.GroupID = ?
    ORDER BY gw.TimePosted DESC
    LIMIT $postsPerPage OFFSET $offset
");
$stmt->execute([$groupId]);
$wallPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM `GroupWall` WHERE GroupID = ?");
$stmt->execute([$groupId]);
$totalWallPosts = (int)$stmt->fetchColumn();
$totalWallPages = ceil($totalWallPosts / $postsPerPage);
?>





<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title><?php echo $groupName ?> - Afterworld</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/CSS/Pages/Groups/Groups.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/DropDownMenus.css">
    <link rel="stylesheet" href="/CSS/Pages/Build/StudioWidget.css">
	<link rel="stylesheet" href="/CSS/Base/CSS/main___d7f658c4695ed776947e7d072c17ef0f_m.css">
	<link rel="stylesheet" href="/CSS/Base/CSS/page___bf085a0aa25ce4df4c0be2fa1dc7e79a_m.css">
    <script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script> 
    <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
      window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>");
    </script>
    <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript">
      window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>");
    </script>
    <style>
        .build-col { vertical-align: middle; }
    </style>
<script>
$(document).ready(function() {
    $('#build-new-button').on('click', function() {
        $('#build-new-dropdown-menu').toggle(); // Toggle visibility
    });
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#build-new-button, #build-new-dropdown-menu').length) {
            $('#build-new-dropdown-menu').hide();
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll(".tab");
  const contents = document.querySelectorAll(".tab-content");

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      contents.forEach(c => c.style.display = "none");

      tab.classList.add("active");
      const contentId = "GroupsPeoplePane_" + tab.id.split("_")[1];
      const contentDiv = document.getElementById(contentId);
      if(contentDiv) {
        contentDiv.style.display = "block";
      }
    });
  });
});
</script>


</head>
<body data-internal-page-name="" data-performance-relative-value="0.5" class="" id="rbx-body">
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>
    <form name="aspnetForm" method="post" action="/Groups/group.aspx?gid=<?php echo $groupId ?>" id="aspnetForm" class="nav-container no-gutter-ads">
        <div id="navContent" class="nav-content nav-no-left" style="">
            <div class="nav-content-inner">
                <div id="MasterContainer">
					                <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>

                    <div id="AdvertisingLeaderboard">


                        <iframe name="Roblox_Default_Top_728x90" allowtransparency="true" frameborder="0" height="110" scrolling="no" src="/userads/1" width="728" data-js-adtype="iframead" data-ruffle-polyfilled=""></iframe>

                    </div>




                    <div id="BodyWrapper">

                        <div id="RepositionBody">
                            <div id="Body" class="">

                                <style type="text/css">
                                    #Body {
                                        padding: 10px;
                                        width: 970px;
                                    }
                                </style>
                                <div class="MyRobloxContainer">
                                    <div id="mid-column" class="GroupsPage">

                                        <div id="SearchControls">

                                            <div class="content">
                                                <input name="ctl00$cphRoblox$GroupSearchBar$SearchKeyword" type="text" id="ctl00_cphRoblox_GroupSearchBar_SearchKeyword" onclick="if(this.value == 'Search all groups'){ this.value = ''};$(this).removeClass('default');" class="SearchKeyword default translate"
                                                maxlength="100" value="Search all groups">
                                                <!--<select name="ctl00$cphRoblox$GroupSearchBar$SearchFiltersDropdown2" id="ctl00_cphRoblox_GroupSearchBar_SearchFiltersDropdown2">

</select>-->
                                                <input type="submit" name="ctl00$cphRoblox$GroupSearchBar$SearchButton" value="Search" onclick="javascript:if ($get(SearchKeywordText).value == '' || $get(SearchKeywordText).value == 'Search all groups') return false;" id="ctl00_cphRoblox_GroupSearchBar_SearchButton"
                                                class="group-search-button translate">
                                                <input type="text" style="visibility: hidden; position: absolute">
                                                <!-- Enter key submission hack - IE -->
                                            </div>
                                        </div>

                                        <script type="text/javascript">
                                            var SearchKeywordText = 'ctl00_cphRoblox_GroupSearchBar_SearchKeyword';
                                        </script>


                                        <div id="description">
                                            <div class="GroupPanelContainer">
                                                <div class="left-col">
                                                    <div class="GroupThumbnail">
                                                        <a id="ctl00_cphRoblox_GroupDescriptionEmblem" title="LOL" onclick="__doPostBack('ctl00$cphRoblox$GroupDescriptionEmblem','')" style="display:inline-block;cursor:pointer;"><img src="/Thumbs/Group.ashx?gid=<?php echo $groupId ?>&width=150&height=150" height="150" width="150" border="0" alt="LOL"></a>
                                                    </div>
                                                    <div class="GroupOwner">
                                                        <div id="ctl00_cphRoblox_OwnershipPanel">
                                                            Owned By:
                                                            <br>
                                                            <a style="font-style: italic;" href="/User.aspx?id=<?php echo $groupCreatorId ?>" onclick=""><?php echo $groupCreatorUsername ?></a>
                                                        </div>
                                                        <div id="MemberCount">Members: <?php echo $memberCount ?></div>

                                                    </div>
													<form method="POST" style="margin-top: 10px;">
														<button id="ctl00_cphRoblox_JoinGroup" name="JoinGroup" type="submit" class="btn-neutral btn-large">
															Join Group
														</button>
													</form>

                                                    <div>

                                                    </div>

                                                </div>
                                                <div class="right-col">
                                                    <h2 class="notranslate"><?php echo $groupName ?></h2>
                                                    <div id="GroupDescP" class="linkify">
                                                        <pre class="notranslate"><?php echo $groupDescription ?></pre>
                                                    </div>
                                                    <div id="ctl00_cphRoblox_AbuseReportButton_AbuseReportPanel" class="ReportAbuse">

                                                        <span class="AbuseIcon"><a id="ctl00_cphRoblox_AbuseReportButton_ReportAbuseIconHyperLink" href="/abusereport/group?id=2&amp;RedirectUrl=http%3a%2f%2fwww.roblox.com%2fGroups%2fgroup.aspx%3fgid%3d2"><img src="/web/20160216055208im_/http://www.roblox.com/images/abuse.PNG?v=2" alt="Report Abuse" style="border-width:0px;"></a></span>
                                                        <span class="AbuseButton"><a id="ctl00_cphRoblox_AbuseReportButton_ReportAbuseTextHyperLink" href="/abusereport/group?id=2&amp;RedirectUrl=http%3a%2f%2fwww.roblox.com%2fGroups%2fgroup.aspx%3fgid%3d2">Report Abuse</a></span>

                                                    </div>
                                                    <br>


                                                </div>
                                                <div style="clear: both;"></div>
                                            </div>

                                            <div id="GroupsPeopleContainer">
                                                <div>

                                                    <div id="GroupsPeople_Games" class="tab">Games</div>

                                                    <div id="GroupsPeople_Places" class="tab ">Personal Servers</div>

                                                    <div id="GroupsPeople_Members" class="tab active">Members</div>
                                                    <div id="GroupsPeople_Allies" class="tab">Allies</div>
                                                    <div id="GroupsPeople_Enemies" class="tab">Enemies</div>
                                                    <div id="GroupsPeople_Items" class="tab">Store</div>
                                                    <div style="clear: both;"></div>
                                                </div>
                                                <div id="GroupsPeople_Pane">

                                                    <div id="GroupsPeoplePane_Games" class="tab-content" style="display: none;">

                                                        <div class="results-container" data-page="1" data-group-id="2">
                                                            <div class="GroupPlace">
                                                                <div>
                                                                    <a href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/260400546/Content-Deleted"><img src="https://web.archive.org/web/20160216055208im_/http://t7.rbxcdn.com/d6723f3b91f5813187db2fd4971d1059" data-retry-url="" title="[ Content Deleted ]"></a>
                                                                </div>
                                                                <div class="PlaceName">
                                                                    <a class="NameText" href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/260400546/Content-Deleted">[ Content Deleted ]</a>
                                                                </div>
                                                                <div class="PlayersOnline">0 players online</div>
                                                            </div>
                                                            <div class="GroupPlace">
                                                                <div>
                                                                    <a href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/299893742/LOL-Place-0"><img src="https://web.archive.org/web/20160216055208im_/http://t3.rbxcdn.com/b87119e1031e9ac7ed177168ef8eb90e" data-retry-url="" title="LOL'# Place ######: 0"></a>
                                                                </div>
                                                                <div class="PlaceName">
                                                                    <a class="NameText" href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/299893742/LOL-Place-0">LOL'# Place ######: 0</a>
                                                                </div>
                                                                <div class="PlayersOnline">0 players online</div>
                                                            </div>
                                                            <div class="GroupPlace">
                                                                <div>
                                                                    <a href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/296952028/LOL-City"><img src="https://web.archive.org/web/20160216055208im_/http://t6.rbxcdn.com/51075c13f480c009003768bdb8ab77c0" data-retry-url="" title="LOL City"></a>
                                                                </div>
                                                                <div class="PlaceName">
                                                                    <a class="NameText" href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/296952028/LOL-City">LOL City</a>
                                                                </div>
                                                                <div class="PlayersOnline">0 players online</div>
                                                            </div>
                                                            <div class="GroupPlace">
                                                                <div>
                                                                    <a href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/289734719/Hotel-Of-LOL"><img src="https://web.archive.org/web/20160216055208im_/http://t6.rbxcdn.com/54fdf4b0303feebddbd1c1ba5fe369df" data-retry-url="" title="Hotel Of LOL"></a>
                                                                </div>
                                                                <div class="PlaceName">
                                                                    <a class="NameText" href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/289734719/Hotel-Of-LOL">Hotel Of LOL</a>
                                                                </div>
                                                                <div class="PlayersOnline">0 players online</div>
                                                            </div>
                                                            <div class="GroupPlace">
                                                                <div>
                                                                    <a href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/277570340/LOLs-Pictures"><img src="https://web.archive.org/web/20160216055208im_/http://t6.rbxcdn.com/ce04a7a5fa039d5d7e91cf92e0990c65" data-retry-url="" title="LOL's Pictures"></a>
                                                                </div>
                                                                <div class="PlaceName">
                                                                    <a class="NameText" href="https://web.archive.org/web/20160216055208/http://www.roblox.com/games/277570340/LOLs-Pictures">LOL's Pictures</a>
                                                                </div>
                                                                <div class="PlayersOnline">0 players online</div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="SkinnyRightArrow" id="GroupGamesNext"></div>

                                                    </div>

                                                    <div id="GroupsPeoplePane_Places" class="tab-content MyGroups" style="display: none">


                                                        <div id="GroupPlacesContainer">
                                                            <div id="GroupPlaceTemplate" class="GroupPlace" style="display:none;">
                                                                <div class="PlaceThumb"></div>
                                                                <div class="PlaceInfo">
                                                                    <div class="PlaceName"></div>
                                                                    <div class="PlayersOnline"></div>
                                                                </div>
                                                            </div>
                                                            <div class="SkinnyLeftArrow" id="GroupPlacesPrev"></div>
                                                            <div id="GroupPlaces"></div>
                                                            <div class="SkinnyRightArrow" id="GroupPlacesNext"></div>
                                                        </div>
                                                        <div class="clear"></div>

                                                        <script type="text/javascript">
                                                            $(function () {
                                                                var pageNum = 1;
                                                                var pageSize = 4;
                                                                var lastPage = Math.floor(73 / pageSize) + 1;
                                                                var groupId = 2;
                                                        
                                                                function fetchPlaces() {
                                                                    Roblox.Website.Services.GroupService.GetGroupPlaces(groupId, (pageNum - 1) * pageSize, pageSize, function (data) {
                                                                        $('#GroupPlaces').html('');
                                                                        $(data).each(function (i, place) {
                                                                            
                                                                            var newPlace = $('#GroupPlaceTemplate').clone().removeAttr('id').show();
                                                                            newPlace.find('.PlaceThumb').append('<a href="' + place.NavigateUrl + '"><img src="' + place.IconUrl + '" alt="' + place.Name.escapeHTML() + '" width="100px" style="padding:2px;"/></a>');
                                                                            newPlace.find('.PlaceName').append('<a class="NameText notranslate" href="' + place.NavigateUrl + '">' + place.Name.escapeHTML().replace('&amp;hellip;','...') + ' </a>');
                                                                            newPlace.find('.PlayersOnline').html(place.Stats.CurrentPlayersCount + ' players online');
                                                                            $('#GroupPlaces').append(newPlace);
                                                                        });
                                                                        if (pageNum > 1)
                                                                            $('#GroupPlacesPrev').css('visibility', 'visible');
                                                                        else
                                                                            $('#GroupPlacesPrev').css('visibility', 'hidden');
                                                                        if (pageNum < lastPage)
                                                                            $('#GroupPlacesNext').css('visibility', 'visible');
                                                                        else
                                                                            $('#GroupPlacesNext').css('visibility', 'hidden');
                                                                    });
                                                                }
                                                        
                                                                $('#GroupPlacesPrev').click(function() {
                                                                    if (pageNum > 1)
                                                                        pageNum--;
                                                                    fetchPlaces();
                                                                });
                                                                $('#GroupPlacesNext').click(function() {
                                                                    if (pageNum < lastPage)
                                                                        pageNum++;
                                                                    fetchPlaces();
                                                                });
                                                        
                                                                fetchPlaces();
                                                            });
                                                        </script>
                                                    </div>
													<div id="GroupsPeoplePane_Members" class="tab-content" style="display: block;">
														<div id="GroupRoleSetsMembersPane">
															<div id="ctl00_cphRoblox_rbxGroupRoleSetMembersPane_GroupMembersUpdatePanel" class="MembersUpdatePanel">
																<div>
																	<div class="Members_DropDown">
																		<form method="GET" id="roleFilterForm">
																			<input type="hidden" name="gid" value="<?php echo $groupId ?>">
																			Page
																			<input type="text" id="pageInput" name="page" value="<?php echo $page ?>" style="width: 40px;">
																			<select name="role" id="roleSelect" class="MembersDropDownList" onchange="document.getElementById('roleFilterForm').submit();" style="max-width: 100%">
																				<?php foreach ($roles as $role): 
																					$roleId = (int)$role['RoleID'];
																					$count = isset($roleCounts[$roleId]) ? $roleCounts[$roleId] : 0;
																					$selected = ($roleId === $selectedRole) ? 'selected' : '';
																					$roleName = htmlspecialchars($role['RoleName']);
																				?>
																					<option value="<?php echo $roleId ?>" <?php echo $selected ?>>
																						<?php echo $roleName ?> (<?php echo $count ?>)
																					</option>
																				<?php endforeach; ?>
																			</select>
																		</form>
																	</div>

																	<div style="clear:both;"></div>

																	<?php foreach ($members as $index => $member): 
																		$groupMemberID = (int)$member['UserID'];
																		$groupMemberUsername = htmlspecialchars($member['Username']);
																		$groupMemberOnlineStatus = 'Offline';
																		$groupMemberOnlineStatusImage = '/images/offline.png';
																	?>
																	<div class="GroupMember">
																		<div class="Avatar">
																			<span class="OnlineStatus"><img alt="<?php echo $groupMemberUsername ?> is <?php echo $groupMemberOnlineStatus ?>." src="<?php echo $groupMemberOnlineStatusImage ?>" style="border-width:0;"></span>
																			<a title="<?php echo $groupMemberUsername ?>" href="/User.aspx?id=<?php echo $groupMemberID ?>" style="display:inline-block;height:100px;width:100px;cursor:pointer;">
																				<img height="100" width="100" border="0" src="/Thumbs/Avatar.ashx?userId=<?php echo $groupMemberID ?>&width=100&height=100" alt="<?php echo $groupMemberUsername ?>">
																			</a>
																		</div>
																		<div class="Summary">
																			<span class="Name"><a title="<?php echo $groupMemberUsername ?>" href="/User.aspx?id=<?php echo $groupMemberID ?>"><?php echo $groupMemberUsername ?></a></span>
																		</div>
																	</div>
																	<?php endforeach; ?>

																	<div style="clear:both;"></div>

																	<div id="ctl00_cphRoblox_rbxGroupRoleSetMembersPane_dlUsers_Footer_div" class="FooterPager">
																		<span>
																			<a class="pagerbtns previous" href="?gid=<?php echo $groupId ?>&role=<?php echo $selectedRole ?>&page=<?php echo max(1, $page-1) ?>" <?php if ($page <= 1) echo 'style="pointer-events:none;opacity:0.5;"'; ?>>&lt; Prev</a>
																			&nbsp;
																			Page 
																			<input type="text" value="<?php echo $page ?>" readonly style="width: 30px; text-align:center;">
																			of <?php echo max(1, $totalPages) ?>
																			&nbsp;
																			<a class="pagerbtns next" href="?gid=<?php echo $groupId ?>&role=<?php echo $selectedRole ?>&page=<?php echo min($totalPages, $page+1) ?>" <?php if ($page >= $totalPages) echo 'style="pointer-events:none;opacity:0.5;"'; ?>>Next &gt;</a>
																		</span>
																	</div>

																</div>
															</div>
															<div style="clear: both"></div>
														</div>
													</div>
                                                    <div id="GroupsPeoplePane_Allies" class="tab-content">
                                                        <div id="ctl00_cphRoblox_rbxGroupAlliesPane_RelationshipsUpdatePanel" class="grouprelationshipscontainer">

                                                            <div>



                                                                <div style="width:42px;height:42px;padding:8px;float:left">
                                                                    <a id="ctl00_cphRoblox_rbxGroupAlliesPane_RelationshipsListView_ctrl0_AssetImage1" alt="Telamon Fan Club!!!!!!!!!!!" title="Telamon Fan Club!!!!!!!!!!!" href="https://web.archive.org/web/20160216055208/http://www.roblox.com/Groups/group.aspx?gid=312987"
                                                                    style="display:inline-block;height:42px;width:42px;cursor:pointer;"><img src="https://web.archive.org/web/20160216055208im_/http://t1.rbxcdn.com/1ea7f92cf958e19d9670bbd9be08b023" height="42" width="42" border="0" alt="Telamon Fan Club!!!!!!!!!!!"></a>
                                                                </div>

                                                                <div style="clear:both;margin-bottom:10px;"></div>

                                                                <div style="text-align:center">

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div id="GroupsPeoplePane_Enemies" class="tab-content">

                                                    </div>
                                                    <div id="GroupsPeoplePane_Items" class="tab-content">

                                                        <div id="GroupItemPaneInstructions">
                                                            <p>Groups have the ability to create and sell official shirts, pants, and t-shirts! All revenue goes to group funds.</p>

                                                        </div>
                                                        <div id="GroupItemContent">
                                                            <div id="GroupItemPaneContent"></div>
                                                            <div style="clear:both;text-align: center;padding-top:25px;">
                                                                <a href="https://web.archive.org/web/20160216055208/http://www.roblox.com/catalog/browse.aspx?IncludeNotForSale=false&amp;SortType=3&amp;CreatorID=4584368">See more items for sale by this group</a>

                                                            </div>
                                                        </div>
                                                        <script type="text/javascript">
                                                            $(function () {
                                                                var url = '/catalog/html?CreatorId=4584368&ResultsPerPage=3&IncludeNotForSale=false&SortType=3&' + new Date().getTime();
                                                             $.ajax({
                                                                    method: "GET",
                                                                    url: url,
                                                                    crossDomain: true,
                                                                    xhrFields: {
                                                                        withCredentials: true
                                                                    }
                                                                }).done(function (data) {
                                                                    if (data.indexOf("CatalogItem") == -1) {
                                                                        $('#GroupItemPaneContent').html('<p>This group has no items.</p>');
                                                                        return;
                                                                    }
                                                                    $('#GroupItemPaneContent').html(data);
                                                                    Roblox.require('Widgets.ItemImage', function (itemImage) {
                                                                        itemImage.populate();
                                                                    });
                                                                }, "text");
                                                                });
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>



                                            <script type="text/javascript">
                                                Roblox.ExileModal.InitializeGlobalVars(282, 2);
                                            </script>

                                            


														<div id="ctl00_cphRoblox_GroupWallPane_Wall">
														<div class="StandardBox" style="margin-bottom: 0px; border-bottom: none;">
															<span class="InsideBoxHeader">Wall</span>
															<div style="clear:both;"></div>
														</div>
														<div class="StandardBox GroupWallPane" style="background:#fff;border-top:none;">
															<div style="padding:10px;">
																<form method="POST">
																	<textarea name="wall_text" rows="3" style="width:100%;" placeholder="Post something to the wall..."></textarea>
																	<input type="submit" name="WallPost" value="Post" class="btn-medium btn-neutral" style="margin-top:5px;">
																</form>
															</div>

															<?php foreach ($wallPosts as $index => $post): ?>
																<div class="<?= $index % 2 === 0 ? 'AlternatingItemTemplateEven' : 'AlternatingItemTemplateOdd' ?>">
																	<div class="RepeaterImage">
																		<a href="/User.aspx?id=<?= $post['PosterID'] ?>" style="display:inline-block;height:100px;width:100px;">
																			<img src="/Thumbs/Avatar.ashx?userId=<?= $post['PosterID'] ?>&width=100&height=100" height="100" width="100" alt="<?= htmlspecialchars($post['Username']) ?>">
																		</a>
																	</div>
																	<div class="RepeaterText">
																		<div class="GroupWall_PostContainer notranslate linkify">
																			<?= nl2br($post['Text']) ?>
																		</div>
																		<div>
																			<div class="GroupWall_PostDate">
																				<span style="color: Gray;"><?= date('n/j/Y g:i:s A', strtotime($post['TimePosted'])) ?></span> by
																				<span class="UserLink notranslate">
																					<a href="/User.aspx?id=<?= $post['PosterID'] ?>"><?= htmlspecialchars($post['Username']) ?></a>
																				</span>
																			</div>
																		</div>
																	</div>
																	<div style="clear:both;"></div>
																</div>
															<?php endforeach; ?>

															<div style="text-align:center; padding:10px;">
																<?php if ($wallPage > 1): ?>
																	<a href="?gid=<?= $groupId ?>&wallpage=<?= $wallPage - 1 ?>" class="pagerbtns previous">&lt; Previous</a>
																<?php endif; ?>
																Page <?= $wallPage ?> of <?= $totalWallPages ?>
																<?php if ($wallPage < $totalWallPages): ?>
																	<a href="?gid=<?= $groupId ?>&wallpage=<?= $wallPage + 1 ?>" class="pagerbtns next">Next &gt;</a>
																<?php endif; ?>
															</div>
														</div>

														</div>
                                                            <div style="clear:both;"></div>
                                                        </div>

                                                        <div style="clear:both;"></div>

                                                        <div style="clear:both;"></div>
                                                        <div id="ctl00_cphRoblox_GroupWallPane_dlUsers_Footer_div" class="FooterPager" onclick="handlePagerClick(event, 'wall');">
                                                            <span id="ctl00_cphRoblox_GroupWallPane_GroupWallPager"><span class="pagerbtns previous">&nbsp;</span>&nbsp;
                                                            <div id="ctl00_cphRoblox_GroupWallPane_GroupWallPager_ctl01_GroupWallPagerPanel">

                                                                <div id="ctl00_cphRoblox_GroupWallPane_GroupWallPager_ctl01_Div1" class="paging_wrapper">
                                                                    Page
                                                                    <input name="ctl00$cphRoblox$GroupWallPane$GroupWallPager$ctl01$PageTextBox" type="text" value="1" id="ctl00_cphRoblox_GroupWallPane_GroupWallPager_ctl01_PageTextBox" class="paging_input"> of
                                                                    <div class="paging_pagenums_container">43717</div>
                                                                    <input type="submit" name="ctl00$cphRoblox$GroupWallPane$GroupWallPager$ctl01$HiddenInputButton" value="" onclick="loading('wall');" id="ctl00_cphRoblox_GroupWallPane_GroupWallPager_ctl01_HiddenInputButton" class="pagerbtns" style="display:none;">
                                                                </div>

                                                            </div>
                                                            <a class="pagerbtns next" href="javascript:__doPostBack('ctl00$cphRoblox$GroupWallPane$GroupWallPager$ctl02$ctl00','')">&nbsp;</a>&nbsp;</span>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div id="right-column">
										<div id="ctl00_cphRoblox_rbxGroupFundsPane_GroupFunds" class="StandardBox" style="padding-right:0">
											<b>Funds:</b>
											<span class="robux" style="margin-left:5px"><?php echo htmlspecialchars($funds[0]); ?></span>
											<span class="tickets" style="margin-left:5px"><?php echo htmlspecialchars($funds[1]); ?></span>
										</div>
                                        <div id="Div2" style="height: 600px">


                                            <iframe name="Roblox_Default_Right_160x600" allowtransparency="true" frameborder="0" height="612" scrolling="no" src="/userads/2" width="160" data-js-adtype="iframead" data-ruffle-polyfilled=""></iframe>

                                        </div>
                                    </div>
                                    <div style="clear: both;"></div>
                                    <script type="text/javascript">
                                        if (typeof Roblox === "undefined") {
                                            Roblox = {};
                                        }
                                        if (typeof Roblox.Resources === "undefined") {
                                            Roblox.Resources = {};
                                        }
                                        Roblox.Resources.more = "More";
                                        Roblox.Resources.less = "Less";
                                    </script>
                                </div>

                                <div style="clear:both"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
	<script type="text/javascript" src="/js/emoji.js"></script>

</body>
</html>
