<?php
	if(!defined('INCLUDE_CHECK')) die ('
		<div style="width:100%;height:100%;text-align:center;">
			<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:100px;">
				Sorry, this isn\'t a real page, so I have nothing to show you :-(
			</div>
			<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:30px;margin-bottom:30px;">
				Wait, here\'s a funny cat!
			</div>
			<div style="background-repeat:no-repeat;margin-left:auto;margin-right:auto;width:500px;height:280px;background:url(../images/cat.gif)">
			</div>
		</div>');


	/* Database config */
	$db_host     = 'localhost:3306';
	$db_user     = 'musicmunky';
	$db_pass     = 'Pas9b53!';
	$db_database = 'golfdb';
	/* End config */

	$mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db_database);

	if (mysqli_connect_errno($mysqli)) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
?>
