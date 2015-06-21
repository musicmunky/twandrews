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

	if(!isset($_SESSION['username']) || !isset($_SESSION['userid']) || empty($_SESSION['username']) || empty($_SESSION['userid']))
	{
		header('Location: login.php');
	}

	$adminlink = "";
	if($_SESSION['usertype'] == "ADMIN")
	{
		$adminlink = "<li><a href='admin.php'>Admin</a></li>";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<?php include("includes/headtag.html"); ?>
	</head>
	<body>
		<?php include("includes/header.html"); ?>
		<div class="container centercontent">
			Statistics Page
		</div>
	</body>
</html>
