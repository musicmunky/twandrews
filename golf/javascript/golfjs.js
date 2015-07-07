var states = [	"Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado",
				"Connecticut", "Delaware", "District of Columbia", "Florida", "Georgia",
				"Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky",
				"Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota",
				"Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire",
				"New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota",
				"Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina",
				"South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington",
				"West Virginia", "Wisconsin", "Wyoming" ];

jQuery(document).ready(function() {

	jQuery('select').material_select();
	jQuery( "#state" ).autocomplete({
		source: states,
		messages: {
			noResults: '',
			results: function() {}
		}
	});

});


function checkState(s)
{
	if(!FUSION.lib.isBlank(s) && states.indexOf(s) < 0) {

		FUSION.lib.alert("<p>Please enter a valid State name</p>");
		FUSION.lib.focus("state");
	}
}


function adminUpdateUser()
{
	try {
		var userid = FUSION.get.node("golfid").value;
		var frstnm = FUSION.get.node("firstname").value;
		var lastnm = FUSION.get.node("lastname").value;
		var usernm = FUSION.get.node("username").value;
		var usertp = FUSION.get.node("usertype").value;
		var typenm = FUSION.get.node("select-input-usertype").value;
		var emladd = FUSION.get.node("email").value;
		var errstr = "";
		var errcnt = 80;

		if(FUSION.lib.isBlank(userid)) {
			FUSION.lib.alert("<p>Please make sure the User ID is not blank - refresh the page and try again</p>");
			return false;
		}
		if(FUSION.lib.isBlank(frstnm)) {
			errstr += "<br>First Name";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(lastnm)) {
			errstr += "<br>Last Name";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(usernm)) {
			errstr += "<br>User Name";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(usertp) || typenm == "Select User Type...") {
			errstr += "<br>User Type";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(emladd)) {
			errstr += "<br>Email Address";
			errcnt += 20;
		}
		if(!FUSION.lib.isBlank(errstr)) {
			FUSION.lib.alert({"message":"Please make sure the following fields are not blank:" + errstr,
							  "color":"#F00",
							  "height": errcnt,
							  "text-align":"center"});
			return false;
		}

		var info = {
			"type": "POST",
			"path": "php/golflib.php",
			"data": {
				"method":	"saveUserInfo",
				"libcheck":	true,
				"userid": userid,
				"firstname": frstnm,
				"lastname": lastnm,
				"username": usernm,
				"usertype": usertp,
				"typename": typenm,
				"emailaddress": emladd
			},
			"func": adminUpdateUserResponse
		};
		FUSION.lib.ajaxCall(info);

	}
	catch(err)
	{
		FUSION.lib.alert("ERROR: " + err);
		return false;
	}
}


function adminUpdateUserResponse(h)
{
	var hash = h || {};
	adminClearUserForm();
	if(hash['neworexist'] == 0) //new user
	{
		var lnkdiv	= FUSION.get.node("user-list");
		var lnk		= lnkdiv.innerHTML;
		lnk			+= "<a href='javascript:void(0)' id='user-list-link-" + hash['userid'];
		lnk			+= "' onclick='adminLoadUserForm(\"" + hash['userid'] + "\")' class='collection-item'>";
		lnk			+= hash['firstname'] + " " + hash['lastname'] + "</a>";
		var html	= jQuery.parseHTML(lnk);
		jQuery("#user-list").html(html);
	}
	else //existing user
	{
		FUSION.get.node("user-list-link-" + hash['userid']).innerHTML = hash['firstname'] + " " + hash['lastname'];
	}
	var sorted = jQuery('#user-list a').sort(function(a, b) {return a.innerHTML > b.innerHTML});
	jQuery('#user-list').html('');
	sorted.each(function(i, a) {jQuery('#user-list').append(a)});
}


