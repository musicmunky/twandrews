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
				<div style="background-repeat:no-repeat;margin-left:auto;margin-right:auto;width:500px;height:280px;background:url(../logos/cat.gif)"></div>
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

		$gi = getGoogleInfo($P['searchstring'], false);

		if($gi['status'] == "OK")
		{
			$girsp = $gi['content'];
			$content['result_count'] = $girsp['result_count'];

			if($girsp['result_count'] > 1)
			{
				$status = "success";
				$locs = $girsp['locations'];
				foreach($locs as $pid => $data)
				{
					$content[$pid] = $data;
				}
			}
			else
			{
				$content['lat'] 	= $girsp['lat'];
				$content['lng'] 	= $girsp['lng'];
				$content['city'] 	= $girsp['city'];
				$content['state'] 	= $girsp['state'];
				$content['country'] = $girsp['country'];
				$content['zip'] 	= $girsp['zip'];
				$content['address'] = $girsp['address'];

				$fi = getForecastInfo(array("latitude" => $girsp['lat'], "longitude" => $girsp['lng']), false);
				if($fi['status'] == "200")
				{
					$status = "success";
					$daly = array_slice($fi['content']['daily']['data'], 1, 5);
					$hrly = array_slice($fi['content']['hourly']['data'], 0, 8);
					$crnt = $fi['content']['current'];
					$content['daily'] 	= $daly;
					$content['hourly'] 	= $hrly;
					$content['current'] = $crnt;
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
		$search  = urlencode($s);
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
						"city" 		=> $city[$i]['short_name'],
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
				$rsp['placeid'] = $gc->getPlaceID();
				$rsp['lat'] 	= $gc->getLatitude();
				$rsp['lng'] 	= $gc->getLongitude();
				$rsp['city'] 	= $gc->getCity();
				$rsp['state'] 	= $gc->getState();
				$rsp['country'] = $gc->getCountry();
				$rsp['zip'] 	= $gc->getZipCode();
				$rsp['address'] = $gc->getFormattedAddress();
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


		$fc = new Forecastio(true);
		$fc->loadForecastData($lat, $lng);
		$status = $fc->getStatus();
		$content = array();

		if($status == "200")
		{
			$content['current'] = $fc->getCurrentForecast();
			$content['hourly'] 	= $fc->getHourlyForecast();
			$content['daily'] 	= $fc->getDailyForecast();
		}

		//clear the weather object for garbage collection
		unset($fc);

		$result = array(
				"status" => $status,
				"message" => "",
				"content" => $content
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