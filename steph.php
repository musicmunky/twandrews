<?php
	//require 'php/shutdown.php';
	define('LIBRARY_CHECK',true);
	require 'php/library.php';

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
	else
	{
		$cyear = date('Y');

		$begq = mysql_fetch_assoc(mysql_query("SELECT DISTINCT YEAR FROM scheddates ORDER BY YEAR ASC LIMIT 1;"));
		$endq = mysql_fetch_assoc(mysql_query("SELECT DISTINCT YEAR FROM scheddates ORDER BY YEAR DESC LIMIT 1;"));

		$beg = $begq['YEAR'] - 1;
		$end = $endq['YEAR'] + 1;

		$h = getStephScheduleHtml(array("firstload" => 1, "year" => $cyear));
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Steph's Work Schedule</title>
		<link rel="icon" type="image/png" href="images/calicon.png" />
		<link rel='stylesheet' href='css/calstyle.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/jquery-ui.min.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/bootstrap.css' type="text/css" media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/moment.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/stephschedule.js"></script>
	</head>
	<body>
		<input type="hidden" id="minyear" value="<?php echo $beg; ?>" />
		<input type="hidden" id="maxyear" value="<?php echo $end; ?>" />
		<input type="hidden" id="curyear" value="<?php echo $cyear; ?>" />
		<div id="header" class="header">
			<div id="headercont" class="header-content">
				<div style="float:left;width:200px;padding-left: 10px;">
					<div class="arrowdiv">
						<span id="nextspan" class="spanbtn glyphicon glyphicon-chevron-up" onclick="getNextYear();"
							  style="padding-top:5px;"></span>
					</div>
					<div class="title">
						<div id="titlediv" style="float:left;cursor:default;">
							<?php echo $h['content']['title']; ?>
						</div>
						<div style="float:left;margin-left:5px;cursor:default;">Calendar</div>
					</div>
					<div class="arrowdiv">
						<span id="prevspan" class="spanbtn glyphicon glyphicon-chevron-down" onclick="getPreviousYear();"
							  style="margin-top:-6px;"></span>
					</div>
				</div>
				<div class="header-nav" style="padding-right:10px;">
					<a href="steph.php?logout" class="logoutlink">Logout</a>
				</div>
				<div class="divider"></div>
				<div id="legendmenu" class="header-nav">
					<span>Legend</span>
					<span id="legud" class="glyphicon glyphicon-menu-down"
							  style="display:block;float:right;font-size:12px;margin-top:7px;margin-left:5px;"></span>
					<div class="container">
						<div class="legcont">
							<div class="legexmpl noselect" style="font-weight:bold;color:#F00;">2</div>
							<div id="legworktext" class="noselect">Bold red means Steph is working</div>
						</div>
						<div class="legcont">
							<div class="legexmpl noselect" style="font-weight:bold;color:#00F;">3</div>
							<div id="legkellytext" class="noselect">Bold blue means Steph has a kelly day</div>
						</div>
						<div class="legcont">
							<div class="legexmpl noselect" style="background-color:#FFFF88;color:#000;">5</div>
							<div id="legholidaytext" class="noselect">A yellow background indicates a holiday</div>
						</div>
						<div class="legcont">
							<div class="legexmpl noselect" style="font-style:italic;color:#000;">8</div>
							<div id="legpaydaytext" class="noselect">Italic text indicates Tim's payday</div>
						</div>
					</div>
				</div>
				<div class="divider"></div>
				<div class="header-nav">
					<a href="index.php" class="logoutlink">Home</a>
				</div>
			</div>
		</div>
		<div id="maincontent" class="main-content">
			<div style="width:100%;">
				<div id="tablediv" style="float:left;">
					<?php echo $h['content']['table']; ?>
				</div>
			</div>
		</div>
		<div style="width:100%;"></div>
		<div class="footer">
			<div class="innerfooter" style="text-align:center;">
				<span>Steph's <span id="footeryear"><?php echo $cyear; ?></span> Work Schedule</span>
			</div>
		</div>
		<?php include "includes/dialogs.html" ?>
	</body>
</html>
