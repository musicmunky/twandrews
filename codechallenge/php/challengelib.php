<?php

	$REQ = $_REQUEST;

	if(isset($REQ['libcheck']) && !empty($REQ['libcheck'])){
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
	require_once 'connect.php';
	require 'geocode.php';
	require 'socrata.php';

	date_default_timezone_set('America/New_York');

	$webaddress = "http://twandrews.com/codechallenge";

	if(isset($REQ['method']) && !empty($REQ['method']))
	{
		$method = $REQ['method'];
		$method = urldecode($method);
		$method = $mysqli->real_escape_string($method);

		switch($method)
		{
			case 'getGoogleInfo':	getGoogleInfo($REQ, $mysqli);
				break;
			case 'getSocrataInfo':	getSocrataInfo($REQ, $mysqli);
				break;
			default: noFunction($REQ['method']);
				break;
		}
		mysqli_close($mysqli);
	}


	function noFunction($m)
	{
		$func = $m;
		$result = array(
				"status"	=> "failure",
				"message"	=> "User attempted to call function: " . $func . " which does not exist",
				"content"	=> "You seem to have encountered an error - Contact the web admin if this keeps happening!"
		);
		echo json_encode($result);
	}


	function getSocrataInfo($P, $m)
	{
		$P = escapeArray($P, $m);

		$status  = "";
		$message = "";
		$content = array();

		try
		{
			$view_uid = "3k2p-39jp";
			$root_url = "https://data.seattle.gov";
			$app_token = "rO91a2ol0Bibnga9u74y0VFNc";

			$ginfo = getGoogleInfo($P['searchstring'], $m);
			$range = $P['range'];

			if($ginfo['status'] == "OK")
			{
				$gcontent = $ginfo['content'];
				$content['result_count'] = $gcontent['result_count'];

				if($gcontent['result_count'] > 1)
				{
					$status = "Failure to return a single result";
				}
				else
				{
					$locations = $gcontent['locations'];
					reset($locations);
					$placeid = key($locations);

					$latitude  = $locations[$placeid]['lat'],
					$longitude = $locations[$placeid]['lng'],

					if($latitude != NULL && $longitude != NULL && $range != NULL)
					{
						// Create a new unauthenticated client
						$socrata = new Socrata($root_url, $app_token);

						$params = array("\$where" => "within_circle(location, $latitude, $longitude, $range)");

						$content['response'] = $socrata->get("/resource/$view_uid.json", $params);
					}
				}
			}
			else
			{
				$status = "Failure to connect to Geocode server";
			}
		}
		catch(Exception $e)
		{
			$status  = "ERROR: " . $e->getMessage();
			$message = "ERROR: " . $e->getMessage();
		}

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function getGoogleInfo($s, $m)
	{
		$tmp = escapeArray(array($s), $m);
		$search = $tmp[0];
		$status  = "";
		$message = "";
		$google	 = array();

		$gc = new Geocode(true);
		$gc->loadGeoData($search);

		$status = $gc->getStatus();
		$google['geo_status'] = $status;

		if($status == "OK")
		{
			$c = $gc->getResultCount();
			$google['result_count'] = $c;

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
				$google['locations'][$pids[$i]] = $tmp;
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
				"content" => $google
		);

		return $result;
	}


	function escapeArray($req, $mysqli)
	{
		//recursive function called on the REQ object sent back by an AJAX call
		//it accounts for nested arrays/hashes (these were being nulled out previously)
		foreach($req as $key => $val)
		{
			if(gettype($val) == "array"){
				escapeArray($val);
			}
			else
			{
				$val = urldecode($val);
				$val = $mysqli->real_escape_string($val);
				$req[$key] = $val;
			}
		}
		return $req;
	}

?>
