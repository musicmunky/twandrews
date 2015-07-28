$( document ).ready(function() {

	//IE doesn't like Google fonts...apparently it's Google's fault
	//at the moment, but whatever...load Web Safe font for IE users
	var gbr = FUSION.get.browser();
	if(gbr.browser && gbr.browser == "IE")
	{
		document.body.style.fontFamily = "'Trebuchet MS', Helvetica, sans-serif";
	}

	var locstr = "800 Occidental Ave S, Seattle, WA 98134"; //default address provided by Code Challenge
	var range  = 1; //range in miles

	FUSION.get.node("footerlocation").innerHTML = locstr;
	runSearch(locstr, range);

});


function runSearch(s, r)
{
	var str = s || "";
	var rng = r || "";

	if(FUSION.lib.isBlank(str)){
		var atxt  = "<p style='margin:15px 5px 5px;text-align:center;font-size:16px;font-weight:bold;color:#D00;'>";
			atxt += "Invalid location string - please refresh the page and try again";
			atxt += "</p>";
		FUSION.lib.alert(atxt);
		return false;
	}

	if(FUSION.lib.isBlank(rng)){
		var atxt  = "<p style='margin:15px 5px 5px;text-align:center;font-size:16px;font-weight:bold;color:#D00;'>";
			atxt += "Invalid range - please refresh the page and try again";
			atxt += "</p>";
		FUSION.lib.alert(atxt);
		return false;
	}

	str = str.replace(/\s/ig, "+");
	rng = rng * 1609.34; //convert the range into meters
	var info = {
		"type": "GET",
		"path": "php/challengelib.php",
		"data": {
			"method": 		"getSocrataInfo",
			"libcheck": 	true,
			"searchstring": str,
			"range":		rng
		},
		"func": processCrimeSearch
	};
	FUSION.lib.ajaxCall(info);
}


function processCrimeSearch(h)
{
	var hash = h || {};
}

