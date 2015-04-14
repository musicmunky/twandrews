var cal_current_date 	= new Date();
var cal_days_labels		= ['Sun', 'Mon', 'Tue', 'Wed', 'Thr', 'Fri', 'Sat'];
var cal_days_in_month	= [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
var cal_months_labels	= ['January', 'February', 'March', 'April', 'May', 'June', 'July',
						   'August', 'September', 'October', 'November', 'December'];

var holidays = {
	2013: [[0,1], [0,21], [1,18], [4,27], [6,4], [8,2], [9,14], [10,11], [10,28], [11,25]],
	2014: [[0,1], [0,20], [1,17], [4,26], [6,4], [8,1], [9,13], [10,11], [10,27], [11,25]],
	2015: [[0,1], [0,19], [1,16], [4,25], [6,3], [8,7], [9,12], [10,11], [10,26], [11,25]]
};

var kellys = {
	2013: [[0,30], [1,23], [2,19], [3,12], [3,15], [4,9], [5,2], [5,26], [6,20],
		   [7,13], [8,6], [8,9], [9,3], [9,27], [10,20], [11,14]],
	2014: [[0,7], [0,31], [1,3], [1,27], [2,23], [3,16], [4,10], [5,3], [5,27],
		   [5,30], [6,24], [7,17], [8,10], [9,4], [9,28], [10,21], [10,24], [11,18]],
	2015: [[0,11], [1,4], [1,28], [2,24], [3,17], [3,20], [4,14], [5,7], [6,1],
		   [6,25], [7,18], [8,11], [8,14], [9,8], [10,1], [10,25], [11,19]]
};

var paydays = {
	2013: [[0,4], [0,18], [1,1], [1,15], [2,1], [2,15], [2,29], [3,12], [3,26], [4,10], [4,24], [5,7], [5,21],
		   [6,5], [6,19], [7,2], [7,16], [7,30], [8,13], [8,27], [9,11], [9,25], [10,8], [10,22], [11,6], [11,20]],
	2014: [[0,3], [0,17], [0,31], [1,14], [1,28], [2,14], [2,28], [3,11], [3,25], [4,9], [4,23], [5,6], [5,20],
		   [6,4], [6,18], [7,1], [7,15], [7,29], [8,12], [8,26], [9,10], [9,24], [10,7], [10,21], [11,5], [11,19]],
	2015: [[0,9], [0,26], [1,10], [1,25], [2,10], [2,25], [3,10], [3,24], [4,11], [4,25], [5,10], [5,25],
		   [6,10], [6,24], [7,10], [7,25], [8,10], [8,25], [9,9], [9,26], [10,10], [10,25], [11,10], [11,24]]
};


function setCalInfo()
{
	var curr_year = new Date().getFullYear();
	var slct_year = document.getElementById("year").value;
	var year = (curr_year != slct_year) ? parseInt(slct_year) : parseInt(curr_year);
	var montharray = [  'January', 'February', 'March', 'April', 'May', 'June', 'July',
						'August', 'September', 'October', 'November', 'December' ];
	var firstworks = {
		2013: [ 3, 2, 1, 3, 3, 2, 2, 1, 3, 3, 2, 2 ],
		2014: [ 1, 3, 2, 1, 1, 3, 3, 2, 1, 1, 3, 3 ],
		2015: [ 2, 1, 3, 2, 2, 1, 1, 3, 2, 2, 1, 1 ],
	};
	document.getElementById("title").innerHTML = year + " Calendar";
	for(var i = 0; i < 12; i++)
	{
		var cal = new CalMonth(i, year, firstworks[year][i]);
		cal.generateHTML();
		document.getElementById("div" + montharray[i]).innerHTML = cal.getHTML();
	}
}


function CalMonth(month, year, firstwork)
{
	this.month = (isNaN(month) || month == null) ? cal_current_date.getMonth() : month;
	this.year = (isNaN(year) || year == null) ? cal_current_cate.getFullYear() : year;
	this.firstwork = (isNaN(firstwork) || firstwork == null) ? 0 : firstwork;
	this.html = '';
}


CalMonth.prototype.generateHTML = function()
{
	var firstDay = new Date(this.year, this.month, 1);
	var startingDay = firstDay.getDay();
	var monthLength = cal_days_in_month[this.month];
	var holidaystyle = '';

	if(this.month == 1)
	{
		if((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
		{
			monthLength = 29;
		}
	}

	var monthName = cal_months_labels[this.month];
	var html = '<table class="calendar-table">';
	html += '<tr><th colspan="7">' + monthName + " " + this.year + '</th></tr>';
	html += '<tr class="calendar-header">';
	for(var i = 0; i < 7; i++)
	{
		html += '<td class="calendar-header-day">';
		html += cal_days_labels[i];
		html += '</td>';
	}
	html += '</tr><tr>';
	var day = 1;
	var workday = this.firstwork;
	var cssclass = "";
	var dayhtml = "";
	for(var i = 0; i < 6; i++) // max of 6 weeks in a month (first day on a Fri/Sat, last day on a Sun/Mon, spans 6 weeks)
	{
		for(var j = 0; j < 7; j++) // seven days in the weeks
		{
			cssclass = "calendar-day-off";
			if(workday == day && (i > 0 || j >= startingDay))
			{
				var kell = kellys[this.year];
				for(var z = 0; z < kell.length; z++)
				{
					cssclass = "calendar-day-work";
					if(this.month == kell[z][0] && day == kell[z][1])
					{
						cssclass = "calendar-day-kelly";
						break;
					}
				}
				workday += 3;
			}
			if(day <= monthLength && (i > 0 || j >= startingDay))
			{
				var hol = holidays[this.year];
				for(var k = 0; k < hol.length; k++)
				{
					if(this.month == hol[k][0] && day == hol[k][1])
					{
						holidaystyle = 'style="background-color:#FFFF88;"';
						break;
					}
				}

				dayhtml = day;
				var pay = paydays[this.year];
				for(var n = 0; n < pay.length; n++)
				{
					if(this.month == pay[n][0] && day == pay[n][1])
					{
						dayhtml = "<i>" + day + "</i>";
						break;
					}
				}
				day++;
			}
			else
			{
				dayhtml = "";
			}
			html += '<td class="' + cssclass + '" ' + holidaystyle + '>' + dayhtml + '</td>';
			holidaystyle = '';
		}
		if(day > monthLength)
		{
			break;
		}
		else
		{
			html += '</tr><tr>';
		}
	}
	html += '</tr></table>';
	this.html = html;
}


CalMonth.prototype.getHTML = function()
{
	return this.html;
}
