<?php
	//require 'php/shutdown.php';
	define('LIBRARY_CHECK',true);
	require 'php/library.php';

	require 'php/yweather.php';

	date_default_timezone_set('America/New_York');

	if(!isset($_SESSION))
	{
		session_name('andrewsweather');
		session_start();
	}

	$yw = new yWeather();
	$yw->loadFeed();

	$ast = $yw->getAstronomy();
	$con = $yw->getConditions();
	$loc = $yw->getLocation();
	$atm = $yw->getAtmosphere();
	$wnd = $yw->getWind();
	$frc = $yw->getForecast();
	var_dump($frc);

/* TODO
	get main design finished
	get ajax setup for refresh
	get main window info filled in with PHP instead of JS on initial load
	...
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Weather</title>
		<!--<link rel="shortcut icon" href="images/favicon.ico" />-->
		<link rel='stylesheet' href='css/weather.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/jquery-ui.min.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/bootstrap.css' type="text/css" media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript" type="text/javascript" src="javascript/weather.js"></script>
	</head>
	<body>
		<div style="margin-left:auto;margin-right:auto;height:200px;width:800px;"></div>
		<div id="forecast" style="margin-left:auto;margin-right:auto;">
			<div id="today" style="float:left;width:100%;height:250px;">
				<div style="/*border-bottom:1px solid #afafaf;*/float:left;font-size:26px;margin:10px 20px;width:95%;text-align:center;">
					<span id="dayofweek" style="float:left;"></span>
					<span id="date"></span> <!-- <div id="date" style="width:95%;float:left;margin:0px 20px 10px;font-size:20px;"></div> -->
					<span id="location" style="float:right;"></span>
				</div>
				<div style="width:760px;float:left;margin:0px 20px 10px;height:200px;color:#262626;background-color:#444;border-radius:10px;">
					<div style="line-height:50px;font-size:25px;width:100%;height:55px;background-color:#fff;border-radius:10px 10px 0 0;">
						<span style="float:left;margin-right:15px;margin-left:15px;">Right now:</span>
						<div style="float:right;margin-right:15px;">
							<span id="condition"></span>
							<img id="condimg" src="" />
						</div>
					</div>
					<div style="float:left;width:100%;height:145px;background-color:#444;color:#fff;border-radius:0 0 10px 10px;">
						<div style="float:left;width:33%;">
							<div id="high"></div>
							<div id="low"></div>
						</div>
						<div style="float:left;width:33%;">
							<div id="sunrise"></div>
							<div id="sunset"></div>
						</div>
						<div style="float:left;width:33%;">
							<div id="wind"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="day" id="day2">
				<div id="dayofweek2" class="dayofweek"><?php echo $frc[1]['date']; ?></div>
				<div class="condition">
					<span id="condition2"><?php echo $frc[1]['text']; ?></span>
					<img id="condimg2" src="<?php echo $frc[1]['img']; ?>" />
				</div>
				<div id="icon2" class="condition-icon Mostly-Sunny"></div>
				<div id="high2" class="hightemp"><?php echo $frc[1]['high']; ?></div>
				<div id="low2" class="lowtemp"><?php echo $frc[1]['low']; ?></div>
			</div>
			<div class="day" id="day3">
				<div id="dayofweek3" class="dayofweek"><?php echo $frc[2]['date']; ?></div>
				<div class="condition">
					<span id="condition3"><?php echo $frc[2]['text']; ?></span>
					<img id="condimg3" src="<?php echo $frc[2]['img']; ?>" />
				</div>
				<div id="icon3" class="condition-icon Mostly-Sunny"></div>
				<div id="high3" class="hightemp"><?php echo $frc[2]['high']; ?></div>
				<div id="low3" class="lowtemp"><?php echo $frc[2]['low']; ?></div>
			</div>
			<div class="day" id="day4">
				<div id="dayofweek4" class="dayofweek"><?php echo $frc[3]['date']; ?></div>
				<div class="condition">
					<span id="condition4"><?php echo $frc[3]['text']; ?></span>
					<img id="condimg4" src="<?php echo $frc[3]['img']; ?>" />
				</div>
				<div id="icon4" class="condition-icon Mostly-Sunny"></div>
				<div id="high4" class="hightemp"><?php echo $frc[3]['high']; ?></div>
				<div id="low4" class="lowtemp"><?php echo $frc[3]['low']; ?></div>
			</div>
			<div class="day" id="day5">
				<div id="dayofweek5" class="dayofweek"><?php echo $frc[4]['date']; ?></div>
				<div class="condition">
					<span id="condition5"><?php echo $frc[4]['text']; ?></span>
					<img id="condimg5" src="<?php echo $frc[4]['img']; ?>" />
				</div>
				<div id="icon5" class="condition-icon Mostly-Sunny"></div>
				<div id="high5" class="hightemp"><?php echo $frc[4]['high']; ?></div>
				<div id="low5" class="lowtemp"><?php echo $frc[4]['low']; ?></div>
			</div>
		</div>
		<input type="button" onclick="initialize()" value="Refresh Data" />
	</body>
</html>
