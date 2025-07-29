<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$isAdmin = 0;
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
$reportId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($reportId <= 0) {
    die('Invalid report id');
}
$sql = "SELECT r.*, u.Username AS ReporterUsername FROM reports r LEFT JOIN users u ON u.UserId = r.UserId WHERE r.id = :rid LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['rid' => $reportId]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$report) {
    die('Report not found');
}
$abuserId = null;
$reportType = '';
$commentText = '';
if (!empty($report['Comment'])) {
    $parts = explode(';', $report['Comment']);
    if (!empty($parts[0]) && str_starts_with($parts[0], 'AbuserID:')) {
        $abuserId = (int)str_replace('AbuserID:', '', $parts[0]);
    }
    $reportType  = $parts[1] ?? '';
    $commentText = $parts[2] ?? '';
}
$abuserUsername = null;
if ($abuserId) {
    $a = $pdo->prepare("SELECT Username FROM users WHERE UserId = ?");
    $a->execute([$abuserId]);
    $abuserUsername = $a->fetchColumn();
}
$chatLogsRaw   = $report['Messages'] ?? '';
$chatLogsArray = json_decode($chatLogsRaw, true);
$chatLogsIsJson = is_array($chatLogsArray);
$xmlContent = $report['RawXML'] ?? '';
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
    <li class="tab-item active" data-tab="information">Report Information</li>
    <li class="tab-item" data-tab="chatlogs">Chat Logs</li>
    <li class="tab-item" data-tab="xmlcontent">XML Content</li>
</ul>
<div class="tab-content">
    <div class="tab-panel active" id="information">
        <div class="card">
            <h3>Overview</h3>
            <p><strong>Status:</strong>
               <span class="<?= $report['MarkAsResolved'] ? 'resolved' : 'unresolved' ?>">
                   <?= $report['MarkAsResolved'] ? 'Resolved' : 'Unresolved' ?>
               </span>
            </p>
            <p><strong>Reporter:</strong>
               <a href="/User.aspx?id=<?= $report['UserId'] ?>">
                   <?= htmlspecialchars($report['ReporterUsername'] ?? "User {$report['UserId']}") ?>
               </a>
            </p>
            <p><strong>Abuser:</strong>
               <?php if ($abuserId): ?>
                   <a href="/User.aspx?id=<?= $abuserId ?>">
                       <?= htmlspecialchars($abuserUsername ?? "User {$abuserId}") ?>
                   </a>
               <?php else: ?>—<?php endif; ?>
            </p>
            <p><strong>Report Type:</strong> <?= htmlspecialchars($reportType ?: '—') ?></p>
            <p><strong>Comment:</strong> <?= htmlspecialchars($commentText ?: '—') ?></p>
            <p><strong>Place ID:</strong> <?= $report['PlaceId'] ?></p>
            <p><strong>Game Job ID:</strong> <?= htmlspecialchars($report['JobId']) ?></p>
            <p><strong>Created:</strong>
               <?= date('m/d/Y h:i A', strtotime($report['CreatedAt'])) ?>
            </p>
        </div>
    </div>
    <div class="tab-panel" id="chatlogs">
        <div class="card">
            <h3>Chat Logs</h3>
            <?php if ($chatLogsIsJson): ?>
                <table>
                    <thead><tr><th>#</th><th>User ID</th><th>Message</th></tr></thead>
                    <tbody>
                    <?php foreach ($chatLogsArray as $i => $m): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <a href="/User.aspx?id=<?= $m['userID'] ?>">
                                    <?= $m['userID'] ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($m['text']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <?php if ($chatLogsRaw): ?>
                    <pre><?= htmlspecialchars($chatLogsRaw) ?></pre>
                <?php else: ?>
                    <p>No chat logs stored for this report.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="tab-panel" id="xmlcontent">
        <div class="card">
            <h3>Raw XML Content</h3>
            <?php if ($xmlContent): ?>
                <pre><?= htmlspecialchars($xmlContent) ?></pre>
            <?php else: ?>
                <p>No XML content available.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
document.querySelectorAll('.tab-item').forEach(tab=>{
    tab.addEventListener('click',()=>{
        document.querySelectorAll('.tab-item').forEach(t=>t.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).classList.add('active');
    });
});
</script>

</body>
</html>