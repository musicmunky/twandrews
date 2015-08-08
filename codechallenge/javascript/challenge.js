$( document ).ready(function() {

	//IE doesn't like Google fonts...apparently it's Google's fault
	//at the moment, but whatever...load Web Safe font for IE users
	var gbr = FUSION.get.browser();
	if(gbr.browser && gbr.browser == "IE")
	{
		document.body.style.fontFamily = "'Trebuchet MS', Helvetica, sans-serif";
	}

	//setting up the onclick functionality
	jQuery(".citydiv").click(function(){
		showDisplay(this);
	});

	jQuery("#querybtn").click(function(){
		runSearch();
	});

	//creating the jquery datepickers
	$( "#startdate" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd",
		showButtonPanel: true,
		maxDate: "0",
		minDate: "-365"
	});
	$( "#enddate" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd",
		showButtonPanel: true,
		maxDate: "0",
		minDate: "-365"
	});

	//setting up the slider used on the Highcharts sections
	var today   = new Date();
	var maxDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
	var minDate = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate());

	var min = Math.floor(minDate.getTime() / 86400000);
	var max = Math.floor(maxDate.getTime() / 86400000);

	var slidetimeout;

	$('#dateslider').slider({
		range: true,
        max: max,
		min: min,
		values: [ min, max ],
        slide: function(event, ui) {
			var minDate = new Date(ui.values[0] * 86400000);
			var maxDate = new Date(ui.values[1] * 86400000);
			//clearing and setting the timeout for the slider
			//this is put in place so there is a brief delay after the user stops moving
			//the slider to avoid spamming the server with requests
			//...not the most elegant solution, I know, but it *does* work.
			clearTimeout(slidetimeout);
			slidetimeout = setTimeout(function(){ sliderUpdateChart({"min":minDate, "max":maxDate}); }, 1000);
			setDateRangeDisplay(minDate, maxDate);
		}
    });

	//simple wrapper function to set the "Date Range" span html
	setDateRangeDisplay(minDate, maxDate);

	//ATTEMPT to remove the localStorage object, will fail for older browsers, *should* work on IE9
	try {
		localStorage.removeItem("SODAquery");
	}
	catch(err) {
		FUSION.error.logError(err, "Unable to remove localStorage item: ");
	}

	//clear the form
	clearSearchForm();
});

//global objects used by Google Maps to add/remove markers
var map;
var markers = [];

function setDateRangeDisplay(min, max)
{
	var minstr = getDateString(min);
	var maxstr = getDateString(max);
	FUSION.get.node("daterangespan").innerHTML = minstr + " - " + maxstr;
}


//functionality to handle displaying the correct divs and the "sticky tab" navigation
function showDisplay(t)
{
	var id = "";
	var div = {};
	var ary = [];
	$(".citydiv").each( function() {
		$(this).css({ "background-color": "#FFF" });
		id = $(this).attr("id");
		ary = id.split("-");
		FUSION.get.node(ary[0] + "-div").style.display = "none";
	});

	$(t).css({ "background-color": "#EEE" });
	ary = $(t).attr("id").split("-");
	var divid = ary[0];
	FUSION.get.node(divid + "-div").style.display = "block";
}


//send the request to the server via AJAX
function runSearch()
{
	var str = "800 Occidental Ave S, Seattle, WA 98134"; //default address provided by Code Challenge

	var max = FUSION.get.node("maxresults").value ? FUSION.get.node("maxresults").value : 1000;
	max = parseInt(max) > 50000 ? 50000 : max;

	var rng   = FUSION.get.node("range").value ? FUSION.get.node("range").value : 1;
	var start = FUSION.get.node("startdate").value ? FUSION.get.node("startdate").value : getDefaultDate();
	var end   = FUSION.get.node("enddate").value ? FUSION.get.node("enddate").value : getDefaultDate();

	str = str.replace(/\s/ig, "+");
	var info = {
		"type": "GET",
		"path": "php/challengelib.php",
		"data": {
			"method": 		"getSocrataInfo",
			"libcheck": 	true,
			"searchstring": str,
			"range":		rng,
			"limit":		max,
			"startdate":	start,
			"enddate":		end,
			"timeout":		20000

		},
		"func": processSearchResults
	};
	FUSION.lib.ajaxCall(info);
}


//Process the search results - this takes a hash returned by the AJAX call and sets
//the data for the localStorage component, which is then used by Google Maps and
//Highcharts for data display
function processSearchResults(h)
{
	var hash = h || {};
	var content = hash['response_content'];

	//remove any existing items, just in case
	try {
		localStorage.removeItem("SODAquery");
	}
	catch(err) {
		FUSION.error.logError(err, "Unable to remove localStorage item: ");
	}

	//create the localStorage item
	localStorage.setItem("SODAquery", JSON.stringify(hash));

	//because Javascript doesn't handle dates very well, gotta do some machinations here
	var startary = hash['start_date'].split("T");
	var endary   = hash['end_date'].split("T");
	var startels = startary[0].split("-");
	var endels   = endary[0].split("-");

	var minDate = new Date(startels[0], parseInt(startels[1])-1, startels[2]);
	var maxDate = new Date(endels[0], parseInt(endels[1])-1, endels[2]);
	setDateRangeDisplay(minDate, maxDate);

	var min = Math.floor(minDate.getTime() / 86400000);
	var max = Math.floor(maxDate.getTime() / 86400000);

	//set the new max/min for the slider
	$('#dateslider').slider({
		range: true,
        max: max,
		min: min,
		values: [ min, max ],
    });

	//run the code for the chart
	sliderUpdateChart();

	//display the correct div
	showDisplay(FUSION.get.node("highcharts-tab"));

	//create the Google Map needed
	var mapOptions = {
		zoom: 14,
		center: new google.maps.LatLng(hash['latitude_center'], hash['longitude_center']),
		scaleControl: true
	};

	map = new google.maps.Map(FUSION.get.node("googlemaps-canvas"), mapOptions);

	for(var i = 0; i < hash['response_count']; i++)
	{
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(content[i].latitude, content[i].longitude),
			map: map,
			title: content[i].event_clearance_group
		});
		markers.push(marker);
	}

}

//a couple of simple functions to remove the markers from the google map
function setAllMap(map)
{
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	}
}


function clearMarkers()
{
	setAllMap(null);
}

//some wrapper functions below for basic functionality
function clearSearchForm()
{
	FUSION.get.node("startdate").value = "";
	FUSION.get.node("enddate").value = "";
	FUSION.get.node("maxresults").value = "";
	FUSION.get.node("range").selectedIndex = 0;
}


function getDateString(d)
{
	var dd = FUSION.lib.padZero(d.getDate(), 2);
	var mm = FUSION.lib.padZero(d.getMonth() + 1, 2);
	var yyyy = d.getFullYear();
	var str = yyyy + "-" + mm + "-" + dd;
	return str;
}


function getDefaultDate()
{
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1;
	var yyyy = today.getFullYear();

	if(dd < 10) {
		dd = FUSION.lib.padZero(dd, 2);
	}

	if(mm < 10) {
		mm = FUSION.lib.padZero(mm, 2);
	}

	today = yyyy + "-" + mm + "-" + dd;
	return today;
}

