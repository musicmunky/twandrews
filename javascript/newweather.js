//all javascript for the simple weather page setup
//it uses the FUSION library extensively, so see fusionlib.js
//for other function definitions and such.
//also uses jQuery, because I'm inherently lazy and jQuery
//makes life so much easier sometimes.
var directions = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE",
				  "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];
var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
			  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

$( document ).ready(function() {

	//var con = document.createElement('canvas').getContext;
	//var bool = !!document.createElement('canvas').getContext;
	//alert("BOOL IS: " + bool);

	// by default, icons are black but you can color them
	//var skycons = new Skycons({"color": "pink"});
// 	var skycons = new Skycons();
	// on Android, a nasty hack is needed: {"resizeClear": true}

	// If you want to add more colors :
	// var skycons = new Skycons({"monochrome": false});
	// you can now customize the color of different parts
	// main, moon, fog, cloud, snow, leaf, rain, sun
	var skycons = new Skycons({
		"monochrome": true,
		"colors" : {
			"main": "#444"
		}
	});


	// you can add a canvas by it's ID...
	skycons.add("condimg2", Skycons.PARTLY_CLOUDY_DAY);
	skycons.add("condimg3", Skycons.RAIN);
	skycons.add("condimg4", Skycons.PARTLY_CLOUDY_NIGHT);
	skycons.add("condimg5", Skycons.PARTLY_CLOUDY_DAY);

	// ...or by the canvas DOM element itself.
//	skycons.add(document.getElementById("icon2"), Skycons.RAIN);

	// if you're using the Forecast API, you can also supply
	// strings: "partly-cloudy-day" or "rain".

	// start animation!
	skycons.play();

	// you can also halt animation with skycons.pause()

	// want to change the icon? no problem:
	//skycons.set("icon1", Skycons.PARTLY_CLOUDY_NIGHT);

	// want to remove one altogether? no problem:
	//skycons.remove("icon2");


	//IE doesn't like Google fonts...apparently it's Google's fault
	//at the moment, but whatever...load Web Safe font for IE users
	//and set the browser info in the footer

	var gbr = FUSION.get.browser();
// 	var nde = "footerbrowserok";
// 	var dbr = "detectedbrowserok";
// 	var fbt = gbr.browser + " " + gbr.version;
	if(gbr.browser && gbr.browser == "IE")
	{
		document.body.style.fontFamily = "'Trebuchet MS', Helvetica, sans-serif";
// 		fbt = "Internet Explorer " + gbr.version;
// 		nde = "footerbrowserbad";
// 		dbr = "detectedbrowserbad";
	}

// 	FUSION.get.node(dbr).style.display = "block";
// 	FUSION.get.node(nde).innerHTML = "Detected Browser: " + fbt;


	var val = FUSION.get.node("localzipcode").value;
	var info = {};

	if(supportsHtml5Storage())
	{
		var lsa = [];
		var lso = {};
		var lss = "";
		for(var i = 0; i < localStorage.length; i++)
		{
			lss = localStorage.getItem(localStorage.key(i));
			if(typeof lss !== undefined && localStorage.key(i).match(/^ocd\d+$/))
			{
				//throwing in a try/catch here, due to IE11 creating *weird* items
				//that can not be parsed by JSON.  The match statement above
				//should catch them, but juuuust in case, this will filter out
				//any other weirdness
				try
				{
					lso = JSON.parse(lss);
					if(lso.woeid && lso.woeid.match(/^\d+$/))
					{
						lsa.push(lso);
					}
				}
				catch(err)
				{
					FUSION.error.logError(err);
				}
			}
		}

		if(lsa.length > 0)
		{
			//sort descending by age, oldest to youngest entries
			lsa.sort(function(a,b) { return parseInt(a.order) - parseInt(b.order) } );

			//load the oldest (original) item in the array into the main area
			loadWeather(lsa[0].woeid);

			//create location divs for all localStorage items
			for(var j = 0; j < lsa.length; j++)
			{
				addCityDiv(lsa[j], false);
			}
		}
		else
		{
			//no existing localStorage item matches, so load the info based on the
			//users zip code
			info = {
				"type": "POST",
				"path": "php/library.php",
				"data": {
					"method": "getWeatherInfo",
					"libcheck": true,
					"zipcode": val

				},
				"func": getWeatherResponse
			};
			FUSION.lib.ajaxCall(info);
		}
	}
	else
	{
		//if an older browser that does not support localStorage,
		//just load the info by the user's Zip code
		info = {
			"type": "POST",
			"path": "php/library.php",
			"data": {
				"method": "getWeatherInfo",
				"libcheck": true,
				"zipcode": val,
				"localstore": false
			},
			"func": getWeatherResponse
		};
		FUSION.lib.ajaxCall(info);
	}

});


