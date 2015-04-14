//start with the jQuery stuff...
$(function() {

	$( "#admintabs" ).tabs({
		activate: function(event,ui){
			if(ui.newTab.index() == 1)
			{
				/*
				$.ajax({
					type: "POST",
					url: "php/library.php",
					data: { firstload: 	0,
							method:  	'getComicTable', 
							libcheck: 	true},
					success: function(result){
						var response = JSON.parse(result);
						if(response['status'] == "success")
						{
							document.getElementById("comictablebody").innerHTML = response['content'];
						}
						return false;
					},
					error: function(){
						methodFail();
					}
				});
				return false;
				*/
			}
		}
	});

	$( "#userform" ).dialog({
		autoOpen: false,
		height: 360,
		width: 550,
		modal: true,
	});
	
	$( "#passwordform" ).dialog({
		autoOpen: false,
		height: 310,
		width: 550,
		modal: true,
	});
	
	$( "#startdate" ).datepicker({ dateFormat: "yy-mm-dd" });
	$( "#enddate" ).datepicker({ dateFormat: "yy-mm-dd" });
	
	$( "#edit_golivedate" ).datepicker({ dateFormat: "yy-mm-dd" });
});

//begin standard functions
function showCreateUserForm()
{
	clearUserForm();
	document.getElementById("updateuserbtn").style.display = "none";
	document.getElementById("updateuserbtn").style.visibility = "hidden";
	document.getElementById("createuserbtn").style.display = "inline";
	document.getElementById("createuserbtn").style.visibility = "visible";
	$( "#userform" ).dialog( 'option', 'title', 'Create New User' );
	$( "#userform" ).dialog( "open" );
}

function eventChkBoxClick(t, pre)
{
	var cboxs = document.getElementsByName(t.name);
	var slcts = document.getElementsByName(pre + "eventtimes");
	for(var i = 0; i < cboxs.length; i++)
	{
		if(cboxs[i].id != t.id && t.checked && cboxs[i].checked)
		{
			cboxs[i].checked = false;
		}
	}

	for(var j = 0; j < slcts.length; j++)
	{
		slcts[j].disabled = (t.checked) ? true : false;
	}
}

function showUpdateUserForm(i)
{
	var id = "";
	if(i)
	{
		id = i;
	}
	else
	{
		alert("Could not retrieve user information!");
		return false;
	}
	clearUserForm();
	document.getElementById("userid").value = id;
	document.getElementById("uname").value  = document.getElementById("unamehdn"+id).value;
	document.getElementById("ufname").value = document.getElementById("firsthdn"+id).value;
	document.getElementById("ulname").value = document.getElementById("lasthdn"+id).value;
	document.getElementById("uemail").value = document.getElementById("emailhdn"+id).value;
		
	document.getElementById("updateuserbtn").style.display = "inline";
	document.getElementById("updateuserbtn").style.visibility = "visible";
	document.getElementById("createuserbtn").style.display = "none";
	document.getElementById("createuserbtn").style.visibility = "hidden";
	$( "#userform" ).dialog( "option", "title", "Update Your Info" );
	$( "#userform" ).dialog( "open" );
}

function hideUserForm()
{
	clearUserForm();
	$( "#userform" ).dialog( "close" );
}

function clearUserForm()
{
	document.getElementById("uname").value = "";
	document.getElementById("ufname").value = "";
	document.getElementById("ulname").value = "";
	document.getElementById("uemail").value = "";
}

function hidePasswordForm()
{
	clearPasswordForm();
	$( "#passwordform" ).dialog( "close" );
}

function clearPasswordForm()
{
	document.getElementById("currpass").value = "";
	document.getElementById("newpass").value = "";
	document.getElementById("repnewpass").value = "";
}

function checkUserForm()
{
	var un = document.getElementById("uname").value;
	var fn = document.getElementById("ufname").value;
	var ln = document.getElementById("ulname").value;
	var ue = document.getElementById("uemail").value;
	
	var error = "";
	var missing = "";
	if(un.match(/^\s*$/))
	{
		missing = missing + "\nUsername";
	}
	if(fn.match(/^\s*$/))
	{
		missing = missing + "\nFirst Name";
	}
	if(ln.match(/^\s*$/))
	{
		missing = missing + "\nLast Name";
	}
	if(ue.match(/^\s*$/))
	{
		missing = missing + "\nEmail Address";
	}
	if(missing)
	{
		error = "The following fields are required:\n" + missing;
	}

	if(error){
		alert(error);
		return false;
	}
	else
	{
		return true;
	}
}

