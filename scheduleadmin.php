<?php 
	define('LIBRARY_CHECK',true);
	require 'php/library.php';

	if(isset($_GET['logout']))
	{
		session_destroy();
		$_SESSION = array();
		header("Location: login.php");
		exit;
	}

	if(!isset($_SESSION))
	{
		session_name('andrewscal');
		session_start();
	}

	$html = "";
	if(!isset($_SESSION['username']) || !isset($_SESSION['userid']))
	{
		header('Location: login.php');
	}
	else
	{
		$userquery = mysql_query("SELECT * FROM eventadmin ORDER BY ID ASC;");
		$html = "<table style='border-collapse:collapse;width:100%;'>
					<tr class='headerrow usertablerow'>
						<td class='usertbltd'>username</td>
						<td class='usertbltd'>first name</td>
						<td class='usertbltd'>last name</td>
						<td class='usertbltd'>email</td>
						<td class='usertbltd'></td>
						<td class='usertbltd'></td>
					</tr>";
		$count = 0;
		while($row = mysql_fetch_assoc($userquery))
		{
			$count++;
			$altclass = ($count % 2) ? "" : "altrow";
			$btnhtml  = ($row['ID'] == $_SESSION['userid']) ? "<input type='button' class='updateuserbtn' value='Update' onclick='showUpdateUserForm(" . $row['ID'] . ")' />" : "";
			$passhtml = ($row['ID'] == $_SESSION['userid']) ? "<input type='button' class='passbtn' value='Change Password' onclick='showUpdatePasswordForm(" . $row['ID'] . ")' />" : "";
			$html .= "  <input type='hidden' id='unamehdn" . $row['ID'] . "' value='" . $row['USER'] . "' />
						<input type='hidden' id='firsthdn" . $row['ID'] . "' value='" . $row['FIRST'] . "' />
						<input type='hidden' id='lasthdn" . $row['ID'] . "' value='" . $row['LAST'] . "' />
						<input type='hidden' id='emailhdn" . $row['ID'] . "' value='" . $row['EMAIL'] . "' />
						<tr class='usertablerow tablerow " . $altclass . "'>
							<td class='usertbltd' id='tduname" . $row['ID'] . "'>" . $row['USER'] . "</td>
							<td class='usertbltd' id='tdfname" . $row['ID'] . "'>" . $row['FIRST'] . "</td>
							<td class='usertbltd' id='tdlname" . $row['ID'] . "'>" . $row['LAST'] . "</td>
							<td class='usertbltd' id='tdemail" . $row['ID'] . "'>" . $row['EMAIL'] . "</td>
							<td class='usertbltd'>" . $btnhtml . "</td>
							<td class='usertbltd'>" . $passhtml . "</td></tr>";
		}
		$html .= "</table>";
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>The Andrews Calendar</title>
		<meta charset='utf-8' />
		<link rel="icon" type="image/png" href="images/calicon.png" />
		<link rel="stylesheet" href="css/jquery-ui-1.10.4.custom.css" type="text/css" media="screen" charset="utf-8"></link>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/schedule.js"></script>
		<style>
			body {
				margin: 0;
				padding: 0;
				font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
				font-size: 14px;
			}

			#calendar {
				width: 900px;
				margin: 40px auto;
			}
		</style>
	</head>
	<body>
		<div class="header">
			Calendar Administration
			<span style="cursor:pointer;float:right;font-family:Monaco,Consolas,'Lucida Console',monospace;font-size:16px;margin-top:12px;">
				<a id="logout" name="logout" style="text-decoration:none;" href="scheduleadmin.php?logout">logout</a>
			</span>
		</div>
		<div style="margin-top:100px;margin-left:50px;margin-bottom:50px;width:1000px;float:left;">
			<div id="admintabs">
				<ul>
					<li><a href="#newtab">Add New Event</a></li>
					<li><a href="#edittab">Edit Event</a></li>
					<li><a href="#usertab">Add/Update Users</a></li>
				</ul>
				<div id="newtab">
					<div style="width:100%;margin-top:20px;">
						<form id="newevenform">
							<div class="fielddivs">
								<div class="labeldivs">Event Title:<span style="color:red;">*</span></div>
								<input name="eventtitle" type="text" id="eventtitle" class="fieldinputs" value="" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Event Type:<span style="color:red;">*</span></div>
								<select id="eventtype" class="fieldinputs">
									<option value=""></option>
									<option value="Family">Family</option>
									<option value="Friends">Friends</option>
									<option value="Holiday">Holiday</option>
									<option value="Kelly">Kelly</option>
									<option value="Payday">Payday</option>
									<option value="Trip">Trip</option>
									<option value="Work">Work</option>
								</select>
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Description: </div>
								<textarea name="eventdesc" id="eventdesc" style="width:500px;height:100px;resize:none;"></textarea>
							</div>
							<div class="fielddivs" id="postdatediv">
								<div class="labeldivs">Start Date:<span style="color:red;">*</span></div>
								<input type="text" id="startdate" name="startdate" style="float:left;" />
								<select id="starttime" name="eventtimes" style="float:left;margin-left:10px;margin-right:10px;">
									<option value=""></option>
									<option value="00:00">12:00</option>
									<option value="00:30">12:30</option>
									<option value="01:00">01:00</option>
									<option value="01:30">01:30</option>
									<option value="02:00">02:00</option>
									<option value="02:30">02:30</option>
									<option value="03:00">03:00</option>
									<option value="03:30">03:30</option>
									<option value="04:00">04:00</option>
									<option value="04:30">04:30</option>
									<option value="05:00">05:00</option>
									<option value="05:30">05:30</option>
									<option value="06:00">06:00</option>
									<option value="06:30">06:30</option>
									<option value="07:00">07:00</option>
									<option value="07:30">07:30</option>
									<option value="08:00">08:00</option>
									<option value="08:30">08:30</option>
									<option value="09:00">09:00</option>
									<option value="09:30">09:30</option>
									<option value="10:00">10:00</option>
									<option value="10:30">10:30</option>
									<option value="11:00">11:00</option>
									<option value="11:30">11:30</option>
								</select>
								<select id="startampm" style="float:left;" onChange="setOptionTimes(this, 'start', '')" name="eventtimes">
									<option value="am">am</option>
									<option value="pm">pm</option>
								</select>
								<div class="labeldivs" style="float:left;width:140px;">
									<input type="checkbox" id="eventallday" name="chkday"
										   onclick="eventChkBoxClick(this, '');" style="margin-left:10px;margin-right:10px;" />
									<span style="display:block;float:right;margin-top:-1px;">All Day Event</span>
								</div>
							</div>
							<div class="fielddivs" id="postdatediv">
								<div class="labeldivs">End Date:<span style="color:red;">*</span></div>
								<input type="text" id="enddate" name="enddate" style="float:left;" />
								<select id="endtime" name="eventtimes" style="float:left;margin-left:10px;margin-right:10px;">
									<option value=""></option>
									<option value="00:00">12:00</option>
									<option value="00:30">12:30</option>
									<option value="01:00">01:00</option>
									<option value="01:30">01:30</option>
									<option value="02:00">02:00</option>
									<option value="02:30">02:30</option>
									<option value="03:00">03:00</option>
									<option value="03:30">03:30</option>
									<option value="04:00">04:00</option>
									<option value="04:30">04:30</option>
									<option value="05:00">05:00</option>
									<option value="05:30">05:30</option>
									<option value="06:00">06:00</option>
									<option value="06:30">06:30</option>
									<option value="07:00">07:00</option>
									<option value="07:30">07:30</option>
									<option value="08:00">08:00</option>
									<option value="08:30">08:30</option>
									<option value="09:00">09:00</option>
									<option value="09:30">09:30</option>
									<option value="10:00">10:00</option>
									<option value="10:30">10:30</option>
									<option value="11:00">11:00</option>
									<option value="11:30">11:30</option>
								</select>
								<select id="endampm" name="eventtimes" onChange="setOptionTimes(this, 'end', '')" style="float:left;">
									<option value="am">am</option>
									<option value="pm">pm</option>
								</select>
								<div class="labeldivs" style="float:left;width:155px;">
									<input type="checkbox" id="eventmultiday" name="chkday"
										   onclick="eventChkBoxClick(this, '');" style="margin-left:10px;margin-right:10px;" />
									<span style="display:block;float:right;margin-top:-1px;">Multi-Day Event</span>
								</div>
							</div>
							<div class="fielddivs" style="text-align: center;">
								<input type="button" class="uploadbtn" value="Add Event" onclick="validateEvent('');" style="margin-right:40px;" />
								<input type="button" class="uploadbtn" value="Clear Form" onclick="clearNewEventForm(0, '');" />
							</div>
						</form>
					</div>
				</div>
				<div id="edittab">
					<div style="width:100%;margin-top:20px;">
							<div class="fielddivs">
								<div class="labeldivs">Filter by Title: </div>
								<input type="text" id="comicquery" name="comicquery" onkeyup="comicQuery();" class="fieldinputs" value="" />
							</div>
							<div class="scrollableContainer">
								<div class="scrollingArea">
									<table class="allcomics scrollable">
										<thead>
											<tr>
												<th class="comid"><div>ID</div></th>
												<th class="comtitle"><div>Title</div></th>
												<th class="comdate"><div>Post Date</div></th>
												<th class="comstat"><div>Post Status</div></th>
											</tr>
										</thead>
										<tbody id="comictablebody" name="comictablebody">
											<tr class='mainrow'>
												<td colspan='3'
													style="background-image:url(logos/loader.gif);height:30px;background-repeat:no-repeat;background-position:center;"></td>
											</tr>
											<?php //echo $comichtmltable; ?>
										</tbody>
									</table>
								</div>
							</div>
						<form id="updateeventform">
							<div class="fielddivs">
								<div class="labeldivs">Event Title:<span style="color:red;">*</span></div>
								<input type="text" id="editeventtitle" class="fieldinputs" value="" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Event Type:<span style="color:red;">*</span></div>
								<select id="editeventtype" class="fieldinputs">
									<option value=""></option>
									<option value="Family">Family</option>
									<option value="Friends">Friends</option>
									<option value="Holiday">Holiday</option>
									<option value="Kelly">Kelly</option>
									<option value="Payday">Payday</option>
									<option value="Trip">Trip</option>
									<option value="Work">Work</option>
								</select>
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Description: </div>
								<textarea id="editeventdesc" style="width:500px;height:100px;resize:none;"></textarea>
							</div>
							<div class="fielddivs" id="postdatediv">
								<div class="labeldivs">Start Date:<span style="color:red;">*</span></div>
								<input type="text" id="editstartdate" style="float:left;" />
								<select id="editstarttime" name="editeventtimes" style="float:left;margin-left:10px;margin-right:10px;">
									<option value=""></option>
									<option value="00:00">12:00</option>
									<option value="00:30">12:30</option>
									<option value="01:00">01:00</option>
									<option value="01:30">01:30</option>
									<option value="02:00">02:00</option>
									<option value="02:30">02:30</option>
									<option value="03:00">03:00</option>
									<option value="03:30">03:30</option>
									<option value="04:00">04:00</option>
									<option value="04:30">04:30</option>
									<option value="05:00">05:00</option>
									<option value="05:30">05:30</option>
									<option value="06:00">06:00</option>
									<option value="06:30">06:30</option>
									<option value="07:00">07:00</option>
									<option value="07:30">07:30</option>
									<option value="08:00">08:00</option>
									<option value="08:30">08:30</option>
									<option value="09:00">09:00</option>
									<option value="09:30">09:30</option>
									<option value="10:00">10:00</option>
									<option value="10:30">10:30</option>
									<option value="11:00">11:00</option>
									<option value="11:30">11:30</option>
								</select>
								<select id="editstartampm" style="float:left;"
										onChange="setOptionTimes(this, 'start', 'edit')" name="editeventtimes">
									<option value="am">am</option>
									<option value="pm">pm</option>
								</select>
								<div class="labeldivs" style="float:left;width:140px;">
									<input type="checkbox" id="editeventallday" name="editchkday"
										   onclick="eventChkBoxClick(this, 'edit');" style="margin-left:10px;margin-right:10px;" />
									<span style="display:block;float:right;margin-top:-1px;">All Day Event</span>
								</div>
							</div>
							<div class="fielddivs" id="postdatediv">
								<div class="labeldivs">End Date:<span style="color:red;">*</span></div>
								<input type="text" id="editenddate" style="float:left;" />
								<select id="editendtime" name="editeventtimes" style="float:left;margin-left:10px;margin-right:10px;">
									<option value=""></option>
									<option value="00:00">12:00</option>
									<option value="00:30">12:30</option>
									<option value="01:00">01:00</option>
									<option value="01:30">01:30</option>
									<option value="02:00">02:00</option>
									<option value="02:30">02:30</option>
									<option value="03:00">03:00</option>
									<option value="03:30">03:30</option>
									<option value="04:00">04:00</option>
									<option value="04:30">04:30</option>
									<option value="05:00">05:00</option>
									<option value="05:30">05:30</option>
									<option value="06:00">06:00</option>
									<option value="06:30">06:30</option>
									<option value="07:00">07:00</option>
									<option value="07:30">07:30</option>
									<option value="08:00">08:00</option>
									<option value="08:30">08:30</option>
									<option value="09:00">09:00</option>
									<option value="09:30">09:30</option>
									<option value="10:00">10:00</option>
									<option value="10:30">10:30</option>
									<option value="11:00">11:00</option>
									<option value="11:30">11:30</option>
								</select>
								<select id="editendampm" name="editeventtimes" onChange="setOptionTimes(this, 'end', 'edit')" style="float:left;">
									<option value="am">am</option>
									<option value="pm">pm</option>
								</select>
								<div class="labeldivs" style="float:left;width:155px;">
									<input type="checkbox" id="editeventmultiday" name="editchkday"
										   onclick="eventChkBoxClick(this, 'edit');" style="margin-left:10px;margin-right:10px;" />
									<span style="display:block;float:right;margin-top:-1px;">Multi-Day Event</span>
								</div>
							</div>
							<div class="fielddivs" style="text-align: center;">
								<input type="button" class="uploadbtn" value="Add Event"
									   onclick="validateEvent('edit');" style="margin-right:40px;" />
								<input type="button" class="uploadbtn" value="Clear Form" onclick="clearEventForm(0, 'edit');" />
							</div>
						</form>
					</div>
				</div>
				<div id="usertab">
					<div style="width:100%;margin-top:20px;">
						<div id="tablediv" style="width:100%;padding-top:20px;">
							<?php echo $html; ?>
						</div>
						<div class="fielddivs" style="text-align: center;padding-top:20px;">
							<input type="button" id="adduserbtn" name="adduserbtn"
								   class="uploadbtn" value="Add User" onclick="showCreateUserForm();" />
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="userform">
			<p class="validateTips"><i>All fields are required.</i></p>
			<form>
				<fieldset>
					<input type="hidden" id="userid" name="userid" value="" />
					<div class="fielddivs" style="padding-top:15px;">
						<div class="userdivs">Username: </div>
						<input name="uname" type="text" id="uname" class="userinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="userdivs">First Name: </div>
						<input name="ufname" type="text" id="ufname" class="userinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="userdivs">Last Name: </div>
						<input name="ulname" type="text" id="ulname" class="userinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="userdivs">Email: </div>
						<input name="uemail" type="text" id="uemail" class="userinputs" value="" />
					</div>
					<div class="fielddivs" style="text-align: center;">
						<input type="button" id="createuserbtn" class="createuserbtn" value="Create User" onclick="createUser()" />
						<input type="button" id="updateuserbtn" class="createuserbtn" value="Update User" onclick="updateUser()" />
						<input type="button" class="createuserbtn" value="Cancel" onclick="hideUserForm()" />
					</div>
				</fieldset>
			</form>
		</div>

		<div id="passwordform" title="Update Your Password">
			<p class="validateTips"><i>All fields are required.</i></p>
			<form>
				<fieldset>
					<div class="fielddivs" style="padding-top:15px;">
						<div class="passworddivs">Current Password: </div>
						<input name="currpass" type="password" id="currpass" class="passwordinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="passworddivs">New Password: </div>
						<input name="newpass" type="password" id="newpass" class="passwordinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="passworddivs">Repeat New Password: </div>
						<input name="repnewpass" type="password" id="repnewpass" class="passwordinputs" value="" />
					</div>
					<div class="fielddivs" style="text-align: center;">
						<input type="button" id="passwordbtn" class="createuserbtn" value="Update Password" onclick="updatePassword()" />
						<input type="button" class="createuserbtn" value="Cancel" onclick="hidePasswordForm()" />
					</div>
				</fieldset>
			</form>
		</div>
	</body>
</html>
