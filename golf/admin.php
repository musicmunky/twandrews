<?php
	define('LIBRARY_CHECK',true);
	require 'php/golflib.php';

	if(isset($_GET['logout']))
	{
		session_destroy();
		$_SESSION = array();
		header("Location: login.php");
		exit;
	}

	if(!isset($_SESSION))
	{
		session_name('andrewsgolf');
		session_start();
	}

	if(!isset($_SESSION['username']) || !isset($_SESSION['userid']) || empty($_SESSION['username']) || empty($_SESSION['userid']))
	{
		header('Location: login.php');
	}
	elseif(!isset($_SESSION['usertype']) || empty($_SESSION['usertype']) || $_SESSION['usertype'] != "ADMIN")
	{
		header('Location: index.php');
	}

	$adminlink = "";
	if($_SESSION['usertype'] == "ADMIN")
	{
		$adminlink = "<li><a href='admin.php'>Admin</a></li>";
	}

	$users = $mysqli->query("SELECT FIRSTNAME, LASTNAME, ID FROM golfusers ORDER BY LASTNAME;");
	$userhtml = "";
	if($users)
	{
		while($row = $users->fetch_assoc())
		{
			$userhtml .= "<a href='javascript:void(0)' onclick='loadUserForm(\"" . $row['ID'] . "\")' class='collection-item'>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</a>";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<?php include("includes/headtag.html"); ?>
	</head>
	<body>
		<?php include("includes/header.html"); ?>
		<div class="container centercontent">
			<div class="row">
				<div class="col s12">
					<ul class="tabs">
						<li class="tab col s3"><a class="active" href="#courses">Courses</a></li>
						<li class="tab col s3"><a href="#users">Users</a></li>
					</ul>
				</div>
				<div id="courses" class="col s12">
					<div id="courses-content">
						<div id="course-list-wrapper"
							 style="height:200px;overflow-y:auto;width:100%;margin-top:30px;padding:0 5px;border:1px solid #CCC;background-color:#EEE;">
							<div id="course-list" class="collection">
								<?php echo $userhtml; ?>
							</div>
						</div>
						<div class="row">
							<form class="col s12">
								<div class="row">
									<div class="input-field col s6">
										<input id="courseid" type="hidden" value="" />
										<input id="coursename" type="text" class="validate">
										<label id="lblcoursename" for="coursename">Course Name</label>
									</div>
									<div class="input-field col s6">
										<select>
											<option value="" disabled selected>Select Course Length...</option>
											<option value="9">9 Holes</option>
											<option value="18">18 Holes</option>
											<option value="36">36 Holes</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<input id="address1" type="text" class="validate">
										<label id="lbladdress1" for="address1">Address 1</label>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<input id="address2" type="text" class="validate">
										<label id="lbladdress2" for="address2">Address 2</label>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<input id="address3" type="text" class="validate">
										<label id="lbladdress3" for="address3">Address 3</label>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s4">
										<input id="city" type="text" class="validate">
										<label id="lblcity" for="city">City</label>
									</div>
									<div class="input-field col s4">
										<input id="state" type="text" class="validate">
										<label id="lblstate" for="state">State</label>
									</div>
									<div class="input-field col s4">
										<input id="zipcode" type="text" class="validate">
										<label id="lblzipcode" for="zipcode">Zip Code</label>
									</div>
								</div>
								<div class="row">
									<div class="col s6">
										<input type="button" style="float:left;" onClick="updateUser()" class="btn" value="Update User" />
									</div>
									<div class="col s6">
										<input type="button" style="float:right;" onClick="clearUserForm()" class="btn" value="Clear Form" />
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div id="users" class="col s12">
					<div id="users-content">
						<div id="user-list-wrapper" style="height:200px;overflow-y:auto;width:100%;margin-top:30px;padding:0 5px;border:1px solid #CCC;background-color:#EEE;">
							<div id="user-list" class="collection">
								<?php echo $userhtml; ?>
							</div>
						</div>
						<div class="row">
							<form class="col s12">
								<div class="row">
									<div class="input-field col s6">
										<input id="golfid" type="hidden" value="" />
										<input id="firstname" type="text" class="validate">
										<label id="lblfirstname" for="firstname">First Name</label>
									</div>
									<div class="input-field col s6">
										<input id="lastname" type="text" class="validate">
										<label id="lbllastname" for="lastname">Last Name</label>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<input id="username" type="text" class="validate">
										<label id="lblusername" for="username">Username</label>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<input id="email" type="email" class="validate">
										<label id="lblemail" for="email">Email</label>
									</div>
								</div>
								<div class="row">
									<div class="col s6">
										<input type="button" style="float:left;" onClick="updateUser()" class="btn" value="Update User" />
									</div>
									<div class="col s6">
										<input type="button" style="float:right;" onClick="clearUserForm()" class="btn" value="Clear Form" />
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
