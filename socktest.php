<?php
	define('LIBRARY_CHECK',true);
	require 'php/socklib.php';

	date_default_timezone_set('America/New_York');

	if(isset($_GET['logout']))
	{
		session_destroy();
		$_SESSION = array();
		header("Location: login.php");
		exit;
	}

	if(!isset($_SESSION))
	{
		session_name('andrewscal');
		session_start();
	}

	if(!isset($_SESSION['username']) || !isset($_SESSION['userid']))
	{
		header('Location: login.php');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>WebSocket Testing</title>
		<link rel='stylesheet' type="text/css" href='css/fusionlib.css' media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/sockstyle.css' media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
 		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/wsjs.js"></script>
		<script language="javascript" type="text/javascript" src="https://www.google.com/jsapi"></script>
	</head>

<!--	<body onload="initWs()">//-->
	<body>
		<div class="main-wrapper">

			<h3 style="width:100%;">WebSocket</h3>

			<div id="log"></div>

			<div style="width:100%;margin-top:10px;margin-bottom:10px;">
				<label>Enter Message:</label>
				<textarea id="msg" style="width:100%;height:100px;resize:none;" onkeypress="onkey(event)"></textarea>
			</div>

			<div style="width:100%;height:40px;text-align:center;">
				<button onclick="sendWs()">Send</button>
				<button onclick="quitWs()">Quit</button>
				<button onclick="reconnectWs()">Reconnect</button>
				<button onclick="clearWs()">Clear Log</button>
			</div>

			<div style="width:100%;height:40px;text-align:center;">
				<input type="button" value="Start WebSocket" onclick="startWs()" />
				<input type="button" value="Stop WebSocket" onclick="stopWs()" />
				<input type="button" value="Is WebSocket Running?" onclick="checkWs()" />
<!--				<input type="button" value="Restart Apache" onclick="restartApache()" />//-->
			</div>
		</div>
	</body>
</html>