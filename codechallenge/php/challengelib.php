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

	require "phpsoda.phar";
	use allejo\Socrata\SodaClient;
	use allejo\Socrata\SodaDataset;
	use allejo\Socrata\SoqlQuery;

//	require 'socrata.php';

	date_default_timezone_set('America/New_York');

	//this handles all the requests being sent to the server
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


	/**
	* Default function that returns to an AJAX call if the function requested is not found
	*
	* @param Method Name $m
	*/
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


	/**
	* Retrieve the data requested from the Socrata dataset
	* This function implements the phpsoda.phar library made available through GitHub by user allejo
	*
	* @param Request $P
	* @param MySQLi $m
	*/
	function getSocrataInfo($P, $m)
	{
		//just a little data sanitation, move along...
		$P = escapeArray($P, $m);

		$status  = "";
		$message = "";
		$content = array();

		try
		{
			//set the connection variables for the API
			$view_uid  = "3k2p-39jp";
			$root_url  = "https://data.seattle.gov";
			$app_token = "rO91a2ol0Bibnga9u74y0VFNc";

			//get the lat/long data for the search string,
			//in this case the string is hard-coded, but it can handle any search string that Google can handle
			$ginfo = getGoogleInfo($P['searchstring'], $m);

			//convert the range from miles to meters
			$range = (isset($P['range'])) ? ($P['range'] * 1609.34) : 1609.34; //bleh...magic number, but it's specified in the requirements

			//if the google request came back successfully...
			if($ginfo['status'] == "OK")
			{
				$gcontent = $ginfo['content'];

				//keeping this here JUST in case things get a little funky and google returns more than one result
				//it's not difficult to handle this more gracefully, but given the nature of the project a simple
				//page refresh should fix this
				if($gcontent['result_count'] > 1)
				{
					$status = "Failure to return a single result";
				}
				else
				{
					$locations = $gcontent['locations'];
					reset($locations);
					$placeid = key($locations);

					$latitude  = $locations[$placeid]['lat'];
					$longitude = $locations[$placeid]['lng'];

					//creating the objects to retrieve the data from Socrata...
					$sodaClient  = new SodaClient($root_url, $app_token);
					$sodaDataset = new SodaDataset($sodaClient, $view_uid);
					$soqlQuery   = new SoqlQuery();

					//for whatever reason the "location" string returns an error when the query is attempted,
					//but "incident_location" works correctly, even thought it *should* be deprecated
					//may research further if time allows

//					$loc_type = "location";
					$loc_type = "incident_location";

					//formatting the date/time parameters
					$today     = date("Y-d-m");
					$enddate   = isset($P['enddate']) ? $P['enddate'] : $today;
					$tmpstart  = strtotime($today . " -1 year");
					$startdate = isset($P['startdate']) ? $P['startdate'] : date("Y-d-m", $tmpstart);

					$startdate .= "T00:00:00";
					$enddate   .= "T23:59:59";
					$datestr   = " and event_clearance_date > '" . $startdate . "' and event_clearance_date < '" . $enddate . "'";

					$soqlQuery->where("within_circle(" .
										$loc_type . ", " .
										$latitude . ", " .
										$longitude . ", " .
										$range . ")" . $datestr);
					if(isset($P['limit']))
					{
						$soqlQuery->limit(intval($P['limit']));
					}

					//filling the array that will be sent back to the browser
					$results = $sodaDataset->getDataset($soqlQuery);
					$content['response_content'] = $results;
					$content['response_count']   = count($results);
					$content['latitude_center']  = $latitude;
					$content['longitude_center'] = $longitude;
					$content['start_date']       = $startdate;
					$content['end_date']         = $enddate;

					$status = "success";

// 					$content['initial_types'] = getIncidentTypes($results);
// 					$metadata = $sodaDataset->getMetadata();
// 					$content['response_metadata'] = $metadata;
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

/*
	function getIncidentTypes($data)
	{
		$types = array();
		for($i = 0; $i < count($data); $i++)
		{
			$itg = $data[$i]['initial_type_group'];
			if(array_key_exists($itg, $types))
			{
				$types[$itg]++;
			}
			else
			{
				$types[$itg] = 1;
			}
		}
		return $types;
	}
*/

	/**
	* Uses the Google Geocode API to retrieve lat/long values (along with other data)
	* for a given search string
	*
	* @param Search String $s
	* @param MySQLi $m
	*/
	function getGoogleInfo($s, $m)
	{
		$tmp     = escapeArray(array($s), $m);
		$search  = $tmp[0];
		$status  = "";
		$message = "";
		$google	 = array();

		//setting up the request to the Google API
		$gc = new Geocode($m);
		$gc->loadGeoData($search);

		//verifying the request was successful
		$status = $gc->getStatus();
		$google['geo_status'] = $status;

		//if so...
		if($status == "OK")
		{
			//filling the array to return to the requesting function
			$c = $gc->getResultCount();
			$google['result_count'] = $c;

			$lats = $gc->getLatitude("a");
			$lngs = $gc->getLongitude("a");
			$adds = $gc->getFormattedAddress("a");
			for($i = 0; $i < $c; $i++)
			{
				$tmp = array(
					"placeid"	=> $pids[$i],
					"lat" 		=> $lats[$i],
					"lng" 		=> $lngs[$i],
					"address"	=> $adds[$i]
				);
				//filling a hash of locations based on their Google PlaceID
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


	/**
	* A recursive function called on the REQ object sent back by an AJAX call
	* it accounts for nested arrays/hashes
	*
	* @param Request $req
	* @param MySQLi $mysqli
	*/
	function escapeArray($req, $mysqli)
	{
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
