//start with the jQuery stuff...
$( document ).ready(function() {

	$( "#newtimeform" ).dialog({
		autoOpen: false,
		height: 460,
		width: 400,
		modal: true,
		close: function( event, ui ) { clearNewTimeForm(); }
	});

	jQuery('#legendmenu').click(function() {
		jQuery(".container").toggleClass("container-open");
		jQuery("#legud").toggleClass("menuopen menuclose");
	});

	// https://github.com/jonthornton/jquery-timepicker
	$('#starttime').timepicker({ "step":15, "className":"tstpwrapper" });
	$('#startbreaktime').timepicker({ "step":15, "className":"tstpwrapper" });
	$('#endbreaktime').timepicker({ "step":15, "className":"tstpwrapper" });
	$('#endtime').timepicker({ "step":15, "className":"tstpwrapper" });

});


function getNextMonth()
{
	var dorefresh = true;
	var yrlen = FUSION.get.node("year").length;
	var mnlen = FUSION.get.node("month").length;
	var yrind = FUSION.get.node("year").selectedIndex;
	var mnind = FUSION.get.node("month").selectedIndex;
	var nextbtn = FUSION.get.node("nextbutton");
	FUSION.get.node("previousbutton").disabled = false;
	if((mnind + 1) < mnlen)
	{
		FUSION.get.node("month").selectedIndex = mnind + 1;
		nextbtn.disabled = false;
	}
	else
	{
		if((yrind + 1) < yrlen)
		{
			FUSION.get.node("month").selectedIndex = 0;
			FUSION.get.node("year").selectedIndex = yrind + 1;
			nextbtn.disabled = false;
		}
		else
		{
			dorefresh = false;
			nextbtn.disabled = true;
		}
	}

	if(dorefresh)
	{
		refreshTimesheet();
	}
}


function getPreviousMonth()
{
	var dorefresh = true;
	var yrind = FUSION.get.node("year").selectedIndex;
	var mnind = FUSION.get.node("month").selectedIndex;
	var prevbtn = FUSION.get.node("previousbutton");
	FUSION.get.node("nextbutton").disabled = false;
	if((mnind - 1) >= 0)
	{
		FUSION.get.node("month").selectedIndex = mnind - 1;
		prevbtn.disabled = false;
	}
	else
	{
		if((yrind - 1) >= 0)
		{
			FUSION.get.node("month").selectedIndex = FUSION.get.node("month").length - 1;
			FUSION.get.node("year").selectedIndex = yrind - 1;
			prevbtn.disabled = false;
		}
		else
		{
			prevbtn.disabled = true;
			dorefresh = false;
		}
	}

	if(dorefresh)
	{
		refreshTimesheet();
	}
}


function refreshTimesheet()
{
	var yr = FUSION.get.selectedValue("year");
	var mn = FUSION.get.selectedValue("month");
	var ui = FUSION.get.node("userid").value;

	FUSION.set.overlayMouseWait();
	$.ajax({
		type: "GET",
		url: "php/library.php",
		data: { method:  'getMonthInfo',
				libcheck: true,
				year:  yr,
				month: mn,
			    userid: ui,
				firstload: 0
		},
		success: function(result){
			var response = JSON.parse(result);
			if(response['status'] == "success")
			{
				FUSION.get.node("sidetableheader").innerHTML = response['content']['headstr'];
				var mainhtml = response['content']['mainhtml']
				var sidehtml = response['content']['sidehtml'] + response['content']['finalhtml'];
				FUSION.lib.modifyIETable("maintabletbody", mainhtml);
				FUSION.lib.modifyIETable("sidetabletbody", sidehtml);

				var wh = FUSION.get.pageHeight() - 60;
				FUSION.get.node("mainwrapper").style.height = wh + "px";
			}
			FUSION.set.overlayMouseNormal();
			return false;
		},
		error: function(){
			FUSION.set.overlayMouseNormal();
			FUSION.error.showError("There was a problem retrieving the timesheet data");
		}
	});
	return false;
}


function clearNewTimeForm()
{
	FUSION.get.node("ntform").reset();
}