function adminUpdateCourse()
{
	try {
		var crsid = FUSION.get.node("courseid").value;
		var locid = FUSION.get.node("locationid").value;
		var crsnm = FUSION.get.node("coursename").value;
		var crsln = FUSION.get.node("courselength").value;
		var style = FUSION.get.node("coursestyle").value;
		var adrs1 = FUSION.get.node("address1").value;
		var adrs2 = FUSION.get.node("address2").value;
		var adrs3 = FUSION.get.node("address3").value;
		var ccity = FUSION.get.node("city").value;
		var state = FUSION.get.node("state").value;
		var zipcd = FUSION.get.node("zipcode").value;
		var errstr = "";
		var errcnt = 80;

		if(FUSION.lib.isBlank(crsid)) {
			FUSION.lib.alert("<p>Please make sure the Course ID is not blank - refresh the page and try again</p>");
			return false;
		}
		if(FUSION.lib.isBlank(crsnm)) {
			errstr += "<br>Course Name";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(crsln)) {
			errstr += "<br>Course Length";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(style)) {
			errstr += "<br>Course Style";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(adrs1)) {
			errstr += "<br>Course Address";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(adrs2) && !FUSION.lib.isBlank(adrs3)) {
			errstr += "<br>Move address 3 entry to address 2";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(ccity)) {
			errstr += "<br>City";
			errcnt += 20;
		}
		if(FUSION.lib.isBlank(state)) {
			errstr += "<br>State";
			errcnt += 20;
		}
		if(!FUSION.lib.isBlank(errstr)) {
			FUSION.lib.alert({"message":"Please make sure the following fields are not blank:" + errstr,
							  "color":"#F00",
							  "height": errcnt,
							  "text-align":"center"});
			return false;
		}

		var info = {
			"type": "POST",
			"path": "php/golflib.php",
			"data": {
				"method":		"saveCourseInfo",
				"libcheck":		true,
				"courseid":		crsid,
				"locationid":	locid,
				"coursename":	crsnm,
				"courselength":	crsln,
				"coursestyle":	style,
				"address1":		adrs1,
				"address2":		adrs2,
				"address3":		adrs3,
				"city":			ccity,
				"state":		state,
				"zipcode":		zipcd,
			},
			"func": adminUpdateCourseResponse
		};
		FUSION.lib.ajaxCall(info);

	}
	catch(err)
	{
		FUSION.lib.alert("ERROR: " + err);
		return false;
	}
}


function adminUpdateCourseResponse(h)
{
	var hash = h || {};
	adminClearCourseForm();
	if(hash['neworexist'] == 0) //new course
	{
		var lnkdiv	= FUSION.get.node("course-list");
		var lnk		= lnkdiv.innerHTML;
		lnk			+= "<a href='javascript:void(0)' id='course-list-link-" + hash['courseid'];
		lnk			+= "' onclick='adminLoadCourseForm(" + hash['courseid'] + ", " + hash['locationid'] + ")' class='collection-item'>";
		lnk			+= hash['coursename'] + "</a>";
		var html	= jQuery.parseHTML(lnk);
		jQuery("#course-list").html(html);
	}
	else //existing course
	{
		FUSION.get.node("course-list-link-" + hash['courseid']).innerHTML = hash['coursename'];
	}
	var sorted = jQuery('#course-list a').sort(function(a, b) {return a.innerHTML > b.innerHTML});
	jQuery('#course-list').html('');
	sorted.each(function(i, a) {jQuery('#course-list').append(a)});
}


function adminLoadCourseForm(ci, li)
{
	var id = ci || 0;
	var lc = li || 0;
	if(FUSION.lib.isBlank(id)){
		var atxt  = "<p style='margin:15px 5px 5px;text-align:center;font-size:16px;font-weight:bold;color:#D00;'>";
			atxt += "Invalid course id - please refresh the page and try again!";
			atxt += "</p>";
		FUSION.lib.alert(atxt);
		return false;
	}

	var info = {
		"type": "GET",
		"path": "php/golflib.php",
		"data": {
			"method":	  "getCourseInfo",
			"libcheck":   true,
			"courseid":   id,
			"locationid": lc
		},
		"func": adminLoadCourseResponse
	};
	FUSION.lib.ajaxCall(info);
}


function adminLoadUserForm(i)
{
	var id = i || "";
	if(FUSION.lib.isBlank(id)){
		var atxt  = "<p style='margin:15px 5px 5px;text-align:center;font-size:16px;font-weight:bold;color:#D00;'>";
			atxt += "Invalid user id - please refresh the page and try again!";
			atxt += "</p>";
		FUSION.lib.alert(atxt);
		return false;
	}

	var info = {
		"type": "GET",
		"path": "php/golflib.php",
		"data": {
			"method": 	"getUserInfo",
			"libcheck": true,
			"golfid":	id,
		},
		"func": adminLoadUserResponse
	};
	FUSION.lib.ajaxCall(info);
}


