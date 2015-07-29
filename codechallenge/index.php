<?php

	define('LIBRARY_CHECK',true);
	require 'php/challengelib.php';

	$title = "CrimeWatch";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="viewport" content="initial-scale=1, maximum-scale=1" />
		<title><?php echo $title; ?></title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel='stylesheet' type="text/css" href='css/ccstyle.css'  media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/fusionlib.css' media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/jquery-ui.css' media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.3.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript" type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAItsJVet4OiBn43s-X40CmFFKTbGEzcUY"></script>
		<script language="javascript" type="text/javascript" src="javascript/challenge.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/hcjs.js"></script>
		<script type="text/javascript">
			/*
			function initialize() {
				var mapOptions = {
					center: { lat: 47.5951456, lng: -122.331601},
					zoom: 15
				};
				var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			}
			google.maps.event.addDomListener(window, 'load', initialize);
			*/
		</script>
	</head>
	<body>
		<!-- default to Seattle if there's no localStorage -->
		<input type="hidden" id="defaultzipcode" value="98134" />
		<div id="header" class="header">
			<div id="headercont" class="header-content">
				<div class="header-logo">
					<div class="logowrapper">
						<div class="title">
							<img id="logo" src="images/handcuffs.png" class="logoimage" />
							<div id="titlediv" class="titletext"><?php echo $title; ?></div>
						</div>
					</div>
					<div class="h100fl">
						<div id="datewrapper" class="h100fl">
							<span id="date" class="headspan"></span>
						</div>
					</div>
				</div>

				<div class="header-search">
					<div class="w100fl h100fl" id="srchcont">
						Socrata Code Challenge
					</div>
				</div>
			</div>
		</div>
		<div id="mainwrapper" class="mainwrapper">
			<div class="oldcitywrapper">
				<div id="oldcitydiv" class="w100fl h100fl">
					<div id="apisettings-tab" class="citydiv" style="background-color:#EEE;border-right:1px solid #DDD;border-left:1px solid #DDD;">
						Settings
					</div>
					<div id="highcharts-tab" class="citydiv">
						Incidents by Category
					</div>
					<div id="googlemaps-tab" class="citydiv">
						Google Maps
					</div>
					<div id="about-tab" class="citydiv">
						About
					</div>
				</div>
			</div>

			<!-- SETTINGS DIV -->
			<div id="apisettings-div" class="w100fl" style="display:block;height:500px;">
				<h3>Search Settings</h3>
				<div class="settingsrow">
					<span class="fl settingsspan">Max Results: </span>
					<input type="text" id="maxresults" value="" class="searchbox searchinput" onkeyup="FUSION.lib.noAlpha(this)" />
				</div>
				<div class="settingsrow">
					<span class="fl settingsspan">Range: </span>
					<select id="range" class="searchbox" style="height:22px;">
						<option value="">Select a Range...</option>
						<option value="0.5">0.5 Miles</option>
						<option value="1">1 Mile</option>
						<option value="5">5 Miles</option>
						<option value="10">10 Miles</option>
					</select>
				</div>
				<div class="settingsrow">
					<span class="fl settingsspan">Start Date: </span>
					<input type="text" id="startdate" value="" class="searchbox searchinput" />
				</div>
				<div class="settingsrow">
					<span class="fl settingsspan">End Date: </span>
					<input type="text" id="enddate" value="" class="searchbox searchinput" />
				</div>
				<div class="settingsrow" style="text-align:center;width:100%;">
					<input type="button" value="Submit Query" class="srchbtn" id="querybtn" />
				</div>
			</div>

			<!-- HIGHCHARTS DIV -->
			<div id="highcharts-div" class="w100fl" style="display:none;height:500px;">
				<h3>Incidents by Category</h3>
				<div id="hc-controls" style="width:100%;line-height:40px;height:120px;">
					<div style="width:50%;float:left;">
						<span class="fl settingsspan" style="width:100px;">Chart Type: </span>
						<select id="charttype" class="searchbox" style="height:22px;margin-top:0px;width:150px;">
							<option value="">Select a Type...</option>
							<option value="pie">Pie</option>
							<option value="column">Column</option>
							<option value="bar">Bar</option>
						</select>
					</div>
					<div style="width:50%;float:left;">
						<span class="fl settingsspan" style="width:150px;">Display Parameter: </span>
						<select id="chartparams" class="searchbox" style="height:22px;margin-top:0px;width:200px;">
							<option value="">Select a Parameter...</option>
							<option value="initial_type_group">Call Type</option>
							<option value="district_sector">District Sector</option>
							<option value="zone_beat">Zone</option>
							<option value="hundred_block_location">General Location</option>
							<option value="event_clearance_description">Description</option>
						</select>
					</div>

					<div style="width:100%;float:left;">
						<span class="fl settingsspan" style="width:100%;">Date Range: <span id="daterangespan" style="margin-left:20px;"></span></span>
					</div>
					<div style="width:100%;float:left;">
						<div id="dateslider" style="width:calc(100% - 30px);float:left;margin-left:15px;"></div>
					</div>
				</div>
				<div id="hc-container" style="width:100%;height:500px;float:left;"></div>
			</div>

			<!-- GOOGLE MAPS DIV -->
			<div id="googlemaps-div" class="w100fl" style="display:none;height:500px;">
				<h3>Incidents by Location</h3>
				<div id="googlemaps-canvas" class="" style="width:100%;height:500px;float:left;"></div>
			</div>

			<!-- ABOUT DIV -->
			<div id="about-div" class="w100fl" style="display:none;height:500px;">
				<h3>About This Project</h3>
				<div class="settingsrow" style="width:100%;">
					<p>
						This page was created as part of a 24-hour Socrata Code Challenge.  It displays crime statistics for the city of Seattle, WA within a given radius of CenturyLink Field (default radius is 1 mile).
					</p>
					<p>
						The data displayed are an attempt to show proper use of the SODA API, along with the Google Maps Javascript API and the Google Geocode API.  These data are not
						to be used in any production environment or without proper attribution.
					</p>
					<p>
						Please contact <a href="mailto:musicmunky@gmail.com">Timothy Andrews</a> for questions/comments.
					</p>
				</div>
			</div>
		</div>
		<div class="footer">
			<div class="innerfooter tac">
				<div class="footer-content-left">
					<span>Crime Info for <span id="footerlocation">800 Occidental Ave S, Seattle, WA 98134</span></span>
				</div>
				<div class="footer-content-right">
					<span>
						Powered by <a href="http://dev.socrata.com/" class="cldclr" target="_blank">Socrata</a>
					</span>
				</div>
			</div>
		</div>
	</body>
</html>
