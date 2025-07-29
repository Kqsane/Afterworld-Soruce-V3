<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
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
			<h1>AfterWorld Launcher</h1>
			<div>
			<span class="status-error">This Launcher system is going to be deprecated soon, and to cumply the new system that's under development.</span>
				<div>
				<form action="./post-server.php" method="post">
					<label for="sip">Server IP:</label>
					<input type="text" class="text-box text-box-medium" id="sip" name="sip"><br><br>
					<label for="sport">Server Port:</label>
					<input type="text" class="text-box text-box-medium" id="sport" name="sport"><br><br>
					<input type="submit"  class="btn-large btn-large-green-play" value="        Play">
				</form>
				<p>NOTE: DEPENDING ON YOUR SERVER SOURCE, YOUR IP MAY BE EXPOSED. TRUSTED SERVICES ARE FOR EXAMPLE: Playit.gg</p>
			</div>
			</div>
			<b>If are you interested to join in a existing server, <a href="/games/list/get-server.php" class="btn-small btn-neutral">go to this page</a></b>
		</center>
	</body>
</html>