function supportsHtml5Storage()
{
	//generic function to check if the browser can handle
	//and use localStorage, or if they're living in the stone age
	try
	{
		return 'localStorage' in window && window['localStorage'] !== null;
	}
	catch(err)
	{
		FUSION.error.logError(err);
		return false;
	}
}


function hideSearchDiv(t)
{
	if(FUSION.lib.isBlank(t.value)){
		var ls = FUSION.get.node("locselect");
		ls.style.height = "0px";
		ls.innerHTML = "";
		ls.style.display = "none";
	}
}


function runSearch()
{
	var val = FUSION.get.node("searchbox").value;
	if(FUSION.lib.isBlank(val)){
		var atxt  = "<p style='margin:15px 5px 5px;text-align:center;font-size:16px;font-weight:bold;color:#D00;'>";
			atxt += "Please enter a search string!";
			atxt += "</p>";
		FUSION.lib.alert(atxt);
		return false;
	}

	val = val.replace(/\s/ig, "+");
	var info = {
		"type": "POST",
		"path": "php/weatherlib.php",
		"data": {
			"method": "getWeatherInfo",
			"libcheck": true,
			"searchstring": val
		},
		"func": runSearchResponse
	};
	FUSION.lib.ajaxCall(info);
}


function locationClick(h)
{
	var hash = JSON.parse(h);
	var info = {
		"type": "POST",
		"path": "php/weatherlib.php",
		"data": {
			"method": "getForecastInfo",
			"libcheck": true,
			"latitude": hash.lat,
			"longitude": hash.lng,
			"geoinfo": hash
		},
		"func": runSearchResponse
	};
	FUSION.lib.ajaxCall(info);
}


function runSearchResponse(h)
{
	var hash = h || {};
	var rc = hash['result_count'];

	if(parseInt(rc) > 1)
	{
		var locsel = FUSION.get.node("locselect");
		locsel.innerHTML = "";

		var geoinfo = {};
		var locs = 0;
		for(var key in hash)
		{
			if(/^geocodeid/.test(key) && locs < 5)
			{
				locs++;
				geoinfo = hash[key];
				var div = FUSION.lib.createHtmlElement({"type":"div",
														"attributes":{"class":"loclinkdiv"}});
				var lnk = FUSION.lib.createHtmlElement({"type":"a","text":geoinfo.address,
														"style":{
															"text-decoration":"none",
															"color":"#EEE",
															"display":"block",
															"width":"90%",
															"height":"40px",
															"margin-left":"5%",
															"margin-right":"5%"
														},
														"onclick":"locationClick('" + JSON.stringify(geoinfo) + "')",
													    "attributes":{"href":"javascript:void(0)"}});
				div.appendChild(lnk);
				locsel.appendChild(div);
			}
		}
		locsel.style.height = (locs * 40) + "px";
		locsel.style.display = "block";

	}
	else
	{
		processForecast(hash);
	}
}







