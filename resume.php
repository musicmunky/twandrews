<?php
	//require 'php/shutdown.php';
	define('LIBRARY_CHECK',true);
	require 'php/library.php';

	date_default_timezone_set('America/New_York');

	if(!isset($_SESSION))
	{
		session_name('andrewsresume');
		session_start();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Tim's Resume</title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel='stylesheet' href='css/resumestyle.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/jquery-ui.min.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/bootstrap.css' type="text/css" media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu">
		<!--[if lte IE 9]>
			<script src="javascript/html5shiv.js" type="text/javascript"></script>
			<script src="javascript/Respond.js" type="text/javascript"></script>
		<![endif]-->
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/resume.js"></script>


		<style>
			.day {
				border: 0 solid #555;
				float: left;
				height: 120px;
				padding: 2% 3% 0 1%;
				position: relative;
				text-align: center;
				width: 16%;
			}

			#forecast {
				background-color: #222;
				color: white;
				height: 140px;
				width: 800px;
			}
		</style>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>

		<script type="text/javascript">

			//all the weather codes from this page (about halfway down):
			//	https://developer.yahoo.com/weather/documentation.html
			var csscodes = {
				0: 	"tornado",
				1: 	"tropical-storm",
				2: 	"hurricane",
				3: 	"severe-thunderstorms",
				4: 	"thunderstorms",
				5: 	"mixed-rain-and-snow",
				6: 	"mixed-rain-and-sleet",
				7: 	"mixed-snow-and-sleet",
				8: 	"freezing-drizzle",
				9: 	"drizzle",
				10: "freezing-rain",
				11: "showers",
				12: "showers",
				13: "snow-flurries",
				14: "light-snow-showers",
				15: "blowing-snow",
				16: "snow",
				17: "hail",
				18: "sleet",
				19: "dust",
				20: "foggy",
				21: "haze",
				22: "smoky",
				23: "blustery",
				24: "windy",
				25: "cold",
				26: "cloudy",
				27: "mostly-cloudy-night",
				28: "mostly-cloudy-day",
				29: "partly-cloudy-night",
				30: "partly-cloudy-day",
				31: "clear-night",
				32: "sunny",
				33: "fair-night",
				34: "fair-day",
				35: "mixed-rain-and-hail",
				36: "hot",
				37: "isolated-thunderstorms",
				38: "scattered-thunderstorms",
				39: "scattered-thunderstorms",
				40: "scattered-showers",
				41: "heavy-snow",
				42: "scattered-snow-showers",
				43: "heavy-snow",
				44: "partly-cloudy",
				45: "thundershowers",
				46: "snow-showers",
				47: "isolated-thundershowers",
				3200: "not-available"
			};

			google.load("feeds", "1");
			google.setOnLoadCallback(initialize);
			google.feeds.Feed.XML_FORMAT;
			function initialize() {
				var feed = new google.feeds.Feed("http://weather.yahooapis.com/forecastrss?w=2503308");
				feed.setResultFormat(google.feeds.Feed.XML_FORMAT);
				feed.load(function(result) {
					if (!result.error) {

						//get the full xml document in case you need to use it later
						var xml = result.xmlDocument;

						//get the specific elements you want for the 5-day forecast
						var ywf = xml.getElementsByTagName("yweather:forecast");
						var container = document.getElementById("forecast");
						var parentdiv = document.createElement("div");

						//variable declarations - vars should never be declared in a loop
						//ask me why on Sunday and I'll explain it
						var childdiv = null;
						var nam = null;
						var cnd = null;
						var icn = null;
						var hgh = null;
						var low = null;
						var txt = "";
						var cde = "";
						var dte = "";
						var frc = "";
						var att = {};

						for(var i = 0; i < ywf.length; i++)
						{
							att = ywf[i].attributes; //get ALL the attributes for the node

							cde = (att.code) ? att.code.value : ""; //the forecast code for each day
							dte = (att.date) ? att.date.value : ""; //today's date, in case you need it later

							//I've built a big hash with all the forecast codes based
							//on the stuff provided in the Yahoo Weather API
							//if you don't want it you can just remove this line
							frc = (typeof csscodes === "object") ? csscodes[cde] : "";

							nam = document.createElement("div");
							cnd = document.createElement("div");
							icn = document.createElement("div");
							hgh = document.createElement("div");
							low = document.createElement("div");

							//make sure attributes exist, otherwise will throw an error
							txt = (att.text) ? att.text.value.replace(/\./g, "") : "";
							cnd.innerHTML = txt;
							nam.innerHTML = (att.day)  ? att.day.value : "";
							hgh.innerHTML = (att.high) ? att.high.value + "&deg;" : "";
							low.innerHTML = (att.low)  ? att.low.value + "&deg;" : "";

							//you can append the new divs and add their class names at the same time
							//reduces the lines of code needed without sacrificing clarity
							childdiv = document.createElement("div");
							childdiv.appendChild(nam).className = "dayofweek";
							childdiv.appendChild(cnd).className = "condition";
							childdiv.appendChild(icn).className = "condition-icon " + txt.replace(/\s/g, "-");
							childdiv.appendChild(hgh).className = "hightemp";
							childdiv.appendChild(low).className = "lowtemp";

							parentdiv.appendChild(childdiv).className = "day";
						}
						container.appendChild(parentdiv);

						/*var container = document.getElementById("forecast");
						var fed = result.feed.entries;
						var ent = result.feed.entries[0].content;
						ent = ent.replace(/\r?\n|\r/g, "");
						var entar = ent.split(/\<br\s*[\/]?\>/g);
						var parentdiv = document.createElement("div");
						var childdiv = null;
						for(var i = 0; i < entar.length; i++)
						{
							if(entar[i].match(/^(sun|mon|tue|wed|thu|fri|sat){1}\s+-/i))
							{
								var a = entar[i].split(/\s*-\s?/);
								var day = a[0];
								var war = a[1].split(" ");
								var idx = war.indexOf("High:");
								var ht = war[idx + 1];
								var lt = war[idx + 3];
								var td = war.slice(0,idx).join(" ");

								var nam = document.createElement("div");
								var tdy = document.createElement("div");
								var hgh = document.createElement("div");
								var low = document.createElement("div");

								nam.innerHTML = day;
								tdy.innerHTML = td.replace(/\./g, "");
								hgh.innerHTML = "High: " + ht;
								low.innerHTML = "Low: " + lt;

								childdiv = document.createElement("div");
								childdiv.setAttribute("id", "cd_" + i);
								childdiv.appendChild(nam);
								childdiv.appendChild(tdy);
								childdiv.appendChild(hgh);
								childdiv.appendChild(low);
								parentdiv.appendChild(childdiv);
							}
						}
						container.appendChild(parentdiv);*/
					}
				});
			}
			google.setOnLoadCallback(initialize);
       </script>



	</head>
	<body>
		<div id="forecast" style="margin-left:auto;margin-right:auto;"></div>
		<div id="maincontainer" class="container">
			<div id="sidebar" class="sidebar">
				<div id="sidebarwrapper" class="sidebarwrapper">
					<div id="home" title="home" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-home"></span>home</div>
					<div id="about" title="about" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-user"></span>about</div>
					<div id="education" title="education" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-education"></span>education</div>
					<div id="experience" title="experience" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-briefcase"></span>experience</div>
					<div id="interests" title="interests" class="sidebar-nav">
						<span class="sidebar-link glyphicon glyphicon-info-sign"></span>interests</div>
				</div>
			</div>
			<div id="content" class="content">
				<div id="home-content" class="content-section" style="background-color:blue;">
					<div class="picwrapper">
						<img src="images/tim1.jpg" style="border-radius:75px;width:150px;" />
					</div>
				</div>
				<div id="about-content" class="content-section" style="background-color:red;"></div>
				<div id="education-content" class="content-section" style="background-color:green;"></div>
				<div id="experience-content" class="content-section" style="background-color:#EFEFEF;"></div>
				<div id="interests-content" class="content-section" style="background-color:#EBAC89;"></div>
			</div>
		</div>
	</body>
</html>
