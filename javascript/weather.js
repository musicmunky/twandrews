var days_labels		= ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

var months_labels	= [ 'January', 'February', 'March', 'April', 'May', 'June', 'July',
					 	'August', 'September', 'October', 'November', 'December'];

var months_labels_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
						   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

var directions = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE",
                  "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];

//all the weather codes from this page (about halfway down):
//	https://developer.yahoo.com/weather/documentation.html
var csscodes = {
	0: 	"tornado",
	1: 	"tropical-storm",
	2: 	"hurricane",
	3: 	"severe-thunderstorms",
	4: 	"thunderstorms",
	5: 	"mixed-rain-and-snow",
	6: 	"mixed-rain-and-sleet",
	7: 	"mixed-snow-and-sleet",
	8: 	"freezing-drizzle",
	9: 	"drizzle",
	10: "freezing-rain",
	11: "showers",
	12: "showers",
	13: "snow-flurries",
	14: "light-snow-showers",
	15: "blowing-snow",
	16: "snow",
	17: "hail",
	18: "sleet",
	19: "dust",
	20: "foggy",
	21: "haze",
	22: "smoky",
	23: "blustery",
	24: "windy",
	25: "cold",
	26: "cloudy",
	27: "mostly-cloudy-night",
	28: "mostly-cloudy-day",
	29: "partly-cloudy-night",
	30: "partly-cloudy-day",
	31: "clear-night",
	32: "sunny",
	33: "fair-night",
	34: "fair-day",
	35: "mixed-rain-and-hail",
	36: "hot",
	37: "isolated-thunderstorms",
	38: "scattered-thunderstorms",
	39: "scattered-thunderstorms",
	40: "scattered-showers",
	41: "heavy-snow",
	42: "scattered-snow-showers",
	43: "heavy-snow",
	44: "partly-cloudy",
	45: "thundershowers",
	46: "snow-showers",
	47: "isolated-thundershowers",
	3200: "not-available"
};


function testLoad()
{
	google.load('feeds', '1', {"callback": initialize});
}

//google.load("feeds", "1");
//google.setOnLoadCallback(initialize);
google.load('feeds', '1', {"callback": initialize});
//google.feeds.Feed.XML_FORMAT;


function initialize()
{
	var feed = new google.feeds.Feed("http://weather.yahooapis.com/forecastrss?w=2503308");
	feed.setResultFormat(google.feeds.Feed.XML_FORMAT);
	feed.load(function(result) {
		if (!result.error) {

			var xml = result.xmlDocument;
			var imgstr = "http://l.yimg.com/a/i/us/we/52/";

			var ywf   = xml.getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "forecast");
			var conel = xml.getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "condition")[0].attributes;
			var locel = xml.getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "location")[0].attributes;
			var astel = xml.getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "astronomy")[0].attributes;
			var atmel = xml.getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "atmosphere")[0].attributes;
			var wndel = xml.getElementsByTagNameNS("http://xml.weather.yahoo.com/ns/rss/1.0", "wind")[0].attributes;

			var att = ywf[0].attributes;
			var datel = xml.getElementsByTagName("lastBuildDate")[0];

			var dt = new Date(datel.textContent);
			var dystr = days_labels[dt.getDay()];
			var dtstr = months_labels[dt.getMonth()] + " " + dt.getDate() + ", " + dt.getUTCFullYear();

			var spd = parseInt(wndel.speed.value);
			var dir = "N/A";
			if(spd > 0){
				var deg = parseInt(wndel.direction.value);
				var res = Math.floor((deg + 11.25) / 22.5);
				dir = directions[res % 16];
			}

			FUSION.get.node("location").innerHTML = locel.city.value + ", " + locel.region.value;
			FUSION.get.node("date").innerHTML = dtstr;
			FUSION.get.node("dayofweek").innerHTML = dystr;
			FUSION.get.node("condition").innerHTML = conel.text.value;
			FUSION.get.node("condimg").src = imgstr + conel.code.value + ".gif";
			FUSION.get.node("high").innerHTML = "HIGH: " + att.high.value;
			FUSION.get.node("low").innerHTML = "LOW: " + att.low.value;
			//FUSION.get.node("humidity").innerHTML  = "HUMIDITY: " + atmel.humidity.value;
			//FUSION.get.node("humidity").innerHTML += "<br>PRESSURE: " + atmel.pressure.value;
			//FUSION.get.node("humidity").innerHTML += "<br>VISIBILITY: " + atmel.visibility.value;
			FUSION.get.node("sunrise").innerHTML = "SUNRISE: " + astel.sunrise.value;
			FUSION.get.node("sunset").innerHTML = "SUNSET: " + astel.sunset.value;
			FUSION.get.node("wind").innerHTML  = "WIND SPEED: " + spd;
			FUSION.get.node("wind").innerHTML += "<br>DIRECTION: " + dir;
			FUSION.get.node("wind").innerHTML += "<br>CHILL: " + wndel.chill.value;

			var txt = "";
			var cde = "";
			var dte = "";
			var frc = "";
			var dst = "";
			var j = 0;
