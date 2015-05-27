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
	date_default_timezone_set('America/New_York');

	$webaddress = "http://twandrews.com/";

	if(isset($_POST['method']) && !empty($_POST['method']))
	{
		$method = $_POST['method'];
		$method = urldecode($method);
		$method = mysql_real_escape_string($method);

		switch($method)
		{
			case 'startWebSocket': startWebSocket($_POST);
				break;
			case 'stopWebSocket': stopWebSocket($_POST);
				break;
			case 'isWsRunning': isWsRunning($_POST);
				break;
			case 'restartApache': restartApache($_POST);
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
				"content"	=> "You seem to have encountered an error - Contact the site admin if this keeps happening!"
		);
		echo json_encode($result);
	}


	function restartApache($P)
	{
		$P = escapeArray($P);

		$status = "success";
		$message = "";

		$se = shell_exec('service httpd restart');

		$s = preg_replace('/\\n/', "<br>", $se);

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> $s
		);

		echo json_encode($result);
	}


	function startWebSocket($P)
	{
		$P = escapeArray($P);

		$status  = "success";
		$message = "";

 		//$se = shell_exec('nohup php -q /var/www/twandrews.com/public_html/php/runwebsock.php > /dev/null 2>&1 &');
		$content = array();
		$checkws = runCmd('ps -aef | grep runwebsock.php | grep -v grep');
		if(count($checkws['output']) > 0)
		{
			$content['output'] = array("Process already running: " . $checkws['output'][0]);
			$content['retval'] = 0;
			$content['errmsg'] = "";
		}
		else
		{
			$content = runCmd('../startws.sh');
		}

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> $content
		);

		echo json_encode($result);
	}


	function stopWebSocket($P)
	{
		$P = escapeArray($P);

		$status  = "success";
		$message = "";
		$content = runCmd('../stopws.sh');

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> $content
		);

		echo json_encode($result);
	}


	function isWsRunning($P)
	{
		$P = escapeArray($P);

		$status  = "success";
		$message = "";
		$content = runCmd('ps -aef | grep runwebsock.php | grep -v grep');

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> $content
		);

		echo json_encode($result);
	}


	function runCmd($cmd)
	{
		$out = array();
		$ret = "";
		$err = "";
		$content = array();

		try {
			exec($cmd, $out, $ret);
		}
		catch(Exception $e) {
			$err = $e->getMessage();
		}

		$content['output'] = $out;
		$content['retval'] = $ret;
		$content['errmsg'] = $err;

		return $content;
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
