<?php
	define('LIBRARY_CHECK',true);
	require 'php/library.php';

	$badsub = 0;
	$message = "foo";
	if(!empty($_POST))
	{
		$user = isset($_POST['txtusername']) ? $_POST['txtusername'] : "";
		$pass = isset($_POST['txtpassword']) ? $_POST['txtpassword'] : "";

		$user = urldecode($user);
		$pass = urldecode($pass);
		$user = mysql_real_escape_string($user);
		$pass = mysql_real_escape_string($pass);
		$hashedpassword = md5($pass);

		$checkpass = mysql_fetch_assoc(mysql_query("SELECT ID, USER
													FROM eventadmin
													WHERE USER='" . $user . "'
													AND PASSWORD='" . $hashedpassword . "';"));

		if(isset($checkpass['ID']) && $checkpass['ID'] != "")
		{
			ini_set('session.gc_maxlifetime', 24*60*60);
			ini_set('session.gc_probability',1);
			ini_set('session.gc_divisor',100);
			if(!isset($_SESSION))
			{
				session_name('andrewscal');
				session_start();
			}

			$_SESSION['userid'] = $checkpass['ID'];
			$_SESSION['username'] = $checkpass['USER'];
		}
		else
		{
			/*echo ("<script language='javascript'>
					window.alert('Incorrect Username/Password combo!');
					document.location.href = 'login.php';
					</script>");*/

			$badsub = 1;
			$message = "Incorrect Username/Password combo!";
			/*
			echo ("<link rel='stylesheet' href='css/fusionlib.css' type='text/css' media='screen' charset='utf-8'>
					<script language='javascript' type='text/javascript' src='javascript/jquery-1.11.0.min.js'></script>
					<script language='javascript' type='text/javascript' src='javascript/fusionlib.js'></script>
					<script language='javascript'>
					FUSION.lib.alert('Incorrect Username/Password combo!');
					document.location.href = 'login.php';
					</script>");
			*/
			//header('Location: dhdlogin');
		}
	}

	if(isset($_SESSION['username']) && isset($_SESSION['userid']))
	{
		header('Location: index.php');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>MyDevelopment Login</title>
		<meta charset='utf-8' />
		<link rel="icon" type="image/png" href="images/calicon.png" />
		<link rel='stylesheet' href='css/style.css' />
		<link rel='stylesheet' href='css/login.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/login.js"></script>
		<script language='javascript'>
			if(<?php echo $badsub; ?>){
				FUSION.lib.alert('<?php echo $message; ?>');
				//window.alert('<?php //echo $message; ?>');
				//document.location.href = 'login.php';
			}
		</script>
	</head>
	<body>
		<div class="mainloginclass">
			<form id='loginform' action='login.php' method='post'
				  accept-charset='UTF-8' class="login" onsubmit="return validateForm();">
				<div class="inputdivs">
					<input class="logintxt" type="text" id="txtusername" name="txtusername" placeholder="username" />
				</div>
				<div class="inputdivs">
					<input class="logintxt" type="password" id="txtpassword" name="txtpassword" placeholder="password" />
				</div>
				<div class="inputdivs">
					<input class="loginbtn" type="submit" value="login" />
				</div>
			</form>
		</div>
	</body>
</html>