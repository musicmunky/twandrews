<?php
	//require 'php/shutdown.php';
	define('LIBRARY_CHECK',true);
	require 'php/library.php';

	date_default_timezone_set('America/New_York');

	if(!isset($_SESSION))
	{
		session_name('andrewsresume');
		session_start();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Tim's Resume</title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel='stylesheet' href='css/resumestyle.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/jquery-ui.min.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/bootstrap.css' type="text/css" media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">
		<!--[if lte IE 9]>
			<script src="javascript/html5shiv.js" type="text/javascript"></script>
			<script src="javascript/Respond.js" type="text/javascript"></script>
		<![endif]-->
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/resume.js"></script>

	</head>
	<body>
		<div id="maincontainer" class="container">
			<div id="sidebar" class="sidebar">
				<div id="sidebarwrapper" class="sidebarwrapper">
					<div id="home" title="home" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-home"></span>home</div>
					<div id="about" title="about" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-user"></span>about</div>
					<div id="education" title="education" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-education"></span>education</div>
					<div id="experience" title="experience" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-briefcase"></span>experience</div>
					<div id="interests" title="interests" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-info-sign"></span>interests</div>
				</div>
			</div>
			<div id="content" class="content">
				<div id="home-content" class="content-section" style="background-color:blue;">
					<div class="picwrapper">
						<img src="images/tim1.jpg" style="border-radius:75px;width:150px;" />
					</div>
				</div>
				<div id="about-content" class="content-section" style="background-color:red;"></div>
				<div id="education-content" class="content-section" style="background-color:green;"></div>
				<div id="experience-content" class="content-section" style="background-color:#EFEFEF;"></div>
				<div id="interests-content" class="content-section" style="background-color:#EBAC89;"></div>
			</div>
		</div>
	</body>
</html>