function processForecast(h)
{
	//the catch-all function for most responses from the server
	//basically just fills in the information for a given location
	//based on the array returned by the server to the AJAX request
	//names should make it clear what each line is doing
	var hash = h || {};

	var geoinfo = {};
	for(var key in hash)
	{
		if(/^geocodeid/.test(key))
		{
			geoinfo = hash[key];
		}
	}

	//NEED TO REEXAMINE THE OBJSIZE FUNCTION...ITS ADDING A CHILD FOR THE NUMBER OF RESULTS
	if(FUSION.get.objSize(geoinfo) > 1)
	{
		var regstr = "";
		if(typeof geoinfo.country !== undefined && geoinfo.country == "US")
		{
			regstr = (typeof geoinfo.state !== undefined && !FUSION.lib.isBlank(geoinfo.state)) ?
						geoinfo.city + ", " + geoinfo.state : geoinfo.city;
		}
		else
		{
			var statestr = (typeof geoinfo.state !== undefined && !FUSION.lib.isBlank(geoinfo.state) && geoinfo.state != "NO_DATA") ?
								", " + geoinfo.state : "";
			var cntrystr = (typeof geoinfo.country !== undefined && !FUSION.lib.isBlank(geoinfo.country) && geoinfo.country != "NO_DATA") ?
								", " + geoinfo.country : "";
			regstr = geoinfo.city + statestr + cntrystr;
		}
		FUSION.get.node("footerlocation").innerHTML = geoinfo.address;
		FUSION.get.node("location").innerHTML 		= regstr;
		var sb = FUSION.get.node("searchbox");
		sb.value = "";
		hideSearchDiv(sb);


		var hrly = hash['hourly'];
		var rain = 0;
		var wind = 0;
		var drct = "";
		var wstr = "N/A 0 kph";
		var hobj = {};

		var ipp = 0;
		var skycons = new Skycons({
			"monochrome": true,
			"colors" : {
				"main": "#444"
			}
		});

		for(var i = 0; i < hrly.length; i++)
		{
			ipp = i + 1;
			FUSION.get.node("hrdisplay" + ipp).innerHTML = getTime(hrly[i]['time']);
			FUSION.get.node("hrcondtion" + ipp).innerHTML = hrly[i]['summary'];
			FUSION.get.node("hrtemp" + ipp).innerHTML = Math.round(hrly[i]['temperature']) + "&deg; C";
			rain = Math.round(hrly[i]['precipProbability'] * 100);
			rain += "%";
			FUSION.get.node("hrrainchance" + ipp).innerHTML = rain;
			wind = hrly[i]['windSpeed'];
			drct = hrly[i]['windBearing'];
			if(hrly[i]['windSpeed'] > 0){
				wstr = getWindBearing(hrly[i]['windBearing']) + " " + Math.round(hrly[i]['windSpeed']) + " kph";
			}
			FUSION.get.node("hrwind" + ipp).innerHTML = wstr;
			skycons.add("hricon" + ipp, hrly[i]['icon']);
		}

		var crnt = hash['current'];
		var daly = hash['daily'];

		FUSION.get.node("condition").innerHTML  = crnt['summary'] + ", " + Math.round(crnt['temperature']) + "&deg;";
		skycons.add("condimg", crnt['icon']);

		var wind = "N/A 0 kph";
		if(crnt['windSpeed'] > 0) {
			wind = getWindBearing(crnt['windBearing']) + " " + Math.round(crnt['windSpeed']) + " kph";
		}
		FUSION.get.node("wind").innerHTML		= wind;
		FUSION.get.node("sunrise").innerHTML	= getTime(daly[0]['sunriseTime']);
		FUSION.get.node("sunset").innerHTML		= getTime(daly[0]['sunsetTime']);
		FUSION.get.node("high").innerHTML 		= Math.round(daly[0]['temperatureMax']) + "&deg;";
		FUSION.get.node("low").innerHTML 		= Math.round(daly[0]['temperatureMin']) + "&deg;";
		FUSION.get.node("dailyfrc").innerHTML 	= daly[0]['summary'];

		var d = 0;
		var dstr = "";
		var k = 0;
		for(var j = 1; j < 5; j++)
		{
			k = j + 1;
			d = new Date(daly[j]['time'] * 1000);
			dstr = days[d.getDay()] + ", " + months[d.getMonth()] + " " + d.getDate();
			FUSION.get.node("dayofweek" + k).innerHTML  = dstr;
			FUSION.get.node("condition" + k).innerHTML  = daly[j]['summary'];
			FUSION.get.node("high" + k).innerHTML 		= Math.round(daly[j]['temperatureMax']) + "&deg;";
			FUSION.get.node("low" + k).innerHTML 		= Math.round(daly[j]['temperatureMin']) + "&deg;";
			skycons.add("condimg" + k, daly[j]['icon']);
		}

		skycons.play();


/*
	//quick check to see if a new location div is required
	//this should be false if the user clicks an existing location div
	//to load info for that area
	if(adv)
	{
		addCityDiv({
			"woeid": hash['woeid'],
			"city": loc.city,
			"region": loc.region,
			"order": Math.floor(Date.now() / 1000)
		}, lcs);
	}
*/
	}

}