function showNewTimeForm(i)
{
	clearNewTimeForm();

	FUSION.get.node("dateid").value = i;
	var darray = i.split("_");
// 	var y = FUSION.get.selectedValue("year");
	var m = darray[1];
	var d = darray[2];
	var y = darray[3];
	var cm = FUSION.get.node("month").value;
	var ui = FUSION.get.node("userid").value;

	var suffix = m + "_" + d;
	var dstr = m + "/" + d + "/" + y;

	FUSION.set.overlayMouseWait();
	$.ajax({
		type: "GET",
		url: "php/library.php",
		data: { method:  'getDateInfo',
				libcheck: true,
				year:  y,
				month: m,
			   	day: d,
			    currmonth: cm,
			    userid: ui,
				firstload: 0},
		success: function(result){
			var response = JSON.parse(result);
			if(response['status'] == "success")
			{
				var dstr 	= FUSION.lib.padZero(m, 2) + "/" + FUSION.lib.padZero(d, 2) + "/" + y;

				var start 	= response['content']['start'];
				var end 	= response['content']['end'];
				var begbr 	= response['content']['begbr'];
				var endbr 	= response['content']['endbr'];
				var pto 	= response['content']['pto'];
				var leave 	= response['content']['leave'];
				var note 	= response['content']['note'];

				if(start && !FUSION.lib.isBlank(start)) {
					FUSION.get.node("starttime").value = start;
				}

				if(begbr && !FUSION.lib.isBlank(begbr)) {
					FUSION.get.node("startbreaktime").value = begbr;
				}

				if(endbr && !FUSION.lib.isBlank(endbr)) {
					FUSION.get.node("endbreaktime").value = endbr;
				}

				if(end && !FUSION.lib.isBlank(end)) {
					FUSION.get.node("endtime").value = end;
				}

				if(pto && !FUSION.lib.isBlank(pto)) {
					FUSION.get.node("pto").value = pto;
				}

				if(leave && !FUSION.lib.isBlank(leave)) {
					FUSION.get.node("leave").value = leave;
				}

				if(note && !FUSION.lib.isBlank(note)) {
					FUSION.get.node("note").value = note;
				}
				FUSION.set.overlayMouseNormal();
				$("#newtimeform").dialog("option", "title", "Edit Entry - " + dstr);
				$("#newtimeform").dialog("open");
			}
			else
			{
				FUSION.set.overlayMouseNormal();
				FUSION.lib.alert("The app encountered an error!");
			}
		},
		error: function(){
			FUSION.set.overlayMouseNormal();
			FUSION.error.showError("There was a problem retrieving the data from the server");
		},
		timeout:5000
	});
	return false;
}


function hideNewTimeForm()
{
	$("#newtimeform").dialog( "close" );
	clearNewTimeForm();
}


