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
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="../steph/javascript/bootstrap.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<style>
			body {
				background-color:#EEE !important;
				font-family:Helvetica;
			}
			a {
				text-decoration:none;
				color:#333;
				outline:none !important;
				cursor:pointer;
			}
			a:hover {
				text-decoration:none;
			}
			ul {
				list-style: none outside none;
			}
			.logoutdiv{
				padding:10px;
				border-top:1px solid #333;
				width:100%;
				font-weight:bold;
				font-size:21px;
				margin-top:20px;
				float:left;
			}
			.lglink {
				float:right;
			}
			.lglink:hover {
				color:#23527C;
				text-decoration:none;
			}
			.navspan {
				margin-right:10px;
				vertical-align:middle;
				margin-top:-5px;
			}
			.nswarning { color:#dd9a3b; }
			.nsokay { color:#5CB85C; }
			.mainwrapper {
				background-color: #fff;
				border-left: 1px solid #CCC;
				border-right: 1px solid #CCC;
				height: 100%;
				margin-left: auto;
				margin-right: auto;
				min-height: 700px;
				padding-left: 10px;
				padding-right: 10px;
				width: 1000px;
			}
			.maindiv {
				width:100%;
				float:left;
			}
			.lidiv {
				border-top: 1px solid #333;
				width:100%;
				font-size:24px;
				margin-top:20px;
				margin-bottom:20px;
			}
			.pagecolumn {
				width:50%;
				float:left;
				padding:20px;
			}
			.ulnav {
				margin-top:10px;
				margin-bottom:10px;
				padding-left:0px;
			}
			.linav {
				height:55px;
				border-radius:4px;
			}
			.ulnav > li > a {
				display: block;
				padding: 10px 15px;
				position: relative;
				border-radius:4px;
				width:80%;
				float:left;
			}
			.ulnav > li:hover {
				background-color:#EEE !important;
			}
			.colheader {
				width:100%;
				font-weight:bold;
				font-size:24px;
			}
			.remlnk {
				font-size: 18px;
				padding: 18px 10px !important;
				text-align: center;
				width: 10% !important;
			}
			.editlnk {
				font-size: 18px;
				padding: 18px 10px !important;
				text-align: center;
				width: 10% !important;
			}
			.alert_closebtn {
				float:right;
				width:30px;
				height:26px;
				background-color:#EDDDCD;
				border:1px solid #DADADA;
				border-radius:4px;
				margin:5px;
			}
			.alert_content {
				width:100%;
				float:left;
			}
			.new_item_cell {
				float:left;
				width:100%;
				margin-top:10px;
			}
			.new_item_lbl {
				font-size:14px;
				margin-bottom:0;
				width:100%;
				display:block;
				float:left;
				margin-top:5px;
			}
			.new_item_txt, .new_item_slct, .new_item_txtarea {
				background-color:#F3F3F3;
				border:none;
				box-shadow:0 2px 3px -2px #666;
				float:left;
				font-size:14px;
				height:30px;
				width:100%;
				padding:5px;
			}
			.new_item_txtarea {
				height:60px !important;
				resize:none;
			}
			#new_item_header {
				background-color:#68A4C4;
				border-bottom:1px solid #888;
				border-radius:3px;
				box-shadow:0 2px 2px -2px #666;
				padding-top:3px;
			}
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$('.remlnk').click(function(){
					removeItem(this.id);
				});
				$('.editlnk').click(function(){
					showAddItem(this.id);
				});
				$("#addlnk").click(function(){
					showAddItem("");
				});
			});
			function showAddItem(i){
				var id = i || "";
				var ttl = "Edit this Item"
				var pid = 0;
				if(FUSION.lib.isBlank(id)){
					ttl = "Add New Item";
				}
				else
				{
					var tmp = id.split("_");
					pid = tmp[1];
				}

				FUSION.get.node("ni_pageid").value = pid;
				FUSION.get.node("new_item_title").innerHTML = ttl;
				FUSION.get.node("new_item_overlay").style.height = FUSION.get.pageHeight() + "px";
				FUSION.get.node("new_item_overlay").style.display = "block";
				FUSION.lib.dragable("new_item_header", "new_item_wrapper");
			}
			function hideNewItem(){
				FUSION.get.node("new_item_overlay").style.display = "none";
				FUSION.get.node("new_item_title").innerHTML = "";
			}
			function removeItem(i){
				if(FUSION.lib.isBlank(i)){
					FUSION.lib.alert("Invalid ID - please refresh the page and try again")
				}
				else{
					alert("I WAS CALLED");
				}
			}
		</script>
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