function getWeather()
{
	//load the weather based on the entry from the search box
	//still need to handle cases where the city is returned but
	//not the region...doesn't really hurt anything, but doesn't
	//look good (see Mexico City, zip code 11111)
	var val = FUSION.get.node("searchbox").value;
	if(!val.match(/^(\d){5}$/))
	{
		FUSION.lib.alert("Please enter a valid zip code!");
		return false;
	}

	var info = {
		"type": "POST",
		"path": "php/library.php",
		"data": {
			"method": "getWeatherInfo",
			"libcheck": true,
			"zipcode": val
		},
		"func": getWeatherResponse
	};

	FUSION.lib.ajaxCall(info);
}


function loadWeather(id)
{
	//For when a user has items in localStorage and comes back to the page,
	//or when a user clicks a location div to load weather for that city
	var woeid = id || 0;
	if(woeid == 0)
	{
		FUSION.lib.alert("Unable to determine WOEID - please refresh page and try again");
		return false;
	}

	var info = {
		"type": "POST",
		"path": "php/library.php",
		"data": {
			"method": "getWeatherInfo",
			"libcheck": true,
			"woeid": woeid,
			"load": true
		},
		"func": getWeatherResponse
	};

	FUSION.lib.ajaxCall(info);
}


function removeCityDiv(id)
{
	//remove the location div - need to add in a sexier confirm box
	//in place of the standard confirm...so ugly
	var woeid = id || 0;
	if(woeid == 0)
	{
		FUSION.lib.alert("Unable to determine WOEID - please refresh page and try again");
		return false;
	}

	var yn = confirm("Are you sure you'd like to remove this entry?");
	if(yn)
	{
		FUSION.remove.node("ocd" + woeid);
		try{
			//attempt to remove the localStorage item...sometimes causes
			//an issue in older versions of IE...because of course it does
			localStorage.removeItem("ocd" + woeid);
		}
		catch(err){
			FUSION.error.logError(err);
		}
	}
}


function addCityDiv(h, ls)
{
	var hash = h  || {};
	var lcst = ls || false;

	var div = FUSION.get.node("oldcitydiv");
	var els = div.getElementsByTagName("div");

	if(lcst)
	{
		//if no localStorage item exists, create one if possible
		localStorage.setItem("ocd" + hash['woeid'], JSON.stringify(hash));
	}

	if(els.length == 4)
	{
		//if there are already 4 locations stored, remove the oldest one
		//techincally it removes the last div, which *should* be the oldest,
		//but I should really do a sort here to make sure...I'll come back to it
		var eid = els[3].id;
		FUSION.remove.node(eid);
		localStorage.removeItem(eid);
	}

	var chk = FUSION.get.node("ocd" + hash['woeid']);
	if(!chk)
	{
		//begin creating the location box - just a div that holds a link and a span,
		//pretty straight-forward
		var ndv = FUSION.lib.createHtmlElement({"type":"div",
												"attributes":{
													"id": "ocd" + hash['woeid'], "class":"citydiv" }});

		var regstr = (typeof hash.region !== undefined && !FUSION.lib.isBlank(hash.region)) ?
					hash.city + ", " + hash.region : hash.city;

		var lnk = FUSION.lib.createHtmlElement({"type":"a",
												"onclick":"loadWeather(" + hash['woeid'] + ")",
												"text": regstr,
												"attributes":{
													"href":"javascript:void(0);",
													"title": regstr,
													"class":"citylink"
												}});
		//check the browser - IE is so fickle with glyphicons, so rather than deal with
		//every possible case, just use the "X" if it's IE, otherwise load the glyphicons
		//yes, I know that this doesn't account for older versions of FF/Chrome/etc, BUT
		//let's be honest...if someone is using those browsers, they're probably updating
		//them too (or at least have something newer than FF v4)
		//var gbr = FUSION.get.browser();
		//var brs = gbr['browser'];

		var img = FUSION.lib.createHtmlElement({"type":"img",
												"onclick":"removeCityDiv(" + hash['woeid'] + ")",
												"style":{"width":"12px","height":"12px"},
												"attributes":{
													"src":"../images/iconic/x-6x.png",
													"class":"removespan",
													"title":"Remove Location" }});
		ndv.appendChild(lnk);
		ndv.appendChild(img);

		//another concession for IE...insertBefore has issues in IE if there are no
		//existing elements in the parent div.  Because of course it does.
		if(els.length == 0)
		{
			div.appendChild(ndv);
		}
		else
		{
			div.insertBefore(ndv, div.childNodes[0]);
		}
	}
}