function adminLoadCourseResponse(h)
{
	var hash = h || {};
	var crs = hash['course'];
	var loc = hash['location'];
	FUSION.get.node("courseid").value	= crs['COURSEID'];
	FUSION.get.node("locationid").value	= loc['LOCATIONID'];
	FUSION.get.node("coursename").value	= crs['COURSENAME'];
	FUSION.get.node("address1").value	= loc['ADDRESS1'];
	FUSION.get.node("address2").value	= loc['ADDRESS2'];
	FUSION.get.node("address3").value	= loc['ADDRESS3'];
	FUSION.get.node("city").value		= loc['CITY'];
	FUSION.get.node("state").value		= loc['STATE'];
	FUSION.get.node("zipcode").value	= loc['ZIPCODE'];

	FUSION.set.selectedText("courselength", crs['COURSELENGTH'] + " Holes");
	FUSION.set.selectedText("coursestyle", crs['TYPENAME']);
	FUSION.get.node("select-input-coursestyle").value = crs['TYPENAME'];
	FUSION.get.node("select-input-courselength").value = crs['COURSELENGTH'] + " Holes";

	FUSION.get.node("coursename").className	= "validate valid";
	FUSION.get.node("address1").className	= "validate valid";
	FUSION.get.node("city").className		= "validate valid";
	FUSION.get.node("state").className		= "validate valid";
	FUSION.get.node("zipcode").className	= "validate valid";

	FUSION.get.node("lblcoursename").className	= "active";
	FUSION.get.node("lbladdress1").className	= "active";
	FUSION.get.node("lblcity").className		= "active";
	FUSION.get.node("lblstate").className		= "active";
	FUSION.get.node("lblzipcode").className		= "active";

	if(!FUSION.lib.isBlank(loc['ADDRESS2']))
	{
		FUSION.get.node("address2").className	 = "validate valid";
		FUSION.get.node("lbladdress2").className = "active";
	}
	if(!FUSION.lib.isBlank(loc['ADDRESS3']))
	{
		FUSION.get.node("address3").className	 = "validate valid";
		FUSION.get.node("lbladdress3").className = "active";
	}
}


function adminLoadUserResponse(h)
{
	var hash = h || {};
	FUSION.get.node("firstname").value	= hash['FIRSTNAME'];
	FUSION.get.node("lastname").value	= hash['LASTNAME'];
	FUSION.get.node("username").value	= hash['USERNAME'];
	FUSION.get.node("email").value		= hash['EMAILADD'];
	FUSION.get.node("golfid").value		= hash['GOLFID'];
	FUSION.set.selectedText("usertype", hash['TYPENAME']);
	FUSION.get.node("select-input-usertype").value = hash['TYPENAME'];

	FUSION.get.node("firstname").className	= "validate valid";
	FUSION.get.node("lastname").className	= "validate valid";
	FUSION.get.node("username").className	= "validate valid";
	FUSION.get.node("email").className		= "validate valid";

	FUSION.get.node("lblfirstname").className	= "active";
	FUSION.get.node("lbllastname").className	= "active";
	FUSION.get.node("lblusername").className	= "active";
	FUSION.get.node("lblemail").className		= "active";
}


function adminClearUserForm()
{
	FUSION.get.node("firstname").value	= "";
	FUSION.get.node("lastname").value	= "";
	FUSION.get.node("username").value	= "";
	FUSION.get.node("email").value		= "";
	FUSION.get.node("golfid").value		= 0;
	clearMaterialSelect("usertype", "Select User Type...");

	FUSION.get.node("firstname").className	= "validate";
	FUSION.get.node("lastname").className	= "validate";
	FUSION.get.node("username").className	= "validate";
	FUSION.get.node("email").className		= "validate";

	FUSION.get.node("lblfirstname").className	= "";
	FUSION.get.node("lbllastname").className	= "";
	FUSION.get.node("lblusername").className	= "";
	FUSION.get.node("lblemail").className		= "";
}


function adminClearCourseForm()
{
	FUSION.get.node("courseid").value	= 0;
	FUSION.get.node("locationid").value	= 0;
	FUSION.get.node("coursename").value	= "";
	FUSION.get.node("address1").value	= "";
	FUSION.get.node("address2").value	= "";
	FUSION.get.node("address3").value	= "";
	FUSION.get.node("city").value		= "";
	FUSION.get.node("state").value		= "";
	FUSION.get.node("zipcode").value	= "";

	FUSION.get.node("coursename").className	= "validate";
	FUSION.get.node("address1").className	= "validate";
	FUSION.get.node("address2").className	= "validate";
	FUSION.get.node("address3").className	= "validate";
	FUSION.get.node("city").className		= "validate";
	FUSION.get.node("state").className		= "validate";
	FUSION.get.node("zipcode").className	= "validate";

	FUSION.get.node("lblcoursename").className	= "";
	FUSION.get.node("lbladdress1").className	= "";
	FUSION.get.node("lbladdress2").className	= "";
	FUSION.get.node("lbladdress3").className	= "";
	FUSION.get.node("lblcity").className		= "";
	FUSION.get.node("lblstate").className		= "";
	FUSION.get.node("lblzipcode").className		= "";

	clearMaterialSelect("courselength", "Select Course Length...");
	clearMaterialSelect("coursestyle", "Select Course Style...");
}


function clearMaterialSelect(el, t)
{
	FUSION.get.node(el).selectedIndex = -1;
	FUSION.get.node("select-input-" + el).value = t;
}