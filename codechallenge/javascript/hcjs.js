/*
 * Highcharts implementation code for Code Challenge!
 * Moved to a separate file for readability/maintainability/...ility/etc
 */

function getChart()
{
	sliderUpdateChart();
}


function sliderUpdateChart(mm)
{
	var minmax = mm || {};
	var charttype = FUSION.lib.isBlank(FUSION.get.node("charttype").value) ? "pie" : FUSION.get.node("charttype").value;
	switch(charttype) {
		case "pie":
			getPieChart(minmax);
			break;
		case "column":
			getColumnChart();
			break;
		case "bar":
			getBarChart(minmax);
			break;
		default:
			getPieChart(minmax);
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


function getPieChart(mm)
{
	var param	= FUSION.get.node("chartparams").value;
	var partxt	= FUSION.get.selectedText("chartparams");
	var start	= "";
	var end 	= "";

	var lsdata = localStorage.getItem("SODAquery");
	var sodata = {};
	var chdata = [];
	try {
		sodata  = JSON.parse(lsdata);
		start	= mm.hasOwnProperty("min") ? mm['min'] : sodata['start_date'];
		end 	= mm.hasOwnProperty("max") ? mm['max'] : sodata['end_date'];
	}
	catch(err){
		FUSION.error.logError(err);
	}

	var totals = parseInt(sodata['response_count']);
	if(totals > 0)
	{
		var tmp = {};
		var entry = {};
		var pval = "";
		for(var i = 0; i < totals; i++)
		{
			tmp = sodata['response_content'][i];
			if(tmp.hasOwnProperty(param))
			{
				pval = tmp[param];
				if(compareIncidentTime(start, end, tmp['event_clearance_date']))
				{
					var check = $.grep(chdata, function(e) { return e.name == pval });
					if(check.length == 0)
					{
						chdata.push({ name: pval, y: 1 });
					}
					else
					{
						check[0].y++;
					}
				}
			}
		}

		$('#hc-container').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: "Crimes Reported Around CenturyLink Field, Grouped By " + partxt + "(" + chdata.length + ")"
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
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
						}
					}
				}
			},
			series: [{
				name: "Reported Crimes",
				colorByPoint: true,
				data: chdata
			}]
		});
	}
}


function getBarChart(mm)
{
	var param = FUSION.get.node("chartparams").value;
	var partxt = FUSION.get.selectedText("chartparams");

	var lsdata = localStorage.getItem("SODAquery");
	var sodata = {};
	var chdata = [];
	try {
		sodata = JSON.parse(lsdata);
	}
	catch(err){
		FUSION.error.logError(err);
	}

	var totals = parseInt(sodata['response_count']);
	if(totals > 0)
	{
		var tmp = {};
		var entry = {};
		var pval = "";
		for(var i = 0; i < totals; i++)
		{
			tmp = sodata['response_content'][i];
			if(tmp.hasOwnProperty(param))
			{
				pval = tmp[param];
				if(compareIncidentTime(start, end, tmp['event_clearance_date']))
				{
					var check = $.grep(chdata, function(e) { return e.name == pval });
					if(check.length == 0)
					{
						chdata.push({ name: pval, data: [1] });
					}
					else
					{
						check[0].data[0]++;
					}
				}
			}
		}

		$('#hc-container').highcharts({
			chart: {
				type: 'bar'
			},
			title: {
				text: "Crimes Reported Around CenturyLink Field, Grouped By " + partxt
			},
			xAxis: {
				categories: [partxt],
				title: {
					text: null
				}
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
			tooltip: {
				valueSuffix: ''
			},
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
				backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
				shadow: false
			},
			credits: {
				enabled: false
			},
			series: chdata
		});
	}
}


function getColumnChart()
{
	$('#hc-container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Monthly Average Rainfall'
        },
        subtitle: {
            text: 'Source: WorldClimate.com'
        },
        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Rainfall (mm)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Tokyo',
            data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

        }, {
            name: 'New York',
            data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

        }, {
            name: 'London',
            data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

        }, {
            name: 'Berlin',
            data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

        }]
    });
}