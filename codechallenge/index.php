<?php

	define('LIBRARY_CHECK',true);
	require 'php/challengelib.php';

	$title = "CrimeWatch";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="viewport" content="initial-scale=1, maximum-scale=1" />
		<title><?php echo $title; ?></title>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel='stylesheet' type="text/css" href='css/ccstyle.css'  media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/fusionlib.css' media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.3.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script language="javascript" type="text/javascript" src="javascript/challenge.js"></script>
	</head>
	<body>
		<!-- default to Seattle if there's no localStorage -->
		<input type="hidden" id="defaultzipcode" value="98134" />
		<div id="header" class="header">
			<div id="headercont" class="header-content">
				<div class="header-logo">
					<div class="logowrapper">
						<div class="title">
							<img id="logo" src="images/handcuffs.png" class="logoimage" />
							<div id="titlediv" class="titletext"><?php echo $title; ?></div>
						</div>
					</div>
					<div class="h100fl">
						<div id="datewrapper" class="h100fl">
							<span id="date" class="headspan"></span>
						</div>
					</div>
				</div>

				<div class="header-search">
					<div class="w100fl h100fl" id="srchcont">
						<span class="fl">Search: </span>
						<form class="h100fl" id="searchform" onsubmit="runSearch();return false;">
							<input type="text" id="searchbox" value="" class="searchbox"
								   onkeyup="hideSearchDiv(this)" onchange="hideSearchDiv(this)" />
							<button class="srchbtn">
								<img src="images/magnify-glass.png" id="srchicn" />
							</button>
						</form>
					</div>
					<div id="locselect" class="locdiv"></div>
				</div>
			</div>
		</div>
<!--	<div id='displayinfodiv' style='width:100%;height:500px;margin-top:60px;overflow-y:scroll;'>
			<pre><?php //var_dump(json_decode($content, true)); ?></pre>
		</div> -->
		<div id="mainwrapper" class="mainwrapper">
		</div>
		<div class="footer">
			<div class="innerfooter tac">
				<div class="footer-content-left">
					<span>Crime info for <span id="footerlocation"></span></span>
				</div>
				<div class="footer-content-right">
					<span>
						Powered by <a href="http://dev.socrata.com/" class="cldclr" target="_blank">Socrata</a>
					</span>
				</div>
			</div>
		</div>
	</body>
</html>
