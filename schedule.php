<?php
	define('LIBRARY_CHECK',true);
	require 'php/library.php';

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

	$html = "";
	if(!isset($_SESSION['username']) || !isset($_SESSION['userid']))
	{
		header('Location: login.php');
	}
	else
	{

	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>The Andrews Calendar</title>
		<meta charset='utf-8' />
		<link rel="icon" type="image/png" href="images/calicon.png" />
		<link rel='stylesheet' href='css/jquery-ui.min.css' />
		<link href='css/fullcalendar.css' rel='stylesheet' />
		<link href='css/fullcalendar.print.css' rel='stylesheet' media='print' />
		<script src='javascript/moment.min.js'></script>
<!--		<script src='javascript/jquery.min.js'></script>
		<script src='javascript/jquery-ui.custom.min.js'></script>-->
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/training.js"></script>
		<script src='javascript/fullcalendar.min.js'></script>
		<style>
			body {
				margin: 0;
				padding: 0;
				font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
				font-size: 14px;
			}

			#calendar {
				width: 900px;
				margin: 40px auto;
			}
		</style>
	</head>
	<body>
		<a id="logout" name="logout" style="text-decoration:none;" href="schedule.php?logout">logout</a>
		<a href="scheduleadmin.php" style="text-decoration: none;">Admin Page</a>
		<div id='calendar'></div>
	</body>
</html>