function updateUser()
{
	var id = document.getElementById("userid").value;
	var un = document.getElementById("uname").value;
	var fn = document.getElementById("ufname").value;
	var ln = document.getElementById("ulname").value;
	var ue = document.getElementById("uemail").value;

	if(checkUserForm())
	{
		$.ajax({
			type: "POST",
			url: "php/library.php",
			data: { method:  'updateUser', 
				libcheck: true, 
				userid:		id,
				username:	un,
				firstname:	fn,
				lastname:	ln,
				useremail:	ue},
			success: function(result){
				var response = JSON.parse(result);
				alert(response['message']);
				if(response['status'] == "success")
				{
					document.getElementById("unamehdn" + id).value = un;
					document.getElementById("firsthdn" + id).value = fn;
					document.getElementById("lasthdn" + id).value = ln;
					document.getElementById("emailhdn" + id).value = ue;
					document.getElementById("tduname" + id).innerHTML = un;
					document.getElementById("tdfname" + id).innerHTML = fn;
					document.getElementById("tdlname" + id).innerHTML = ln;
					document.getElementById("tdemail" + id).innerHTML = ue;
					hideUserForm();
					clearUserForm();
				}
				return false;
			},
			error: function(){
				methodFail();
			}
		});
		return false;
	}
}

function createUser()
{
	var un = document.getElementById("uname").value;
	var fn = document.getElementById("ufname").value;
	var ln = document.getElementById("ulname").value;
	var ue = document.getElementById("uemail").value;
	
	if(checkUserForm())
	{
		$.ajax({
			type: "POST",
			url: "php/library.php",
			data: { method:  'createUser', 
					libcheck: true, 
					username:	un,
					firstname:	fn,
					lastname:	ln,
					useremail:	ue},
			success: function(result){
				var response = JSON.parse(result);
				alert(response['message']);
				if(response['status'] == "success")
				{
					document.getElementById("tablediv").innerHTML = response['content'];
					hideUserForm();
					clearUserForm();
				}
				return false;
			},
			error: function(){
				methodFail();
			}
		});
		return false;
	}
}

function showUpdatePasswordForm(i)
{
	var id = "";
	if(i)
	{
		id = i;
	}
	else
	{
		alert("Could not retrieve user information!");
		return false;
	}
	document.getElementById("userid").value = id;
	clearPasswordForm();
	$( "#passwordform" ).dialog( "open" );
}

function updatePassword()
{
	var id = document.getElementById("userid").value;
	var cp  = document.getElementById("currpass").value;
	var np1 = document.getElementById("newpass").value;
	var np2 = document.getElementById("repnewpass").value;
	
	var error = "";
	var missing = "";
	if(cp.match(/^\s*$/))
	{
		missing = missing + "\nCurrent Password";
	}
	if(np1.match(/^\s*$/))
	{
		missing = missing + "\nNew Password";
	}
	if(np2.match(/^\s*$/))
	{
		missing = missing + "\nRepeat New Password";
	}
	if(missing)
	{
		error = "The following fields are required:\n" + missing;
	}
	if(np1 != np2)
	{
		error = error + "\nThe two new password fields must match!";
	}
	if(error){
		alert(error);
		return false;
	}
	$.ajax({
		type: "POST",
		url: "php/library.php",
		data: { method:  'updatePassword',
				libcheck: true,
				userid:		id,
				currpass:	cp,
				newpass:	np1},
		success: function(result){
			var response = JSON.parse(result);
			alert(response['message']);
			if(response['status'] == "success")
			{
				hidePasswordForm();
				clearPasswordForm();
			}
			return false;
		},
		error: function(){
			methodFail();
		}
	});
	return false;
}

function clearNewEventForm(s, pre)
{
	var yn = s ? 1 : confirm("Are you sure you want to clear this form?");
	if(yn)
	{
		document.getElementById(pre + "eventtitle").value = "";
		document.getElementById(pre + "eventdesc").value = "";
		document.getElementById(pre + "startdate").value = "";
		document.getElementById(pre + "enddate").value = "";
		document.getElementById(pre + "eventtype").selectedIndex = 0;
		document.getElementById(pre + "eventallday").checked = false;
		document.getElementById(pre + "eventmultiday").checked = false;
		var sels = document.getElementsByName(pre + "eventtimes");
		for(var i = 0; i < sels.length; i++)
		{
			sels[i].selectedIndex = 0;
			sels[i].disabled = false;
		}
	}
}

