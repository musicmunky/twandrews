#!/usr/bin/env php
<?php

require_once('websockets.php');
date_default_timezone_set('America/New_York');

class echo_server extends WebSocketServer
{
	//protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
	protected function process ($user, $message)
	{
		if($message == 'help')
		{
			$reply = 'Following commands are available:<br>"date" - returns the date<br>"hi" - says hello';
		}
		else if($message == 'date')
		{
			$reply = "Current date is " . date('Y-m-d H:i:s');
		}
		else if($message == 'hi')
		{
			$reply = "Hello user! This is a websocket server!";
		}
		else
		{
			$reply = "Thank you for the message: '$message'";
		}

		//$u = var_export($user, true);
		//$u = preg_replace('/\\n/', "<br>", $u);
		//$reply = $reply . "<br><br><br>User Info:<br>" . $u;

		$this->send($user, $reply);

		//The uri component say /a/b/c
		//echo "Requested resource : " . $user->requestedResource . "n";
	}

	/**
        This is run when socket connection is established. Send a greeting message
    */
	protected function connected ($user)
	{
		//Send welcome message to user
		$welcome_message = 'Hello!<br>Welcome to the Websocket server!<br>Type "help" to see what commands are available.';
		$this->send($user, $welcome_message);
	}

	/**
        This is where cleanup would go, in case the user had any sort of
        open files or other objects associated with them.  This runs after the socket
        has been closed, so there is no need to clean up the socket itself here.
    */
	protected function closed ($user)
	{
		echo "User closed connectionn";
	}
}

$host = "45.55.189.112";
$port = "9000";

$server = new echo_server($host , $port );

	try {
		$server->run();
	}
	catch (Exception $e) {
		$server->stdout($e->getMessage());

	}

/*
	require_once('websockets.php');

	class echoServer extends WebSocketServer {

		//protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
		protected function process ($user, $message) {
			$this->stdout("GOT TO THE PROCESS FUNCTION");
			$this->send($user, $message);
		}

		protected function connected ($user) {
			// Do nothing: This is just an echo server, there's no need to track the user.
			// However, if we did care about the users, we would probably have a cookie to
			// parse at this step, would be looking them up in permanent storage, etc.
			$this->send($user, "Successfully connected!!");
		}

		protected function closed ($user) {
			// Do nothing: This is where cleanup would go, in case the user had any sort of
			// open files or other objects associated with them. This runs after the socket
			// has been closed, so there is no need to clean up the socket itself here.
		}
	}

	$echo = new echoServer("45.55.189.112","9000");

	try {
		$echo->run();
	}
	catch (Exception $e) {
		$echo->stdout($e->getMessage());

	}
*/
?>