<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
class AdminLevel {
    const ASSET_MOD = 1;
    const MODERATOR = 2;
    const ADMIN = 3;
    const HEAD_ADMIN = 4;
    const DEV = 5;
}
$currentPage = basename($_SERVER['PHP_SELF']);
if (empty($_COOKIE["_ROBLOSECURITY"])) {
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
$userId = (int)$info['UserId'];
$username = $info['Username'] ?? 'Guest';
$adminLevel = 0;
$stmt = $pdo->prepare('SELECT isAdmin FROM users WHERE UserId = :userId LIMIT 1');
$stmt->execute([':userId' => $userId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row && isset($row['isAdmin'])) {
    $adminLevel = (int)$row['isAdmin'];
}
function requireAdminLevel(int $requiredLevel, int $actualLevel) {
    if ($actualLevel < $requiredLevel) {
        header("Location: /home");
        exit();
    }
}
if ($adminLevel === 0 && stripos($currentPage, 'home') === false) {
    header('Location: /home');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="/Admi/favicon.ico" type="image/x-icon" />
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Afterworld CSM</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        zoom: 67%;
    }
    .sidebar {
        margin-top: 70px;
        margin-left: 40px;
        margin-right: 40px;
        margin-bottom: 0;
        width: 350px;
        border: 1px solid #2578BB;
        border-radius: 6px;
        z-index: 1;
        overflow-y: auto;
    }
    .section {
        border-bottom: 4px solid #e0e0e0;
        background-color: #f4f4f4;
        padding: 15px;
        height: 15px;
        width: 98.7%;
        position: absolute;
        justify-content: space-between;
    }
    .welcome {
        float: right;
    }
    .home {
        position: absolute;
        margin-top: 55px;
    }
    .main-content {
        flex-grow: 1;
        padding: 20px;
        margin-top: 70px;
    }
    h1 {
        margin-top: 0;
        font-weight: normal;
        position: absolute;
        margin-top: 30px;
    }
    h2 {
        font-size: 18px;
        color: white;
        background-color: #2578BB;
        padding: 20px;
        margin-top: -5px;
        height: 15px;
        font-weight: normal;
    }
    h3 {
        margin-left: 15px;
        font-size: 16px;
    }
    ul {
        list-style-type: none;
        padding: 0;
        margin-left: 15px;
    }
    li {
        margin-bottom: 5px;
    }
    a {
        text-decoration: none;
        color: #3498db;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
        margin-top: 30px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input[type="radio"] {
        margin-right: 5px;
        height: 15px;
    }
    select, textarea {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
    }
    textarea {
        height: 100px;
    }
    button {
        height: 50px;
        width: 90px;
        background-color: #2578BB;
        color: white;
        border-radius: 6px;
        font-size: 20px;
        margin-left: 8px;
    }
    select {
        width: 400px;
    }
    textarea {
        width: 388px;
    }
    hr {
        width: 90%;
        margin-left: 16px;
        border: 1px solid #e0e0e0;
        border-radius: 1em;
    }
    .gray {
        color: gray;
    }
    .secondhr {
        margin-left: 0px;
        width: 252px;
        margin-top: 12px;
    }
    .bold {
        font-weight: bold;
    }
    .lookup {
        margin-left: 48px;
        height: 36px;
        width: 256px;
        border-radius: 4px;
    }
    .marginleft {
        margin-left: 16px;
    }
    .margintop {
        margin-top: 8px;
    }
    .marginright {
        margin-left: 20px;
    }
    .marginright2 {
        margin-left: 9px;
    }
    button:hover {
        cursor: pointer;
    }
    button:active {
        background-color: #2068a0;
    }
    .resultstable {
        margin-top: 96px;
        position: absolute;
    }
    th, td {
        border: 1px solid;
        border-color: lightgray;
        padding: 2vh;
        text-align: left;
    }
    .bigger {
        font-size: 20px;
    }
    .red {
        background-color: red;
        color: red;
    }
    .hidden {
        display: none;
    }
    input {
        font-size: 22px;
    }
    .bold {
    font-weight: bold;
}



.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
  padding-top: 60px;
  font-family: Arial, sans-serif;
}

.modal-dialog {
  background-color: #fff;
  margin: auto;
  border-radius: 8px;
  width: 450px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  animation: fadeInDown 0.3s ease-out;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modal-header, .modal-footer {
  padding: 15px 20px;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-header {
  border-bottom: 1px solid #dee2e6;
}

.modal-footer {
  border-top: 1px solid #dee2e6;
  border-bottom: none;
  justify-content: flex-end;
}

.modal-title {
  margin: 0;
  font-weight: 600;
  font-size: 1.25rem;
  color: #212529;
}

.btn-close {
  background: transparent;
  border: none;
  font-size: 1.5rem;
  font-weight: 700;
  color: #6c757d;
  cursor: pointer;
  line-height: 1;
}

.btn-close:hover {
  color: #000;
}

.modal-body {
  padding: 15px 20px;
  font-size: 1rem;
  color: #495057;
  display: flex;
  flex-direction: column;
  align-items: center;  /* Center content horizontally */
}

.modal-body label {
  display: block;
  margin-top: 10px;
  font-weight: 500;
  width: 100%;
  max-width: 400px;
  text-align: left; /* keep labels left aligned */
}

.modal-body input[type="text"],
.modal-body textarea {
  width: 100%;
  max-width: 400px;
  padding: 8px;
  margin-top: 5px;
  margin-bottom: 10px;
  border-radius: 4px;
  border: 1px solid #ced4da;
  font-size: 1rem;
  font-family: Arial, sans-serif;
}

.modal-body textarea {
  resize: none; /* disable resizing */
}

.btn {
  padding: 8px 16px;
  font-size: 1rem;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-family: Arial, sans-serif;
  transition: background-color 0.2s ease;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
  margin-right: 10px;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

.btn-primary {
  background-color: #2578BB;
  color: white;
}

.btn-primary:hover {
  background-color: #2068a0;
}
</style>
</head>
<body>
<script src="/Admi/buttons.js"></script>

<div class="section">
    <span>Customer Moderation Panel</span>
    <div class="welcome">Hi <?php echo htmlspecialchars($username); ?> (<a href="/home">go back</a>) | (<a href="/authentication/logout">logout</a>)</div>
</div>
    <div class="sidebar">
        <h2>Admin</h2>
		<?php if ($adminLevel >= AdminLevel::ASSET_MOD): ?>
        <h3>User Admin</h3>
        <hr style=";">
        <ul>
		<?php if ($adminLevel >= AdminLevel::MODERATOR): ?>
            <li><a href="/Admi">Find user</a></li>
		<?php endif; ?>
        <?php if ($adminLevel >= AdminLevel::ADMIN): ?>
            <li><a href="/Admi/GrantBadge.aspx">Grant Badges</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::ASSET_MOD): ?>
            <li><a href="/Admi/AssetTransfer.aspx">Asset Transfer</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::ASSET_MOD): ?>
            <li><a href="/Admi/AssetUpload.aspx">Asset Upload</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::ADMIN): ?>
            <li><a href="/Admi/UserTransactions.aspx">User Transactions</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::MODERATOR): ?>
            <li><a href="/Admi/SendMessage.aspx">Send Personal Message</a></li>
		<?php endif; ?>
        </ul>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::DEV): ?>
        <h3>Site</h3>
        <hr style=";">
        <ul>
		<?php if ($adminLevel >= AdminLevel::DEV): ?>
            <li><a href="/Admi/Alerts.aspx">Site-Wide Alerts</a></li>
		<?php endif; ?>
        <?php if ($adminLevel >= AdminLevel::ASSET_MOD): ?>
            <li><a href="/Admi/AssetModeration.aspx">Asset Moderation</a></li>
		<?php endif; ?>
        <?php if ($adminLevel >= AdminLevel::ASSET_MOD): ?>
            <li><a href="/Admi/AssetApproval.aspx">Asset Approval</a></li>
		<?php endif; ?>
        </ul>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::ADMIN): ?>
        <h3>Economy</h3>
        <hr style=";">
        <ul>
		<?php if ($adminLevel >= AdminLevel::ADMIN): ?>
            <li><a href="/Admi/GrantMembership.aspx">Grant Membership</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::ADMIN): ?>
            <li><a href="/Admi/AdjustAssets.aspx">Adjust Assets, Credit & Currency</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::HEAD_ADMIN): ?>
            <li><a href="/Admi/Imbursements.aspx">Imbursements</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::HEAD_ADMIN): ?>
            <li><a href="/Admi/ImbursementsBlacklist.aspx">Imbursements Blacklist</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::HEAD_ADMIN): ?>
            <li><a href="/Admi/MassRefund.aspx">Mass Refund</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::ADMIN): ?>
            <li><a href="/Admi/GrantCurrency.aspx">Grant Currency</a></li>
		<?php endif; ?>
        </ul>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::MODERATOR): ?>
        <h3>Servers</h3>
        <hr style=";">
        <ul>
		<?php if ($adminLevel >= AdminLevel::DEV): ?>
            <li><a href="/Admi/Usage.aspx">Server Usage</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::DEV): ?>
            <li><a href="/Admi/GS.aspx">Game Servers</a></li>
		<?php endif; ?>
		<?php if ($adminLevel >= AdminLevel::MODERATOR): ?>
            <li><a href="/Admi/Reports.aspx">In-Game Reports</a></li>
		<?php endif; ?>
        </ul>
		<?php endif; ?>
    </div>