function addEvent(pre)
{
	var title = document.getElementById(pre + "eventtitle").value;
	var etype = getSelectedText(pre + "eventtype");
	var edesc = document.getElementById(pre + "eventdesc").value;
	var sdate = document.getElementById(pre + "startdate").value;
	var edate = document.getElementById(pre + "enddate").value;
	var alday = document.getElementById(pre + "eventallday").checked;
	var mlday = document.getElementById(pre + "eventmultiday").checked;	
	var stime = getSelectedValue(pre + "starttime");
	var etime = getSelectedValue(pre + "endtime");

	var start_str = "";
	var enddt_str = "";

	if(alday)
	{
		start_str = sdate + " 00:00:00";
		enddt_str = sdate + " 11:59:59";
	}
	else if(mlday)
	{
		start_str = sdate + " 00:00:00";
		enddt_str = edate + " 11:59:59";
	}
	else
	{
		start_str = sdate + " " + stime + ":00";
		enddt_str = edate + " " + etime + ":00";
	}

	var method = (pre == "edit") ? 'updateEvent' : 'addNewEvent';
	$.ajax({
		type: "POST",
		url: "php/library.php",
		data: { method: method,
				libcheck: true,
				title: title,
				etype: etype,
				edesc: edesc,
				start: start_str,
				enddt: enddt_str},
		success: function(result){
			var response = JSON.parse(result);
			if(response['status'] == "success")
			{
				alert(response['message']);
				clearNewEventForm(1, pre);
			}
			return false;
		},
		error: function(){
			methodFail();
		}
	});
	return false;
}

function validateEvent(pre)
{
	var title = document.getElementById(pre + "eventtitle").value;
	var sdate = document.getElementById(pre + "startdate").value;
	var edate = document.getElementById(pre + "enddate").value;
	var etype = getSelectedText(pre + "eventtype");
	var alday = document.getElementById(pre + "eventallday").checked;
	var mlday = document.getElementById(pre + "eventmultiday").checked;	
	var stime = getSelectedValue(pre + "starttime");
	var etime = getSelectedValue(pre + "endtime");

	var error = "";
	var missing = "";
	if(title.match(/^\s*$/))
	{
		missing = missing + "\nTitle";
	}
	if(etype.match(/^\s*$/))
	{
		missing = missing + "\nType";
	}
	if(sdate.match(/^\s*$/))
	{
		missing = missing + "\nStart Date";
	}
	if(edate.match(/^\s*$/) && !alday)
	{
		missing = missing + "\nEnd Date";
	}
	if(!alday && !mlday && (stime.match(/^\s*$/) || etime.match(/^\s*$/)))
	{
		missing = missing + "\nStart and End Time";
	}

	if(missing)
	{
		error = "The following fields are required:\n" + missing;
		alert(error);
		return false;
	}

	if(alday)
	{
		edate = sdate;
	}
	sdate = sdate.replace(/-/g, "/");
	edate = edate.replace(/-/g, "/");
	if(!alday && !mlday)
	{
		sdate = sdate + " " + stime + ":00";
		edate = edate + " " + etime + ":00";
	}
	var start = Date.parse(sdate) / 1000;
	var endtm = Date.parse(edate) / 1000;

	if(start > endtm)
	{
		alert("Start time is after the end time");
		return false;
	}
	if(start == endtm && !alday)
	{
		alert("Please select a different start and end time for the event");
		return false;
	}
	addEvent(pre);
}

function validateUpdate()
{
	//var file = document.getElementById("edit_cfile").value;
	var cid  = document.getElementById("edit_comicid").value;
	var name = document.getElementById("edit_cname").value;
	var cttl = document.getElementById("edit_ctitle").value;
	var calt = document.getElementById("edit_calt").value;
	var csub = document.getElementById("edit_csub").value;
	var golv = document.getElementById("edit_golivedate").value;
	var rdio = document.getElementsByName("edit_golive");
	
	var chck = true;
	var rdbt = "";
	for(var elem in rdio)
	{
		if(rdio[elem].checked)
		{
			rdbt = rdio[elem].value;
			chck = false;
		}
	}
	
	var error = "";
	var missing = "";
	if(name.match(/^\s*$/))
	{
		missing = missing + "\nFile name";
	}
	if(cid.match(/^\s*$/))
	{
		missing = missing + "\nComic ID";
	}
	if(cttl.match(/^\s*$/))
	{
		missing = missing + "\nComic Title";
	}
	if(calt.match(/^\s*$/))
	{
		missing = missing + "\nAlt-text";
	}
	if(csub.match(/^\s*$/))
	{
		missing = missing + "\nSub-text";
	}
	if(chck)
	{
		missing = missing + "\nWhen to post";
	}
	if(!chck && rdbt == "future" && golv.match(/^\s*$/))
	{
		missing = missing + "\nPost date";
	}
	if(missing)
	{
		error = "The following fields are required:\n" + missing;
		alert(error);
		return false;
	}
	document.getElementById('updatingdiv').style.visibility = 'visible';
	document.getElementById('updatingdiv').style.display = 'block';
	return true;
}

		
function comicQuery()
{
	var txt = document.getElementById("comicquery");
	var val = txt.value;
	var getall = 0;
	if(val.match(/^\s*$/))
	{
		getall = 1;
	}
	
	if(!getall && val.length < 2)
	{}
	else
	{
		$.ajax({
			type: "GET",
			url: "php/comicquery.php",
			data: { q: val, ga: getall },
			success: function(result){
				var response = JSON.parse(result);
				if(response['status'] == "success")
				{
					document.getElementById("comictablebody").innerHTML = response['content']['tablehtml'];
				}
				return false;
			},
			error: function(){
				methodFail();
			}
		});
		return false;
	}
}

