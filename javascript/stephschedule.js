//start with the jQuery stuff...
jQuery(function() {

	jQuery("#dateclassifier").dialog({
		autoOpen: false,
		height: 225,
		width: 325,
		modal: true,
		dialogClass: 'dc-dialog',
		create: function(){
			jQuery(this).closest(".ui-dialog").find(".ui-dialog-title").addClass("dc-dialog-title");
		}
	});

	jQuery("#setfirstwork").dialog({
		autoOpen: false,
		height: 175,
		width: 325,
		modal: true,
		dialogClass: 'dc-dialog',
		create: function(){
			jQuery(this).closest(".ui-dialog").find(".ui-dialog-title").addClass("dc-dialog-title");
		}
	});

	jQuery('#legendmenu').click(function() {
		jQuery(".container").toggleClass("container-open");
		jQuery("#legud").toggleClass("glyphicon-menu-down glyphicon-menu-up");
	});

});


//global variable listing the various types
var types = ['holiday', 'kelly', 'payday'];

function getPreviousYear()
{
	try {
		var cy  = parseInt(FUSION.get.node("curyear").value);
		var my  = parseInt(FUSION.get.node("minyear").value);
		var dsp = ((cy - 1) == my) ? "none" : "block";
		FUSION.get.node("prevspan").style.setProperty("display", dsp, "important");
		FUSION.get.node("nextspan").style.setProperty("display", "block", "important");
		FUSION.get.node("curyear").value = cy - 1;
		refreshCalendar();
	}
	catch(err) {
		FUSION.error.logError(err);
		FUSION.error.showError(err);
	}
}


function getNextYear()
{
	try {
		var cy  = parseInt(FUSION.get.node("curyear").value);
		var xy  = parseInt(FUSION.get.node("maxyear").value);
		var dsp = ((cy + 1) == xy) ? "none" : "block";
		FUSION.get.node("prevspan").style.setProperty("display", "block", "important");
		FUSION.get.node("nextspan").style.setProperty("display", dsp, "important");
		FUSION.get.node("curyear").value = cy + 1;
		refreshCalendar();
	}
	catch(err) {
		FUSION.error.logError(err);
		FUSION.error.showError(err);
	}
}


function changeDateTypes()
{
	var mn = FUSION.get.node("datemth").value;
	var dy = FUSION.get.node("dateday").value;
	var yr = FUSION.get.node("curyear").value;
	var clss = FUSION.get.node("td_" + mn + "_" + dy).className;
	var typehash = {};
	for(var i = 0; i < types.length; i++)
	{
		var c = FUSION.get.node(types[i]).checked;
		typehash[types[i]] = c ? 1 : 0;
	}

	var info = {
		"type":	"GET",
		"path":	"php/library.php",
		"data": {
			"method":	"changeStephDateInfo",
			"libcheck": true,
			"year":		yr,
			"month":	mn,
			"day":		dy,
			"cssclass": clss,
			"daytypes":	typehash
		},
		"func":	changeDateResponse,
	};
	FUSION.lib.ajaxCall(info);
}


function changeDateResponse(h)
{
	if(h)
	{
		try {
			jQuery("#dateclassifier").dialog("close");
			clearDateForm();

			var hash = h;
			var td = FUSION.get.node(hash['tdid']);
			td.className = hash['tdclass'];
			td.style.backgroundColor = hash['tdcolor'];
			td.children[0].style.fontStyle = hash['spanstyle'];
			FUSION.get.node("maxyear").value = hash['maxyear'];
			FUSION.get.node("minyear").value = hash['minyear'];
		}
		catch(err) {
			FUSION.error.logError(err);
			FUSION.error.showError(err, "There was a problem getting the calendar data:\n");
		}
	}
}


function getDateInfo(i)
{
	var yr = FUSION.get.node("curyear").value;
	var ar = i.split("_");
	var mn = ar[1];
	var dy = ar[2];
	var info = {
		"type":	"GET",
		"path":	"php/library.php",
		"data": {
			"method":	"getStephDateInfo",
			"libcheck": true,
			"year":		yr,
			"month":	mn,
			"day":		dy
		},
		"func":	showDateForm,
	};
	FUSION.lib.ajaxCall(info);
}


function getFwInfo(i,n)
{
	var num  = i || 0;
	var name = n || "";
	var yr 	 = FUSION.get.node("curyear").value;
	var info = {
		"type":	"GET",
		"path":	"php/library.php",
		"data": {
			"method":	"getFwInfo",
			"libcheck": true,
			"year":		yr,
			"month":	num,
			"mname":	name
		},
		"func":	showFwForm,
	};
	FUSION.lib.ajaxCall(info);
}


