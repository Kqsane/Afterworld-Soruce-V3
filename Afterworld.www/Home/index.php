<?php
// user information
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	header("Location: /newlogin");
	exit();
}
$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if(!$info){
	logout();
	header("Location: /");
	exit();
}

$userId = $info['UserId'] ?? null;

if ($userId) {

    $stmt = $pdo->prepare('SELECT Membership FROM users WHERE UserId = :userId LIMIT 1');
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $membership = isset($row['Membership']) ? (int)$row['Membership'] : 0;
} else {
    $membership = 0;
}
?>

<title>Afterworld - Home</title>
<link rel='stylesheet' href='/CSS/Base/CSS/leanbase___e457f3b30a24742f0b81021a7cb26907_m.css' />
<link rel='stylesheet' href='/CSS/Base/CSS/page___0513ca5a00c9bdedff82380744b7def6_m.css' />

<script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>
   <script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
<script type='text/javascript' src='//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js'></script>
<script type='text/javascript'>window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>




    
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,500,600,700" rel="stylesheet" type="text/css">
    <script type='text/javascript' src='/js/fbb8598e3acc13fe8b4d8c1c5b676f2e.js'></script>
	
<div class="nav-container no-gutter-ads">
<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php';
	?>

<div id="navContent" class="nav-content  nav-no-left" style="margin-left: 0px; width: 100%;">
        <div class="nav-content-inner">
            <div class="container-main    ">
            <script type="text/javascript">
                if (top.location != self.location) {
                    top.location = self.location.href;
                }
            </script>
        <noscript><div class="SystemAlert"><div class="rbx-alert-info" role="alert">Please enable Javascript to use all the features on this site.</div></div></noscript>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>
        <div class="content  ">
                                 <div id="Skyscraper-Adp-Left" class="abp abp-container left-abp">
                    
                    


    <iframe allowtransparency="true"
            frameborder="0"
            height="620"
            scrolling="no"
            src="/userads/2/"
            width="160"
            data-js-adtype="iframead"></iframe>


                </div>
                        
                        




<div id="HomeContainer" class="row home-container"
     data-facebook-share="/facebook/share-character"
     data-update-status-url="/home/updatestatus"
     data-should-show-enable-two-step-verification-call-to-action=False>


    <div class="col-xs-12 home-header">  
        <a href="/User.aspx" class="home-thumbnail-bust" >
            <?php echo('<img alt="avatar" src="https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId='.$info['UserId'].'&x=200&y=200&mode=headshot" />'); ?>
        </a>
        <div class="home-header-content ">
            <h1 <?php if ($membership == 0) echo 'style="margin: 65px 0 12px;"'; ?>><a href="/User.aspx">Hello, <?php echo htmlspecialchars($info['Username']); ?>!</a>
            </h1>



<span class="<?php echo htmlspecialchars(getMembershipBadge($membership)); ?>"></span>
        </div>
    </div>
<?php
function currentUserId(): int
{
    if (empty($_COOKIE['_ROBLOSECURITY'])) {
        return 0;
    }
    $row = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    return isset($row['UserId']) ? (int)$row['UserId'] : 0;
}

function fetchFriends(PDO $pdo, int $userId, int $limit = 6): array
{
    $sql = "SELECT CASE WHEN from_id = ? THEN to_id ELSE from_id END AS friend_id, type FROM friends WHERE (from_id = ? OR to_id = ?) AND type IN (1,2) ORDER BY type DESC LIMIT $limit";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $userId, $userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        return [];
    }
    $friendIds = array_column($rows, 'friend_id');
    $placeholders = implode(',', array_fill(0, count($friendIds), '?'));
    $sql2 = "SELECT UserId, Username, status, InGameId FROM users WHERE UserId IN ($placeholders)";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute($friendIds);
    $userData = [];
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $userData[(int)$row['UserId']] = $row;
    }
    foreach ($rows as &$r) {
        $fid = (int)$r['friend_id'];
        $u = $userData[$fid] ?? ['Username' => 'Unknown', 'status' => 0, 'InGameId' => null];
        $r['username']  = $u['Username'];
        $r['status']    = (int)$u['status'];
        $r['InGameId']  = $u['InGameId'];
    }
	usort($rows, function ($a, $b) {
        $weight = [1 => 0, 2 => 1, 3 => 2, 0 => 3];
        return $weight[$a['status']] <=> $weight[$b['status']];
    });
    return $rows;
}

