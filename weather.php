<?php
	//require 'php/shutdown.php';
	define('LIBRARY_CHECK',true);
	require 'php/library.php';
	//require 'php/yweather.php';

	date_default_timezone_set('America/New_York');

	if(!isset($_SESSION))
	{
		session_name('andrewsweather');
		session_start();
	}

	$yw = new yWeather();
	$ip = $yw->getIpInfo();
	$id = $yw->getWoeidByZip($ip['postal']);

	unset($yw);


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
	//echo "<pre>";
	//var_dump($procs);
	//echo "</pre>";

/* TODO
	get main design finished
	...
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Weather</title>
		<link rel="shortcut icon" href="images/faviconweather.ico" />
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
		<input type="hidden" id="localwoeid" value="<?php echo $id; ?>" />
		<input type="hidden" id="localzipcode" value="<?php echo $ip['postal']; ?>" />
		<div style="margin-left:auto;margin-right:auto;height:42px;width:760px;background-color:#222;color:#FFF;padding:20px 20px 0;">
			<div style="width:100%;height:100%;background-color:#444;">
				<div style="width:100%;float:left;height:40px;line-height:40px;text-align:right;">
					<span style="font-style:italic;margin-right:10px;">Search for a city by Zip Code: </span>
					<form onsubmit="getWeather();return false;" style="width:260px;float:right;">
						<input type="text" id="searchbox" value="" style="color:#222;width:200px;text-align:right;" />
						<button style="margin-right:10px;background:none repeat scroll 0% 0% #444;border:0px none;height:40px;outline:none;">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</form>
				</div>
			</div>
		</div>
		<div style="margin-left:auto;margin-right:auto;height:42px;width:760px;background-color:#222;color:#FFF;padding:0 20px;">
			<div id="oldcitydiv" style="width:100%;height:100%;background-color:#444;"></div>
		</div>
		<div id="forecast" style="margin-left:auto;margin-right:auto;border-radius:0 0 15px 15px;">
			<div id="today" style="float:left;width:100%;height:250px;">
				<div style="float:left;font-size:26px;margin:10px 20px;width:95%;text-align:center;">
					<span id="dayofweek" class="headspan" style="text-align:left;"><?php echo $frc[0]['day']; ?></span>
					<span id="date" class="headspan" style="text-align:center;"><?php echo $frc[0]['dstr']; ?></span>
					<span id="location" class="headspan" style="text-align:right;"><?php echo $loc['city'] . ", " . $loc['region']; ?></span>
				</div>
				<div style="width:760px;float:left;margin:0px 20px 10px;height:200px;color:#262626;background-color:#444;border-radius:10px;">
					<div style="line-height:50px;font-size:25px;width:100%;height:55px;background-color:#fff;border-radius:10px 10px 0 0;">
						<span style="float:left;margin-right:15px;margin-left:15px;">Right now:</span>
						<div style="float:right;margin-right:15px;">
							<span id="condition"><?php echo $con['text'] ?>, <?php echo $con['temp'] . "&deg;"; ?></span>
							<img id="condimg" src="<?php echo $con['img'] ?>" />
						</div>
					</div>
					<div style="float:left;width:100%;height:145px;background-color:#444;color:#fff;border-radius:0 0 10px 10px;">
						<div style="float:left;width:33%;">
							<div id="high">HIGH: <?php  echo $frc[0]['high']; ?></div>
							<div id="low">LOW: <?php  echo $frc[0]['low']; ?></div>
							<div id="dailyfrc">FORECAST: <?php  echo $frc[0]['text']; ?></div>
						</div>
						<div style="float:left;width:33%;">
							<div id="sunrise">SUNRISE: <?php echo $ast['sunrise']; ?></div>
							<div id="sunset">SUNSET: <?php echo $ast['sunset']; ?></div>
						</div>
						<div style="float:left;width:33%;">
							<div id="wind">
								<div id="windspeed">WIND SPEED: <?php echo $wnd['speed']; ?></div>
								<div id="winddirection">DIRECTION: <?php echo $wnd['direction']; ?></div>
								<div id="windchill">CHILL: <?php echo $wnd['chill']; ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="day" id="day2">
				<div id="dayofweek2" class="dayofweek"><?php echo $frc[1]['date']; ?></div>
				<div class="condition">
					<span id="condition2" class="conspan"><?php echo $frc[1]['text']; ?></span>
					<img id="condimg2" src="<?php echo $frc[1]['img']; ?>" />
				</div>
				<div id="icon2" class="condition-icon Mostly-Sunny"></div>
				<div class="tempdiv" style="float:left;">
					<span class="glyphicon glyphicon-arrow-up highspan"></span>
					<span id="high2"><?php echo $frc[1]['high']; ?></span>
				</div>
				<div class="tempdiv" style="float:right;">
					<span class="glyphicon glyphicon-arrow-down lowspan"></span>
					<span id="low2"><?php echo $frc[1]['low']; ?></span>
				</div>
			</div>
			<div class="day" id="day3">
				<div id="dayofweek3" class="dayofweek"><?php echo $frc[2]['date']; ?></div>
				<div class="condition">
					<span id="condition3" class="conspan"><?php echo $frc[2]['text']; ?></span>
					<img id="condimg3" src="<?php echo $frc[2]['img']; ?>" />
				</div>
				<div id="icon3" class="condition-icon Mostly-Sunny"></div>
				<div class="tempdiv" style="float:left;">
					<span class="glyphicon glyphicon-arrow-up highspan"></span>
					<span id="high3"><?php echo $frc[2]['high']; ?></span>
				</div>
				<div class="tempdiv" style="float:right;">
					<span class="glyphicon glyphicon-arrow-down lowspan"></span>
					<span id="low3"><?php echo $frc[2]['low']; ?></span>
				</div>
			</div>
			<div class="day" id="day4">
				<div id="dayofweek4" class="dayofweek"><?php echo $frc[3]['date']; ?></div>
				<div class="condition">
					<span id="condition4" class="conspan"><?php echo $frc[3]['text']; ?></span>
					<img id="condimg4" src="<?php echo $frc[3]['img']; ?>" />
				</div>
				<div id="icon4" class="condition-icon Mostly-Sunny"></div>
				<div class="tempdiv" style="float:left;">
					<span class="glyphicon glyphicon-arrow-up highspan"></span>
					<span id="high4"><?php echo $frc[3]['high']; ?></span>
				</div>
				<div class="tempdiv" style="float:right;">
					<span class="glyphicon glyphicon-arrow-down lowspan"></span>
					<span id="low4"><?php echo $frc[3]['low']; ?></span>
				</div>
			</div>
			<div class="day" id="day5">
				<div id="dayofweek5" class="dayofweek"><?php echo $frc[4]['date']; ?></div>
				<div class="condition">
					<span id="condition5" class="conspan"><?php echo $frc[4]['text']; ?></span>
					<img id="condimg5" src="<?php echo $frc[4]['img']; ?>" />
				</div>
				<div id="icon5" class="condition-icon Mostly-Sunny"></div>
				<div class="tempdiv" style="float:left;">
					<span class="glyphicon glyphicon-arrow-up highspan"></span>
					<span id="high5"><?php echo $frc[4]['high']; ?></span>
				</div>
				<div class="tempdiv" style="float:right;">
					<span class="glyphicon glyphicon-arrow-down lowspan"></span>
					<span id="low5"><?php echo $frc[4]['low']; ?></span>
				</div>
			</div>
		</div>
		<!--<div style="margin-left:auto;margin-right:auto;height:100px;width:800px;background-color:#fff;">
			<?php //echo $cmdstr; ?>
		</div>-->
	</body>
</html>
