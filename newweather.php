<?php
	//require 'php/shutdown.php';
	define('LIBRARY_CHECK',true);
	require 'php/weatherlib.php';

	date_default_timezone_set('America/New_York');

	$title = "My Weather";

	/**
	*
	*	TODO:
	*		- Implement Google Geocode API location searching
	*		- Implement Forecast.io API weather lookup
	*		- Integrate F.io with the existing javascript
	*		- Implement Skycons, as well as alternatives for non-html5 browsers
	*/
/*
	$cmdstr = shell_exec("ps -aef | grep php | grep -v grep");
	$matches = array();
	$cmdarr = explode("\n", $cmdstr);
	$procs = array();
	foreach ($cmdarr as $value)
	{
		if(preg_match("/^\w+\s+(\d+)\s+.*$/", $value, $matches))
		{
			array_push($procs, $matches[1]);
		}
	}
*/

//   	$lat = "39.3919764";
//   	$lng = "-76.9873477";
//  	$apinfo = mysql_fetch_assoc(mysql_query("SELECT APIKEY, URL FROM weatherapikeys WHERE SERVICE='forecast';"));
//  	$apikey = $apinfo['APIKEY'];
//  	$apiurl = $apinfo['URL'];
//  	$requrl = $apiurl . $apikey . "/" . $lat . "," . $lng . "?exclude=minutely";
//  	$content = file_get_contents($requrl);

// 	$reqstat = explode(" ", $http_response_header[0]);
// 	$numreqs = explode(" ", $http_response_header[8]);

//  	$apiinfo = mysql_fetch_assoc(mysql_query("SELECT APIKEY, URL FROM weatherapikeys WHERE SERVICE='google';"));
//  	$key = $apiinfo['APIKEY'];
//  	$url = $apiinfo['URL'];
//  	$requrl = $url . "address=Statue+of+Liberty" . "&key=" . $key;
//  	$content = file_get_contents($requrl);





