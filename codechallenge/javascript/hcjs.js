/*
 * Highcharts implementation code for Code Challenge!
 * Moved to a separate file for readability/maintainability/...ility/etc
 */


function sliderUpdateChart(mm)
{
	var minmax = mm || {};
	var charttype = FUSION.lib.isBlank(FUSION.get.node("charttype").value) ? "pie" : FUSION.get.node("charttype").value;

	var param  = FUSION.get.node("chartparams").value;
	var partxt = FUSION.get.selectedText("chartparams");
	var start	= "";
	var end 	= "";

	var sodata = {};
	var chdata = [];

	try {
		var lsdata = localStorage.getItem("SODAquery");
		sodata  = JSON.parse(lsdata);
		start	= minmax.hasOwnProperty("min") ? minmax['min'] : sodata['start_date'];
		end 	= minmax.hasOwnProperty("max") ? minmax['max'] : sodata['end_date'];
	}
	catch(err){
		FUSION.error.logError(err);
		return false;
	}

	var totals = parseInt(sodata['response_count']);
	if(totals > 0)
	{
		var tmp = {};
		var entry = {};
		var pval = "";
		var newtotal = 0;
		clearMarkers();

		for(var i = 0; i < totals; i++)
		{
			tmp = sodata['response_content'][i];
			if(tmp.hasOwnProperty(param))
			{
				pval = tmp[param];
				if(compareIncidentTime(start, end, tmp['event_clearance_date']))
				{
					var check = $.grep(chdata, function(e) { return e.name == pval });
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(tmp.latitude, tmp.longitude),
						map: map,
						title: tmp.initial_type_group
					});
					markers.push(marker);

					if(charttype == "pie"){
						if(check.length == 0)
						{
							chdata.push({ name: pval, y: 1 });
						}
						else
						{
							check[0].y++;
						}
					}
					else
					{
						if(check.length == 0)
						{
							chdata.push({ name: pval, data: [1] });
						}
						else
						{
							check[0].data[0]++;
						}
					}
					newtotal++;
				}
			}
		}

		var chartdata = {
			"partxt": partxt,
			"newtotal": newtotal,
			"chdata": chdata
		}
		switch(charttype) {
			case "pie":
				getPieChart(chartdata);
				break;
			case "column":
				getColumnChart(chartdata);
				break;
			case "bar":
				getBarChart(chartdata);
				break;
			default:
				getPieChart(chartdata);
		}
	}
}


function compareIncidentTime(s, e, i)
{
	var start	 = moment(s);
	var end 	 = moment(e);
	var incident = moment(i);
	var btwn	 = (incident >= start && incident <= end) ? true : false;
	return btwn;
}


function getPieChart(obj)
{
	$('#hc-container').highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: "Crimes Reported Around CenturyLink Field, Grouped By " + obj['partxt'] + "(" + obj['newtotal'] + ")"
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %',
					style: {
						color: '#000'
					}
				}
			}
		},
		series: [{
			name: "Reported Crimes",
			colorByPoint: true,
			data: obj['chdata']
		}]
	});
}


function getBarChart(obj)
{
	$('#hc-container').highcharts({
		chart: {
			type: 'bar'
		},
		title: {
			text: "Crimes Reported Around CenturyLink Field, Grouped By " + obj['partxt'] + "(" + obj['newtotal'] + ")"
		},
		xAxis: {
			categories: [obj['partxt']],
			title: { text: null }
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Incidents Reported',
				align: 'high'
			},
			labels: {
				overflow: 'justify'
			}
		},
		tooltip: {},
		plotOptions: {
			bar: {
				dataLabels: {
					enabled: true
				}
			}
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'top',
			x: -10,
			y: 80,
			floating: false,
			borderWidth: 1,
			backgroundColor: '#FFFFFF',
			shadow: false
		},
		credits: {
			enabled: false
		},
		series: obj['chdata']
	});

}


function getColumnChart(obj)
{
	$('#hc-container').highcharts({
		chart: {
			type: 'column'
		},
		title: {
			text: "Crimes Reported Around CenturyLink Field, Grouped By " + obj['partxt'] + "(" + obj['newtotal'] + ")"
		},
		subtitle: { text: "" },
		xAxis: {
			categories: [obj['partxt']],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Incidents Reported',
			}
		},
		tooltip: {},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: obj['chdata']
	});

}