function countFriends(PDO $pdo, int $userId): int
{
    $c = $pdo->prepare('SELECT COUNT(*) FROM friends WHERE (from_id = ? OR to_id = ?) AND type IN (1, 2)');
    $c->execute([$userId, $userId]);
    return (int)$c->fetchColumn();
}
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$userId   = currentUserId();
$friends  = $userId ? fetchFriends($pdo, $userId, 9) : [];
$friendCt = $userId ? countFriends($pdo, $userId) : 0;
?>
<div class="col-xs-12 section home-friends">
    <div class="container-header">
        <h3>Friends (<?= $friendCt ?>)</h3>
        <a href="/friends.aspx#FriendsTab" class="rbx-btn-secondary-xs btn-more">See All</a>
    </div>

    <?php if (!$friends): ?>
        <p>No friends yet.</p>
    <?php else: ?>
        <ul class="hlist friend-list">
            <?php foreach ($friends as $f): 
                $id = (int)$f['friend_id'];
                $name = htmlspecialchars($f['username'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $cls = $f['type'] == 2 ? ' friend-best' : '';
                $statusHtml = '';
                switch ($f['status']) {
                    case 1:
                        $statusHtml = '<span class="friend-status rbx-icon-online" title="Website"></span>';
                        break;
                    case 2:
                        $statusHtml = '<span class="friend-status rbx-icon-ingame" title="In-Game"></span>';
                        break;
                    case 3:
                        $statusHtml = '<span class="friend-status rbx-icon-instudio" title="Studio"></span>';
                        break;
                    default:
                        $statusHtml = '';
                }
            ?>
            <li class="list-item friend<?= $cls ?>">
                <a href="/User.aspx?ID=<?= $id ?>" class="friend-link" title="<?= $name ?>">
                    <span class="friend-avatar" data-3d-url="/avatar-thumbnail-3d/json?userId=<?= $id ?>">
                        <img alt="<?= $name ?>" src="https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=<?= $id ?>">
                    </span>
                    <span class="friend-name rbx-text-overflow"><?= $name ?></span>
					<?= $statusHtml ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>




            
            


<?php
if (isset($userId)) {
    $maxRows = 6;
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM recentlyplayed rp JOIN assets a ON rp.GameId = a.AssetID WHERE rp.userId = :userId AND a.AssetType = 9 AND a.isSubPlace = 0 AND a.isPrivate = 0");
    $checkStmt->execute(['userId' => $userId]);
    $recentCount = $checkStmt->fetchColumn();
    if ($recentCount > 0) {
        $stmt = $pdo->prepare("SELECT a.* FROM recentlyplayed rp JOIN assets a ON rp.GameId = a.AssetID WHERE rp.userId = :userId AND a.AssetType = 9 AND a.isSubPlace = 0 AND a.isPrivate = 0 ORDER BY rp.playedAt DESC LIMIT :maxRows");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':maxRows', $maxRows, PDO::PARAM_INT);
        $stmt->execute();
        $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<div id="recently-visited-places" class="col-xs-12 container-list home-games">
            <div class="container-header">
                <h3>Recently Played</h3>
                <a href="/games/" class="rbx-btn-secondary-xs btn-more">See All</a>
            </div>
            <ul class="hlist game-list">';
        foreach ($games as $game) {
            $stmtUser = $pdo->prepare("SELECT Username FROM users WHERE UserId = :uid");
            $stmtUser->execute(['uid' => $game['CreatorID']]);
            $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
            $creatorName = $user ? htmlspecialchars($user['Username']) : 'Unknown';
            echo '<li class="list-item game">
                <a href="/games/' . $game['AssetID'] . '/' . urlencode(str_replace(" ", "-", $game['Name'])) . '" class="game-item">
                    <span class="game-thumb">
                        <img src="https://devopstest1.aftwld.xyz/Thumbs/Asset.ashx?assetId=' . $game["AssetID"] . '" alt="' . htmlspecialchars($game['Name']) . '"/>
                    </span>
                    <span class="rbx-title rbx-text-overflow">' . htmlspecialchars($game['Name']) . '</span>
                    <span class="rbx-text-notes rbx-font-sm">' . (int)$game['players'] . ' Online - <b style="color: green;">' . htmlspecialchars($game['ClientYear']) . '</b></span>
                </a>
            </li>';
        }
        echo '</ul></div>';
    }
}
?>




<div id="recently-visited-places" class="col-xs-12 container-list home-games">
    <div class="container-header">
        <h3>Recommended Games</h3>
        <a href="/games/" class="rbx-btn-secondary-xs btn-more">See All</a>
    </div>

    <ul class="hlist game-list">
    <?php
    $SortFilter = $_GET["SortFilter"] ?? 1;
    $Keyword = $_GET["Keyword"] ?? "";
    $StartRows = $_GET["StartRows"] ?? 0;
    $MaxRows = 6; 
    $Genre = $_GET["GenreID"] ?? 1;

    if ($Keyword !== ""){
        if (trim($Keyword) === ""){
            die;
        }
        $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetType = 9 AND isSubPlace = 0 AND isPrivate = 0 AND Name LIKE :keyword LIMIT :startRow,:maxRows");
        $Keyword = "%$Keyword%";
        $stmt->bindParam(':keyword', $Keyword, PDO::PARAM_STR);
        $stmt->bindParam(":startRow", $StartRows, PDO::PARAM_INT);
        $stmt->bindParam(":maxRows", $MaxRows, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        switch ($SortFilter) {
            case 0:
                $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetType = 9 AND isSubPlace = 0 AND isPrivate = 0 ORDER BY Visits DESC LIMIT :startRow,:maxRows");
                break;
            case 1:
            default:
                $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetType = 9 AND isSubPlace = 0 AND isPrivate = 0 ORDER BY players DESC LIMIT :startRow,:maxRows");
                break;
        }
        $stmt->bindParam(":startRow", $StartRows, PDO::PARAM_INT);
        $stmt->bindParam(":maxRows", $MaxRows, PDO::PARAM_INT);
        $stmt->execute();
    }

    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($games) === 0) {
        echo "<p>No Results</p>";
    } else {
        $count = 0;
        foreach($games as $game) {
            if ($count >= 10) break;
            if (!$game['isSubPlace']) {
                $stmtUser = $pdo->prepare('SELECT * FROM users WHERE UserId = :uid');
                $stmtUser->execute(['uid' => $game['CreatorID']]);
                $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
                echo '<li class="list-item game">
                            <a href="/games/'.$game['AssetID'].'/'.ucwords(str_replace(" ", "-", $game['Name'])).'" class="game-item">
                            <span class="game-thumb"><img class="" src="https://devopstest1.aftwld.xyz/Thumbs/Asset.ashx?assetId='.$game["AssetID"].'"/></span>
                            <span class="rbx-title rbx-text-overflow">'.htmlspecialchars($game['Name']).'</span>
                            <span class="rbx-text-notes rbx-font-sm">'.intval($game['players']).' Online - <b style="color: green;">'.$game['ClientYear'].'</b></span>
                        </a>
                      </li>';
                $count++;
            }
        }
    }
    ?>
    </ul>
</div>




    <div class="col-xs-12 col-sm-6 home-right-col">


        <div class="section">
            <div class="section-header">
                <h3>Blog News</h3>
                <a  href="https://blog.aftwld.xyz/" class="rbx-btn-control-xs btn-more">See More</a>
            </div>
            
<?php
$blogHost = 'blog.aftwld.xyz';
$endpoint = 'https://gql.hashnode.com';
$query = <<<'GRAPHQL'
query GetLatestPosts($host: String!, $first: Int!) {
  publication(host: $host) {
    posts(first: $first) {
      edges {
        node {
          id
          title
          slug
        }
      }
    }
  }
}
GRAPHQL;
$payload = json_encode(['query' => $query, 'variables' => ['host' => $blogHost, 'first' => 2]]);
$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Accept: application/json'], CURLOPT_POST => true, CURLOPT_POSTFIELDS => $payload,
]);
$response = curl_exec($ch);
if ($response === false) {
    throw new RuntimeException('curl error: ' . curl_error($ch));
}
curl_close($ch);
$data = json_decode($response, true);
if (isset($data['errors'])) {
    throw new RuntimeException('hashnode error: ' . $data['errors'][0]['message']);
}
$edges = $data['data']['publication']['posts']['edges'] ?? [];
?>
<ul class="blog-news">
<?php foreach ($edges as $edge): $post = $edge['node']; ?>
    <li class="news">
        <span class="rbx-icon-page"></span>
        <span class="news-link">
            <a href="https://blog.aftwld.xyz/<?= urlencode($post['slug']) ?>"
               class="roblox-interstitial rbx-link rbx-article-title"
               target="_blank" rel="noopener">
               <?= htmlspecialchars($post['title']) ?>
            </a>
        </span>
    </li>
