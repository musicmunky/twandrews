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

	$userhtml	= "";
	$users		= $mysqli->query("SELECT FIRSTNAME, LASTNAME, ID FROM golfusers ORDER BY LASTNAME;");
	if($users)
	{
		while($row = $users->fetch_assoc())
		{
			$userhtml .= "<a href='javascript:void(0)' onclick='loadUserForm(\"" . $row['ID'] . "\")' class='collection-item'>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</a>";
		}
	}

	$coursehtml	= "";
	$courses	= $mysqli->query("SELECT ID, COURSENAME FROM course ORDER BY ID ASC;");
	if($courses)
	{
		while($row = $courses->fetch_assoc())
		{
			$coursehtml .= "<a href='javascript:void(0)' onclick='loadCourseForm(\"" . $row['ID'] . "\")' class='collection-item'>" . $row['COURSENAME'] . "</a>";
		}
	}

	$ctypehtml 	= "<option value='' disabled selected>Select Course Style...</option>";
	$ctypes 	= $mysqli->query("SELECT * FROM course_type ORDER BY ID ASC;");
	if($ctypes)
	{
		while($row = $ctypes->fetch_assoc())
		{
			$ctypehtml .= "<option value='" . $row['ID'] . "'>" . ucfirst($row['TYPENAME']) . "</option>";
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
								<?php echo $coursehtml; ?>
							</div>
						</div>
						<div class="row">
							<form class="col s12">
								<div class="row">
									<div class="input-field col s4">
										<input id="courseid" type="hidden" value="0" />
										<input id="coursename" type="text" class="validate">
										<label id="lblcoursename" for="coursename">Course Name</label>
									</div>
									<div class="input-field col s4">
										<select id="courselength">
											<option value="" disabled selected>Select Course Length...</option>
											<option value="9">9 Holes</option>
											<option value="18">18 Holes</option>
											<option value="36">36 Holes</option>
										</select>
									</div>
									<div class="input-field col s4">
										<select id="coursestyle">
											<?php echo $ctypehtml; ?>
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
										<select id="state">
											<option value="" disabled selected>Select a State...</option>
											<option value="AL">Alabama</option>
											<option value="AK">Alaska</option>
											<option value="AZ">Arizona</option>
											<option value="AR">Arkansas</option>
											<option value="CA">California</option>
											<option value="CO">Colorado</option>
											<option value="CT">Connecticut</option>
											<option value="DE">Delaware</option>
											<option value="DC">District Of Columbia</option>
											<option value="FL">Florida</option>
											<option value="GA">Georgia</option>
											<option value="HI">Hawaii</option>
											<option value="ID">Idaho</option>
											<option value="IL">Illinois</option>
											<option value="IN">Indiana</option>
											<option value="IA">Iowa</option>
											<option value="KS">Kansas</option>
											<option value="KY">Kentucky</option>
											<option value="LA">Louisiana</option>
											<option value="ME">Maine</option>
											<option value="MD">Maryland</option>
											<option value="MA">Massachusetts</option>
											<option value="MI">Michigan</option>
											<option value="MN">Minnesota</option>
											<option value="MS">Mississippi</option>
											<option value="MO">Missouri</option>
											<option value="MT">Montana</option>
											<option value="NE">Nebraska</option>
											<option value="NV">Nevada</option>
											<option value="NH">New Hampshire</option>
											<option value="NJ">New Jersey</option>
											<option value="NM">New Mexico</option>
											<option value="NY">New York</option>
											<option value="NC">North Carolina</option>
											<option value="ND">North Dakota</option>
											<option value="OH">Ohio</option>
											<option value="OK">Oklahoma</option>
											<option value="OR">Oregon</option>
											<option value="PA">Pennsylvania</option>
											<option value="RI">Rhode Island</option>
											<option value="SC">South Carolina</option>
											<option value="SD">South Dakota</option>
											<option value="TN">Tennessee</option>
											<option value="TX">Texas</option>
											<option value="UT">Utah</option>
											<option value="VT">Vermont</option>
											<option value="VA">Virginia</option>
											<option value="WA">Washington</option>
											<option value="WV">West Virginia</option>
											<option value="WI">Wisconsin</option>
											<option value="WY">Wyoming</option>
										</select>
									</div>
									<div class="input-field col s4">
										<input id="zipcode" type="text" class="validate">
										<label id="lblzipcode" for="zipcode">Zip Code</label>
									</div>
								</div>
								<div class="row">
									<div class="col s6">
										<input type="button" style="float:left;" onClick="updateCourse()" class="btn" value="Update Course" />
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
							<form id="golfuserform" class="col s12">
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
