<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if (!isset($_COOKIE["_ROBLOSECURITY"])) {
    header("Location: /NewLogin");
    exit();
}

$info = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if (!$info) {
    logout();
    header("Location: /");
    exit();
}

$clientId = '1393323068352495666';
$discordRedirect = 'https://devopstest1.aftwld.xyz/my/linkdiscord';
$discordOAuthUrl = "https://discord.com/api/oauth2/authorize?" . http_build_query([
    'response_type' => 'code',
    'client_id' => $clientId,
    'scope' => 'identify',
    'redirect_uri' => $discordRedirect
]);

$success = false;
$error = false;
$userInfo = null;

if (!empty($_COOKIE['_ROBLOSECURITY'])) {
    $userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    $userId = $userInfo['UserId'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
        if (isset($_POST['textbox'])) {
            $desc = trim($_POST['textbox']);
            if (strlen($desc) > 1000) {
                $desc = substr($desc, 0, 1000);
            }
            $stmt = $pdo->prepare("UPDATE users SET Description = :desc WHERE UserId = :id");
            $success = $stmt->execute(['desc' => $desc, 'id' => $userId]);
        }

        $fields = ['show_current_experience', 'inventory_visibility', 'trading'];
        $updates = [];
        $params = [];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $updates[] = "{$field} = :{$field}";
                $params[$field] = $_POST[$field];
            }
        }

        if (!empty($updates)) {
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE UserId = :id";
            $params['id'] = $userId;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $success = true;
        }

        $socialFields = [
            'twitter' => 'twitter_link',
            'discord' => 'discord_link',
            'tiktok' => 'tiktok_link',
            'youtube' => 'youtube_link',
            'twitch' => 'twitch_link',
            'github' => 'github_link',
            'roblox' => 'roblox_link',
        ];

        $socialUpdates = [];
        $socialParams = [];

        foreach ($socialFields as $postField => $dbField) {
            if (isset($_POST[$postField])) {
                $url = trim($_POST[$postField]);
                if ($url === '') {
                    $socialParams[$dbField] = null;
                } else {
                    if (strlen($url) > 255) {
                        $url = substr($url, 0, 255);
                    }
                    $socialParams[$dbField] = $url;
                }
                $socialUpdates[] = "{$dbField} = :{$dbField}";
            }
        }

        if (!empty($socialUpdates)) {
            $sql = "UPDATE users SET " . implode(', ', $socialUpdates) . " WHERE UserId = :id";
            $socialParams['id'] = $userId;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($socialParams);
            $success = true;
        }

        $userInfo = getuserinfo($_COOKIE['_ROBLOSECURITY']);
    } elseif (!$userId) {
        $error = true;
    }
} else {
    $error = true;
}
?>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

