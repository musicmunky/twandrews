<?php
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
		$eyear = $cyear + 5;
		$nummonth = date('n');

		$months = array(1 => "January",
						2 => "February",
						3 => "March",
						4 => "April",
						5 => "May",
						6 => "June",
						7 => "July",
						8 => "August",
						9 => "September",
						10 => "October",
						11 => "November",
						12 => "December");

		$yearhtml = "";
		for($i = ($cyear - 1); $i <= $eyear; $i++)
		{
			$sel = ($i == $cyear) ? " selected" : "";
			$yearhtml .= "<option value='" . $i . "'" . $sel . ">" . $i . "</option>";
		}

		$monthhtml = "";
		foreach ($months as $num => $name)
		{
			$sel = ($num == $nummonth) ? " selected" : "";
			$monthhtml .= "<option value='" . $num . "'" . $sel . ">" . $name . "</option>";
		}

		$hrselect = "<option value=''></option>";
		for($i = 1; $i <= 12; $i++)
		{
			$n = sprintf('%02d', $i);
			$hrselect .= "<option value='" . $n . "'>" . $n . "</option>";
		}
		$mnselect = "<option value=''></option><option value='00'>00</option><option value='30'>30</option>";
		$apselect = "<option value=''></option><option value='am'>AM</option><option value='pm'>PM</option>";

		$tsinfo = getMonthInfo(array("month" => $nummonth, "year" => $cyear, "userid" => $_SESSION['userid'], "firstload" => 1));

		$maintablehtml = $tsinfo['content']['mainhtml'];
		$sidetablehtml = $tsinfo['content']['sidehtml'];
		$finalsidetablehtml = $tsinfo['content']['finalhtml'];
