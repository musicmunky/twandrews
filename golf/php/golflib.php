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

	date_default_timezone_set('America/New_York');

	$webaddress = "http://twandrews.com/golf";

	if(isset($REQ['method']) && !empty($REQ['method']))
	{
		$method = $REQ['method'];
		$method = urldecode($method);
		$method = $mysqli->real_escape_string($method);

		switch($method)
		{
			case 'getUserInfo':		getUserInfo($REQ, $mysqli);
				break;
			case 'getCourseInfo':	getCourseInfo($REQ, $mysqli);
				break;
			case 'saveUserInfo':	saveUserInfo($REQ, $mysqli);
				break;
			case 'saveCourseInfo':	saveCourseInfo($REQ, $mysqli);
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


	function getUserInfo($P, $m)
	{
		$P = escapeArray($P, $m);

		$status  = "";
		$message = "";
		$content = array();

		$user = $m->prepare("SELECT u.FIRSTNAME, u.LASTNAME, u.ID, u.GOLFNAME, u.EMAILADDRESS, u.USERTYPEID, t.TYPENAME
							FROM golf_users AS u INNER JOIN user_types AS t
								ON u.USERTYPEID = t.ID
							WHERE u.ID = ? LIMIT 1;");
		$user->bind_param("i", $P['golfid']);
		$user->execute();

		if($user->errno != 0)
		{
			$status = "failure";
			$message = "Error attempting to retrieve user info:<br>" . $user->error . "<br>Error code: " . $user->errno;
		}
		else
		{
			$result = $user->get_result();
			$rslt	= $result->fetch_assoc();
			$content['FIRSTNAME'] = $rslt['FIRSTNAME'];
			$content['LASTNAME']  = $rslt['LASTNAME'];
			$content['USERNAME']  = $rslt['GOLFNAME'];
			$content['EMAILADD']  = $rslt['EMAILADDRESS'];
			$content['GOLFID']	  = $rslt['ID'];
			$content['USERTYPE']  = $rslt['USERTYPEID'];
			$content['TYPENAME']  = $rslt['TYPENAME'];
			$status = "success";
		}
		$user->close();

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function getCourseInfo($P, $m)
	{
		$P = escapeArray($P, $m);

		$status   = "";
		$message  = "";
		$content  = array();
		$course   = array();
		$location = array();

		$crs = $m->prepare("SELECT c.ID, c.LOCATIONID, c.COURSENAME, c.TYPEID, c.NUMHOLES, t.TYPENAME
							FROM course AS c INNER JOIN course_type AS t
								ON c.TYPEID = t.ID
							WHERE c.ID = ? LIMIT 1;");
		$crs->bind_param("i", $P['courseid']);
		$crs->execute();

		if($crs->errno != 0)
		{
			$status = "failure";
			$message = "Error attempting to retrieve course info:<br>" . $crs->error . "<br>Error code: " . $crs->errno;
		}
		else
		{
			$result = $crs->get_result();
			$rslt	= $result->fetch_assoc();
			$locid  = $rslt['LOCATIONID'];

			$course['COURSEID']		= $rslt['ID'];
			$course['COURSENAME']	= $rslt['COURSENAME'];
			$course['COURSETYPE']	= $rslt['TYPEID'];
			$course['TYPENAME']		= ucfirst($rslt['TYPENAME']);
			$course['COURSELENGTH'] = $rslt['NUMHOLES'];
			$status = "success";
			$crs->close();

			$loc = $m->prepare("SELECT *
								FROM course_location
								WHERE ID = ? LIMIT 1;");
			$loc->bind_param("i", $locid);
			$loc->execute();
			if($loc->errno != 0)
			{
				$status = "failure";
				$message = "Error attempting to retrieve course info:<br>" . $loc->error . "<br>Error code: " . $loc->errno;
			}
			else
			{
				$result = $loc->get_result();
				$rslt	= $result->fetch_assoc();
				$location['LOCATIONID']	= $rslt['ID'];
				$location['ADDRESS1']	= $rslt['ADDRESS1'];
				$location['ADDRESS2']	= $rslt['ADDRESS2'];
				$location['ADDRESS3']	= $rslt['ADDRESS3'];
				$location['CITY']		= $rslt['CITY'];
				$location['STATE']		= $rslt['STATE'];
				$location['ZIPCODE']	= $rslt['ZIPCODE'];
				$loc->close();
			}
		}
		if(is_resource($crs)){
			$crs->close();
		}

		$content['course']   = $course;
		$content['location'] = $location;

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function saveUserInfo($P, $m)
	{
		global $webaddress;

		$P = escapeArray($P, $m);
		$status = "";
		$message = "";
		$n_or_e = 1;
		$content = array();

		$userid = $P['userid'];
		if($userid == 0)
		{
			//insert new user, but first check for existing username and email address
			$n_or_e = 0;

			$usercheck = $m->prepare("SELECT ID FROM golf_users WHERE GOLFNAME = ?;");
			$usercheck->bind_param("s", $P['username']);
			$usercheck->execute();
			$userreslt = $usercheck->get_result();
			$usercheck->close();

			$emalcheck = $m->prepare("SELECT ID FROM golf_users WHERE EMAILADDRESS = ?;");
			$emalcheck->bind_param("s", $P['emailaddress']);
			$emalcheck->execute();
			$emalreslt = $emalcheck->get_result();
			$emalcheck->close();

			if($userreslt->num_rows > 0 || $emalreslt->num_rows > 0)
			{
				$status = "failure";
				$message = "";
				if($userreslt->num_rows > 0){
					$message .= "<br>That username is not available - please use a different name";
				}
				if($emalreslt->num_rows > 0){
					$message .= "<br>That email address is already being used - please use a different address";
				}
			}
			else
			{
				$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
				$count = mb_strlen($chars);
				$password = "";
				$length = 12;
				for ($i = 0, $password = ''; $i < $length; $i++)
				{
					$index = rand(0, $count - 1);
					$password .= mb_substr($chars, $index, 1);
				}
				$hashedpassword = md5($password);

				$insert = $m->prepare("INSERT INTO golf_users(GOLFNAME, USERTYPEID, FIRSTNAME, LASTNAME, GOLFPASSWORD, EMAILADDRESS)
										VALUES (?, ?, ?, ?, ?, ?)");
				$insert->bind_param("sissss",
									$P['username'],
									$P['usertype'],
									$P['firstname'],
									$P['lastname'],
									$hashedpassword,
									$P['emailaddress']);
				$insert->execute();
				if($insert->errno != 0)
				{
					$status = "failure";
					$message = "Error attempting to add user:<br>" . $insert->error . "<br>Error code: " . $insert->errno;
				}
				else
				{
					$userid  = $insert->insert_id;
					$message = "New user created!";
// 					$to      = $email;
					$to      = "musicmunky@gmail.com";
					$subject = "New Account Created";
					$emailmessage = "Hello,\r\n\r\nYour account has been created!\r\n\r\nYour login information is:\r\n" .
									"username: " . $P['username'] . "\r\npassword: " . $password . "\r\n\r\n" .
									"Please go here to login and change your password:\r\n" .
									$webaddress . "/login.php";
					$headers =	"From: admin@twandrews.com" . "\r\n" .
								"Reply-To: admin@twandrews.com" . "\r\n" .
								"X-Mailer: PHP/" . phpversion();
					mail($to, $subject, $emailmessage, $headers);

					$status = "success";
					$message = "User added successfully!";
				}
				$insert->close();
			}
		}
		else
		{
			//update existing user
			$emalcheck = $m->prepare("SELECT ID FROM golf_users WHERE EMAILADDRESS = ? and ID != ?;");
			$emalcheck->bind_param("si", $P['emailaddress'], $userid);
			$emalcheck->execute();
			$emalreslt = $emalcheck->get_result();
			$emalcheck->close();

			if($emalreslt->num_rows > 0)
			{
				$status  = "failure";
				$message = "<br>That email address is already being used - please use a different address";
			}
			else
			{
				$update = $m->prepare("UPDATE golf_users SET GOLFNAME = ?, USERTYPEID = ?, FIRSTNAME = ?, LASTNAME = ?, EMAILADDRESS = ?
										WHERE ID = ?");
				$update->bind_param("sisssi",
								    $P['username'],
									$P['usertype'],
									$P['firstname'],
									$P['lastname'],
									$P['emailaddress'],
								    $userid);
				$update->execute();

				if($update->errno != 0)
				{
					$status = "failure";
					$message = "Error attempting to update user:<br>" . $update->error . "<br>Error code: " . $update->errno;
				}
				else
				{
					$status = "success";
					$message = "User added successfully!";
				}
				$update->close();
			}
		}

		$content['userid']		= $userid;
		$content['neworexist']	= $n_or_e;
		$content['firstname']	= $P['firstname'];
		$content['lastname']	= $P['lastname'];
		$content['username']	= $P['username'];

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function saveCourseInfo($P, $m)
	{
		$P = escapeArray($P, $m);
		$status  = "";
		$message = "";
		$n_or_e  = 1;
		$content = array();

		$courseid	= $P['courseid'];
		$locationid = $P['locationid'];
		if($courseid == 0)
		{
			//insert new user, but first check for existing username and email address
			$n_or_e = 0;

			$addrcheck = $m->prepare("SELECT ID FROM course_location WHERE ADDRESS1 = ?;");
			$addrcheck->bind_param("s", $P['address1']);
			$addrcheck->execute();
			$addrreslt = $addrcheck->get_result();
			$addrcheck->close();

			$namecheck = $m->prepare("SELECT ID FROM course WHERE COURSENAME = ?;");
			$namecheck->bind_param("s", $P['coursename']);
			$namecheck->execute();
			$namereslt = $namecheck->get_result();
			$namecheck->close();

			if($addrreslt->num_rows > 0 || $namereslt->num_rows > 0)
			{
				$status = "failure";
				$message = "";
				if($addrreslt->num_rows > 0){
					$message .= "<br>That address is already in the database";
				}
				if($namereslt->num_rows > 0){
					$message .= "<br>That course is already in the database";
				}
			}
			else
			{
				$insert = $m->prepare("INSERT INTO course_location(ADDRESS1, ADDRESS2, ADDRESS3, CITY, STATE, ZIPCODE)
										VALUES (?, ?, ?, ?, ?, ?)");
				$insert->bind_param("sssssi",
									$P['address1'],
									$P['address2'],
									$P['address3'],
									$P['city'],
									$P['state'],
									$P['zipcode']);
				$insert->execute();
				if($insert->errno != 0)
				{
					$status = "failure";
					$message = "Error attempting to add course address:<br>" . $insert->error . "<br>Error code: " . $insert->errno;
				}
				else
				{
					$locationid = $insert->insert_id;
					$message	= "New location added!";
					$status		= "success";
					$message	= "Location added successfully!";
					$insert->close();

					$insert = $m->prepare("INSERT INTO course(COURSENAME, LOCATIONID, TYPEID, NUMHOLES)
											VALUES (?, ?, ?, ?)");
					$insert->bind_param("siii",
										$P['coursename'],
										$locationid,
										$P['coursestyle'],
										$P['courselength']);
					$insert->execute();
					if($insert->errno != 0)
					{
						$status = "failure";
						$message = "Error attempting to add course:<br>" . $insert->error . "<br>Error code: " . $insert->errno;
					}
					else
					{
						$courseid	= $insert->insert_id;
						$message	= "New course added!";
						$status		= "success";
						$message	= "Course added successfully!";
					}
				}
				$insert->close();
			}
		}
		else
		{
			//update existing course
			$update = $m->prepare("UPDATE course_location SET ADDRESS1 = ?, ADDRESS2 = ?, ADDRESS3 = ?, CITY = ?, STATE = ?, ZIPCODE = ?
									WHERE ID = ?");
			$update->bind_param("sssssii",
								$P['address1'],
								$P['address2'],
								$P['address3'],
								$P['city'],
								$P['state'],
								$P['zipcode'],
								$P['locationid']);
			$update->execute();

			if($update->errno != 0)
			{
				$status = "failure";
				$message = "Error attempting to update location:<br>" . $update->error . "<br>Error code: " . $update->errno;
			}
			else
			{
				//update existing course
				$status = "success";
				$message = "Location updated successfully!";
				$update->close();

				$update = $m->prepare("UPDATE course SET COURSENAME = ?, TYPEID = ?, NUMHOLES = ?
										WHERE ID = ?");
				$update->bind_param("siii",
									$P['coursename'],
									$P['coursestyle'],
									$P['courselength'],
									$P['courseid']);
				$update->execute();

				if($update->errno != 0)
				{
					$status = "failure";
					$message = "Error attempting to update course:<br>" . $update->error . "<br>Error code: " . $update->errno;
				}
				else
				{
					$status = "success";
					$message = "Course updated successfully!";
				}
			}
			$update->close();
		}

		$content['courseid']	= $courseid;
		$content['locationid']	= $locationid;
		$content['neworexist']	= $n_or_e;
		$content['coursename']	= $P['coursename'];

		$result = array(
				"status"  => $status,
				"message" => $message,
				"content" => $content
		);

		echo json_encode($result);
	}


	function escapeArray($req, $mysqli)
	{
		//recursive function called on the REQ object sent back by an AJAX call
		//it accounts for nested arrays/hashes (these were being nulled out previously)
		foreach($req as $key => $val)
		{
			if(gettype($val) == "array") {
				escapeArray($val);
			}
			else {
				$val = urldecode($val);
				$val = $mysqli->real_escape_string($val);
				$req[$key] = $val;
			}
		}
		return $req;
	}

?>
