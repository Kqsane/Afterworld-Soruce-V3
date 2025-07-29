<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$isAdmin = 0;
$currentPage = basename($_SERVER['PHP_SELF']);

$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if (!$info || !isset($info['UserId'])) {
    logout();
    header("Location: /");
    exit();
}

if (!empty($_COOKIE['_ROBLOSECURITY'])) {
    $userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    if (is_array($userInfo) && !empty($userInfo['UserId'])) {
        $userId = (int)$userInfo['UserId'];
        $stmt = $pdo->prepare('SELECT isAdmin FROM users WHERE UserId = :userId LIMIT 1');
        $stmt->execute([':userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $isAdmin = isset($row['isAdmin']) ? (int)$row['isAdmin'] : 0;
    }
}
if ($isAdmin === 0 && stripos($currentPage, 'home') === false) {
    header('Location: /home');
    exit();
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/Admi/SideBar.php';
if ($adminLevel < AdminLevel::ASSET_MOD) {
    echo <<<HTML
<div class="main-content"><br>
    <div class="header">
        <h1>We empty as HELL!</h1>
    </div>
</div>
</body>
</html>
HTML;
exit;
}
$userId = $info['UserId'];
$username = $info['Username'] ?? 'Guest';
$searchResult = null;
$banInfo = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($adminLevel < AdminLevel::ASSET_MOD) {
        http_response_code(403);
        echo 'Unauthorized';
        exit();
    }
    if (!empty($_POST['userid'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE UserId = :value LIMIT 1");
        $stmt->bindValue(':value', $_POST['userid']);
        $stmt->execute();
        $searchResult = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (!empty($_POST['username'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE Username = :value LIMIT 1");
        $stmt->bindValue(':value', $_POST['username']);
        $stmt->execute();
        $searchResult = $stmt->fetch(PDO::FETCH_ASSOC);
    }


    if ($searchResult && isset($searchResult['UserId'])) {
        $stmt = $pdo->prepare("SELECT * FROM bans WHERE UserId = :userid ORDER BY ReviewedAt DESC LIMIT 1");
        $stmt->bindParam(':userid', $searchResult['UserId']);
        $stmt->execute();
        $banInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    if ($adminLevel < AdminLevel::ASSET_MOD) {
        http_response_code(403);
        echo 'Unauthorized';
        exit();
    }
    $newUsername = trim($_POST['new_username']);
    $newBio = trim($_POST['new_bio']);
    $editUserId = intval($_POST['edit_userid']);

    if (!empty($newUsername) && !empty($editUserId)) {
        $stmt = $pdo->prepare("UPDATE users SET Username = :username, Description = :bio WHERE UserId = :userid");
        $stmt->bindParam(':username', $newUsername);
        $stmt->bindParam(':bio', $newBio);
        $stmt->bindParam(':userid', $editUserId, PDO::PARAM_INT);
        $stmt->execute();


        $stmt = $pdo->prepare("SELECT * FROM users WHERE UserId = :userid LIMIT 1");
        $stmt->bindValue(':userid', $editUserId);
        $stmt->execute();
        $searchResult = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($searchResult) {
            $stmt = $pdo->prepare("SELECT * FROM bans WHERE UserId = :userid ORDER BY ReviewedAt DESC LIMIT 1");
            $stmt->bindParam(':userid', $searchResult['UserId']);
            $stmt->execute();
            $banInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}
?>
<div class="main-content">
    <br><br>
    <div class="header">
        <h1>Find User</h1>
    </div>
    <br>

    <form method="POST" class="form-content">
        <div class="form-group">
            <span class="bold">UserId</span>
            <input class="lookup" name="userid" type="text">
            <button type="submit">Submit</button>

            <span class="bold marginleft">UserName</span>
            <input class="lookup" name="username" type="text">
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
                    This user is a admin!
                </div>
            <?php endif; ?>

            <div style="display: flex; flex-direction: column; gap: 15px; font-size: 22px;">
                <div><span class="bold">Asset Name:</span> <a href="http://aftwld.xyz/User.aspx?ID=<?= $userId ?>" style="color: #2578BB; text-decoration: none;"><?= $username ?></a></div>
                <div><span class="bold">Asset ID:</span> <?= $assetId ?></div>
                <div><span class="bold">Asset Type:</span> <?= $membership ?></div>
                <div><span class="bold">Submission Date:</span> <?= date("F j, Y g:i A", $joinDate) ?></div>
<div><span class="bold">Banned:</span> <?= (!empty($searchResult['IsBanned']) && $searchResult['IsBanned'] == 1) ? 'Yes' : 'No' ?></div>

<div><hr></div>
    <div><span class="bold">Username:</span> <a href="http://aftwld.xyz/User.aspx?ID=<?= $userId ?>" style="color: #2578BB; text-decoration: none;"><?= $username ?></a></div>
    <div><span class="bold">Join Date:</span> <?= date("F j, Y g:i A", $joinDate) ?></div>
    <div><span class="bold">Internal Note:</span> <?= htmlspecialchars($banInfo['InternalNote']) ?></div>
    <div><span class="bold">Banned:</span> <?= (!empty($searchResult['IsBanned']) && $searchResult['IsBanned'] == 1) ? 'Yes' : 'No' ?></div>
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



<?php if ($searchResult): ?>
   // placeholde
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
   <p>No users found.</p>
<?php endif; ?>



</div>
<script>
function openModal() {
  document.getElementById("editUserModal").style.display = "block";
}

function closeModal() {
  document.getElementById("editUserModal").style.display = "none";
}

// Close when clicking outside modal
window.onclick = function(event) {
  const modal = document.getElementById("editUserModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
</body>
</html>
