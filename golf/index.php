<?php
	define('LIBRARY_CHECK',true);
	require 'php/golflib.php';

	if(isset($_GET['logout']))
	{
		session_destroy();
		$_SESSION = array();
		header("Location: login.php");
		exit;
	}

	if(!isset($_SESSION))
	{
		session_name('andrewsgolf');
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
		<title>MyGolf</title>
		<!--<link rel="icon" type="image/png" href="images/calicon.png" />-->
		<link rel='stylesheet' href='../css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="../javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="../javascript/fusionlib.js"></script>
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
		</style>
	</head>
	<body>
		<div class="maindiv">
			<div style="width:100%;font-weight:bold;font-size:21px;margin-top:20px;">
				<a href="index.php?logout" style="font-size:24px;display:block;width:50%;">logout</a>
			</div>
		</div>
		<div style="width:100%;float:left;"><!-- Empty div for testing code from time to time --></div>

	</body>
</html>
