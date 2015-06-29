jQuery(document).ready(function() {
	jQuery('select').material_select();
});


function updateUser()
{
	alert("updateUser called");
}


function updateCourse()
{
	alert("updateCourse called");
}


function loadCourseForm(i)
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
		"func": loadCourseResponse
	};
	FUSION.lib.ajaxCall(info);
}


function loadUserForm(i)
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
		"func": loadUserResponse
	};
	FUSION.lib.ajaxCall(info);
}


function loadUserResponse(h)
{
	var hash = h || {};
	FUSION.get.node("firstname").value	= hash['FIRSTNAME'];
	FUSION.get.node("lastname").value	= hash['LASTNAME'];
	FUSION.get.node("username").value	= hash['USERNAME'];
	FUSION.get.node("email").value		= hash['EMAILADD'];
	FUSION.get.node("golfid").value		= hash['GOLFID'];

	FUSION.get.node("firstname").className	= "validate valid";
	FUSION.get.node("lastname").className	= "validate valid";
	FUSION.get.node("username").className	= "validate valid";
	FUSION.get.node("email").className		= "validate valid";

	FUSION.get.node("lblfirstname").className	= "active";
	FUSION.get.node("lbllastname").className	= "active";
	FUSION.get.node("lblusername").className	= "active";
	FUSION.get.node("lblemail").className		= "active";
}


function clearUserForm()
{
	FUSION.get.node("firstname").value	= "";
	FUSION.get.node("lastname").value	= "";
	FUSION.get.node("username").value	= "";
	FUSION.get.node("email").value		= "";
	FUSION.get.node("golfid").value		= "";

	FUSION.get.node("firstname").className	= "validate";
	FUSION.get.node("lastname").className	= "validate";
	FUSION.get.node("username").className	= "validate";
	FUSION.get.node("email").className		= "validate";

	FUSION.get.node("lblfirstname").className	= "";
	FUSION.get.node("lbllastname").className	= "";
	FUSION.get.node("lblusername").className	= "";
	FUSION.get.node("lblemail").className		= "";
}