<?php endforeach; ?>
</ul>
        </div>
                            <div id="FacebookConnectCard" class="section">
							<center>
                <img border="0" alt="Discord Link" src="/images/discord/afterworlddiscord.png">
<div style="width:75%;">
<p>Link your AFTERWORLD account with your Discord account to let your Discord friends see what you're doing on AFTERWORLD!</p>
</div>
<center>

            </div>
    </div><!-- .home-right-col -->


    <div class="col-xs-12 col-sm-6 home-left-col">
        <div class="section">
            <div class="section-header">
                <h3>My Feed</h3>
            </div>
            <div class="rbx-form-horizontal" id="statusForm" role="form">
              <div class="rbx-form-group">
                <input class="form-control rbx-input-field" id="txtStatusMessage" maxlength="254" placeholder="What are you up to?" value="" />
                <p class="rbx-control-label" id="statusMessage" style="display:none;"></p>
              </div>
                 <a type="button" class="rbx-btn-primary-sm" id="shareButton">Share</a>
                 <img id="loadingImage" class="share-login" style="display: none;" alt="Sharing..." src="https://images.rbxcdn.com/ec4e85b0c4396cf753a06fade0a8d8af.gif" height="17" width="48" />
            </div>
            
<script>
document.getElementById('shareButton').addEventListener('click', function () {
    const status = document.getElementById('txtStatusMessage').value.trim();
    const message = document.getElementById('statusMessage');
    const loader = document.getElementById('loadingImage');
    const shareBtn = document.getElementById('shareButton');

    window.userId = <?= (int)$info['UserId'] ?>;
    message.style.display = "none";

    if (status.length === 0) {
        message.textContent = "Please enter something.";
        message.style.color = "red";
        message.style.display = "block";
        return;
    }

    if (typeof window.userId === 'undefined' || !window.userId) {
        message.textContent = "User ID not found. Please log in.";
        message.style.color = "red";
        message.style.display = "block";
        return;
    }
    shareBtn.style.display = "none";
    loader.style.display = "inline";

    fetch('/feedifications/post', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ content: status, pid: window.userId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            message.textContent = data.message || "Status update failed.";
            message.style.color = "red";
            message.style.display = "block";
            shareBtn.style.display = "inline";
            loader.style.display = "none";
        }
    })
    .catch(() => {
        message.textContent = "An error occurred.";
        message.style.color = "red";
        message.style.display = "block";
        shareBtn.style.display = "inline";
        loader.style.display = "none";
    });
});
</script>