// 		$height = $tsinfo['content']['height'];
		$headstring = $tsinfo['content']['headstr'];

		$nameinfo = mysql_fetch_assoc(mysql_query("SELECT FIRST, LAST FROM eventadmin WHERE ID=" . $_SESSION['userid'] . ";"));
		$name = $nameinfo['FIRST'] . " " . $nameinfo['LAST'];
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>Tim's Work Schedule</title>
		<link rel="icon" type="image/png" href="images/calicon.png">
		<link rel='stylesheet' href='css/style.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/jquery-ui.min.css' type="text/css" media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/tsjs.js"></script>
	</head>
	<body>
		<!--<div id="mainwrapper" style="width:1100px;margin-left:auto;margin-right:auto;background-color:#EFEFEF;height:<?php //echo $height; ?>;">-->
		<div id="mainwrapper" style="width:1100px;margin-left:auto;margin-right:auto;background-color:#EFEFEF;height:100%;">
			<div id="headwrapper" class="wrapper" style="height:120px;border-bottom:1px solid;margin:10px;padding:0px;">
				<div style="float: right; position: relative; margin-bottom: -55px;">
					<label style="display:block;margin-right:5px;font-size:12px;">Welcome, <?php echo $name; ?>!</label>
					<a href="timesheet.php?logout" 
					   style="display:block;float:right;" title="logout">
						 <img src="images/logout.png" style="height: 15px; width: 15px;" />
					</a><br />
					<a style="display:block;font-size:12px;text-decoration:none;float:right;margin-right:20px;margin-top:10px;" href="scheduleadmin.php">
						Update Name/Password
					</a>
				</div>
				<div id="headrowtop" style="display:block;float:left;width:100%;height:60px;text-align:center;">
					<span style="display:block;margin-top:10px;font-size:30px;">Work Schedule</span>
				</div>

				<div style="float:left;width:300px;">
					<div style="width:100%;margin-bottom:10px;">
						<label style="width:175px;">Please select a Month: </label>
						<select id="month" style="width:90px;" onchange="refreshTimesheet()">
							<?php echo $monthhtml; ?>
						</select>
					</div>
					<div style="width:100%;">
						<label style="width:175px;">Please select a Year: </label>
						<select id="year" style="width:90px;" onchange="refreshTimesheet()">
							<?php echo $yearhtml; ?>
						</select>
					</div>
				</div>

				<div style="margin-bottom:10px;float:left;height:50px;width:400px;margin-left:50px;">
					<input id="previousbutton" class="navbuttons" type="button"
						   value="<< Prev Month" onclick="getPreviousMonth();" style="margin-left:20px;" />
					<input id="nextbutton" class="navbuttons" type="button"
						   value="Next Month >>" onclick="getNextMonth();" style="margin-left:200px;" />
				</div>

			</div>
			<div id="contentwrapper" class="wrapper">
				<div id="maintablewrapper" style="width:680px;float:left;">
					<table id="maintable" style="margin-top:20px;">
						<thead>
							<tr style="border:2px solid;">
								<th class="maintablecol">Date</th>
								<th class="maintablecol">Start</th>
								<th class="maintablecol">Begin Break</th>
								<th class="maintablecol">End Break</th>
								<th class="maintablecol">End</th>
								<th class="maintablecol">Hours</th>
								<th class="maintablecol">Leave/PTO</th>
							</tr>
						</thead>
						<tbody id="maintabletbody">
							<?php echo $maintablehtml; ?>
						</tbody>
					</table>
					<span id="maintemp" style="visibility:hidden;"></span>
				</div>
				<div id="sidetablewrapper" style="width:400px;float:left;">
					<table id="sidetable" style="float:right;">
						<thead>
							<tr style="border:2px solid;">
								<th id="sidetableheader" colspan="3" style="background-color:#CCFFCC;">
									<?php echo $headstring; ?>
								</th>
							</tr>
							<tr style="border:2px solid;">
								<th style="width:100px;">Day</th>
								<th style="width:75px;">Hours</th>
								<th style="width:170px;text-align:left;">Note</th>
							</tr>
						</thead>
						<tbody id="sidetabletbody">
							<?php echo $sidetablehtml; ?>
							<?php echo $finalsidetablehtml; ?>
						</tbody>
					</table>
					<span id="sidetemp" style="visibility:hidden;"></span>
				</div>
			</div>
		</div>
		<input type="hidden" id="userid" value="<?php echo $_SESSION['userid']; ?>" />

		<div id="newtimeform" title="New Timesheet Entry">
			<div>
				<form id="ntform">
				<input type="hidden" id="dateid" value="" />
				<div class="fielddivs" style="padding-top:15px;">
					<div class="userdivs">Start: </div>
					<select id="starthour"	 class="selflds"><?php echo $hrselect; ?></select><span>:</span>
					<select id="startminute" class="selflds"><?php echo $mnselect; ?></select>
					<select id="startampm"	 class="selflds"><?php echo $apselect; ?></select>
				</div>
				<div class="fielddivs">
					<div class="userdivs">Begin Break: </div>
					<select id="startbreakhour"		class="selflds"><?php echo $hrselect; ?></select><span>:</span>
					<select id="startbreakminute"	class="selflds"><?php echo $mnselect; ?></select>
					<select id="startbreakampm"		class="selflds"><?php echo $apselect; ?></select>
				</div>
				<div class="fielddivs">
					<div class="userdivs">End Break: </div>
					<select id="endbreakhour"	class="selflds"><?php echo $hrselect; ?></select><span>:</span>
					<select id="endbreakminute" class="selflds"><?php echo $mnselect; ?></select>
					<select id="endbreakampm"	class="selflds"><?php echo $apselect; ?></select>
				</div>
				<div class="fielddivs">
					<div class="userdivs">End: </div>
					<select id="endhour"	class="selflds"><?php echo $hrselect; ?></select><span>:</span>
					<select id="endminute"	class="selflds"><?php echo $mnselect; ?></select>
					<select id="endampm"	class="selflds"><?php echo $apselect; ?></select>
				</div>
				<div class="fielddivs">
					<div class="userdivs">Holiday/PTO: </div>
					<input type="text" id="pto" class="userinputs" style="width:170px" value="" />
				</div>
				<div class="fielddivs">
					<div class="userdivs">Leave: </div>
					<input type="text" id="leave" class="userinputs" style="width:170px" value="" />
				</div>
				<div class="fielddivs">
					<div class="userdivs">Note: </div>
					<input type="text" id="note" class="userinputs" style="width:170px" value="" />
				</div>
				<div class="fielddivs" style="text-align: center;">
					<input type="button" class="createuserbtn" value="Update Entry" onclick="addUpdateTimeEntry()" />
					<input type="button" class="createuserbtn" value="Cancel" onclick="hideNewTimeForm()" />
				</div>
				</form>
			</div>
		</div>
		<div style="width: 100%; bottom: 0px; float: left;"></div>
	</body>
</html>
