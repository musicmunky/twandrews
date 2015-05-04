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
	$titletext = "";
	$prdtext = "";
	$devtext = "";

	if(!isset($_SESSION['username']) || !isset($_SESSION['userid']))
	{
		header('Location: login.php');
	}
	else
	{
		$prdtext = "title='Primary development complete'";
		$devtext = "title='Currently under development'";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>Please choose a page</title>
		<link rel="icon" type="image/png" href="images/calicon.png" />
		<link rel='stylesheet' href='../steph/css/bootstrap.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='../steph/css/bootstrap-theme.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="../steph/javascript/bootstrap.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<style>
			body {
				background-color:#DEE1E4 !important;
				font-family:Helvetica;
			}
			a {
				text-decoration:none;
				color:#DEE1E4;
				outline:none;
			}
			.lglink:hover {
				color:#DEE1E4;
				text-decoration:none;
			}
			.navspan {
				margin-right:10px;
				vertical-align:middle;
				margin-top:-5px;
			}
			.nswarning { color:#dd9a3b; }
			.nsokay { color:#5CB85C; }
			.maindiv {
				background-color:#262626;
				margin-left:100px;
				width:525px;
				color:#DEE1E4;
				padding:20px;
				margin-top:100px;
				border:1px solid #666;
				border-radius:12px;
			}
			.lidiv {
				border-top: 1px solid #DEE1E4;
				border-bottom: 1px solid #DEE1E4;
				width:100%;
				font-size:24px;
				margin-top:20px;
				margin-bottom:20px;
			}
		</style>
	</head>
	<body>
		<div class="maindiv">
			<div style="width:100%;font-weight:bold;font-size:24px;">
				Please select a destination:
			</div>
			<div class="lidiv">
				<ul class="nav nav-pills nav-stacked" style="margin-top:10px;margin-bottom:10px;">
					<li <?php echo $prdtext; ?>><a href="timesheet" target="_blank">
							<span class="glyphicon glyphicon-ok-sign navspan nsokay" aria-hidden="true"></span>
							Tim's Timesheet</a></li>
					<li <?php echo $prdtext; ?>><a href="steph" target="_blank">
							<span class="glyphicon glyphicon-ok-sign navspan nsokay" aria-hidden="true"></span>
							Steph's Work Schedule</a></li>
					<li <?php echo $prdtext; ?>><a href="adminer/andrewsdb.php" target="_blank">
							<span class="glyphicon glyphicon-ok-sign navspan nsokay" aria-hidden="true"></span>
							Adminer</a></li>
					<li <?php echo $prdtext; ?>><a href="icecoder/index.php" target="_blank">
							<span class="glyphicon glyphicon-ok-sign navspan nsokay" aria-hidden="true"></span>
							ICEcoder</a></li>
					<li <?php echo $prdtext; ?>><a href="https://github.com/musicmunky/twandrews" target="_blank">
							<span class="glyphicon glyphicon-ok-sign navspan nsokay" aria-hidden="true"></span>
							Github</a></li>
					<li <?php echo $devtext; ?>><a href="resume" target="_blank">
							<span class="glyphicon glyphicon-exclamation-sign navspan nswarning" aria-hidden="true"></span>
							Tim's Resume</a></li>
					<li <?php echo $devtext; ?>><a href="schedule" target="_blank">
							<span class="glyphicon glyphicon-exclamation-sign navspan nswarning" aria-hidden="true"></span>
							Andrews Family Schedule</a></li>
					<li <?php echo $devtext; ?>><a href="training.php" target="_blank">
							<span class="glyphicon glyphicon-exclamation-sign navspan nswarning" aria-hidden="true"></span>
							Running Schedule</a></li>
					<li <?php echo $devtext; ?>><a href="steph/stephtest.php" target="_blank">
							<span class="glyphicon glyphicon-exclamation-sign navspan nswarning" aria-hidden="true"></span>
							Recipe Project</a></li>
					<li <?php echo $devtext; ?>><a href="socktest" target="_blank">
							<span class="glyphicon glyphicon-exclamation-sign navspan nswarning" aria-hidden="true"></span>
							WebSockets Testing</a></li>
					<li <?php echo $devtext; ?>><a href="weather" target="_blank">
							<span class="glyphicon glyphicon-exclamation-sign navspan nswarning" aria-hidden="true"></span>
							Weather App</a></li>
				</ul>
			</div>
			<div style="width:100%;font-weight:bold;font-size:21px;margin-top:20px;">
				<a class="lglink" href="index.php?logout" style="font-size:24px;display:block;width:50%;">logout</a>
			</div>
		</div>
		<div style="width:100%;float:left;"><!-- Empty div for testing code from time to time --></div>

	</body>
</html>