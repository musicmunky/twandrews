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
	require 'connect.php';
	//require 'forecastio.php';
	//require 'geocode.php';

	date_default_timezone_set('America/New_York');

	$webaddress = "http://twandrews.com/golf";

	if(isset($REQ['method']) && !empty($REQ['method']))
	{
		$method = $REQ['method'];
		$method = urldecode($method);
		$method = $mysqli->real_escape_string($method);

		switch($method)
		{
			case 'getUserInfo': getUserInfo($REQ, $mysqli);
				break;
			case 'getForecastInfo': getForecastInfo($REQ);
				break;
			case 'getWeatherInfo': getWeatherInfo($REQ);
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


	function getWeatherInfo($P)
	{
		$P = escapeArray($P);

		$status  = "";
		$message = "";
		$content = array();

		$result = array(
				"status" => $status,
				"message" => $message,
				"content" => $content
		);
		echo json_encode($result);
	}

	function getUserInfo($P, $m)
	{
		$P = escapeArray($P, $m);

		$status  = "";
		$message = "";
		$content = array();

		$user = $m->query("SELECT FIRSTNAME, LASTNAME, ID, GOLFNAME, EMAILADDRESS FROM golfusers WHERE ID=" . $P['golfid'] . ";");
		if($user)
		{
			$rslt = $user->fetch_assoc();
			$content['FIRSTNAME'] = $rslt['FIRSTNAME'];
			$content['LASTNAME']  = $rslt['LASTNAME'];
			$content['USERNAME']  = $rslt['GOLFNAME'];
			$content['EMAILADD']  = $rslt['EMAILADDRESS'];
			$content['GOLFID']	  = $rslt['ID'];
			$status = "success";
		}
		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function getForecastInfo($P, $ajax = true)
	{
		if($ajax){
			$P = escapeArray($P);
		}

		$status = "success";
		$fcstatus = "";
		$content = array();

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


	function escapeArray($post, $m)
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
				$val = $m->real_escape_string($val);
				$post[$key] = $val;
			}
		}
		return $post;
	}

?>
