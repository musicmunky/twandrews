

function validate()
{
	var els = getElementsByAttr("validdata");
	var err = 0;
	var errhash = {};
	try {
		for(var i = 0; i < els.length; i++)
		{
			var t 	 = els[i].type;
			var elid = els[i].id;
			var elnm = els[i].name;
			switch(t) {
				case "text":
					var t = els[i].value;
					if(!t || t.match(/^\s*$/))
	    			{
						err += 1;
						els[i].style.border = "2px solid red";
						break;
					}
					var dtype = els[i].datatype;
					switch(dtype) {
						case "phone":
							var regex = /^(1?)(-| ?)(\()?([0-9]{3})(\)|-| |\)-|\) )?([0-9]{3})(-| )?([0-9]{4}|[0-9]{4})$/;
							if(!regex.test(t))
								{ err += 1; }
							break;
						case "zipcode":
							var regex = /^\d{5}(?:[-\s]\d{4})?$/;
							if(!regex.test(t))
								{ err += 1; }
							break;
						case "email":
							var regex = /[a-z0-9]+([-+._][a-z0-9]+){0,2}@.*?(\.(a(?:[cdefgilmnoqrstuwxz]|ero|(?:rp|si)a)|b(?:[abdefghijmnorstvwyz]iz)|c(?:[acdfghiklmnoruvxyz]|at|o(?:m|op))|d[ejkmoz]|e(?:[ceghrstu]|du)|f[ijkmor]|g(?:[abdefghilmnpqrstuwy]|ov)|h[kmnrtu]|i(?:[delmnoqrst]|n(?:fo|t))|j(?:[emop]|obs)|k[eghimnprwyz]|l[abcikrstuvy]|m(?:[acdeghklmnopqrstuvwxyz]|il|obi|useum)|n(?:[acefgilopruz]|ame|et)|o(?:m|rg)|p(?:[aefghklmnrstwy]|ro)|qa|r[eosuw]|s[abcdeghijklmnortuvyz]|t(?:[cdfghjklmnoprtvwz]|(?:rav)?el)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])\b){1,2}/;
							if(!regex.test(t))
								{ err += 1; }
							break;
					}
			        break;

			    case "select-one":
			    	var v = getSelectedValue(elid);
			    	if(!v || v.match(/^\s*$/))
			    		{ err += 1; }
			        break;

			    case "checkbox":
			    	var chkvals = new Array();
					$.each($("input[name='" + elnm + "']:checked"), function() {
						chkvals.push($(this).val());
					});
					if(chkvals.length < 1)
						{ err += 1; }
			        break;

			    case "radio":
			    	var r = document.querySelector("input[name='" + elnm + "']:checked");
			    	if(!r || r.length < 1)
			    		{ err += 1; }
			    	break;

			    case "hidden":
			    	var h = els[i].value;
					if(!h || h.match(/^\s*$/))
		    			{ err += 1; }
			        break;
			}
		}
		if(err > 1)
		{
			return false;
		}
	}
	catch(error) {
		alert("Error during form validation - please try again, or contact an administrator:\n" + error);
		return false;
	}
	return true;
}


function getObjSize(obj)
{
	try {
		var hasNonLeafNodes = false;
		var childCount = 0;
		for(var child in obj)
		{
			if(typeof obj[child] === 'object')
			{
				childCount += getObjSize(obj[child]);
				hasNonLeafNodes = true;
			}
			else
			{
				childCount++;
			}
		}
		if(hasNonLeafNodes)
		{
			obj.num_children = childCount;
			return childCount;
		}
		else
		{
			return childCount;
		}
	}
	catch(err) {
		obj.is_error = true;
		obj.error_message = err;
		return 0;
	}
}

function checkValid()
{
	if(!validate())
	{
		alert("Please make sure all form fields have values!");
		return false;
	}
	return true;
}