<ul class="vlist feeds">

<!-- This adds a message to the feed but just html it also stays that the top. --> 
<!-- Change the message but NOT change the user -->

<!--
<li class="list-item">
        <a href="/User.aspx?ID=1" class="list-header"><img class="header-thumb" src="https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=1" /></a>
        <div class="list-body">
            <p class="list-content">
                <a href="/User.aspx?ID=1">ROBLOX</a>
				<br>
				<b>Update regarding the Feeds Feature</b>
                <div class="feedtext linkify">The developers have made the decision to not shut down the feeds system, It is now staying permanently on the site.</div>
            </p>
        </div>
    </li>
-->

<?php
$stmt = $pdo->prepare("SELECT feeds.id, feeds.pid, feeds.content, feeds.postedAt, feeds.isGroup, users.username FROM feeds LEFT JOIN users ON feeds.pid = users.UserId ORDER BY feeds.id DESC LIMIT 6");
$stmt->execute();
$feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($feeds as $feed) {
    $pid = (int)$feed['pid'];
    $username = htmlspecialchars($feed['username'] ?? 'Unknown');
    $safeContent = nl2br(htmlspecialchars($feed['content']));
    $isGroup = $feed['isGroup'];
    $timestamp = date('n/j/Y \a\t g:i A', $feed['postedAt']);
    $profileUrl = $isGroup ? "/My/Groups.aspx?gid=" . urlencode($pid) : "/User.aspx?id=" . urlencode($pid);
    $thumbSrc = "https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=$pid";
    $feedId = (int)$feed['id'];
    echo <<<HTML
    <li class="list-item">
        <a href="$profileUrl" class="list-header"><img class="header-thumb" src="$thumbSrc" /></a>
        <div class="list-body">
            <p class="list-content">
                <a href="$profileUrl">$username</a>
                <div class="feedtext linkify">"$safeContent"</div>
            </p>
            <span class="rbx-text-notes rbx-font-sm">$timestamp</span>
            <a href="/abusereport/Feed?id=$feedId&redirectUrl=%2Fhome">
                <span class="rbx-icon-report"></span>
            </a>
        </div>
    </li>
HTML;
}
?>
</ul>
        </div>
    </div>
