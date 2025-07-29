<!DOCTYPE html>
<html lang="en">

<head>
  <?php
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
  if ($adminLevel < AdminLevel::DEV) {
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
  
$jobId = null;
foreach (['jobid', 'jobId', 'JobID', 'JobId'] as $param) {
    if (!empty($_GET[$param])) {
        $jobId = $_GET[$param];
        break;
    }
}
if (!$jobId) {
    http_response_code(400);
    exit('jobid never defined in url');
}
if (!preg_match('/^[a-f0-9-]{36}$/i', $jobId)) {
    http_response_code(400);
    exit('Invalid JobID format');
}
$stmt = $pdo->prepare('SELECT playerList FROM Jobs WHERE jobId = :job');
$stmt->execute(['job' => $jobId]);
$jobRow = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$jobRow) {
    exit('no jub like that, sad');
}
$playerIds = json_decode($jobRow['playerList'], true);
$playerIds = is_array($playerIds) ? array_filter($playerIds, 'is_numeric') : [];
if (!$playerIds) {
    $players = [];
} else {
    $placeholders = implode(',', array_fill(0, count($playerIds), '?'));
    $stmt = $pdo->prepare("SELECT UserId, Username, Membership FROM users WHERE UserId IN ($placeholders)");
    $stmt->execute($playerIds);
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    usort($players, fn($a, $b) => array_search($a['UserId'], $playerIds) <=> array_search($b['UserId'], $playerIds));
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
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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
      background-size: 16px 16px;
      /* ensures proper scaling */
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
      background-size: 16px 16px;
      /* scale for clarity */
    }

    .robux-text {
      color: #060;
      font-weight: bold;
    }
    
    .button-row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap; 
      margin-top: 10px;
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
      <li class="tab-item active" data-tab="players">Players</li>
      <li class="tab-item" data-tab="executor">Script Executor</li>
      <li class="tab-item" data-tab="shutdown">Shutdown</li>
    </ul>

    <div class="tab-content">

      <div class="tab-panel active" id="players">
        <div class="header">
          <h1><?= htmlspecialchars($jobId) ?></h1>
          <br>
        </div>
    <?php if (!$players): ?>
        <p>No players are currently in this server.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th class="bold bigger">Username</th>
                    <th class="bold bigger">User ID</th>
                    <th class="bold bigger">Membership</th>
                    <th class="bold bigger">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($players as $p): ?>
                    <tr>
                        <td><a class="bigger" href="/User.aspx?ID=<?= (int)$p['UserId'] ?>"><?= htmlspecialchars($p['Username']) ?></a></td>
                        <td class="bigger"><?= (int)$p['UserId'] ?></td>
                        <td class="bigger"><?= (int)$p['Membership'] ?></td>
                        <td class="bigger"><a href="/Admi/GS/kickUser.aspx?id=<?= urlencode($p['UserId']) ?>&jobid=<?= urlencode($jobId) ?>">Kick</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
		<?php endif; ?>
      </div>
	  
      <div class="tab-panel" id="executor">
        <div class="header">
          <h1>Script Executor</h1>
          <br>
        </div>
        <div class="form-group">
            <label for="server-script">Script:</label>
            <textarea name="server_send_script" id="server-script"></textarea>
        </div>
        <div class="button-row">
          <button type="button" name="submit_script" id="submit_script_btn">Submit</button>
          <button type="button" name="sm_template" class="btn btn-secondary" id="sm-script">Server Message</button>
          <button type="button" name="kill_template" class="btn btn-secondary" id="kill-script">Kill Player</button>
        </div>
        <div class="form-group">
            <label for="script-result">Result:</label>
            <textarea name="server_send_result" id="script-result" readonly></textarea>
        </div>
	  </div>
	  
      <div class="tab-panel" id="shutdown">
      <div class="header">
          <h1>Job Shutdown</h1>
          <br>
        </div>
      <div>
	    <b>NOTICE:</b><p>This is a last resort, shut down if the job is unresponsive</p>
        <a href="/Admi/GS/Shutdown.aspx?jobid=<?= htmlspecialchars($jobId) ?>">
          <button>Shutdown Job</button>
        </a>
    </div>
</body>

</html>

<!-- button boxe paster --> 
<script>
  const sm_paste = document.getElementById('sm-script');
  const kill_paste = document.getElementById('kill-script');
  const script_box = document.getElementById('server-script');
  const result_box = document.getElementById('script-result');
  const submit_script_btn = document.getElementById("submit_script_btn");

  const sm_template = `-- SM Template Script
local m = Instance.new("Message")
m.Parent = Workspace
m.Text = "your text here"
wait(10)
m:Remove()`;

  const kill_template = `-- Kill Template Script
local userId = %replaceme%

for _, v in ipairs(game:GetService("Players"):GetPlayers()) do
    if v.userId == userId then
        if v.Character then
            v.Character.Humanoid:TakeDamage(100)
        end
        break
    end
end`;

  submit_script_btn.addEventListener("click", () => {
    fetch("/Admi/GS/executeScript.aspx", {
      method: "POST",
      body: JSON.stringify({
        script: script_box.value ?? "",
        jobId: "<?=$jobId?>"
      }),
      headers: {
        "Content-Type": "application/json; charset=UTF-8"
      }
    }).then((response) => response.json())
      .then((json) => {
        result_box.value = json.result ?? ""
      })
      .catch((e) => console.log("an error occured:", e));
  });

  sm_paste.addEventListener('click', () => {
    script_box.value = sm_template + '\n';
  });

  kill_paste.addEventListener('click', () => {
    script_box.value = kill_template + '\n';
  });
  
</script>


<script>
  document.querySelectorAll('.tab-item').forEach(tab => {
    tab.addEventListener('click', function() {
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