function getElementsByAttr(attr)
{
	var matchingElements = [];
	var allElements = document.getElementsByTagName('*');
	for (var i = 0; i < allElements.length; i++)
	{
		if (allElements[i].getAttribute(attr))
		{
			matchingElements.push(allElements[i]);
		}
	}
	return matchingElements;
}

//get the currently selected text in a select box / dropdown list
function getSelectedText(el)
{
	var sel = document.getElementById(el);
	var idx = sel.selectedIndex;
	var txt = sel.options[idx].innerHTML;
	return txt;
}


//get the currently selected text in a select box / dropdown list
function getSelectedValue(el)
{
	var sel = document.getElementById(el);
	var val = sel.options[sel.selectedIndex].value
	return val;
}


//Set the mouse cursor to the "waiting" icon
function setOverlayMouseWait()
{
	$(".ui-widget-overlay").css( "cursor", "wait" );
	$(".ui-dialog").css( "cursor", "wait" );
	$(".btn").css( "cursor", "wait" );
	$(".glyphicon").css( "cursor", "wait" );
	$("a").css( "cursor", "wait" );
	$("label").css( "cursor", "wait" );
	$("div").css( "cursor", "wait" );
	$("body").css( "cursor", "wait" );
}


//Set the mouse cursor to the "default" icon
function setOverlayMouseNormal()
{
	$(".ui-widget-overlay").css( "cursor", "auto" );
	$(".ui-dialog").css( "cursor", "auto" );
	$(".btn").css( "cursor", "pointer" );
	$(".glyphicon").css( "cursor", "pointer" );
	$("a").css( "cursor", "pointer" );
	$("label").css( "cursor", "auto" );
	$("div").css( "cursor", "auto" );
	$("body").css( "cursor", "auto" );
}


//Given a row id, remove that row from its table
function removeRow(id)
{
  var yn = confirm("Are you sure you'd like to remove this record?");
  if(yn)
  {
    var row = document.getElementById(id);
    var tbd = row.parentNode;
    tbd.removeChild(row);
    return true;
  }
  return false;
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


//create an HTMLElement with parameters passed in by hash
function createHtmlElement(hash)
{
	try {
		var type  = hash['type'];
		var attrs = hash['attributes'];
		var styls = hash['style'];
		var text  = hash['text'];
		var click = hash['onclick'];
		var keyup = hash['keyup'];
		
		var el = "";
		if(type && !type.toString().match(/^\s*$/))
		{
			el = document.createElement(type);
		}
		else
		{
			alert("You must provide an element type when creating a new HTMLElement");
			return document.createElement("div");
		}

		if(attrs && Object.size(attrs) > 0)
		{
			for (key in attrs)
			{
				el.setAttribute(key, attrs[key]);
			}
		}

		if(styls && Object.size(styls) > 0)
		{
			//use jquery to set the style...easier this way
			$( el ).css(styls);
		}

		if(text && !text.toString().match(/^\s*$/))
		{
			var tn = document.createTextNode(text);
			el.appendChild(tn);
		}

		if(click && !click.match(/^\s*$/))
		{
			//el.onclick = function(){ showRemoveMentorForm(me_id, mr_id, me_name, mr_name); };
			//callback issues here with variable assignment...trying this way to see if it will work
			el.onclick = new Function(click);
		}

		if(keyup && !keyup.match(/^\s*$/))
		{
			el.onkeyup = new Function(keyup);
		}

	return el;
	}
	catch(err) {
		alert("ERROR: " + err);
		return document.createElement("div");
	}
}


//prevents a users from typing anything but numer characters in a text field
function noAlpha(th)
{
	th.value = th.value.replace(/[^\d]+/,"");
}


//This handy function was pulled from the net and it allows you to sort
//an array of hashes by a given key - seems to work pretty well so far
var sort_by = function(field, reverse, primer)
{
	var key = function (x) {return primer ? primer(x[field]) : x[field]};
	return function (a,b)
	{
		var A = key(a), B = key(b);
		return ( (A < B) ? -1 : ((A > B) ? 1 : 0) ) * [-1,1][+!!reverse];
	}
}