function showFwForm(h)
{
	if(h)
	{
		try {
			clearFwForm();
			var hash = h;
			var num  = hash['monthnum'];
			var name = hash['monthname'];
			var fw   = hash['firstwork'];
			FUSION.get.node("fwmonthnum").value = num;
			FUSION.get.node("fwmonthname").value = name;
			FUSION.get.node("fwmonthspan").innerHTML = name;
			FUSION.get.node("fwdayselect").selectedIndex = parseInt(fw);
			jQuery("#setfirstwork").dialog("open");
		}
		catch(err) {
			FUSION.error.logError(err);
			FUSION.error.showError(err, "There was a problem getting the calendar data:\n");
		}
	}
}


function hideFwForm()
{
	jQuery("#setfirstwork").dialog("close");
	clearFwForm();
}


function changeFwDate()
{
	var num  = FUSION.get.node("fwmonthnum").value;
	var name = FUSION.get.node("fwmonthname").value;
	var yr   = FUSION.get.node("curyear").value;
	var fw   = FUSION.get.selectedValue("fwdayselect");
	if(FUSION.lib.isBlank(num) || FUSION.lib.isBlank(name) || FUSION.lib.isBlank(yr) || FUSION.lib.isBlank(fw))
	{
		alert("Please make sure all required fields have values!");
		return false;
	}

	var info = {
		"type":	"GET",
		"path":	"php/library.php",
		"data": {
			"method":	"setFwInfo",
			"libcheck": true,
			"year":		yr,
			"month":	num,
			"mname":	name,
			"firstwork":fw
		},
		"func":	changeFwDateResponse,
	};
	FUSION.lib.ajaxCall(info);

}


function changeFwDateResponse(h)
{
	if(h)
	{
		try {
			var hash = h;
			var div = FUSION.get.node("div" + hash['monthname']);
			div.innerHTML = hash['monthhtml'];
			FUSION.get.node("maxyear").value = hash['maxyear'];
			FUSION.get.node("minyear").value = hash['minyear'];
			jQuery("#setfirstwork").dialog("close");
			clearFwForm();
		}
		catch(err) {
			FUSION.error.logError(err);
			FUSION.error.showError(err, "There was a problem getting the calendar data:\n");
		}
	}

}


function clearDateForm()
{
	FUSION.get.node("dcform").reset();
}


function clearFwForm()
{
	FUSION.get.node("fwform").reset();
}


function showDateForm(h)
{
	if(h)
	{
		try {
			clearDateForm();
			var hash = h;
			var dt = hash['datetypes'];

			//disable kelly box unless it's a work/kelly eligible day
			var kc = FUSION.get.node("kelly");
			kc.checked = dt['kelly'] ? true : false;
			kc.disabled = true;

			FUSION.get.node("holiday").checked = dt['holiday'] ? true : false;
			FUSION.get.node("payday").checked  = dt['payday']  ? true : false;

			FUSION.get.node("datemth").value = hash['month'];
			FUSION.get.node("dateday").value = hash['day'];

			//re-enable kelly box if it's a work/kelly eligible day
			var td = FUSION.get.node("td_" + hash['month'] + "_" + hash['day']);
			if(td.className.match(/work|kelly$/))
			{
				kc.disabled = false;
			}

			var dtstr = FUSION.lib.padZero(hash['month'],2) + "/" + FUSION.lib.padZero(hash['day'],2) + "/" + hash['year'];
			jQuery("#dateclassifier").dialog("option", "title", "Change Date Type - " + dtstr);
			jQuery("#dateclassifier").dialog("open");
		}
		catch(err) {
			FUSION.error.logError(err);
			FUSION.error.showError(err, "There was a problem getting the calendar data:\n");
		}
	}
}


function hideDateForm()
{
	jQuery("#dateclassifier").dialog("close");
	clearDateForm();
}


function refreshCalendar()
{
	var yr = FUSION.get.node("curyear").value;
	var info = {
		"type":	"GET",
		"path":	"php/library.php",
		"data": {
			"method":	"getStephScheduleHtml",
			"libcheck":  true,
			"year":		 yr,
			"firstload": 0
		},
		"func":	refreshCalendarResponse,
	};
	FUSION.lib.ajaxCall(info);
}


function refreshCalendarResponse(h)
{
	if(h)
	{
		try {
			var hash = h;
			var tdiv = "#titlediv";
			jQuery(tdiv).addClass("flipcss1");
			jQuery(tdiv).bind('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', { "t":hash['title'] }, function (e) {
				var d = e.data;
				FUSION.get.node("titlediv").innerHTML = d['t'];
				jQuery(tdiv).removeClass('flipcss1');
				jQuery(tdiv).addClass("flipcss2");
				jQuery(tdiv).bind('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function (e) {
					jQuery(tdiv).removeClass('flipcss2');
				});
				FUSION.get.node("footeryear").innerHTML = d['t'];
			});
			//FUSION.get.node("tablediv").innerHTML = hash['table'];
			FUSION.get.node("maincontent").innerHTML = hash['table'];
		}
		catch(err) {
			FUSION.error.logError(err);
			FUSION.error.showError(err, "There was a problem getting the calendar data:\n");
		}
	}

}