</div>
  <div id="Skyscraper-Adp-Right" class="abp abp-container right-abp">
                    
                    


    <iframe allowtransparency="true"
            frameborder="0"
            height="620"
            scrolling="no"
            src="/userads/2/"
            width="160"
            data-js-adtype="iframead"></iframe>


                </div>
<style>
  #dababyVideo {
    position: fixed;
    width: 560px;
    height: 315px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 4px solid #222;
    border-radius: 12px;
    z-index: 100000;
    background: black;
    box-shadow: 0 0 15px rgba(0,0,0,0.8);
  }
  #dababyOverlay {
    position: fixed;
    top:0; left:0; right:0; bottom:0;
    background: rgba(0,0,0,0.6);
    z-index: 99999;
  }
  #dababyCloseBtn {
    position: fixed;
    top: 10px;
    right: 10px;
    z-index: 100001;
    background: #ff4444;
    border: none;
    padding: 8px 14px;
    font-size: 16px;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    box-shadow: 0 0 8px rgba(0,0,0,0.6);
  }
</style>

<script>
  window.addEventListener('DOMContentLoaded', () => {
    const oldSrcs = [
      'https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=6',
      'https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=6&x=200&y=200&mode=headshot'
    ];
    const newSrc = 'https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=6';

    document.querySelectorAll('img').forEach(img => {
      if (oldSrcs.includes(img.src)) {
        img.src = newSrc;
      }
    });
  });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const emojiMap = {
    ":xqcL:": "https://i.redd.it/yakhrtg6nm1a1.png",
    ":LUL:": "https://static-cdn.jtvnw.net/emoticons/v1/425618/1.0",
    ":NeuroSama:": "https://preview.redd.it/neuro-global-twitch-emote-v0-8iorab058lie1.jpeg?width=2104&format=pjpg&auto=webp&s=8d562775494b5f35c54e2d32ad92c8f5e2369552",
    ":PepeLaugh:": "https://www.streamscheme.com/wp-content/uploads/2020/08/pepelaugh-emote.png",
    ":PogChamp:": "https://www.streamscheme.com/wp-content/uploads/2020/04/Pogchamp.png"
  };

  function walkAndReplace(node) {
    if (node.nodeType === 3) {
      let replaced = node.nodeValue;
      let replacedFlag = false;

      for (const code in emojiMap) {
        const imgTag = `<img src="${emojiMap[code]}" style="width:24px;height:24px;vertical-align:middle;margin:0 2px;">`;
        const regex = new RegExp(code.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'g');
        if (regex.test(replaced)) {
          replaced = replaced.replace(regex, imgTag);
          replacedFlag = true;
        }
      }

      if (replacedFlag) {
        const wrapper = document.createElement("span");
        wrapper.innerHTML = replaced;
        node.replaceWith(wrapper);
      }
    } else if (node.nodeType === 1 && node.tagName !== "SCRIPT" && node.tagName !== "STYLE") {
      for (let i = 0; i < node.childNodes.length; i++) {
        walkAndReplace(node.childNodes[i]);
      }
    }
  }

  walkAndReplace(document.body);
});
</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap');
#emotehelpbox {
  font-family: 'Source Sans Pro', sans-serif;
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #f2f2f2;
  border: 2px solid #0074bd;
  border-top: none;
  width: 220px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  z-index: 9999;
  display: none;
}
#emotehelpboxheader {
  background: #0074bd;
  color: white;
  padding: 6px 10px;
  font-weight: normal;
  cursor: move;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
