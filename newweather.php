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

	//echo "<pre>";
	//var_dump($procs);
	//echo "</pre>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Weather (new design)</title>
		<link rel="shortcut icon" href="images/faviconweather.ico" />
		<link rel='stylesheet' type="text/css" href='css/newweather.css'  media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/fusionlib.css' media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/jquery-ui.min.css' media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/bootstrap.css' media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript" type="text/javascript" src="javascript/weather.js"></script>
	</head>
	<body>
		<input type="hidden" id="localwoeid" value="<?php echo $id; ?>" />
		<input type="hidden" id="localzipcode" value="<?php echo $ip['postal']; ?>" />

		<div id="header" class="header">
			<div id="headercont" class="header-content">
				<div style="float:left;width:250px;padding-left:50px;">
					<div class="title">
						<img id="logo" src="images/weatherlogo.png" style="width:40px;margin-top:10px;margin-right:10px;float:left;" />
						<div id="titlediv" style="float:left;cursor:default;">
							My Weather
						</div>
					</div>
				</div>
				<div style="float:left;height:100%;">
					<div id="datewrapper" style="width:200px;float:left;height:100%;">
						<span id="date" class="headspan"></span>
					</div>
				</div>
				<div style="float:right;height:100%;padding-right:50px;">
					<div class="w100fl" style="text-align:right;font-size:16px;height:100%;line-height:4em;">
						<span>Search by Zip Code: </span>
						<form onsubmit="getWeather();return false;" style="width:260px;float:right;height:100%;">
							<input type="text" id="searchbox" value="" style="color:#222;width:200px;text-align:right;" />
							<button style="margin-right:10px;background:none repeat scroll 0% 0% #FFF;border:0px none;height:40px;outline:none;">
								<span class="glyphicon glyphicon-search"></span>
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>

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
								<img id="condimg" src="" />
								<span id="condition"></span>
							</div>
						</div>
					</div>

					<div class="w50fl">
						<div class="tfcdiv" style="">
							<div class="tfcheader" style="border-bottom:1px solid #EEE;margin-bottom:5px;">
								Today's Forecast
							</div>
							<div class="tfcdivinner" style="position:relative;">
								<div id="dailyfrc" style="margin-left:15px;float:left;height:55px;overflow:hidden;width:85%;"></div>
							</div>
							<div class="tfcdivinner" style="position:relative;">
								<div style="margin-left:5px;width:90%;float:left;">Wind:</div>
								<div id="wind" style="margin-left:5px;width:95%;float:left;"></div>
							</div>
							<div class="tfcdivinner">
								<div class="w100fl">
									<div class="w50fl">Sunrise:</div>
									<div id="sunrise"></div>
								</div>
								<div class="w100fl">
									<div class="w50fl">Sunset:</div>
									<div id="sunset"></div>
								</div>
							</div>
							<div class="tfcheader" style="border-top:1px solid #EEE;padding-top:5px;">
								<div class="w50fl">
									<span class="wrmclr" id="high"></span>
								</div>
								<div class="w50fl">
									<span class="cldclr" id="low"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="daywrapper" class="w100fl" style="margin-top:15px;">
					<div class="w50fl">
						<div class="day" id="day2">
							<div id="dayofweek2" class="dayofweek"></div>
							<div class="condition">
								<span id="condition2" class="conspan"></span>
								<img id="condimg2" src="" />
							</div>
							<div class="tempdiv" style="float:left;">
								<span id="high2" class="wrmclr"></span>
							</div>
							<div class="tempdiv" style="float:right;">
								<span id="low2" class="cldclr"></span>
							</div>
						</div>
						<div class="day" id="day3">
							<div id="dayofweek3" class="dayofweek"></div>
							<div class="condition">
								<span id="condition3" class="conspan"></span>
								<img id="condimg3" src="" />
							</div>
							<div class="tempdiv" style="float:left;">
								<span id="high3" class="wrmclr"></span>
							</div>
							<div class="tempdiv" style="float:right;">
								<span id="low3" class="cldclr"></span>
							</div>
						</div>
					</div>
					<div class="w50fl">
						<div class="day" id="day4">
							<div id="dayofweek4" class="dayofweek"></div>
							<div class="condition">
								<span id="condition4" class="conspan"></span>
								<img id="condimg4" src="" />
							</div>
							<div class="tempdiv" style="float:left;">
								<span id="high4" class="wrmclr"></span>
							</div>
							<div class="tempdiv" style="float:right;">
								<span id="low4" class="cldclr"></span>
							</div>
						</div>
						<div class="day" id="day5">
							<div id="dayofweek5" class="dayofweek"></div>
							<div class="condition">
								<span id="condition5" class="conspan"></span>
								<img id="condimg5" src="" />
							</div>
							<div class="tempdiv" style="float:left;">
								<span id="high5" class="wrmclr"></span>
							</div>
							<div class="tempdiv" style="float:right;">
								<span id="low5" class="cldclr"></span>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<!--<div style="margin-left:auto;margin-right:auto;height:100px;width:800px;background-color:#fff;">
			<?php //echo $cmdstr; ?>
		</div>-->
	</body>
</html>