function addUpdateTimeEntry()
{
	var id = FUSION.get.node("dateid").value;
	if(!id || FUSION.lib.isBlank(id))
	{
		FUSION.lib.alert("Unable to determine date - please refresh the page");
		return false;
	}

	var darray = id.split("_");
	var mn = darray[1];
	var dy = darray[2];
	var yr = darray[3];
	var cm = FUSION.get.node("month").value;
// 	var yr = FUSION.get.selectedValue("year");
	var userid = FUSION.get.node("userid").value;

	var start 	= FUSION.get.node("starttime").value;
	var startbr	= FUSION.get.node("startbreaktime").value;
	var endbr	= FUSION.get.node("endbreaktime").value;
	var end		= FUSION.get.node("endtime").value;

	var pto 	= FUSION.get.node("pto").value;
	var lev 	= FUSION.get.node("leave").value;
	var note 	= FUSION.get.node("note").value;

	var msg = "";
	var brmsg = "";
	var err = 0;
	if(parseFloat(pto) < 0)
	{
		msg = "<br>PTO hours can not be negative";
		err++;
	}
	if(parseFloat(lev) < 0)
	{
		msg = "<br>Leave hours can not be negative"
		err++;
	}

	if(!FUSION.lib.isBlank(startbr) || !FUSION.lib.isBlank(endbr))
	{
		if(FUSION.lib.isBlank(start) || FUSION.lib.isBlank(end))
		{
 			brmsg += "<br>Start and End Time";
			err++;
		}
		if(FUSION.lib.isBlank(startbr) || FUSION.lib.isBlank(endbr))
		{
			brmsg += "<br>Start Break and End Break";
			err++
		}
	}

	if(!FUSION.lib.isBlank(start) || !FUSION.lib.isBlank(end))
	{
		if(FUSION.lib.isBlank(start) || FUSION.lib.isBlank(end))
		{
 			msg += "<br>Start and End Time";
			err++;
		}
	}

	if(err > 0)
	{
		var dispmsg = "<span style='font-weight:bold;color:red;display:inline-block;margin-bottom:5px;'>";
		dispmsg += "Please enter the following fields:</span>" + msg + brmsg;
		FUSION.lib.alert({
			"message":dispmsg,
			"height":100 + (15 * err)
		});
		return false;
	}

	var chktmerr = 0;
	var chktmmsg = "";

	if(!FUSION.lib.isBlank(start))
	{
		var dt = yr + "-" + FUSION.lib.padZero(mn, 2) + "-" + FUSION.lib.padZero(dy, 2);
		var startdt  = new Date(dt + "T" + start);
		var enddt    = new Date(dt + "T" + end);
		var millibeg = startdt.getTime();
		var milliend = enddt.getTime();

		if(milliend <= millibeg)
		{
			chktmmsg += "<br>End time should be after start time";
			chktmerr++;
		}

		if(!FUSION.lib.isBlank(startbr))
		{
			var startbrdt  = new Date(dt + "T" + startbr);
			var endbrdt    = new Date(dt + "T" + endbr);
			var millibegbr = startbrdt.getTime();
			var milliendbr = endbrdt.getTime();
			if(millibegbr <= millibeg)
			{
				chktmmsg += "<br>Begin break time should be after start time";
				chktmerr++;
			}
			if(milliendbr <= millibegbr)
			{
				chktmmsg += "<br>End break time should be after begin break time";
				chktmerr++;
			}
			if(milliend <= milliendbr)
			{
				chktmmsg += "<br>End time should be after end break time";
				chktmerr++;
			}
		}
	}

	if(chktmerr > 0)
	{
		var dispchktmmsg = "<span style='font-weight:bold;color:red;display:inline-block;margin-bottom:5px;'>";
		dispchktmmsg += "Please fix the following problems:</span>" + chktmmsg;
		FUSION.lib.alert({
			"message":dispchktmmsg,
			"height":100 + (15 * chktmerr)
		});
		return false;
	}

	var info = {
		"type": "GET",
		"path": "php/library.php",
		"data": {
			"method": "addUpdateTimeEntry",
			"userid": userid,
			"currmonth": cm,
			"year": yr,
			"dateid": id,
			"libcheck": true,
			"start": start,
			"startbr": startbr,
			"endbr": endbr,
			"end": end,
			"pto": pto,
			"leave": lev,
			"note": note
		},
		"func": editTimeResponse
	};
	FUSION.lib.ajaxCall(info);
}


function editTimeResponse(h)
{
	if(h)
	{
		try {
			var hash = h;
			var sfx = hash['suffix'];
			FUSION.get.node("date_" + sfx).innerHTML = hash['date'];
			FUSION.get.node("start_" + sfx).innerHTML = hash['start'];
			FUSION.get.node("begbreak_" + sfx).innerHTML = hash['sbreak'];
			FUSION.get.node("endbreak_" + sfx).innerHTML = hash['ebreak'];
			FUSION.get.node("end_" + sfx).innerHTML = hash['end'];
			FUSION.get.node("hours_" + sfx).innerHTML = hash['hours'];
			FUSION.get.node("pto_" + sfx).innerHTML = parseFloat(hash['pto']) + parseFloat(hash['leave']);
			FUSION.get.node("day_" + sfx).innerHTML = hash['wordday'];
			FUSION.get.node("totalhours_" + sfx).innerHTML = hash['tothours'];
			FUSION.get.node("note_" + sfx).innerHTML = hash['note'];

// 			var pp1tottd = FUSION.get.node("pp1total");
// 			var pp2tottd = FUSION.get.node("pp2total");
			var ppdifftd = FUSION.get.node("ppdiff");
			var mthtottd = FUSION.get.node("monthtotal");

			mthtottd.innerHTML = hash['pptotal'];
// 			pp1tottd.innerHTML = hash['pp1total'];
// 			pp2tottd.innerHTML = hash['pp2total'];
// 			ppdifftd.innerHTML = hash['ppdiff'];
// 			ppdifftd.className = hash['ppcol'];
// 			pp1tottd.className = hash['pp1col'];
// 			pp2tottd.className = hash['pp2col'];

// 			mthtottd.innerHTML = hash['pp1total'] + hash['pp2total'];
// 			pp1tottd.innerHTML = hash['pp1total'];
// 			pp2tottd.innerHTML = hash['pp2total'];
			ppdifftd.innerHTML = hash['ppdiff'];
			ppdifftd.className = hash['ppcol'];
// 			pp1tottd.className = hash['pp1col'];
// 			pp2tottd.className = hash['pp2col'];

			hideNewTimeForm();
		}
		catch(err) {
			FUSION.error.showError(err, err.lineNumber + " - There was an error updating this entry:\n");
		}
	}
}
