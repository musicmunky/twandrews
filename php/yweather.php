<?php

	class yWeather
	{
		var $rssfd = "";
		var $fddoc = "";
		var $imgurl = "http://l.yimg.com/a/i/us/we/52/";
		var $directions = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE",
                  				"S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW");
		var $codes = array(
			0 => "tornado",
			1 => "tropical-storm",
			2 => "hurricane",
			3 => "severe-thunderstorms",
			4 => "thunderstorms",
			5 => "mixed-rain-and-snow",
			6 => "mixed-rain-and-sleet",
			7 => "mixed-snow-and-sleet",
			8 => "freezing-drizzle",
			9 => "drizzle",
			10 => "freezing-rain",
			11 => "showers",
			12 => "showers",
			13 => "snow-flurries",
			14 => "light-snow-showers",
			15 => "blowing-snow",
			16 => "snow",
			17 => "hail",
			18 => "sleet",
			19 => "dust",
			20 => "foggy",
			21 => "haze",
			22 => "smoky",
			23 => "blustery",
			24 => "windy",
			25 => "cold",
			26 => "cloudy",
			27 => "mostly-cloudy-night",
			28 => "mostly-cloudy-day",
			29 => "partly-cloudy-night",
			30 => "partly-cloudy-day",
			31 => "clear-night",
			32 => "sunny",
			33 => "fair-night",
			34 => "fair-day",
			35 => "mixed-rain-and-hail",
			36 => "hot",
			37 => "isolated-thunderstorms",
			38 => "scattered-thunderstorms",
			39 => "scattered-thunderstorms",
			40 => "scattered-showers",
			41 => "heavy-snow",
			42 => "scattered-snow-showers",
			43 => "heavy-snow",
			44 => "partly-cloudy",
			45 => "thundershowers",
			46 => "snow-showers",
			47 => "isolated-thundershowers",
			3200 => "not-available"
		);

		public function __construct($newrssfd = "http://weather.yahooapis.com/forecastrss?w=2503308") {
			$this->rssfd = $newrssfd;
    	}


		function loadFeed($u = "")
		{
			$r = true;
			$u = $u == "" ? $this->getUrl() : $u;
			try {
				if($this->validUrl($u))
				{
					$this->fddoc = new DOMDocument();
					$this->fddoc->load($u);
				}
				else{
					$r = false;
				}
			}
			catch(Exception $e) {
				$r = false;
			}
			return $r;
		}


		function setUrl($u)
		{
			if($this->validUrl($u)){
				$this->rssfd = $u;
				return true;
			}
			else {
				return false;
			}
		}


		function getUrl()
		{
			return $this->rssfd;
		}


		function getBaseUrl()
		{
			$arr = explode("?", $this->getUrl());
			return $arr[0];
		}


		function setRegion($reg)
		{
			$arr = $this->getUrlParams();
			$arr['w'] = $reg;
			$this->setUrlParams($arr);
			return $this->getUrl();
		}


		function getRegion()
		{
			$reg = "";
			$arr = $this->getUrlParams();
			if(isset($arr['w'])) {
				$reg = $arr['w'];
			}
			return $reg;
		}


		function setUrlParams($arr = array())
		{
			try {
				$str = "";
				$par = array();
				foreach ($arr as $key => $value)
				{
					array_push($par, $key . "=" . $value);
				}
				$tmp = array($this->getBaseUrl(), implode("&", $par));
				$this->setUrl(implode("?", $tmp));
				return true;
			}
			catch(Exception $e) {
				return false;
			}
		}


		function getUrlParams()
		{
			$arr = array();
			try {
				$tmp = explode("forecastrss?", $this->getUrl());
				$par = explode("&", $tmp[1]);
				for($i = 0; $i < count($par); $i++)
				{
					$elm = explode("=", $par[$i]);
					$arry[$elm[0]] = $elm[1];
				}
			}
			catch(Exception $e) {
				$arr['ERR_MSG'] = $e->getMessage();
			}
			return $arr;
		}


		function getForecast()
		{
			$arr = array();
			try {
				$tmp = $this->fddoc->getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "forecast");
				if($tmp->length > 0){
					for($i = 0; $i < $tmp->length; $i++)
					{
						$frc = $tmp->item($i);
						$date = date("D, M j", strtotime($frc->getAttribute('date')));
						$code = $frc->getAttribute('code');
						$arr[$i] = array(
									"code" => $code,
									"date" => $date,
									"text" => $frc->getAttribute('text'),
									"high" => $frc->getAttribute('high') . "&deg;",
									"low"  => $frc->getAttribute('low') . "&deg;",
									"day"  => $frc->getAttribute('day'),
									"img"  => $this->imgurl . $code . ".gif");
					}
				}
			}
			catch(Exception $e) {
				$arr['ERR_MSG'] = $e->getMessage();
			}
			return $arr;
		}


		function getAstronomy()
		{
			$arr = array();
			try {
				$tmp = $this->fddoc->getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "astronomy");
				if($tmp->length > 0){
					$ast = $tmp->item(0);
					$arr['sunset'] = $ast->getAttribute('sunset');
					$arr['sunrise'] = $ast->getAttribute('sunrise');
				}
			}
			catch(Exception $e) {
				$arr['ERR_MSG'] = $e->getMessage();
			}
			return $arr;
		}


		function getConditions()
		{
			$arr = array();
			try {
				$tmp = $this->fddoc->getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "condition");
				if($tmp->length > 0){
					$con = $tmp->item(0);
					$arr['text'] = $con->getAttribute('text');
					$arr['code'] = $con->getAttribute('code');
				}
			}
			catch(Exception $e) {
				$arr['ERR_MSG'] = $e->getMessage();
			}
			return $arr;
		}


		function getLocation()
		{
			$arr = array();
			try {
				$tmp = $this->fddoc->getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "location");
				if($tmp->length > 0){
					$loc = $tmp->item(0);
					$arr['city'] = $loc->getAttribute('city');
					$arr['region'] = $loc->getAttribute('region');
				}
			}
			catch(Exception $e) {
				$arr['ERR_MSG'] = $e->getMessage();
			}
			return $arr;
		}


		function getAtmosphere()
		{
			$arr = array();
			try {
				$tmp = $this->fddoc->getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "atmosphere");
				if($tmp->length > 0){
					$atm = $tmp->item(0);
					$arr['humidity'] = $atm->getAttribute('humidity');
					$arr['pressure'] = $atm->getAttribute('pressure');
					$arr['visibility'] = $atm->getAttribute('visibility');
				}
			}
			catch(Exception $e) {
				$arr['ERR_MSG'] = $e->getMessage();
			}
			return $arr;
		}


		function getWind()
		{
			$arr = array();
			try {
				$tmp = $this->fddoc->getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "wind");
				if($tmp->length > 0){
					$wnd = $tmp->item(0);
					$spd = $wnd->getAttribute('speed');
					$arr['speed'] = $spd;
					$arr['chill'] = $wnd->getAttribute('chill');
					$arr['direction'] = "N/A";
					if($spd > 0)
					{
						$d = $wnd->getAttribute('direction');
						$res = floor(($d + 11.25) / 22.5);
						$arr['direction'] = $this->directions[$res];
					}
				}
			}
			catch(Exception $e) {
				$arr['ERR_MSG'] = $e->getMessage();
			}
			return $arr;
		}


		function setCode($c, $s)
		{
			$this->codes[$c] = $s;
		}


		function getCode($c)
		{
			return $this->codes[$c];
		}


		function validUrl($u)
		{
			return preg_match('/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/u', $u);
		}

	}
/*
	$doc = new DOMDocument();
	//$doc->load('http://weather.yahooapis.com/forecastrss?p=SFXX0044&u=c');
	$doc->load("http://weather.yahooapis.com/forecastrss?w=2503308");

	//now I get all elements inside this document with the following name "channel", this is the 'root'
	$channel = $doc->getElementsByTagName("channel");

	//now I go through each item withing $channel
	foreach($channel as $chnl)
	{
		//I then find the 'item' element inside that loop
		$item = $chnl->getElementsByTagName("item");
		foreach($item as $itemgotten)
		{
			//now I search within '$item' for the element "description"
			$describe = $itemgotten->getElementsByTagName("description");

			//once I find it I create a variable named "$description" and assign the value of the Element to it
			$description = $describe->item(0)->nodeValue;

			//and display it on-screen
			echo $description;
		}
	}
*/

?>