<?php
ini_set('display_errors', 1); // i hate php.inf
ini_set('display_startup_errors', 1); // i hate php.inf x2
error_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$id = $_POST['sid'] ?? null;
// missing fields n' shit
if (empty($id)) {
    exit("Please fill in the ID.");
}
// lets make sure that the game exists or smth
$checkQuery = $pdo->prepare("SELECT * FROM game_session WHERE serverid = :serverid");
$checkQuery->execute(['serverid' => strtolower($id)]);
if ($checkQuery->rowCount() > 0) {
    $existingRow = $checkQuery->fetch(PDO::FETCH_ASSOC);
    $existingId = $existingRow['serverid'];
	// if server found, BOOM!
    exit("Server found. <a href='/games/start?placeid=" . $existingId . "'>Join Game</a> <div> <b> Details: </b> <p>Server Id: ".$existingId."</p> </div>");
}
}
?>
<html>
	<head>
	<title>AfterWorld Launcher</title>
	<link rel="stylesheet" href="/CSS/Base/CSS/main___1cacbba05e42ebf55ef7a6de7f5dd3f0_m.css"/>
<link rel="stylesheet" href="/CSS/Pages/Legacy/Navigation.css"/>
	<script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/js/roblox.js"></script>
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
			<h1>AfterWorld Launcher (Load existing server) </h1>
			<div>
			<span class="status-error">This Launcher system is going to be deprecated soon, and to cumply the new system that's under development.</span>
				<form action="./get-server.php" method="post">
					<label for="sid">Server ID:</label>
					<input type="text" class="text-box text-box-large" id="sid" name="sid">
					<input type="submit" class="btn-medium btn-primary" value="Find">
				</form>
				<p>NOTE: DEPENDING ON YOUR SERVER SOURCE, YOUR IP MAY BE EXPOSED. TRUSTED SERVICES ARE FOR EXAMPLE: Playit.gg</p>
			</div>
		</center>
	</body>
</html>