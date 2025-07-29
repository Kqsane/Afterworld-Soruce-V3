<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if(!isset($_COOKIE["_ROBLOSECURITY"])){
	header("Location: /newlogin");
	exit();
}
$userinfo = getuserinfo($_COOKIE["_ROBLOSECURITY"]);
if($userinfo["activated"] == 1){
	header("Location: /home/");
	exit();
}

?>
<html>
<head>
	<title>Afterworld - Activation</title>
<link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css"/>
<link rel="stylesheet" href="/CSS/Pages/Legacy/Navigation.css"/>
	<script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/js/roblox.js"></script>
	<style>
	.activation-box {
		
		width: 700px;
		height: 200px;
		border: 2px solid blue;

		margin-left: auto;
		margin-right: auto;
	}
	</style>
</head>
<body>
<div class="nav-container no-gutter-ads">
<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Header.php';
	?>

<div id="navContent" class="nav-content  nav-no-left" style="margin-left: 0px; width: 100%;">
        <div class="nav-content-inner">
            <div id="MasterContainer">
                    <script type="text/javascript">
                        if (top.location != self.location) {
                            top.location = self.location.href;
                        }
                    </script>


                <div>
                                                            <noscript><div class="SystemAlert"><div class="SystemAlertText">Please enable Javascript to use all the features on this site.</div></div></noscript>
                    <div id="BodyWrapper" class="">
                        <div id="RepositionBody">
                            <div id="Body" style="width:970px">
	<center>
	<h1>Activation</h1>
	</center>
	<div class="activation-box">
	<form action="/landing/activation" method="post">
	<p>
		<span class="status-confirm" style="margin:10px;">In order to be able to play Afterworld, you must Insert the Invite key to access.</span>
	</p>
	<div style="min-height: 45px;margin:10px;margin-bottom:100px;">
		<span class="form-label" style="float:left;padding-top:5px;margin-right:5px;">Invite key:</span>
		<div style="float: left; width: 600px; text-align:left;">
			<input type="text" class="text-box text-box-medium" value="" name="invitekey" placeholder="aftworld-realkey2025" style="width:400px;" />
			<span class="tip-text">Please input a valid key.</span>
		<div>
<?php
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if(!isset($_POST["invitekey"]) || empty($_POST["invitekey"])){
			echo'<p>
				<span class="status-error">Please fill the key.</span>
			</p>
			</div>
		</div>
		<input type="submit" class="btn-large btn-primary"value="Validate" style="margin:10px;"/>';
			exit();
			}
			$invitekey = $_POST["invitekey"];
			$FindKey = $pdo->prepare('SELECT * FROM invitekeys WHERE invitekey = :invitekey');
			$FindKey->execute(['invitekey' => $invitekey]);
			$row = $FindKey->fetch(PDO::FETCH_ASSOC);
			if (!$row){
				echo'<p>
				<span class="status-error">Invalid Key.</span>
				</p>
				</div>
		</div>
		<input type="submit" class="btn-large btn-primary"value="Validate" style="margin:10px;"/>';
				exit();
			}
			
			
			$count = $row["used"] + 1;
			$updateKey = $pdo->prepare('UPDATE `invitekeys` SET `used` = :count WHERE `genId` = :genid;');
			$updateKey->execute(['count' => $count, 'genid' => $row["genId"]]);
			if ($updateKey->rowCount() === 0) {
				echo '<p>
					<span class="status-error">An Unknown error occurred.</span>
					</p>
					</div>
					</div>
					<input type="submit" class="btn-large btn-primary" value="Validate" style="margin:10px;"/>';
				exit();
				}
			$updateuser = $pdo->prepare('UPDATE `users` SET `activated` = 1 WHERE `ROBLOSECURITY` = :token;');
			$updateuser->execute(['token' => $_COOKIE["_ROBLOSECURITY"]]);
			if($updateuser){
				header("Location: /home/");
				exit();
			}else{
				echo'<p>
				<span class="status-error">An Unknown error occured while activating your account..</span>
				</p>
				</div>
		</div>
		<input type="submit" class="btn-large btn-primary"value="Validate" style="margin:10px;"/>';
				exit();
			}
		}
?>
		</div>
		</div>
		<input type="submit" class="btn-large btn-primary"value="Validate" style="margin:10px;"/>
	</div>
	
	</form>
	</div>
	
</body>
</html>