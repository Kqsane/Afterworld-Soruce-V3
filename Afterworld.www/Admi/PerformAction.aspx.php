<!DOCTYPE html>
<html lang="en">
<head>
<!-- afterworld uses 100% pure human code, are u rlly thinking that we use chatgpt LOOOl -->
<!-- wawa -skyler -->
<?php
// trademark newuser, coded all of this lmao
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$isAdmin = 0;
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
if ($adminLevel < AdminLevel::MODERATOR) {
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
$banSuccess = false;
$targetUserId = 0;
$targetUserData = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT UserId, Username, IsBanned, isAdmin, Membership FROM users WHERE UserId = :id LIMIT 1");
    $stmt->execute([':id' => intval($_GET['id'])]);
    $targetUserData = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (isset($_GET['username'])) {
    $stmt = $pdo->prepare("SELECT UserId, Username, IsBanned, isAdmin, Membership FROM users WHERE Username = :username LIMIT 1");
    $stmt->execute([':username' => $_GET['username']]);
    $targetUserData = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($targetUserData) {
    $targetUserId = $targetUserData['UserId'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ban'])) {
    if ($adminLevel < AdminLevel::MODERATOR) {
        http_response_code(403);
        echo 'Unauthorized';
        exit();
    }
    $banType = $_POST['ban_type'] ?? '';
    $moderationReason = $_POST['moderation_reason'] ?? '';
    $presetMessage = $_POST['preset_message'] ?? '';
    $userMessage = $_POST['user_message'] ?? '';
    $internalNote = $_POST['internal_note'] ?? '';
    $targetUserId = intval($_POST['target_userid'] ?? 0);

    $banLengths = [
        'warn' => 0,
        'ban1' => 86400,
        'ban3' => 259200,
        'ban7' => 604800,
        'ban14' => 1209600,
        'delete' => 999999999,
        'poison' => 999999998,
        'macban' => 999999997,
    ];

    $banLength = $banLengths[$banType] ?? 0;
    $isWarning = ($banType === 'warn') ? 1 : 0;
    $reviewedAt = time();

    $stmt = $pdo->prepare("UPDATE users SET IsBanned = 1 WHERE UserId = :userid");
    $stmt->bindParam(':userid', $targetUserId);
    $stmt->execute();

    $stmt = $pdo->prepare("INSERT INTO bans (UserId, BanLength, ReviewedAt, ModNote, InternalNote, isWarning) VALUES (:userid, :banlength, :reviewed, :modnote, :internal, :iswarning)");
    $stmt->execute([
        ':userid' => $targetUserId,
        ':banlength' => $banLength,
        ':reviewed' => $reviewedAt,
        ':modnote' => $userMessage,
        ':internal' => $internalNote,
        ':iswarning' => $isWarning
    ]);

    $banSuccess = true;
}
$moderationHistory = [];

if ($targetUserId) {
    $stmt = $pdo->prepare("SELECT * FROM bans WHERE UserId = :userid ORDER BY ReviewedAt DESC");
    $stmt->bindParam(':userid', $targetUserId, PDO::PARAM_INT);
    $stmt->execute();
    $moderationHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$transactions = [];
$sales = [];

if ($targetUserId) {
    $stmt = $pdo->prepare("SELECT t.transactionDate, u.Username, t.reason, t.robux, t.tix FROM transactions t LEFT JOIN users u ON t.userId = u.UserId WHERE t.userId = :userid AND t.reason = 1 ORDER BY t.transactionDate DESC LIMIT 50");
    $stmt->execute([':userid' => $targetUserId]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT t.transactionDate, u.Username, t.reason, t.robux, t.tix FROM transactions t LEFT JOIN users u ON t.userId = u.UserId WHERE t.userId = :userid AND t.reason = 2 ORDER BY t.transactionDate DESC LIMIT 50");
    $stmt->execute([':userid' => $targetUserId]);
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
</head>
<body>
<script src="buttons.js"></script>
<style>
.custom-tabs {
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    border-bottom: 2px solid #ccc;
    padding: 0;
    margin-bottom: 20px;
    gap: 4px;
}

.tab-item {
    padding: 10px 16px;
    cursor: pointer;
    background: #f1f1f1;
    border: 1px solid transparent;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
    transition: background-color 0.2s ease;
}

.tab-item:hover {
    background-color: #ddd;
}

.tab-item.active {
    background: white;
    border: 1px solid #ccc;
    border-bottom: 2px solid white;
    font-weight: bold;
}

.tab-content {
    padding: 20px;
    border: 1px solid #ccc;
    background: white;
    border-radius: 0 6px 6px 6px;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
}

.custom-card {
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 1.5rem;
  background: #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.custom-card-header {
  background: #f0f0f0;
  padding: 12px 16px;
  border-bottom: 1px solid #ccc;
}

.custom-card-header h5 {
  margin: 0;
  font-size: 1.1rem;
}

.custom-card-body {
  padding: 16px;
}

.summary-row {
  display: flex;
  flex-wrap: wrap;
  gap: 24px;
  margin-bottom: 16px;
}

.summary-col {
  flex: 1 1 300px;
}

.summary-item {
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
}

.summary-item .label {
  font-weight: bold;
  margin-right: 8px;
  min-width: 150px;
}

.inline-link {
  margin-left: 10px;
  font-size: 0.9rem;
  color: #007BFF;
  text-decoration: none;
}

.inline-link:hover {
  text-decoration: underline;
}

.badge {
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 0.85rem;
  font-weight: bold;
  color: white;
}

.badge.success {
  background-color: #28a745;
}

.custom-table-wrapper {
  overflow-x: auto;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 12px;
}

.custom-table td {
  border: 1px solid #ddd;
  padding: 10px;
}

.custom-table .label {
  font-weight: bold;
  width: 180px;
}

.muted {
  color: #777;
}

.btn.small {
  padding: 4px 10px;
  font-size: 0.85rem;
  background: #f5f5f5;
  border: 1px solid #ccc;
  cursor: pointer;
  border-radius: 4px;
}

.btn.small:hover {
  background: #e0e0e0;
}
span.robux,
div.robux {
    background: url('http://www.roblox.com/images/Icons/img-robux.png') no-repeat 0 2px;
    color: #060;
    font-weight: bold;
    padding-left: 24px;
    padding-top: 4px;
    padding-bottom: 4px;
    font-size: 16px;
    line-height: 20px;
    display: inline-block;
    vertical-align: middle;
    background-size: 16px 16px; /* ensures proper scaling */
}

span.tickets,
div.tickets {
    background: url('http://www.roblox.com/images/Tickets.png') no-repeat 0 2px;
    color: #A61;
    font-weight: bold;
    padding-left: 24px;
    padding-top: 4px;
    padding-bottom: 4px;
    font-size: 16px;
    line-height: 20px;
    display: inline-block;
    vertical-align: middle;
    background-size: 16px 16px; /* scale for clarity */
}

.robux-text {
    color: #060;    
    font-weight: bold;
}
</style>
<?php
$membershipNames = ['NoMemberShip', 'BuildersClub', 'TurboBuildersClub', 'OutrageousBuildersClub'];
$membership = isset($targetUserData['Membership']) ? ($membershipNames[(int)$targetUserData['Membership']] ?? 'Unknown') : 'Unknown';
$isAdminFlag = isset($targetUserData['isAdmin']) && (int)$targetUserData['isAdmin'] === 1;
?>


<div class="main-content">
                <div style="background-color: #ffcccc; color: red; font-weight: bold; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    This is not finished and not finalized, expect some updates!
                </div>
<ul class="custom-tabs" id="tabMenu">
    <li class="tab-item active" data-tab="moderate">Moderate</li>
    <li class="tab-item" data-tab="account">Account</li>
    <li class="tab-item" data-tab="mod-history">Moderation History</li>
    <li class="tab-item" data-tab="transactions">Transactions</li>
    <li class="tab-item" data-tab="trades">Trades</li>
    <li class="tab-item" data-tab="messages">Messages</li>
    <li class="tab-item" data-tab="membership">Membership</li>
    <li class="tab-item" data-tab="assets">Adjust Assets</li>
    <li class="tab-item" data-tab="devices">Devices & Unique Info</li>
    <li class="tab-item" data-tab="settings">User Settings</li>
</ul>

<div class="tab-content">

    <div class="tab-panel active" id="moderate">
    <div class="header">
        <h1><?= htmlspecialchars($targetUserData['Username']) ?></h1>
        <br>
    </div>

    <?php if ($targetUserData): ?>
        <div class="user-info" style="margin-bottom: 20px; background: #f5f5f5; padding: 10px; border: 1px solid #ccc;">
            <strong>User Info:</strong><br>
            UserId: <?= htmlspecialchars($targetUserData['UserId']) ?><br>
            Username: <?= htmlspecialchars($targetUserData['Username']) ?><br>
            Ban Status: <?= $targetUserData['IsBanned'] ? 'Banned' : 'Not Banned' ?>
        </div>
    <?php elseif (isset($_GET['id']) || isset($_GET['username'])): ?>
        <div style="color: red; margin-bottom: 20px;">User not found.</div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="target_userid" value="<?= htmlspecialchars($targetUserId) ?>">
        <div class="form-group">
            <div style="background-color: #ffcccc; color: red; font-weight: bold; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                Right now there's no option to unban. If you want to unban somebody (like falsely banned) please select the "Warn" option. Otherwise, the system does its thing itself.
            </div>
            <label>Account State Override:</label>
            <div><input type="radio" name="ban_type" value="warn"> Warn</div>
            <div><input type="radio" name="ban_type" value="ban1"> Ban 1 Day</div>
            <div><input type="radio" name="ban_type" value="ban3"> Ban 3 Days</div>
            <div><input type="radio" name="ban_type" value="ban7"> Ban 7 Days</div>
            <div><input type="radio" name="ban_type" value="ban14"> Ban 14 Days</div>
            <div><input type="radio" name="ban_type" value="delete"> Delete</div>
            <div><input type="radio" name="ban_type" value="poison"> Poison</div>
            <div><input type="radio" name="ban_type" value="macban"> MAC Ban</div>
        </div>

        <div class="form-group">
            <label for="user-message">Message to User:</label>
            <textarea name="user_message" id="user-message"></textarea>
        </div>
        <div class="form-group">
            <label for="internal-note">Internal Moderation Note:</label>
            <textarea name="internal_note" id="internal-note"></textarea>
        </div>
        <button type="submit" name="submit_ban">Submit</button>
    </form>

    <?php if (!empty($banSuccess)): ?>
        <div class="hidden2" style="display:block">
            <span style="color: green; margin-top: 16px;">✔ Action completed successfully.</span>
        </div>
    <?php endif; ?>
</div>

    <div class="tab-panel" id="account">
    
    <div class="custom-card">
  <div class="custom-card-header">
    <h5>Account Summary</h5>
  </div>
  <div class="custom-card-body">
    <div class="summary-row">
      <div class="summary-col">
        <div class="summary-item">
          <span class="label">User Name:</span>
          <span><?= htmlspecialchars($targetUserData['Username']) ?></span>
        </div>
        <div class="summary-item">
          <span class="label">Previous User Names:</span>
          <span>X</span>
        </div>
      </div>
      <div class="summary-col">
        <div class="summary-item">
          <span class="label">Moderation Status:</span>
          <span class="badge success">OK</span>
        </div>
      </div>
    </div>

    <div class="custom-table-wrapper">
      <table class="custom-table">
        <tbody>
          <tr>
            <td class="label">User ID</td>
            <td><?= htmlspecialchars($targetUserData['UserId']) ?></td>
          </tr>
          <tr>
            <td class="label">Username</td>
            <td><?= htmlspecialchars($targetUserData['Username']) ?></td>
          </tr>
          <tr>
            <td class="label">Protected User</td>
            <td>
<?php
if ($targetUserId === 1) {
    echo 'This user is a protected and holder of AFTERWORLD!';
} elseif ($isAdminFlag) {
    echo 'This user is protected!';
} else {
    echo 'X';
}
?>

            <td></td>
          </tr>
          <tr>
            <td class="label">User banned</td>
            <td><?= $targetUserData['IsBanned'] ? 'Yes' : 'No' ?></td>
            <td></td>
          </tr>
          <tr>
            <td class="label">Membership Info</td>
            <td><?= $membership ?></td>
            <td></td>
          </tr>
          <tr>
            <td class="label">Last Activity</td>
            <td>09/08/17</td>
            <td></td>
          </tr>
          <tr>
            <td class="label">Current Location</td>
            <td><span class="muted">N / A</span></td>
            <td></td>
          </tr>
          <tr>
            <td class="label">XBOX User</td>
            <td>N / A</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

    </div>
    <div class="tab-panel" id="mod-history">
    
    <?php if (!empty($moderationHistory)): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="mb-0">Moderation History of <b><?= htmlspecialchars($targetUserData['Username']) ?></b></h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Internal Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($moderationHistory as $entry): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i:s', $entry['ReviewedAt']) ?></td>
                                <td>
                                    <?php
                                        switch ((int)$entry['BanLength']) {
                                            case 0: echo 'Warning'; break;
                                            case 86400: echo '1-Day Ban'; break;
                                            case 259200: echo '3-Day Ban'; break;
                                            case 604800: echo '7-Day Ban'; break;
                                            case 1209600: echo '14-Day Ban'; break;
                                            case 999999999: echo 'Account Deleted'; break;
                                            case 999999998: echo 'Poison Ban'; break;
                                            case 999999997: echo 'MAC Ban'; break;
                                            default: echo 'Custom ('. $entry['BanLength'] .'s)'; break;
                                        }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($entry['ModNote']) ?></td>
                                <td><?= htmlspecialchars($entry['InternalNote']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">No moderation history found for this user.</div>
<?php endif; ?>
    </div>
    <div class="tab-panel" id="transactions">
    <span class="robux">9,999</span>
<br />
<span class="tickets">9,999</span>
<br />
<div class="robux-text">Robux text</div>

    </div>
    <div class="tab-panel" id="trades">This is the Trades tab content.</div>
    <div class="tab-panel" id="messages">This is the Messages tab content.</div>
    <div class="tab-panel" id="membership">This is the Membership tab content.</div>
    <div class="tab-panel" id="assets">This is the Adjust Assets tab content.</div>
    <div class="tab-panel" id="devices">This is the Devices & Unique Info tab content.</div>
    <div class="tab-panel" id="settings">This is the User Settings tab content.</div>
</div>
</body>
</html>

<script>
document.querySelectorAll('.tab-item').forEach(tab => {
    tab.addEventListener('click', function () {
        // Remove 'active' class from all tabs
        document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));

        // Activate clicked tab and its corresponding panel
        this.classList.add('active');
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});
</script>

