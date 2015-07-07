<?php
	define('LIBRARY_CHECK',true);
	require 'php/golflib.php';

	if(!empty($_POST))
	{
		$user = isset($_POST['txtusername']) ? $_POST['txtusername'] : "";
		$pass = isset($_POST['txtpassword']) ? $_POST['txtpassword'] : "";

		$user = urldecode($user);
		$pass = urldecode($pass);
		$user = mysqli_real_escape_string($mysqli, $user);
		$pass = mysqli_real_escape_string($mysqli, $pass);
		$hashedpassword = md5($pass);

		$myquery = "SELECT gu.ID, gu.GOLFNAME, ut.TYPENAME
					FROM golf_users gu, user_types ut
					WHERE gu.GOLFNAME='" . $user . "'
						AND gu.GOLFPASSWORD='" . $hashedpassword . "'
							AND gu.USERTYPEID=ut.TYPEID;";

		$checkpass = array();
		if($result = $mysqli->query($myquery))
		{
			while ($row = $result->fetch_assoc())
			{
        		$checkpass['ID']		= $row['ID'];
				$checkpass['GOLFNAME']	= $row['GOLFNAME'];
				$checkpass['USERTYPE']	= $row['TYPENAME'];
				break;
    		}
		}

		if(isset($checkpass['ID']) && $checkpass['ID'] != "")
		{
			ini_set('session.gc_maxlifetime', 24*60*60);
			ini_set('session.gc_probability', 1);
			ini_set('session.gc_divisor', 100);
			if(!isset($_SESSION))
			{
				session_name('andrewsgolf');
				session_start();
			}

			$_SESSION['userid'] 	= $checkpass['ID'];
			$_SESSION['username']	= $checkpass['GOLFNAME'];
			$_SESSION['usertype']	= $checkpass['USERTYPE'];
		}
/*		else
		{
			echo "<link rel='stylesheet' href='../css/fusionlib.css' type='text/css' media='screen' charset='utf-8'>
				<script language='javascript' type='text/javascript' src='../javascript/jquery-1.11.0.min.js'></script>
				<script language='javascript' type='text/javascript' src='../javascript/fusionlib.js'></script>
				<script>alert('Incorrect Username/Password');</script>";
		}
*/
	}

	if(isset($_SESSION['username']) && !empty($_SESSION['username']) && isset($_SESSION['userid']) && !empty($_SESSION['userid']))
	{
		header('Location: index.php');
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>MyGolf Login</title>
		<meta charset='utf-8' />
		<link rel="icon" type="image/icon" href="images/favicon.ico" />
		<link rel='stylesheet' href='css/golfstyle.css' />
		<link rel='stylesheet' href='../css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="../javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="../javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/golflogin.js"></script>
	</head>
	<body style="background-color:#fff;">
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