if (isset($_COOKIE["_ROBLOSECURITY"]) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_roblosecurity') {
    $currentToken = $_COOKIE["_ROBLOSECURITY"];
    $userInfo = getuserinfo($currentToken);
    if ($userInfo && isset($userInfo['UserId'])) {
        $newToken = bin2hex(random_bytes(20));
        
        $stmt = $pdo->prepare("UPDATE users SET ROBLOSECURITY = :newtoken WHERE UserId = :userid");
        $stmt->execute(['newtoken' => $newToken, 'userid' => $userInfo['UserId']]);
        
        setcookie('_ROBLOSECURITY', $newToken, [
            'expires' => time() + 60*60*24*365,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        
        echo json_encode(['success' => true, 'newToken' => $newToken]);
        exit;
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }
}
?>


<html>
<head>
    <title>Afterworld - My Account</title>
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" />
	<style>
		.SettingSubTitle {
			display: flex;
			align-items: center;
			margin-bottom: 12px;
		}

		.priv-label {
			width: 200px;
			display: inline-block;
			font-size: 14px;
			line-height: 24px;
		}

		.InlineSuperSafeDiv select.form-select {
			width: 250px;
			height: 20px;
			font-size: 14px;
			line-height: 24px;
			padding: 0 6px;
			margin: 0;
			box-sizing: border-box;
		}
		
		.social-input {
		  margin-bottom: 15px;
		}

		.social-input label {
		  display: block;
		  margin-bottom: 5px;
		  font-weight: bold;
		}

		.social-input input {
		  width: 100%;
		  height: 24px;
		  padding: 4px 8px;
		  font-size: 14px;
		}
	</style>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/js/GenericConfirmation.js"></script>
	<script type="text/javascript">
	 function changeUsername() {
		open("https://devopstest1.aftwld.xyz/Users/UsernameChange.aspx", "", "scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=500,height=500,left=-1000,top=-1000");
    }
	</script>
	<style>
	.form-label {
	  margin-bottom: 15px;
	  line-height: 24px;
	}
	</style>
<link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css"/>
<link rel="stylesheet" href="/CSS/Base/CSS/main___be967d1b0b5ea4a8f7f97f7e9a4c080f_m.css"/>
<link rel="stylesheet" href="/CSS/Pages/Legacy/Navigation.css"/>
	
</head>
<body>
	<script type="text/javascript" src="/js/widgets/tabs.js" data-readme="Can go at end of file."></script>
	<div class="nav-container no-gutter-ads">
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php'; ?>

	<div id="navContent" class="nav-content  nav-no-left" style="margin-left: 0px; width: 100%;">
			<div class="nav-content-inner">
				<div id="MasterContainer">
					<script type="text/javascript">
						if (top.location != self.location) {
							top.location = self.location.href;
						}
					</script>
					
					
					<div>
                    <noscript>
						<div class="SystemAlert">
							<div class="SystemAlertText">
								Please enable Javascript to use all the features on this site.
							</div>
						</div>
					</noscript>
					
                    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Alert.php'; ?>
					
                    <div id="BodyWrapper" class="">
                        <div id="RepositionBody">
                            <div id="Body" style="width:970px">
								<div>
									<h1>My Account</h1>
								</div>
								<div class="settings-tab">
									<div class="tab-container">
										<div class="tab-active">Settings</div>
										<div>Social</div>
										<div>Privacy</div>
										<div>Billing</div>
										<div>Security</div>
									</div>
								<div>
								
								<div class="tab-active">
									<div>
										<h3>
											Account Settings
										</h3>
									</div>
									
									<div>
									</div>
								
									<div>
										<p>
											<b>
												Username:
											</b>
											
											<?php echo $info["Username"]?>
											
											<button class="btn-small btn-primary" onclick="changeUsername()">
												Change My Username
											</button>
										</p>
										
										<div style="min-height: 30px;">
											<span class="form-label" style="float:left;padding-top:2px;margin-right:5px;">
												Password:
											</span>
											
											<p>
												******** 
												<span class="btn-control btn-control-medium disabled" disabled="">
													Change Password
												</span>
											</p>
										</div>
										<div style="min-height: 30px;">
											<span class="form-label" style="float:left;padding-top:2px;margin-right:5px;">
												Discord:
											</span>
											<p style="margin-left: 50px;">
												<?php if (!empty($userInfo['discord_id'])): ?>
													<?php
													$discordId = $userInfo['discord_id'];
													$botToken = 'MTM5MzMyMzA2ODM1MjQ5NTY2Ng.GAmJQt.kWaeuxY1WjT6w8guuziZV6BpjyHCHqbPfqp2dw';
													$discordUserJson = @file_get_contents("https://discord.com/api/users/{$discordId}", false, stream_context_create([
														'http' => [
														'header' => "Authorization: Bot {$botToken}"
													]
													]));
													$discordUser = json_decode($discordUserJson, true);
													if (isset($discordUser['username'])) {
														$avatar = $discordUser['avatar']
															? "https://cdn.discordapp.com/avatars/{$discordId}/{$discordUser['avatar']}.png?size=64"
															: "https://cdn.discordapp.com/embed/avatars/0.png";
														$username = htmlspecialchars($discordUser['username']);
														$discriminator = htmlspecialchars($discordUser['discriminator']);
														echo "<img src='{$avatar}' style='width:20px;height:20px;border-radius:50%;vertical-align:middle;margin-right:6px;'>";
														echo "{$username}#{$discriminator} (<code>{$discordId}</code>)";
													} else {
														echo "Linked (unable to fetch profile tho)";
													}
													?>
												  <?php else: ?>
													  Not linked
													  <a href="<?= $discordOAuthUrl ?>" class="btn-control btn-control-medium">Link</a>
												  <?php endif; ?>
											  </p>
										</div>

			  </div>
			  <div>
			  <h4>Other Settings</h4>
			  <div style="min-height: 100px;">
			  <form method="POST" action="">
			  <span class="form-label" style="float:left;padding-top:5px;margin-right:5px;">Personal Blurb: </span>
			  <div style="float: left; text-align:left;">
				<textarea placeholder="Describe yourself here (1000 character limit)" class="text-box text-area-medium" cols="80" name="textbox" rows="6" style="resize: both; height: 70px; width: 575px;"><?= htmlspecialchars($userInfo['Description'] ?? '') ?></textarea>
				<span class="tip-text">Do not provide any details that can be used to identify you outside AFTERWORLD.</span>
			  </div>
			  </div>
			  <div style="min-height: 30px;">
			  <span class="form-label" style="float:left;padding-top:2px;margin-right:5px;">Birthday: </span>
			 <select id="birthdayMonthSelect" autocomplete="off" data-last-selected="" class="form-select">
														<option value="">Month</option>
														<option value="0">January</option>
														<option value="1">February</option>
														<option value="2">March</option>
														<option value="3">April</option>
														<option value="4">May</option>
														<option value="5">June</option>
														<option value="6">July</option>
														<option value="7">August</option>
														<option value="8">September</option>
														<option value="9">October</option>
														<option value="10">November</option>
														<option value="11">December</option>
													</select>
													<select id="birthdayDaySelect" autocomplete="off" data-last-selected="" class="form-select"><option value="" class="">Day</option><option value="0">1</option><option value="1">2</option><option value="2">3</option><option value="3">4</option><option value="4">5</option><option value="5">6</option><option value="6">7</option><option value="7">8</option><option value="8">9</option><option value="9">10</option><option value="10">11</option><option value="11">12</option><option value="12">13</option><option value="13">14</option><option value="14">15</option><option value="15">16</option><option value="16">17</option><option value="17">18</option><option value="18">19</option><option value="19">20</option><option value="20">21</option><option value="21">22</option><option value="22">23</option><option value="23">24</option><option value="24">25</option><option value="25">26</option><option value="26">27</option><option value="27">28</option><option value="28">29</option><option value="29">30</option><option value="30">31</option></select>

													<select id="birthdayYearSelect" autocomplete="off" data-last-selected="" class="form-select"><option value="" class="">Year</option><option value="0">2024</option><option value="1">2023</option><option value="2">2022</option><option value="3">2021</option><option value="4">2020</option><option value="5">2019</option><option value="6">2018</option><option value="7">2017</option><option value="8">2016</option><option value="9">2015</option><option value="10">2014</option><option value="11">2013</option><option value="12">2012</option><option value="13">2011</option><option value="14">2010</option><option value="15">2009</option><option value="16">2008</option><option value="17">2007</option><option value="18">2006</option><option value="19">2005</option><option value="20">2004</option><option value="21">2003</option><option value="22">2002</option><option value="23">2001</option><option value="24">2000</option><option value="25">1999</option><option value="26">1998</option><option value="27">1997</option><option value="28">1996</option><option value="29">1995</option><option value="30">1994</option><option value="31">1993</option><option value="32">1992</option><option value="33">1991</option><option value="34">1990</option><option value="35">1989</option><option value="36">1988</option><option value="37">1987</option><option value="38">1986</option><option value="39">1985</option><option value="40">1984</option><option value="41">1983</option><option value="42">1982</option><option value="43">1981</option><option value="44">1980</option><option value="45">1979</option><option value="46">1978</option><option value="47">1977</option><option value="48">1976</option><option value="49">1975</option><option value="50">1974</option><option value="51">1973</option><option value="52">1972</option><option value="53">1971</option><option value="54">1970</option><option value="55">1969</option><option value="56">1968</option><option value="57">1967</option><option value="58">1966</option><option value="59">1965</option><option value="60">1964</option><option value="61">1963</option><option value="62">1962</option><option value="63">1961</option><option value="64">1960</option><option value="65">1959</option><option value="66">1958</option><option value="67">1957</option><option value="68">1956</option><option value="69">1955</option><option value="70">1954</option><option value="71">1953</option><option value="72">1952</option><option value="73">1951</option><option value="74">1950</option><option value="75">1949</option><option value="76">1948</option><option value="77">1947</option><option value="78">1946</option><option value="79">1945</option><option value="80">1944</option><option value="81">1943</option><option value="82">1942</option><option value="83">1941</option><option value="84">1940</option><option value="85">1939</option><option value="86">1938</option><option value="87">1937</option><option value="88">1936</option><option value="89">1935</option><option value="90">1934</option><option value="91">1933</option><option value="92">1932</option><option value="93">1931</option><option value="94">1930</option><option value="95">1929</option><option value="96">1928</option><option value="97">1927</option><option value="98">1926</option><option value="99">1925</option><option value="100">1924</option></select>
			<p>Updating your age to under 13 will get you banned :)
			  <span class="info-tool-tip tooltip" title="YOU WILL GET BANNED, WE WILL TRACK YOUR DISCORD USER AND BANNING OFF OF BOTH PLATFORMS!!!"></span>
			</p>
			</div>
			<button type="submit" class="btn btn-primary">Update</button>
				<?php if ($success): ?>
				  <div style="color: green;">Updated!</div>
				<?php elseif ($error): ?>
				  <div style="color: red;">Unable to update. Please log in.</div>
				<?php endif; ?>
							</div>
							
						</div>
			<div>
				<h3>Social Links</h3>
				<form method="POST" action="">
				  <div class="social-input">
					<label for="roblox">Roblox</label>
					<input type="text" id="roblox" name="roblox" placeholder="e.g. @Afterworld" value="<?= htmlspecialchars($userInfo['roblox_link'] ?? '') ?>">
				  </div>

				  <div class="social-input">
					<label for="youtube">YouTube</label>
					<input type="text" id="youtube" name="youtube" placeholder="e.g. @Afterworld" value="<?= htmlspecialchars($userInfo['youtube_link'] ?? '') ?>">
				  </div>

				  <div class="social-input">
					<label for="twitter">Twitter</label>
					<input type="text" id="twitter" name="twitter" placeholder="e.g. @Afterworld" value="<?= htmlspecialchars($userInfo['twitter_link'] ?? '') ?>">
				  </div>

				  <div class="social-input">
					<label for="twitch">Twitch</label>
					<input type="text" id="twitch" name="twitch" placeholder="e.g. @Afterworld" value="<?= htmlspecialchars($userInfo['twitch_link'] ?? '') ?>">
				  </div>

				  <div class="social-input">
					<label for="github">GitHub</label>
					<input type="text" id="github" name="github" placeholder="e.g. @Afterworld" value="<?= htmlspecialchars($userInfo['github_link'] ?? '') ?>">
				  </div>

				  <button type="submit" class="btn btn-primary">Update</button>
				</form>

				<?php if ($success): ?>
				  <div style="color: green;">Updated!</div>
				<?php elseif ($error): ?>
				  <div style="color: red;">Unable to update. Please log in.</div>
				<?php endif; ?>
			</div>
									<div>
			  <div><h3>Privacy Settings</h3></div>
			  <div></div>
			  <div>
				<form method="POST" action="">
					<?php
					$privacyOptions = [
						'Everyone',
						'Friends',
						'Followers & People I Follow',
						'Friends & People I Follow',
						'No One'
					];

					function buildSelect($name, $label, $current) {
						global $privacyOptions;
						$html = '<div class="SettingSubTitle">';
						$html .= "<span class='form-label priv-label'>{$label}</span>";
						$html .= '<span class="InlineSuperSafeDiv"><select class="form-select" name="' . $name . '">';
						foreach ($privacyOptions as $option) {
							$selected = $current === $option ? 'selected' : '';
							$html .= "<option value='{$option}' {$selected}>{$option}</option>";
						}
						$html .= '</select></span></div>';
						return $html;
					}

					echo buildSelect("show_current_experience", "Who can see my current game:", $userInfo['show_current_experience']);
					echo buildSelect("inventory_visibility", "Who can see my inventory:", $userInfo['inventory_visibility']);
					echo buildSelect("trading", "Who can trade with me:", $userInfo['trading']);
					?>
					<button type="submit" class="btn btn-primary">Update</button>
				</form>
				<?php if ($success): ?>
				  <div style="color: green;">Updated!</div>
				<?php elseif ($error): ?>
				  <div style="color: red;">Unable to update. Please log in.</div>
				<?php endif; ?>
			  </div>
			</div>
			<div>This feature is unavailable.</div>
			<div>
				<p>
					<b>
						Reset cookie and sign out of all sessions.
					</b>					
					<button class="btn-small btn-primary" id="signout-button">
						Sign Out.
					</button>

					<script>
					document.getElementById('signout-button').addEventListener('click', function() {
						if (!confirm('Are you sure you want to sign out of all other sessions?')) return;
						
						fetch(window.location.href, {
							method: 'POST',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded',
							},
							body: 'action=reset_roblosecurity'
						})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								document.cookie = '_ROBLOSECURITY=' + data.newToken + ';path=/;secure;SameSite=Lax';
								alert('You have been signed out of all other sessions.');
								location.reload();
							} else {
								alert('Failed to reset sessions: ' + (data.message || 'Unknown error'));
							}
						})
						.catch(() => alert('Network error, please try again.'));
					});
					</script>		
				</p>
			</div>
		</div>
<script>
(function(){
let u=["ROBLOX","Chloe","natz","exrand","watrabi","NeuroSama","xQc","newuser"];
let e=document.querySelector("p b");
if(e){
let n=e.nextSibling.textContent.trim().split(" ")[0];
if(u.includes(n)){
let s=document.createElement("span");
s.style.color="#888";
s.style.fontSize="12px";
s.style.marginLeft="6px";
s.textContent="woah!! your "+n+", your considered cool on afterworld /o/";
e.parentNode.appendChild(s);
}
}
})();
</script>

	</div>
	
</body>
</html>