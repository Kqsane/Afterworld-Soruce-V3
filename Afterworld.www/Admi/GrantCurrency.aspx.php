<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

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

$userId = $info['UserId'];
$username = $info['Username'] ?? 'Guest';
$searchResult = null;
$banInfo = null;
$isAdmin  = 0;
$currentPage = basename($_SERVER['PHP_SELF']);

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

if ($searchResult && isset($searchResult['UserId'])) {
    $stmt = $pdo->prepare("SELECT * FROM bans WHERE UserId = :userid ORDER BY ReviewedAt DESC LIMIT 1");
    $stmt->bindParam(':userid', $searchResult['UserId']);
    $stmt->execute();
    $banInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT Robux, Tix FROM users WHERE UserId = ?");
    $stmt->execute([$searchResult['UserId']]);
    $userCurrency = $stmt->fetch(PDO::FETCH_ASSOC);

    $currentRobux = $userCurrency['Robux'] ?? 0;
    $currentTix = $userCurrency['Tix'] ?? 0;
}

if ($searchResult && isset($searchResult['UserId'])) {
    $stmt = $pdo->prepare("SELECT Robux, Tix FROM users WHERE UserId = ?");
    $stmt->execute([$searchResult['UserId']]);
    $userCurrency = $stmt->fetch(PDO::FETCH_ASSOC);

    $currentRobux = $userCurrency['Robux'] ?? 0;
    $currentTix = $userCurrency['Tix'] ?? 0;
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

$currentRobux = null;
$currentTix = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grant_currency'])) {
	if ($adminLevel < AdminLevel::ADMIN) {
        http_response_code(403);
        echo 'Unauthorized';
        exit();
    }
    $editUserId = (int)$_POST['edit_userid'];
    $robuxAmount = (int)$_POST['robux'];
    $tixAmount = (int)$_POST['tix'];

    if ($editUserId > 0) {
        $stmt = $pdo->prepare("SELECT Robux, Tix FROM users WHERE UserId = ?");
        $stmt->execute([$editUserId]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        $newRobux = max(0, ($current['Robux'] ?? 0) + $robuxAmount);
        $newTix = max(0, ($current['Tix'] ?? 0) + $tixAmount);

        $stmt = $pdo->prepare("UPDATE users SET Robux = ?, Tix = ? WHERE UserId = ?");
        $stmt->execute([$newRobux, $newTix, $editUserId]);

        $message = "Successfully updated currency: Robux = {$newRobux}, Tix = {$newTix}.";

        $stmt = $pdo->prepare("SELECT * FROM users WHERE UserId = ?");
        $stmt->execute([$editUserId]);
        $searchResult = $stmt->fetch(PDO::FETCH_ASSOC);

        $currentRobux = $newRobux;
        $currentTix = $newTix;
    }
}

if ($searchResult && isset($searchResult['UserId'])) {
    $stmt = $pdo->prepare("SELECT Robux, Tix FROM users WHERE UserId = ?");
    $stmt->execute([$searchResult['UserId']]);
    $userCurrency = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentRobux = $userCurrency['Robux'] ?? 0;
    $currentTix = $userCurrency['Tix'] ?? 0;
}
?>
<div class="main-content">
    <div class="header">
        <h1>Grant Currency</h1>
    </div>
    <br>
            <div style="background-color: #ffcccc; color: red; font-weight: bold; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    Abusing the system will get you revoked out of the admin team
                </div>
                 <div style="background-color: #ffcccc; color: red; font-weight: bold; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    Use this for giveaway or test purposes!
                </div>
                <hr>
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
<?php if (isset($currentRobux) && isset($currentTix)): ?>
  <div><span class="bold">Robux:</span> <?= htmlspecialchars((string)$currentRobux) ?></div>
  <div><span class="bold">Tix:</span> <?= htmlspecialchars((string)$currentTix) ?></div>
<?php else: ?>
  <div><span class="bold">Robux:</span> N/A</div>
  <div><span class="bold">Tix:</span> N/A</div>
<?php endif; ?>
                <div><button onclick="openModal()">Edit Currency</button></div>
            </div>
        </div>
    </div>

<div id="editUserModal" class="modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Grant Robux and Tix</h5>
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

          <label for="robux">Robux Amount (use negative to remove):</label>
            <input type="number" name="robux" id="robux" class="form-control" min="-1000000" max="1000" required>

            <label for="tix" style="margin-top: 10px;">Tix Amount (use negative to remove):</label>
            <input type="number" name="tix" id="tix" class="form-control" min="-1000000" max="4000" required>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
            <button type="submit" name="grant_currency" class="btn btn-primary">Grant Currency</button>
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
    </div>