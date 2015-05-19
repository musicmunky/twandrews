<?php
	//require 'php/shutdown.php';
	define('LIBRARY_CHECK',true);
	require 'php/weatherlib.php';

	date_default_timezone_set('America/New_York');

	$title = "MyWeather";

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

// 		$reqstat = explode(" ", $http_response_header[0]);
// 		$numreqs = explode(" ", $http_response_header[8]);

//  	$apiinfo = mysql_fetch_assoc(mysql_query("SELECT APIKEY, URL FROM weatherapikeys WHERE SERVICE='google';"));
//  	$key = $apiinfo['APIKEY'];
//  	$url = $apiinfo['URL'];
//  	$requrl = $url . "address=Statue+of+Liberty" . "&key=" . $key;
//  	$content = file_get_contents($requrl);

	$dayhtml  = "";
	$hourhtml = "";
	for($i = 2; $i <= 5; $i++)
	{
		$dayhtml .= "<div class='day' id='day{$i}'>
						<div id='dayofweek{$i}' class='dayofweek'></div>
						<div class='condition' onclick='showCondition(\"conditiontext{$i}\")'>
							<canvas id='condimg{$i}' class='condimg' height='50' width='50'></canvas>
							<span id='condition{$i}' class='conspan'></span>
							<input type='hidden' id='conditiontext{$i}' value='' />
						</div>
						<div class='tempdiv fl'>
							<span id='high{$i}' class='wrmclr'></span>
						</div>
						<div class='tempdiv fr'>
							<span id='low{$i}' class='cldclr'></span>
						</div>
					</div>";
	}


	for($j = 1; $j <= 8; $j++)
	{
		$hourhtml .= "<div class='hour' id='hour{$j}'>
						<div id='hourofday{$j}' class='hourofday'>
							<div class='hrtimediv' id='hrdisplay{$j}'></div>
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
					<div class="logowrapper">
						<div class="title">
							<img id="logo" src="images/weatherlogo.png" class="logoimage" />
							<div id="titlediv" class="titletext">
								<?php echo $title; ?>
							</div>
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
						<span class="fl">Search: </span>
						<form class="h100fl" id="searchform" onsubmit="runSearch();return false;">
							<input type="text" id="searchbox" value="" class="searchbox"
								   onkeyup="hideSearchDiv(this)" onchange="hideSearchDiv(this)" />
							<button class="srchbtn">
								<img src="images/iconic/magnifying-glass-2x.png" id="srchicn" />
							</button>
						</form>
					</div>
					<div id="locselect" class="locdiv"></div>
				</div>
				<div class="header-units">
					<span class="fl" class="unitspan">Units: </span>
					<input class="css-checkbox" type="radio" name="unitradio" id="unitsus" value="us" onclick="setUnits(this.value)" checked />
					<label class="css-label" for="unitsus">US</label>
					<input class="css-checkbox" type="radio" name="unitradio" id="unitsca" value="ca" onclick="setUnits(this.value)" />
					<label class="css-label" for="unitsca">EU</label>
				</div>
			</div>
		</div>
<!--	<div id='displayinfodiv' style='width:100%;height:500px;margin-top:60px;overflow-y:scroll;'>
			<pre><?php //var_dump(json_decode($content, true)); ?></pre>
		</div> -->
		<div id="mainwrapper" class="mainwrapper">
			<div class="oldcitywrapper">
				<div id="oldcitydiv" class="w100fl h100fl"></div>
			</div>
			<div class="forecast">
				<div id="today" class="w100fl">
					<div class="w50fl">
						<div class="tfcdiv locdispwrapper">
							<span id="location" class="locationspan tac"></span>
							<div class="locwrapper">
								<div class="loccanvaswrapper">
									<canvas width="50" height="50" id="condimg"></canvas>
									<span id="condition"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="w50fl tfcbtm">
						<div class="tfcdiv">
							<div class="tfcheader" id="tfcheadertext">
								Today's Forecast
							</div>
							<div class="tfcdivinner" id="condinfo">
								<div id="dailyfrc" onclick="showCondition('conditiontext')"></div>
								<input type="hidden" id="conditiontext" value="" />
							</div>
							<div class="tfcdivinner" id="windinfo">
								<div id="windlabel">Wind:</div>
								<div id="wind"></div>
							</div>
							<div class="tfcdivinner" id="suntimes">
								<div class="w100fl">
									<div class="innerw50fl">Sunrise:</div>
									<div id="sunrise"></div>
								</div>
								<div class="w100fl">
									<div class="innerw50fl">Sunset:</div>
									<div id="sunset"></div>
								</div>
							</div>
							<div class="tfcheader todayhilo">
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
				<div id="daywrapper" class="w100fl daywrapper">
					<?php echo $dayhtml; ?>
				</div>
			</div>
		</div>
		<div class="footer">
			<div class="innerfooter tac">
				<div class="footer-content-left">
					<span>Daily weather for <span id="footerlocation"></span></span>
				</div>
				<div class="footer-content-right">
					<span>
						Powered by <a href="http://forecast.io/" class="cldclr" target="_blank">Forecast</a>
						<span id="numreqs" style="color:#CCC"></span>
					</span>
				</div>

			</div>
		</div>
	</body>
</html>
