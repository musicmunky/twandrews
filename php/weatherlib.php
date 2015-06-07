<?php
	if(isset($_POST['libcheck']) && !empty($_POST['libcheck'])){
		define('LIBRARY_CHECK', true);
	}
	if(!defined('LIBRARY_CHECK')){
		die ('<div style="width:100%;height:100%;text-align:center;">
				<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:100px;">
					Sorry, this isn\'t a real page, so I have nothing to show you :-(
				</div>
				<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:30px;margin-bottom:30px;">Wait, here\'s a funny cat!</div>
				<div style="background-repeat:no-repeat;margin-left:auto;margin-right:auto;width:500px;height:280px;background:url(../images/cat.gif)">
				</div>
			</div>');
	}

	define('INCLUDE_CHECK',true);
	require 'connect.php';
	require 'forecastio.php';
	require 'geocode.php';

	date_default_timezone_set('America/New_York');

	$webaddress = "http://twandrews.com/";

	if(isset($_POST['method']) && !empty($_POST['method']))
	{
		$method = $_POST['method'];
		$method = urldecode($method);
		$method = mysql_real_escape_string($method);

		switch($method)
		{
			case 'getGoogleInfo': getGoogleInfo($_POST);
				break;
			case 'getForecastInfo': getForecastInfo($_POST);
				break;
			case 'getWeatherInfo': getWeatherInfo($_POST);
				break;
			default: noFunction($_POST);
				break;
		}
		mysql_close($link);
	}


	function noFunction()
	{
		$func = $_POST['method'];
		$result = array(
				"status"	=> "failure",
				"message"	=> "User attempted to call function: " . $func . " which does not exist",
				"content"	=> "You seem to have encountered an error - Contact the web admin if this keeps happening!"
		);
		echo json_encode($result);
	}


	function getWeatherInfo($P)
	{
		$P = escapeArray($P);

		$status  = "";
		$message = "";
		$content = array();

		$unt = (isset($P['units']) && $P['units'] != "") ? $P['units'] : "ca";
		$gi = getGoogleInfo($P['searchstring'], false);

		if($gi['status'] == "OK")
		{
			$girsp = $gi['content'];
			$content['result_count'] = $girsp['result_count'];

			$locs = $girsp['locations'];
			$singlepid = "";
			foreach($locs as $pid => $data)
			{
				$singlepid = $pid;
				$content["geocodeid" . $pid] = $data;
			}

			if($girsp['result_count'] > 1)
			{
				$status = "success";
			}
			else
			{
				$fi = getForecastInfo(array("latitude" => $girsp['locations'][$singlepid]['lat'],
											"longitude" => $girsp['locations'][$singlepid]['lng'],
										    "units" => $unt), false);

				if($fi['statuscode'] == "200")
				{
					$status = "success";
					$daly = $fi['content']['daily'];
					$hrly = $fi['content']['hourly'];
					$crnt = $fi['content']['current'];
					$tmzn = $fi['content']['timezone'];
					$nreq = $fi['content']['numberofreqs'];
					$content['daily'] 		= $daly;
					$content['hourly'] 		= $hrly;
					$content['current'] 	= $crnt;
					$content['timezone']	= $tmzn;
					$content['numberofreqs'] = $nreq;
				}
				else
				{
					$status = "Failure to connect to Forecast server";
				}
			}
		}
		else
		{
			$status = "Failure to connect to Geocode server";
		}

		$result = array(
				"status" => $status,
				"message" => $message,
				"content" => $content
		);
		echo json_encode($result);
	}

	function getGoogleInfo($s, $ajax = true)
	{
		//$search  = urlencode($s);
		$tmp = escapeArray(array($s)); //trying this to see if Google can handle apostrophe's better this way...
		$search = $tmp[0];
		$status  = "";
		$message = "";
		$rsp	 = array();

		$gc = new Geocode(true);
		$gc->loadGeoData($search);

		$status = $gc->getStatus();
		$rsp['geo_status'] = $status;

		if($status == "OK")
		{
			$c = $gc->getResultCount();
			$rsp['result_count'] = $c;

			if($c > 1)
			{
				$lats = $gc->getLatitude("a");
				$lngs = $gc->getLongitude("a");
				$city = $gc->getCity("a");
				$sbrb = $gc->getSuburb("a");
				$stat = $gc->getState("a");
				$zipc = $gc->getZipCode("a");
				$ctry = $gc->getCountry("a");
				$pids = $gc->getPlaceID("a");
				$adds = $gc->getFormattedAddress("a");
				for($i = 0; $i < $c; $i++)
				{
					$tmp = array(
						"placeid"	=> $pids[$i],
						"lat" 		=> $lats[$i],
						"lng" 		=> $lngs[$i],
						"suburb"	=> $sbrb[$i]['long_name'],
						"city" 		=> $city[$i]['long_name'],
						"state" 	=> $stat[$i]['short_name'],
						"country" 	=> $ctry[$i]['short_name'],
						"zip" 		=> $zipc[$i]['short_name'],
						"address"	=> $adds[$i]
					);
					$rsp['locations'][$pids[$i]] = $tmp;
				}
			}
			else
			{
				//this else statement (and the if statement in general)
				//might be unnecessary - you should be able to simply
				//run it as is and if there is only one result, it *should*
				//still return the correct stuff...test it out later
				$pid = $gc->getPlaceID();
				$rsp['locations'][$pid] = array(
					"placeid" 	=> $pid,
					"lat" 		=> $gc->getLatitude(),
					"lng" 		=> $gc->getLongitude(),
					"suburb"	=> $gc->getSuburb(),
					"city" 		=> $gc->getCity(),
					"state" 	=> $gc->getState(),
					"country" 	=> $gc->getCountry(),
					"zip" 		=> $gc->getZipCode(),
					"address" 	=> $gc->getFormattedAddress()
				);
			}
		}
		else
		{
			$status = "SERVER ERROR - " . $gc->getStatus();
			$message = "There was an error retrieving your information";
		}

		unset($gc);

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $rsp
		);

		if($ajax){
			echo json_encode($result);
		}
		else {
			return $result;
		}
	}


	function getForecastInfo($P, $ajax = true)
	{
		if($ajax){
			$P = escapeArray($P);
		}
		$lat = $P['latitude'];
		$lng = $P['longitude'];
		$unt = (isset($P['units']) && $P['units'] != "") ? $P['units'] : "ca";

		$status = "success";
		$fc = new Forecastio(true, "", "", $unt);
		$fc->loadForecastData($lat, $lng);
		$fcstatus = $fc->getStatus();
		$content = array();

		if(isset($P['geoinfo']) && $ajax)
		{
			$content['result_count'] = 1;
			$content['geocodeid' . $P['geoinfo']['placeid']] = $P['geoinfo'];
		}

		if($fcstatus == "200")
		{
			$hrly = $fc->getHourlyForecast();
			$daly = $fc->getDailyForecast();
			$harr = array_slice($hrly['data'], 1, 8);
			$darr = array_slice($daly['data'], 0, 5);
			$content['daily'] 		= $darr;
			$content['hourly'] 		= $harr;
			$content['current'] 	= $fc->getCurrentForecast();
			$content['timezone']	= $fc->getTimezone();
			$content['numberofreqs'] = $fc->getNumReqs();
		}
		else
		{
			$status = "failure";
		}

		//clear the weather object for garbage collection
		unset($fc);

		$result = array(
				"status" 		=> $status,
				"statuscode" 	=> $fcstatus,
				"message" 		=> "",
				"content" 		=> $content
		);
		if($ajax){
			echo json_encode($result);
		}
		else {
			return $result;
		}
	}


	function escapeArray($post)
	{
		//recursive function called on the POST object sent back by an AJAX call
		//it accounts for nested arrays/hashes (these were being nulled out previously)
		foreach($post as $key => $val)
		{
			if(gettype($val) == "array") {
				escapeArray($val);
			}
			else {
				$val = urldecode($val);
				$val = mysql_real_escape_string($val);
				$post[$key] = $val;
			}
		}
		return $post;
	}

?>