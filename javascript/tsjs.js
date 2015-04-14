//start with the jQuery stuff...
$( document ).ready(function() {

	$( "#newtimeform" ).dialog({
		autoOpen: false,
		height: 450,
		width: 400,
		modal: true,
		close: function( event, ui ) { clearNewTimeForm(); }
	});

});


function getNextMonth()
{

	//for(var i = 0; i < 100; i++){ console.log(100 - i); }
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
		type: "POST",
		url: "php/library.php",
		data: { method:  'getMonthInfo',
				libcheck: true,
				year:  yr,
				month: mn,
			    userid: ui,
				firstload: 0},
		success: function(result){
			var response = JSON.parse(result);
			if(response['status'] == "success")
			{
				FUSION.get.node("sidetableheader").innerHTML = response['content']['headstr'];
				var mainhtml = response['content']['mainhtml']
				var sidehtml = response['content']['sidehtml'] + response['content']['finalhtml'];
				FUSION.lib.modifyIETable("maintabletbody", mainhtml);
				FUSION.lib.modifyIETable("sidetabletbody", sidehtml);

				//var oh = FUSION.get.node('sidetablewrapper').offsetHeight;
				var oh = $('#sidetablewrapper').height();
				var wh = window.innerHeight;
				//FUSION.get.node("mainwrapper").style.height = (wh > oh) ? wh + "px" : oh + "px";
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
	var y = FUSION.get.selectedValue("year");
	var m = darray[1];
	var d = darray[2];
	var ui = FUSION.get.node("userid").value;

	var suffix = m + "_" + d;
	var dstr = m + "/" + d + "/" + y;

	FUSION.set.overlayMouseWait();
	$.ajax({
		type: "POST",
		url: "php/library.php",
		data: { method:  'getDateInfo',
				libcheck: true,
				year:  y,
				month: m,
			   	day: d,
			    userid: ui,
				firstload: 0},
		success: function(result){
			var response = JSON.parse(result);
			if(response['status'] == "success")
			{
				var fsp = [];
				var ssp = [];
				var ampm = "";
				var dstr = m + "/" + d + "/" + y;

				var start 	= response['content']['start'];
				var end 	= response['content']['end'];
				var begbr 	= response['content']['begbr'];
				var endbr 	= response['content']['endbr'];
				var pto 	= response['content']['pto'];
				var leave 	= response['content']['leave'];
				var note 	= response['content']['note'];

				if(start && !FUSION.lib.isBlank(start)) {
					fsp = start.split(" ");
					ssp = fsp[0].split(":");
					ampm = fsp[1];
					FUSION.set.selectedText("starthour", ssp[0]);
					FUSION.set.selectedText("startminute", ssp[1]);
					FUSION.set.selectedText("startampm", ampm);
				}

				if(begbr && !FUSION.lib.isBlank(begbr)) {
					fsp = begbr.split(" ");
					ssp = fsp[0].split(":");
					ampm = fsp[1];
					FUSION.set.selectedText("startbreakhour", ssp[0]);
					FUSION.set.selectedText("startbreakminute", ssp[1]);
					FUSION.set.selectedText("startbreakampm", ampm);
				}

				if(endbr && !FUSION.lib.isBlank(endbr)) {
					fsp = endbr.split(" ");
					ssp = fsp[0].split(":");
					ampm = fsp[1];
					FUSION.set.selectedText("endbreakhour", ssp[0]);
					FUSION.set.selectedText("endbreakminute", ssp[1]);
					FUSION.set.selectedText("endbreakampm", ampm);
				}

				if(end && !FUSION.lib.isBlank(end)) {
					fsp = end.split(" ");
					ssp = fsp[0].split(":");
					ampm = fsp[1];
					FUSION.set.selectedText("endhour", ssp[0]);
					FUSION.set.selectedText("endminute", ssp[1]);
					FUSION.set.selectedText("endampm", ampm);
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
	var yr = FUSION.get.selectedValue("year");
	var userid = FUSION.get.node("userid").value;

	var sthr 	= FUSION.get.selectedValue("starthour");
	var stmn 	= FUSION.get.selectedValue("startminute");
	var stap 	= FUSION.get.selectedValue("startampm");
	var stbrhr 	= FUSION.get.selectedValue("startbreakhour");
	var stbrmn 	= FUSION.get.selectedValue("startbreakminute");
	var stbrap 	= FUSION.get.selectedValue("startbreakampm");
	var enbrhr 	= FUSION.get.selectedValue("endbreakhour");
	var enbrmn 	= FUSION.get.selectedValue("endbreakminute");
	var enbrap 	= FUSION.get.selectedValue("endbreakampm");
	var enhr 	= FUSION.get.selectedValue("endhour");
	var enmn 	= FUSION.get.selectedValue("endminute");
	var enap 	= FUSION.get.selectedValue("endampm");
	var pto 	= FUSION.get.node("pto").value;
	var lev 	= FUSION.get.node("leave").value;
	var note 	= FUSION.get.node("note").value;

	var sisthr 	 = FUSION.get.node("starthour").selectedIndex;
	var sistmn 	 = FUSION.get.node("startminute").selectedIndex;
	var sistap 	 = FUSION.get.node("startampm").selectedIndex;
	var sienhr 	 = FUSION.get.node("endhour").selectedIndex;
	var sienmn 	 = FUSION.get.node("endminute").selectedIndex;
	var sienap 	 = FUSION.get.node("endampm").selectedIndex;
	var sistbrhr = FUSION.get.node("startbreakhour").selectedIndex;
	var sistbrmn = FUSION.get.node("startbreakminute").selectedIndex;
	var sistbrap = FUSION.get.node("startbreakampm").selectedIndex;
	var sienbrhr = FUSION.get.node("endbreakhour").selectedIndex;
	var sienbrmn = FUSION.get.node("endbreakminute").selectedIndex;
	var sienbrap = FUSION.get.node("endbreakampm").selectedIndex;

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
	if(sistbrhr > 0 || sistbrmn > 0 || sistbrap > 0 || sienbrhr > 0 || sienbrmn > 0 || sienbrap > 0)
	{
		if(sistbrhr <= 0)
		{
			brmsg += "<br>Start Break Hour";
			err++;
		}
		if(sistbrmn <= 0)
		{
			brmsg += "<br>Start Break Minute";
			err++;
		}
		if(sistbrap <= 0)
		{
			brmsg += "<br>Start Break AM or PM";
			err++;
		}
		if(sienbrhr <= 0)
		{
			brmsg += "<br>End Break Hour";
			err++;
		}
		if(sienbrmn <= 0)
		{
			brmsg += "<br>End Break Minute";
			err++;
		}
		if(sienbrap <= 0)
		{
			brmsg += "<br>End Break AM or PM";
			err++;
		}
	}
	if(sisthr > 0 || sistmn > 0 || sistap > 0 || sienhr > 0 || sienmn > 0 || sienap > 0 || err > 0)
	{
		if(sisthr <= 0)
		{
			msg += "<br>Start Hour";
			err++;
		}
		if(sistmn <= 0)
		{
			msg += "<br>Start Minute";
			err++;
		}
		if(sistap <= 0)
		{
			msg += "<br>Start AM or PM";
			err++;
		}
		if(sienhr <= 0)
		{
			msg += "<br>End Hour";
			err++;
		}
		if(sienmn <= 0)
		{
			msg += "<br>End Minute";
			err++;
		}
		if(sienap <= 0)
		{
			msg += "<br>End AM or PM";
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
	if(sisthr > 0)
	{
		//verify start is before end
		var beghr = (stap == "pm" && sthr != 12) ? (sthr + 12) : sthr;
		var endhr = (enap == "pm" && enhr != 12) ? (enhr + 12) : enhr;
		var millibeg = Date.UTC(yr, mn, dy, beghr, stmn);
		var milliend = Date.UTC(yr, mn, dy, endhr, enmn);
		if(milliend <= millibeg)
		{
			chktmmsg += "<br>End time should be after start time";
			chktmerr++;
		}
	}
	if(sistbrhr > 0 && sisthr > 0)
	{
		//verify start is before start break
		//verify start break is before end break
		//verify end break is before end
		var begbrhr = (stbrap == "pm" && stbrhr != 12) ? (stbrhr + 12) : stbrhr;
		var endbrhr = (enbrap == "pm" && enbrhr != 12) ? (enbrhr + 12) : enbrhr;
		var beghr = (stap == "pm" && sthr != 12) ? (sthr + 12) : sthr;
		var endhr = (enap == "pm" && enhr != 12) ? (enhr + 12) : enhr;
		var millibegbr = Date.UTC(yr, mn, dy, begbrhr, stbrmn);
		var milliendbr = Date.UTC(yr, mn, dy, endbrhr, enbrmn);
		var millibeg = Date.UTC(yr, mn, dy, beghr, stmn);
		var milliend = Date.UTC(yr, mn, dy, endhr, enmn);

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
		"type": "POST",
		"path": "php/library.php",
		"data": {
			"method": "addUpdateTimeEntry",
			"userid": userid,
			"year": yr,
			"dateid": id,
			"libcheck": true,
			"starthour": sthr,
			"startminute": stmn,
			"startampm": stap,
			"startbrhour": stbrhr,
			"startbrminute": stbrmn,
			"startbrampm": stbrap,
			"endbrhour": enbrhr,
			"endbrminute": enbrmn,
			"endbrampm": enbrap,
			"endhour": enhr,
			"endminute": enmn,
			"endampm": enap,
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

			var pp1tottd = FUSION.get.node("pp1total");
			var pp2tottd = FUSION.get.node("pp2total");
			var ppdifftd = FUSION.get.node("ppdiff");
			pp1tottd.innerHTML = hash['pp1total'];
			pp2tottd.innerHTML = hash['pp2total'];
			ppdifftd.innerHTML = hash['ppdiff'];
			ppdifftd.className = hash['ppcol'];
			pp1tottd.className = hash['pp1col'];
			pp2tottd.className = hash['pp2col'];

			hideNewTimeForm();
		}
		catch(err) {
			FUSION.error.showError(err, err.lineNumber + " - There was an error updating this entry:\n");
		}
	}
}
