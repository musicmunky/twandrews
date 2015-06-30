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
	{}
}


function adminUpdateUserResponse(h)
{
	var hash = h || {};
}


function adminUpdateCourse()
{
	try {
		var crsid = FUSION.get.node("courseid").value;
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


	}
	catch(err)
	{}
}


function adminLoadCourseForm(i)
{
	var id = i || "";
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
			"method":	"getCourseInfo",
			"libcheck":	true,
			"courseid": id,
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
	FUSION.get.node("golfid").value		= "";
	FUSION.get.node("usertype").selectedIndex = -1;
	FUSION.get.node("select-input-usertype").value = "Select User Type...";

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
{}