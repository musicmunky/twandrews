<?php
//	define('LIBRARY_CHECK',true);
//	require 'php/library.php';

	define('INCLUDE_CHECK',true);
	require 'golf/php/connect.php';

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
		$mysqli->select_db("andrewsdb");

		$projshtml	= "";
		$toolshtml	= "";
		$projs		= $mysqli->query("SELECT * FROM projectpages
									  WHERE PAGETYPE='project'
									  ORDER BY PAGESTAT ASC, ID ASC;");
		if($projs)
		{
			while($row = $projs->fetch_assoc())
			{
				if($row['PAGESTAT'] == "development" && $row['PAGETYPE'] == "project")
				{
					$ttltxt = "Currently under development";
					$csscls = "glyphicon glyphicon-exclamation-sign navspan nswarning";
				}
				else
				{
					$ttltxt = "Primary development complete";
					$csscls = "glyphicon glyphicon-ok-sign navspan nsokay";
				}

				$projshtml .= "<li class='linav' title='" . $ttltxt . "'>
								<a href='" . $row['PAGELINK'] . "' target='_blank'>
									<span class='" . $csscls . "' aria-hidden='true'></span>
									" . $row['PAGENAME'] . "
								</a>
								<a title=\"Edit " . $row['PAGENAME'] . "\"
								   class='editlnk glyphicon glyphicon-pencil' id='editlnk_" . $row['ID'] . "'></a>
								<a title=\"Remove " . $row['PAGENAME'] . "\"
								   class='remlnk glyphicon glyphicon-remove' id='remlnk_" . $row['ID'] . "'></a>
							</li>";
			}
		}

		$tools		= $mysqli->query("SELECT * FROM projectpages
									  WHERE PAGETYPE='tool'
									  ORDER BY ID ASC;");
		if($tools)
		{
			while($row = $tools->fetch_assoc())
			{
				$toolshtml .= "<li class='linav'><a href='" . $row['PAGELINK'] . "' target='_blank'>
								" . $row['PAGENAME'] . "
								</a>
								<a title=\"Edit " . $row['PAGENAME'] . "\"
								   class='editlnk glyphicon glyphicon-pencil' id='editlnk_" . $row['ID'] . "'></a>
								<a title=\"Remove " . $row['PAGENAME'] . "\"
								   class='remlnk glyphicon glyphicon-remove' id='remlnk_" . $row['ID'] . "'></a>
							</li>";
			}
		}
		mysqli_close($mysqli);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>MyDevelopment Projects</title>
		<link rel="icon" type="image/png" href="images/calicon.png" />
		<link rel='stylesheet' href='../steph/css/bootstrap.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='../steph/css/bootstrap-theme.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/indexstyle.css' type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="../steph/javascript/bootstrap.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/indexjs.js"></script>
	</head>
	<body>
		<div class="mainwrapper">
			<div class="maindiv">
				<div style="width:100%;margin-top:50px;">
					<div id="projectdiv" class="pagecolumn">
						<div class="colheader">
							Please choose a project:
						</div>
						<div class="lidiv">
							<ul class="ulnav"><?php echo $projshtml; ?></ul>
						</div>
					</div>
					<div id="tooldiv" class="pagecolumn">
						<div class="colheader">
							Please select a tool:
						</div>
						<div class="lidiv">
							<ul class="ulnav"><?php echo $toolshtml; ?></ul>
						</div>
					</div>
				</div>
				<div class="logoutdiv">
					<a class="lglink" style="float:left;" id="addlnk">Add Item</a>
					<a class="lglink" href="index.php?logout" style="">Logout</a>
				</div>
			</div>
			<div style="width:100%;float:left;"><!-- Empty div for testing code from time to time --></div>
		</div>

		<div id="new_item_overlay" class="fl_alert_overlay">
			<div id="new_item_wrapper" class="fl_alert_wrapper" style="width:350px;height:500px;padding:15px;top:200px">
				<div id="new_item_header" class="alert_content alert_dragable">
					<span id="new_item_title"
						  style="display:block;float:left;font-weight:bold;font-size:18px;height:40px;line-height:37px;margin-left:5px;color:#FFF;">
						Add New Item
					</span>
					<button onclick="hideNewItem()" style="background-color:#EEE;" class="alert_closebtn">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</button>
				</div>
				<div style="width:100%;height:370px;">
					<div class="new_item_cell">
						<label class="new_item_lbl">Page Name:</label>
						<input type="text" class="new_item_txt" id="ni_pagename" value="" />
						<input type="hidden" id="ni_pageid" value="0" />
					</div>
					<div class="new_item_cell">
						<label class="new_item_lbl">Page Link:</label>
						<input type="text" class="new_item_txt" id="ni_pagelink" value="" />
					</div>
					<div class="new_item_cell">
						<label class="new_item_lbl">Page Type:</label>
						<select id="ni_pagetype" class="new_item_slct">
							<option value="">Please select a type...</option>
							<option value="project">Project</option>
							<option value="tool">Tool</option>
						</select>
					</div>
					<div class="new_item_cell">
						<label class="new_item_lbl">Page Status:</label>
						<select id="ni_pagestat" class="new_item_slct">
							<option value="">Please select a status...</option>
							<option value="complete">Completed</option>
							<option value="development">In Development</option>
						</select>
					</div>
					<div class="new_item_cell">
						<label class="new_item_lbl">Page Description:</label>
						<textarea id="ni_pagedesc" class="new_item_txtarea"></textarea>
					</div>

				</div>
			</div>
		</div>
	</body>
</html>