function getWeatherResponse(h)
{
	//the catch-all function for most responses from the server
	//basically just fills in the information for a given location
	//based on the array returned by the server to the AJAX request
	//names should make it clear what each line is doing
	var hash = h || {};
	var wnd = hash['wind'];
	var loc = hash['location'];
	var frc = hash['forecast'];
	var con = hash['conditions'];
	var ast = hash['astronomy'];
	var atm = hash['atmosphere'];
	var adv = hash['adddiv'];
	var lcs = hash['localstore'];

	FUSION.get.node("searchbox").value = "";

	//fill the main section with today's forecast info
	var regstr = (typeof loc.region !== undefined && !FUSION.lib.isBlank(loc.region)) ?
					loc.city + ", " + loc.region : loc.city;

	FUSION.get.node("footerlocation").innerHTML = regstr;
	FUSION.get.node("location").innerHTML 		= regstr;
	FUSION.get.node("date").innerHTML 			= frc[0].day + " / " + frc[0].dstr;

	FUSION.get.node("condition").innerHTML  = con.text + ", " + con.temp + "&deg;";
	FUSION.get.node("condimg").src 			= con.img;

	FUSION.get.node("high").innerHTML 		= frc[0].high;
	FUSION.get.node("low").innerHTML 		= frc[0].low;
	FUSION.get.node("dailyfrc").innerHTML 	= frc[0].text;

	FUSION.get.node("sunrise").innerHTML = ast.sunrise;
	FUSION.get.node("sunset").innerHTML  = ast.sunset;
	FUSION.get.node("wind").innerHTML 	 = wnd.speed + " mph, " + wnd.direction + ", " + wnd.chill;

	//fill the 4 location cards below the main section
	var j = 0;
	for(var i = 1; i < frc.length; i++)
	{
		j = i + 1;
		FUSION.get.node("dayofweek" + j).innerHTML  = frc[i].date;
		FUSION.get.node("condition" + j).innerHTML  = frc[i].text;
		FUSION.get.node("condimg" + j).src 			= frc[i].img;
		FUSION.get.node("high" + j).innerHTML 		= frc[i].high;
		FUSION.get.node("low" + j).innerHTML 		= frc[i].low;
	}

	//quick check to see if a new location div is required
	//this should be false if the user clicks an existing location div
	//to load info for that area
	if(adv)
	{
		addCityDiv({
			"woeid": hash['woeid'],
			"city": loc.city,
			"region": loc.region,
			"order": Math.floor(Date.now() / 1000)
		}, lcs);
	}
}

//just a little shim pulled from the Net to handle Date.now() calls
//older versions of IE don't like it, so this makes it forward AND
//backwards compatible.  Thanks stranger from Stackoverflow!
if (!Date.now) { Date.now = function() { return new Date().getTime(); }}


function getWindBearing(b)
{
	var brng = b || 0;
	var drct = Math.floor((brng + 11.25) / 22.5);
	if(drct < 16){
		return directions[drct];
	}
	else{
		return "N/A";
	}

}


function getTime(ts)
{
	if(typeof ts === "undefined" || typeof ts !== "number" || FUSION.lib.isBlank(ts))
	{
		alert("Invalid timestamp given: " + ts + "\nUnable to determine time");
		return "";
	}
	else
	{
		var date  	= new Date(ts * 1000);
		var hours 	= date.getHours();
		var minutes = "0" + date.getMinutes();
		var hour  	= hours > 11 ? (hours % 12) : (parseInt(hours) == 0) ? 12 : hours;
		var hour  	= 0;
		var ampm  	= "AM";

		if(hours > 11) {
			hour = (hours == 12) ? hours : hours % 12;
			ampm = "PM";
		}
		else if(hours == 0) {
			hour = 12;
		}
		else {
			hour = hours;
		}
		return hour + ':' + minutes.substr(minutes.length-2) + " " + ampm;
	}
}