//Example string to work with...figure out length / display problems:
//Partly cloudy starting in the afternoon, continuing until evening.

	$dayhtml  = "";
	$hourhtml = "";
	for($i = 2; $i <= 5; $i++)
	{
		$dayhtml .= "<div class='day' id='day{$i}'>
						<div id='dayofweek{$i}' class='dayofweek'></div>
						<div class='condition' onclick='showCondition(\"conditiontext{$i}\")'>
							<canvas id='condimg{$i}' height='50' width='50' style='float:left;padding-top:10px;'></canvas>
							<span id='condition{$i}' class='conspan'></span>
							<input type='hidden' id='conditiontext{$i}' value='' />
						</div>
						<div class='tempdiv' style='float:left;'>
							<span id='high{$i}' class='wrmclr'></span>
						</div>
						<div class='tempdiv' style='float:right;'>
							<span id='low{$i}' class='cldclr'></span>
						</div>
					</div>";
	}


	for($j = 1; $j <= 8; $j++)
	{
		$hourhtml .= "<div class='hour' id='hour{$j}'>
						<div id='hourofday{$j}' class='hourofday'>
							<div class='hrtimediv' id='hrdisplay{$j}' style='font-size:16px;'></div>
							<div class='hrconddiv' id='hrcondtion{$j}'></div>
							<div class='hricondiv' id='hricondiv{$j}'>
								<canvas id='hricon{$j}' width='40' height='40'></canvas>
							</div>
							<div class='hrtimediv' id='hrtemp{$j}'></div>
							<div class='hrtimediv' id='hrrain{$j}'>
								<img src='images/iconic/rain-2x.png' class='hrrainchanceicon' />
								<span id='hrrainchance{$j}'></span>
							</div>
							<div class='hrtimediv' id='hrwind{$j}'></div>
						</div>
					</div>";
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="viewport" content="initial-scale=1, maximum-scale=1">
		<title><?php echo $title; ?></title>
		<link rel="shortcut icon" href="images/faviconweather.ico" />
		<link rel='stylesheet' type="text/css" href='css/newweather.css'  media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/fusionlib.css' media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/jquery-ui.min.css' media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/bootstrap.css' media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/radio.css' media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
<!--	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">//-->
<!--	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">//-->
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/skycons.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript" type="text/javascript" src="javascript/newweather.js"></script>
	</head>
	<body>
		<!-- default to NYC if there's no localStorage -->
		<input type="hidden" id="defaultzipcode" value="10001" />

		<div id="header" class="header">
			<div id="headercont" class="header-content">
				<div class="header-logo">
					<div style="float:left;width:200px;">
						<div class="title">
							<img id="logo" src="images/weatherlogo.png" style="width:40px;margin-top:10px;margin-right:10px;float:left;" />
							<div id="titlediv" style="float:left;cursor:default;">
								<?php echo $title; ?>
							</div>
						</div>
					</div>
					<div style="float:left;height:100%;">
						<div id="datewrapper" style="width:200px;float:left;height:100%;">
							<span id="date" class="headspan"></span>
						</div>
					</div>
				</div>

				<div class="header-search">
					<div class="w100fl" style="text-align:right;font-size:16px;height:100%;line-height:4em;">
						<span style="float:left;">Search: </span>
<!--						<form onsubmit="getWeather();return false;" style="width:260px;float:right;height:100%;"> -->
<!--						<form onsubmit="getGeoInfo();return false;" style="width:260px;float:left;height:100%;"> -->
						<form onsubmit="runSearch();return false;" style="width:260px;float:left;height:100%;">
							<input type="text" id="searchbox" value="" class="searchbox"
								   onkeyup="hideSearchDiv(this)" onchange="hideSearchDiv(this)" />
							<button class="srchbtn">
								<img src="images/iconic/magnifying-glass-2x.png" style="width:15px;height:15px;" />
							</button>
						</form>
						<!--
						<span style="height:100%;line-height:60px;float:left;display:inline-block;">
							<img src="images/iconic/cog-6x.png" style="width:15px;height:15px;cursor:pointer;" />
						</span>
						-->
					</div>
					<div id="locselect" class="locdiv"></div>
				</div>

				<div class="header-units">
					<span style="float:left;margin-right:10px;">Units: </span>
					<input class="css-checkbox" type="radio" name="unitradio" id="unitsus" value="us" onclick="setUnits(this.value)" checked />
					<label class="css-label" for="unitsus">US</label>
					<input class="css-checkbox" type="radio" name="unitradio" id="unitsca" value="ca" onclick="setUnits(this.value)" />
					<label class="css-label" for="unitsca">EU</label>
				</div>
			</div>
		</div>
<!--
		<div id='displayinfodiv' style='width:100%;height:500px;margin-top:60px;overflow-y:scroll;'>
			<?php //echo "<p>RESPONSE CODE: " . $reqstat[1] . "&emsp;&emsp;&emsp;&emsp;CALLS MADE TODAY: " . $numreqs[1] . "</p>"; ?>
			<pre>
 				<?php //var_dump(json_decode($content, true)); ?>

			</pre>
		</div>
-->
		<div id="mainwrapper" class="mainwrapper">
			<div class="oldcitywrapper">
				<div id="oldcitydiv" style="width:100%;height:100%;"></div>
			</div>
			<div id="forecast">
				<div id="today" class="w100fl">
					<div class="w50fl">
						<div class="tfcdiv" style="font-size:25px;">
							<span id="location" class="locationspan" style="text-align:center;"></span>
							<div style="float:left;margin-left:15px;width:445px;text-align:center;">
								<div style="height:60px;display:inline-block;">
									<canvas width="50" height="50" id="condimg" style="float:left;padding-top:5px;padding-right:5px;"></canvas>
									<span id="condition" style="height:60px;line-height:60px;float:left;"></span>
								</div>
							</div>
						</div>
					</div>

					<div class="w50fl tfcbtm">
						<div class="tfcdiv">
							<div class="tfcheader" style="border-bottom:1px solid #EEE;margin-bottom:5px;">
								Today's Forecast
							</div>
							<div class="tfcdivinner" style="position:relative;width:39%;">
								<div id="dailyfrc" onclick="showCondition('conditiontext')"
									 style="cursor:pointer;margin-left:15px;float:left;height:45px;overflow:hidden;width:85%;/*font-size:14px;*/"></div>
								<input type="hidden" id="conditiontext" value="" />
							</div>
							<div class="tfcdivinner" style="position:relative;width:30%;">
								<div style="margin-left:5px;width:90%;float:left;">Wind:</div>
								<div id="wind" style="margin-left:5px;width:95%;float:left;"></div>
							</div>
							<div class="tfcdivinner" style="width:30%;">
								<div class="w100fl">
									<div class="innerw50fl">Sunrise:</div>
									<div id="sunrise"></div>
								</div>
								<div class="w100fl">
									<div class="innerw50fl">Sunset:</div>
									<div id="sunset"></div>
								</div>
							</div>
							<div class="tfcheader" style="border-top:1px solid #EEE;font-size:25px;line-height:1.3em;">
								<div class="innerw50fl">
									<span class="wrmclr" id="high"></span>
								</div>
								<div class="innerw50fl">
									<span class="cldclr" id="low"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="hourwrapper" class="w100fl hourwrapper">
					<?php echo $hourhtml; ?>
				</div>
				<div id="daywrapper" class="w100fl" style="margin-top:15px;">
					<?php echo $dayhtml; ?>
				</div>
			</div>
		</div>
		<div class="footer">
			<div class="innerfooter" style="text-align:center;">
				<div class="footer-content-left">
					<span>Daily weather for <span id="footerlocation"></span></span>
				</div>
<!--				<div class="footer-content-center"></div>//-->
				<div class="footer-content-right">
					<span>
						Powered by <a href="http://forecast.io/" style="text-decoration:none;outline:none;" class="cldclr" target="_blank">Forecast</a>
					</span>
				</div>

			</div>
		</div>
	</body>
</html>