/*
			for(var i = 2; i < ywf.length; i++)
			{
				j = i + 1;
				att = ywf[i].attributes; //get ALL the attributes for the node
				cde = (att.code) ? att.code.value : ""; //the forecast code for each day
				dte = (att.date) ? att.date.value : ""; //today's date, in case you need it later
				frc = (typeof csscodes === "object") ? csscodes[cde] : "";
				txt = (att.text) ? att.text.value.replace(/\./g, "") : "";

				var mth = "";
				if(!FUSION.lib.isBlank(dte)){
					var nd = new Date(dte);
					mth = ", " + months_labels_short[nd.getMonth()] + " " + nd.getDate();
				}
				dst = (att.day)  ? att.day.value : "";
				FUSION.get.node("condition" + j).innerHTML  = txt;
				FUSION.get.node("condimg" + j).src = imgstr + cde + ".gif";
				FUSION.get.node("dayofweek" + j).innerHTML  = dst + mth;
				FUSION.get.node("high" + j).innerHTML 		= (att.high) ? att.high.value + "&deg;" : "";
				FUSION.get.node("low" + j).innerHTML 		= (att.low)  ? att.low.value + "&deg;" : "";
				FUSION.get.node("icon" + j).className 		= "condition-icon " + txt.replace(/\s/g, "-");
			}
*/
		}
	});
}
google.setOnLoadCallback(initialize);


/*var container = document.getElementById("forecast");
var fed = result.feed.entries;
var ent = result.feed.entries[0].content;
ent = ent.replace(/\r?\n|\r/g, "");
var entar = ent.split(/\<br\s*[\/]?\>/g);
var parentdiv = document.createElement("div");
var childdiv = null;
for(var i = 0; i < entar.length; i++)
{
	if(entar[i].match(/^(sun|mon|tue|wed|thu|fri|sat){1}\s+-/i))
	{
		var a = entar[i].split(/\s*-\s?/);
		var day = a[0];
		var war = a[1].split(" ");
		var idx = war.indexOf("High:");
		var ht = war[idx + 1];
		var lt = war[idx + 3];
		var td = war.slice(0,idx).join(" ");

		var nam = document.createElement("div");
		var tdy = document.createElement("div");
		var hgh = document.createElement("div");
		var low = document.createElement("div");

		nam.innerHTML = day;
		tdy.innerHTML = td.replace(/\./g, "");
		hgh.innerHTML = "High: " + ht;
		low.innerHTML = "Low: " + lt;

		childdiv = document.createElement("div");
		childdiv.setAttribute("id", "cd_" + i);
		childdiv.appendChild(nam);
		childdiv.appendChild(tdy);
		childdiv.appendChild(hgh);
		childdiv.appendChild(low);
		parentdiv.appendChild(childdiv);
	}
}
container.appendChild(parentdiv);*/