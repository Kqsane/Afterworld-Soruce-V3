<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$isAdmin  = 0;
$currentPage = basename($_SERVER['PHP_SELF']);

if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    logout();
    header("Location: /newlogin");
    exit();
}

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
if ($adminLevel < AdminLevel::ADMIN) {
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
$userId = $info['UserId'];
$username = $info['Username'] ?? 'Guest';
$searchResult = null;
$banInfo = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($adminLevel < AdminLevel::ADMIN) {
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
    if ($adminLevel < AdminLevel::ADMIN) {
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

$stmt = $pdo->prepare("SELECT Membership FROM users WHERE userId = ?");
$stmt->execute([$userId]);
$current = $stmt->fetchColumn();

$membershipOptions = [
    0 => 'NoBuildersClub',
    1 => 'BuildersClub',
    2 => 'TurboBuildersClub',
    3 => 'OutrageousBuildersClub'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_membership'])) {
    if ($adminLevel < AdminLevel::ADMIN) {
        http_response_code(403);
        echo 'Unauthorized';
        exit();
    }
    $membership = (int)$_POST['membership'];
    $editUserId = (int)$_POST['edit_userid'];

    if ($editUserId && array_key_exists($membership, $membershipOptions)) {
        $updateStmt = $pdo->prepare("UPDATE users SET Membership = ? WHERE UserId = ?");
        $updateStmt->execute([$membership, $editUserId]);
        $message = "Membership successfully updated.";

        $stmt = $pdo->prepare("SELECT * FROM users WHERE UserId = :userid LIMIT 1");
        $stmt->bindValue(':userid', $editUserId);
        $stmt->execute();
        $searchResult = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<div class="main-content">
    <div class="header">
        <h1>Grant Membership</h1>
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
                <div><span class="bold">Active Membership(s):</span> <?= $membership ?></div>
                <div><button onclick="openModal()">Edit Membership</button></div>
            </div>
        </div>
    </div>
    
<div id="editUserModal" class="modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit User Membership</h5>
      </div>

      <?php if (isset($message)): ?>
        <div class="alert alert-success" style="margin: 10px;">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>

      <?php if ($searchResult): ?>
        <form method="POST">
          <div class="modal-body">
            <input type="hidden" name="edit_userid" value="<?= $searchResult['UserId'] ?>">

            <label for="membership">Select Membership:</label>
            <select name="membership" id="membership" class="form-select">
              <?php foreach ($membershipOptions as $value => $label): ?>
                <option value="<?= $value ?>" <?= ($searchResult['Membership'] == $value ? 'selected' : '') ?>>
                  <?= $label ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
            <button type="submit" name="apply_membership" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      <?php else: ?>
        <div class="modal-body">
          <p>No user selected. Search for a user first.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>
    </div>
  </div>
</div>



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