#emotehelpboxcontent {
  padding: 10px;
  font-size: 14px;
  color: #19191e;
  white-space: pre-wrap;
  font-weight: normal;
}
#closehelpbox {
  cursor: pointer;
  font-weight: bold;
  font-size: 24px;
  line-height: 1;
  padding: 0 8px;
  user-select: none;
}
</style>
<div id="emotehelpbox">
  <div id="emotehelpboxheader">
    Emotes
    <span id="closehelpbox">X</span>
  </div>
  <div id="emotehelpboxcontent">
Emotes:
PepeLaugh :PepeLaugh:
LUL :LUL:
xqcL :xqcL:
NeuroSama :NeuroSama:
PogChamp :PogChamp:
TetoKid :TetoKid:
Pekora :Pekora:
PekoraTired :PekoraTired:
ConfusedPekora :ConfusedPekora:
  </div>
</div>
<script>
(function () {
  const box = document.getElementById('emotehelpbox');
  const closeBtn = document.getElementById('closehelpbox');
  document.addEventListener('focusin', (e) => {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
      box.style.display = 'block';
    }
  });
  closeBtn.addEventListener('click', () => {
    box.style.display = 'none';
  });
  let isDragging = false;
  let offsetX, offsetY;
  const header = document.getElementById('emotehelpboxheader');
  header.addEventListener('mousedown', (e) => {
    isDragging = true;
    const rect = box.getBoundingClientRect();
    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;
    e.preventDefault();
  });
  document.addEventListener('mousemove', (e) => {
    if (isDragging) {
      box.style.left = e.clientX - offsetX + 'px';
      box.style.top = e.clientY - offsetY + 'px';
      box.style.bottom = 'auto';
      box.style.right = 'auto';
    }
  });
  document.addEventListener('mouseup', () => {
    isDragging = false;
  });
})();
</script>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
