$( document ).ready(function() {

	//IE doesn't like Google fonts...apparently it's Google's fault
	//at the moment, but whatever...load Web Safe font for IE users
	var gbr = FUSION.get.browser();
	if(gbr.browser && gbr.browser == "IE")
	{
		document.body.style.fontFamily = "'Trebuchet MS', Helvetica, sans-serif";
	}

	jQuery(".citydiv").click(function(){
		showDisplay(this);
	});

	jQuery("#querybtn").click(function(){
		runSearch();
	});

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

	clearSearchForm();
	//runSearch();

/*
	$('#container').highcharts({
        title: {
            text: 'Monthly Average Temperature',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: WorldClimate.com',
            x: -20
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '°C'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Tokyo',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: 'New York',
            data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
        }, {
            name: 'Berlin',
            data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
        }, {
            name: 'London',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    });
*/

});


function showDisplay(t)
{
	var id = "";
	var div = {};
	var ary = [];
	$(".citydiv").each( function() {
		$(this).css({
			"background-color": "#FFF",
			"border-right": "none",
			"border-left": "none",
// 			"width": "25%",
		});
		id = $(this).attr("id");
		ary = id.split("-");
		FUSION.get.node(ary[0] + "-div").style.display = "none";
	});

	$(t).css({
// 			"width": "calc(25% - 2px)",
			"background-color": "#EEE",
			"border-right": "1px solid #DDD",
			"border-left": "1px solid #DDD",
	});
	ary = $(t).attr("id").split("-");
	var divid = ary[0];
	/*
	switch(divid) {
		case "highcharts":
			FUSION.lib.alert("<p>Error completing request: " + divid + "</p>");
			break;
		case "googlemaps":
			FUSION.lib.alert("<p>Call to server aborted: " + divid + "</p>");
			break;
		default:
			FUSION.error.logError("Invalid option selected", "Page Error");
	}*/
	FUSION.get.node(divid + "-div").style.display = "block";
}


function runSearch()
{

	var str = "800 Occidental Ave S, Seattle, WA 98134"; //default address provided by Code Challenge


	var max = FUSION.get.node("maxresults").value ? FUSION.get.node("maxresults").value : 1000;
	max = parseInt(max) > 50000 ? 50000 : max;

	var rng = 1;
	if(FUSION.get.node("range").value)
	{
		rng = FUSION.get.node("range").value;
	}

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


function processSearchResults(h)
{
	var hash = h || {};
	var content = hash['response_content'];

	var mapOptions = {
		zoom: 13,
		center: new google.maps.LatLng(hash['latitude_center'], hash['longitude_center'])
	};

	var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

	for(var i = 0; i < hash['response_count']; i++)
	{
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(content[i].latitude, content[i].longitude),
			map: map,
			title: content[i].event_clearance_group
		});
	}
}


function clearSearchForm()
{
	FUSION.get.node("startdate").value = "";
	FUSION.get.node("enddate").value = "";
	FUSION.get.node("maxresults").value = "";
	FUSION.get.node("range").selectedIndex = 0;
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

