
$( document ).ready(function() {

	if(supportsHtml5Storage())
	{
		if(localStorage.length > 0)
		{
			var lsa = [];
			var lso = {};
			var lss = "";
			for(var i = 0; i < localStorage.length; i++)
			{
				var lss = localStorage.getItem(localStorage.key(i));
				if(typeof lss !== undefined)
				{
					lso = JSON.parse(lss);
					if(lso.woeid && lso.woeid.match(/^\d+$/))
					{
						lsa.push(lso);
					}
				}
			}

			lsa.sort(function(a,b) { return parseInt(a.order) - parseInt(b.order) } );

			loadWeather(lsa[0].woeid);
			for(var j = 0; j < lsa.length; j++)
			{
				addCityDiv(lsa[j], false);
			}
		}
		else
		{
			var val = FUSION.get.node("localzipcode").value;
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
	}
	else
	{
		var val = FUSION.get.node("localzipcode").value;
		var info = {
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


function supportsHtml5Storage() {
	try {
		return 'localStorage' in window && window['localStorage'] !== null;
	} catch (e) {
		return false;
	}
}

function getWeather()
{
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
	var woeid = id || 0;
	if(woeid == 0)
	{
		FUSION.lib.alert("Unable to determine WOEID - please refresh page and try again");
		return false;
	}

	var yn = confirm("Are you sure you'd like to remove this entry?");
	if(yn)
	{
		FUSION.remove.nodeById("ocd" + woeid);
		localStorage.removeItem("ocd" + woeid);
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
		localStorage.setItem("ocd" + hash['woeid'], JSON.stringify(hash));
	}

	if(els.length == 4)
	{
		var eid = els[3].id;
		FUSION.remove.nodeById(eid);
		localStorage.removeItem(eid);
	}

	var chk = FUSION.get.node("ocd" + hash['woeid']);
	if(!chk)
	{
		var ndv = FUSION.lib.createHtmlElement({"type":"div", "attributes":{ "id": "ocd" + hash['woeid'], "class":"citydiv" }});
		var lnk = FUSION.lib.createHtmlElement({"type":"a", "onclick":"loadWeather(" + hash['woeid'] + ")",
												"text": hash.city + ", " + hash.region,
												"attributes":{
													"href":"javascript:void(0);",
													"title":hash.city + ", " + hash.region,
													"class":"citylink"
												}});
		var spn = FUSION.lib.createHtmlElement({"type":"span", "onclick":"removeCityDiv(" + hash['woeid'] + ")",
												"attributes":{ "class":"glyphicon glyphicon-remove removespan", "title":"Remove Location" }});
		ndv.appendChild(lnk);
		ndv.appendChild(spn);

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

	FUSION.get.node("location").innerHTML 	= loc.city + ", " + loc.region;
	FUSION.get.node("dayofweek").innerHTML 	= frc[0].day;
	FUSION.get.node("date").innerHTML 		= frc[0].dstr;

	FUSION.get.node("condition").innerHTML  = con.text + ", " + con.temp + "&deg;";
	FUSION.get.node("condimg").src 			= con.img;

	FUSION.get.node("high").innerHTML 		= "HIGH: " + frc[0].high;
	FUSION.get.node("low").innerHTML 		= "LOW: "  + frc[0].low;
	FUSION.get.node("dailyfrc").innerHTML 	= "FORECAST: " + frc[0].text;

	FUSION.get.node("sunrise").innerHTML = "SUNRISE: " + ast.sunrise;
	FUSION.get.node("sunset").innerHTML  = "SUNSET: "  + ast.sunset;

	FUSION.get.node("windspeed").innerHTML 		= "WIND SPEED: " + wnd.speed;
	FUSION.get.node("winddirection").innerHTML  = "DIRECTION: " + wnd.direction;
	FUSION.get.node("windchill").innerHTML 		= "CHILL: " + wnd.chill;

	var j = 0;
	for(var i = 1; i < frc.length; i++)
	{
		j = i + 1;
		FUSION.get.node("dayofweek" + j).innerHTML  = frc[i].date;
		FUSION.get.node("condition" + j).innerHTML  = frc[i].text;
		FUSION.get.node("condimg" + j).src 			= frc[i].img;
		FUSION.get.node("high" + j).innerHTML 		= frc[i].high;
		FUSION.get.node("low" + j).innerHTML 		= frc[i].low;
		FUSION.get.node("icon" + j).className 		= "condition-icon " + frc[i].text.replace(/\s/g, "-");
	}

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


if (!Date.now) {
    Date.now = function() { return new Date().getTime(); }
}