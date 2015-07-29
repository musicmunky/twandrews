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
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.3.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript" type="text/javascript" src="javascript/challenge.js"></script>
		<script language="javascript" type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAItsJVet4OiBn43s-X40CmFFKTbGEzcUY"></script>
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
<!--	<div id='displayinfodiv' style='width:100%;height:500px;margin-top:60px;overflow-y:scroll;'>
			<pre><?php //var_dump(json_decode($content, true)); ?></pre>
		</div> -->
		<div id="mainwrapper" class="mainwrapper">
			<div class="oldcitywrapper">
				<div id="oldcitydiv" class="w100fl h100fl">
					<div id="apisettings-tab" class="citydiv">
						Info and Settings
					</div>
					<div id="highcharts-tab" class="citydiv">
						Incidents by Category
					</div>
					<div id="googlemaps-tab" class="citydiv">
						Google Maps
					</div>

<!--
					<div id="rangediv" class="w50fl h100fl" style="line-height:40px;">
						<span class="fl" style="margin-left:10px;margin-right:10px;">Search Radius: </span>
						<select id="searchbox" class="searchbox" onchange="runSearch(this.value)">
							<option value="0.5">0.5 Miles</option>
							<option value="1" selected>1 Mile</option>
							<option value="5">5 Miles</option>
							<option value="10">10 Miles</option>
						</select>
					</div>
					<div id="limitdiv" class="w50fl h100fl" style="line-height:40px;">
						<span class="fl" style="margin-left:10px;margin-right:10px;">Max Results: </span>
						<input type="text" id="maxresults" value="" class="searchbox" style="height:22px;border:1px solid #DDD;" onkeyup="FUSION.lib.noAlpha(this)" />
					</div>
-->
				</div>
			</div>

<!--			<div id="container" class="w100fl"></div>//-->
			<div id="highcharts-div" class="w100fl" style="display:none;height:500px;">highcharts</div>
			<div id="googlemaps-div" class="w100fl" style="display:none;height:500px;">google maps</div>
			<div id="apisettings-div" class="w100fl" style="display:block;height:500px;">settings</div>
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
