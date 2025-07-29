<?php

declare(strict_types=1);
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/Admi/SideBar.php';

$info = getuserinfo($_COOKIE["_ROBLOSECURITY"] ?? '');
if (!$info || (int)($info['isAdmin'] ?? 0) < 2) {
    die("Access denied."); // wow super security
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assetId'], $_POST['action'])) {
    $assetId = (int)$_POST['assetId'];
    $action = (int)$_POST['action']; // 0-3

    if (in_array($action, [0, 1, 2, 3], true)) {
        $stmt = $pdo->prepare("UPDATE assets SET isApproved = :status, Updated_At = UNIX_TIMESTAMP() WHERE AssetID = :id");
        $stmt->execute([':status' => $action, ':id' => $assetId]);
    }
}

$stmt = $pdo->query("SELECT AssetID, Name, CreatorID, isApproved FROM assets ORDER BY Created_At DESC");
$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$searchResult = null;


$statusMap = [
    0 => 'Unapproved',
    1 => 'Approved',
    2 => 'Waiting',
    3 => 'Review'
];
?>
<div class="main-content">
    <br><br>
    <div class="header">
        <h1>Asset Approval</h1>
    </div>
    <br>

    <form method="POST" class="form-content">
        <div class="form-group">
            <span class="bold">AssetId</span>
            <input class="lookup" name="userid" type="text">
            <button type="submit">Submit</button>
        </div>
    </form>
<?php if ($searchResult): ?>
<div style="background-color: #fff3cd; color: #856404; font-weight: bold; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    User successfully found!
                </div>
    <?php
        $membershipNames = ['NoMemberShip', 'BuildersClub', 'TurboBuildersClub', 'OutrageousBuildersClub'];
        $membership = isset($searchResult['Membership']) ? $membershipNames[(int)$searchResult['Membership']] ?? 'Unknown' : 'Unknown';
        $userId = (int)$searchResult['UserId'];
        $username = htmlspecialchars($searchResult['Username']);
        $joinDate = htmlspecialchars($searchResult['JoinData']);
        $isAdminFlag = isset($searchResult['isAdmin']) && (int)$searchResult['isAdmin'] === 1;
    ?>
    <div style="margin-top: 60px; border: 1px solid #ccc; border-radius: 10px; padding: 30px; background-color: #f9f9f9; display: flex; gap: 30px; width: fit-content;">
        
        <div style="min-width: 150px;">

            <img src="http://aftwld.xyz/Thumbs/Avatar.ashx?userId=<?php echo $userId; ?>" alt="User Avatar" style="width: 150px; height: 150px; border-radius: 10px; border: 1px solid #aaa;">
        </div>

        <div style="display: flex; flex-direction: column;">

            <?php if ($userId === 1): ?>
                <div style="background-color: #ffcccc; color: red; font-weight: bold; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    This user is a protected and holder of AFTERWORLD!
                </div>
            <?php elseif ($isAdminFlag): ?>
                <div style="background-color: #fff3cd; color: #856404; font-weight: bold; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    This user is protected!
                </div>
            <?php endif; ?>

            <div style="display: flex; flex-direction: column; gap: 15px; font-size: 22px;">
                <div><span class="bold">Username:</span> <a href="http://aftwld.xyz/User.aspx?ID=<?= $userId ?>" style="color: #2578BB; text-decoration: none;"><?= $username ?></a></div>
                <div><span class="bold">User ID:</span> <?= $userId ?></div>
                <div><span class="bold">Membership:</span> <?= $membership ?></div>
                <div><span class="bold">Join Date:</span> <?= date("F j, Y g:i A", $joinDate) ?></div>
<div><span class="bold">Banned:</span> <?= (!empty($searchResult['IsBanned']) && $searchResult['IsBanned'] == 1) ? 'Yes' : 'No' ?></div>

<div><hr></div>
<div><span class="bold">Last Ban Record</div>
<?php if ($banInfo): ?>
    <div><span class="bold">Ban Length:</span> <?= $banInfo['BanLength'] ?> seconds</div>
    <div><span class="bold">Reviewed At:</span> <?= date('Y-m-d H:i:s', $banInfo['ReviewedAt']) ?></div>
    <div><span class="bold">Mod Note:</span> <?= htmlspecialchars($banInfo['ModNote']) ?></div>
    <div><span class="bold">Internal Note:</span> <?= htmlspecialchars($banInfo['InternalNote']) ?></div>
    <div><span class="bold">isWarning:</span> <?= $banInfo['isWarning'] ? 'Yes' : 'No' ?></div>
<?php else: ?>
    <div><span class="bold">No ban record found.</span></div>
<?php endif; ?>
<div><hr></div>

                <div><span class="bold">Action:</span> <a href="/Admi/PerformAction.aspx?id=<?= $userId ?>" style="color: #2578BB;">Perform Action</a></div>
                <div><button onclick="openModal()">Edit User Info</button></div>
            </div>
        </div>
    </div>
    
<div id="editUserModal" class="modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit User Info</h5>
      </div>
      <form method="POST" class="modal-body">
        <input type="hidden" name="edit_userid" value="<?= htmlspecialchars($searchResult['UserId']) ?>">
        <input type="hidden" name="update_user" value="1">

        <label for="new_username">New Username</label>
        <input type="text" id="new_username" name="new_username" value="<?= htmlspecialchars($searchResult['Username']) ?>" required>

        <label for="new_bio">New Bio</label>
        <textarea id="new_bio" name="new_bio" rows="4"><?= htmlspecialchars($searchResult['Bio'] ?? '') ?></textarea>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>



<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p>No users found.</p>
<?php endif; ?>