function fillEditForm(i)
{
	var id = i ? i : 0;
	if(!id)
	{
		alert("Please enter a valid comic id!");
		return false;
	}
	else
	{
		$.ajax({
			type: "POST",
			url: "php/library.php",
			data: { method:  'getComicInfo',
					libcheck: true,
					comicid:  id },
			success: function(result){
				var response = JSON.parse(result);
				if(response['status'] == "success")
				{
					var rdio = document.getElementsByName("edit_golive");
					rdio[0].checked = false;
					rdio[1].checked = false;
					var pstat = response['content']['poststat'];
					if(pstat == "publish" || pstat == "inactive")
					{
						document.getElementById("edit_postdatediv").style.display = "none";
						document.getElementById("edit_postdatediv").style.visibility = "hidden";
						rdio[0].checked = true;
					}
					else
					{
						document.getElementById("edit_postdatediv").style.display = "block";
						document.getElementById("edit_postdatediv").style.visibility = "visible";
						rdio[1].checked = true;
					}
					
					var active = document.getElementsByName("edit_active");
					if(pstat != "inactive")
					{
						active[0].checked = true;
					}
					else
					{
						active[1].checked = true;
					}
					
					document.getElementById("edit_golivedate").value = response['content']['postdate'];
					document.getElementById("edit_comicid").value = id;
					document.getElementById("edit_curr").value 	 = response['content']['path'];	  //DISABLED - not editable, just show path of file
	
					document.getElementById("edit_cname").value  = response['content']['name'];	  //comic file name
					document.getElementById("edit_ctitle").value = response['content']['title'];  //comic title
					document.getElementById("edit_calt").value 	 = response['content']['alttxt']; //comic alt-text
					document.getElementById("edit_csub").value 	 = response['content']['subtxt']; //comic sub-text
					document.getElementById("edit_ctags").value  = response['content']['tags'];	  //comic tags (if exist)
	
					document.getElementById("h_edit_cname").value  = response['content']['name'];	//comic file name
					document.getElementById("h_edit_ctitle").value = response['content']['title'];  //comic title
					document.getElementById("h_edit_calt").value   = response['content']['alttxt']; //comic alt-text
					document.getElementById("h_edit_csub").value   = response['content']['subtxt']; //comic sub-text
					document.getElementById("h_edit_ctags").value  = response['content']['tags'];	//comic tags (if exist)
				}
				return false;
			},
			error: function(){
				methodFail();
			}
		});
		return false;
	}
}

function methodFail()
{
	alert("The call to the server failed - please try again");
}

function tabSelect(t)
{
	var s = t ? t : 0;
	$("#admintabs").tabs("option", "active", s);
}

//set the values of the time select to reflect 
//military time based on am/pm selection
function setOptionTimes(t, s, pre)
{
	var ampm = t.value;
	var time = document.getElementById(pre + s + "time");
	var count = (ampm == "am") ? 0 : 12;
	for(var i = 1; i < time.options.length-1; i += 2)
	{
		var z = (count < 10) ? "0" : "";
		time.options[i].value = z + count + ":00";
		time.options[i+1].value = z + count + ":30";
		count++;
	}
}

//get the currently selected text in a select box / dropdown list
function getSelectedValue(el)
{
	var sel = document.getElementById(el);
  var val = sel.options[sel.selectedIndex].value
  return val;
}

//get the currently selected text in a select box / dropdown list
function getSelectedText(el)
{
	var sel = document.getElementById(el);
	var idx = sel.selectedIndex;
	var txt = sel.options[idx].innerHTML;
	return txt;
}

//set the selected text of a select box / dropdown list
//return true if a value is found, otherwise return false
function setSelectedText(el,t)
{
	var sel = document.getElementById(el);
	var res = false;
	for (var i = 0; i < sel.options.length; i++)
	{
		if (sel.options[i].text == t)
		{
			sel.selectedIndex = i;
			res = true;
			break;
		}
	}
	return res;
}