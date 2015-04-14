cal_days_labels		= ['Sun', 'Mon', 'Tue', 'Wed', 'Thr', 'Fri', 'Sat'];
cal_months_labels	= ['January', 'February', 'March', 'April', 'May', 'June', 'July',
					   'August', 'September', 'October', 'November', 'December'];
cal_days_in_month	= [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

holidays = new Array();
holidays[0] = new Array(0, 1);
holidays[1] = new Array(0, 19);
holidays[2] = new Array(1, 16);
holidays[3] = new Array(4, 25);
holidays[4] = new Array(6, 3);
holidays[5] = new Array(8, 7);
holidays[6] = new Array(9, 12);
holidays[7] = new Array(10, 11);
holidays[8] = new Array(10, 26);
holidays[9] = new Array(11, 25);
/*
holidays[0] = new Array(0, 1);
holidays[1] = new Array(0, 20);
holidays[2] = new Array(1, 17);
holidays[3] = new Array(4, 26);
holidays[4] = new Array(6, 4);
holidays[5] = new Array(8, 1);
holidays[6] = new Array(9, 13);
holidays[7] = new Array(10, 11);
holidays[8] = new Array(10, 27);
holidays[9] = new Array(11, 25);
*/
/*
holidays[0] = new Array(0, 1);
holidays[1] = new Array(0, 21);
holidays[2] = new Array(1, 18);
holidays[3] = new Array(4, 27);
holidays[4] = new Array(6, 4);
holidays[5] = new Array(8, 2);
holidays[6] = new Array(9, 14);
holidays[7] = new Array(10, 11);
holidays[8] = new Array(10, 28);
holidays[9] = new Array(11, 25);
*/

kellys = new Array();
kellys[0] = new Array(0, 11);
kellys[1] = new Array(1, 4);
kellys[2] = new Array(1, 28);
kellys[3] = new Array(2, 24);
kellys[4] = new Array(3, 17);
kellys[5] = new Array(3, 20);
kellys[6] = new Array(4, 14);
kellys[7] = new Array(5, 7);
kellys[8] = new Array(6, 1);
kellys[9] = new Array(6, 25);
kellys[10] = new Array(7, 18);
kellys[11] = new Array(8, 11);
kellys[12] = new Array(8, 14);
kellys[13] = new Array(9, 8);
kellys[14] = new Array(10, 1);
kellys[15] = new Array(10, 25);
kellys[16] = new Array(11, 19);
/*
kellys = new Array();
kellys[0] = new Array(0, 7);
kellys[1] = new Array(0, 31);
kellys[2] = new Array(1, 3);
kellys[3] = new Array(1, 27);
kellys[4] = new Array(2, 23);
kellys[5] = new Array(3, 16);
kellys[6] = new Array(4, 10);
kellys[7] = new Array(5, 3);
kellys[8] = new Array(5, 27);
kellys[9] = new Array(5, 30);
kellys[10] = new Array(6, 24);
kellys[11] = new Array(7, 17);
kellys[12] = new Array(8, 10);
kellys[13] = new Array(9, 4);
kellys[14] = new Array(9, 28);
kellys[15] = new Array(10, 21);
kellys[16] = new Array(10, 24);
kellys[17] = new Array(11, 18);
*/
/*
kellys[0] = new Array(0, 30);
kellys[1] = new Array(1, 23);
kellys[2] = new Array(2, 19);
kellys[3] = new Array(3, 12);
kellys[4] = new Array(3, 15);
kellys[5] = new Array(4, 9);
kellys[6] = new Array(5, 2);
kellys[7] = new Array(5, 26);
kellys[8] = new Array(6, 20);
kellys[9] = new Array(7, 13);
kellys[10] = new Array(8, 6);
kellys[11] = new Array(8, 9);
kellys[12] = new Array(9, 3);
kellys[13] = new Array(9, 27);
kellys[14] = new Array(10, 20);
kellys[15] = new Array(11, 14);
*/

paydays = new Array();
paydays[0] = new Array(0, 9);
paydays[1] = new Array(0, 26);
paydays[2] = new Array(1, 10);
paydays[3] = new Array(1, 25);
paydays[4] = new Array(2, 10);
paydays[5] = new Array(2, 25);
paydays[6] = new Array(3, 10);
paydays[7] = new Array(3, 24);
paydays[8] = new Array(4, 11);
paydays[9] = new Array(4, 25);
paydays[10] = new Array(5, 10);
paydays[11] = new Array(5, 25);
paydays[12] = new Array(6, 10);
paydays[13] = new Array(6, 24);
paydays[14] = new Array(7, 10);
paydays[15] = new Array(7, 25);
paydays[16] = new Array(8, 10);
paydays[17] = new Array(8, 25);
paydays[18] = new Array(9, 9);
paydays[19] = new Array(9, 26);
paydays[20] = new Array(10, 10);
paydays[21] = new Array(10, 25);
paydays[22] = new Array(11, 10);
paydays[23] = new Array(11, 24);
/*
paydays[0] = new Array(0, 3);
paydays[1] = new Array(0, 17);
paydays[2] = new Array(0, 31);
paydays[3] = new Array(1, 14);
paydays[4] = new Array(1, 28);
paydays[5] = new Array(2, 14);
paydays[6] = new Array(2, 28);
paydays[7] = new Array(3, 11);
paydays[8] = new Array(3, 25);
paydays[9] = new Array(4, 9);
paydays[10] = new Array(4, 23);
paydays[11] = new Array(5, 6);
paydays[12] = new Array(5, 20);
paydays[13] = new Array(6, 4);
paydays[14] = new Array(6, 18);
paydays[15] = new Array(7, 1);
paydays[16] = new Array(7, 15);
paydays[17] = new Array(7, 29);
paydays[18] = new Array(8, 12);
paydays[19] = new Array(8, 26);
paydays[20] = new Array(9, 10);
paydays[21] = new Array(9, 24);
paydays[22] = new Array(10, 7);
paydays[23] = new Array(10, 21);
paydays[24] = new Array(11, 5);
paydays[25] = new Array(11, 19);
*/
/*
paydays[0] = new Array(0, 4);
paydays[1] = new Array(0, 18);
paydays[2] = new Array(1, 1);
paydays[3] = new Array(1, 15);
paydays[4] = new Array(2, 1);
paydays[5] = new Array(2, 15);
paydays[6] = new Array(2, 29);
paydays[7] = new Array(3, 12);
paydays[8] = new Array(3, 26);
paydays[9] = new Array(4, 10);
paydays[10] = new Array(4, 24);
paydays[11] = new Array(5, 7);
paydays[12] = new Array(5, 21);
paydays[13] = new Array(6, 5);
paydays[14] = new Array(6, 19);
paydays[15] = new Array(7, 2);
paydays[16] = new Array(7, 16);
paydays[17] = new Array(7, 30);
paydays[18] = new Array(8, 13);
paydays[19] = new Array(8, 27);
paydays[20] = new Array(9, 11);
paydays[21] = new Array(9, 25);
paydays[22] = new Array(10, 8);
paydays[23] = new Array(10, 22);
paydays[24] = new Array(11, 6);
paydays[25] = new Array(11, 20);
*/


cal_current_date = new Date();


function setCalInfo()
{
	var curr_year = new Date().getFullYear();
	var slct_year = document.getElementById("year").value;
	var year = (curr_year != slct_year) ? parseInt(slct_year) : parseInt(curr_year);
	var montharray = [  'January', 'February', 'March', 'April', 'May', 'June', 'July',
						'August', 'September', 'October', 'November', 'December' ];
	//var firstworks = [ 3, 2, 1, 3, 3, 2, 2, 1, 3, 3, 2, 2 ];
	//var firstworks = [ 1, 3, 2, 1, 1, 3, 3, 2, 1, 1, 3, 3 ];
	var firstworks = [ 2, 1, 3, 2, 2, 1, 1, 3, 2, 2, 1, 1 ];
	document.getElementById("title").innerHTML = year + " Calendar";
	for(var i = 0; i < 12; i++)
	{
		var cal = new Calendar(i, year, firstworks[i]);
		cal.generateHTML();
		document.getElementById("div" + montharray[i]).innerHTML = cal.getHTML();
	}
}


function Calendar(month, year, firstwork)
{
	this.month = (isNaN(month) || month == null) ? cal_current_date.getMonth() : month;
	this.year = (isNaN(year) || year == null) ? cal_current_cate.getFullYear() : year;
	this.firstwork = (isNaN(firstwork) || firstwork == null) ? 0 : firstwork;
	this.html = '';
}


Calendar.prototype.generateHTML = function()
{
	var firstDay = new Date(this.year, this.month, 1);
	var startingDay = firstDay.getDay();
	var monthLength = cal_days_in_month[this.month];
	var holidaystyle = '';
	var paydaystyle1 = '';
	var paydaystyle2 = '';

	if(this.month == 1)
	{
		if((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
		{
			monthLength = 29;
		}
	}

	var monthName = cal_months_labels[this.month];
	var html = '<table class="calendar-table">';
	html += '<tr><th colspan="7">';
	html += monthName + " " + this.year;
	html += '</th></tr>';
	html += '<tr class="calendar-header">';
	for(var i = 0; i <= 6; i++)
	{
		html += '<td class="calendar-header-day">';
		html += cal_days_labels[i];
		html += '</td>';
	}
	html += '</tr><tr>';
	var day = 1;
	var workday = this.firstwork;
	for(var i = 0; i < 9; i++)
	{
		for(var j = 0; j <= 6; j++)
		{
			var cssclass = "calendar-day-off";
			if(workday == day && (i > 0 || j >= startingDay))
			{
				for(var z = 0; z < kellys.length; z++)
				{
					if(this.month == kellys[z][0] && day == kellys[z][1])
					{
						cssclass = "calendar-day-kelly";
						break;
					}
					else
					{
						cssclass = "calendar-day-work";
					}
				}
				workday += 3;
			}
			if(day <= monthLength && (i > 0 || j >= startingDay))
			{
				for(var k = 0; k < holidays.length; k++)
				{
					if(this.month == holidays[k][0] && day == holidays[k][1])
					{
						holidaystyle = 'style="background-color:#FFFF88;"';
						break;
					}
				}
				for(var n = 0; n < paydays.length; n++)
				{
					if(this.month == paydays[n][0] && day == paydays[n][1])
					{
						paydaystyle1 = '<i>';
						paydaystyle2 = '</i>';
						break;
					}
				}
			}
			html += '<td class="' + cssclass + '" ' + holidaystyle + '>';
			if(day <= monthLength && (i > 0 || j >= startingDay))
			{
				if(paydaystyle1 == '<i>')
				{
					html += paydaystyle1 + day + paydaystyle2;
				}
				else
				{
					html += day;
				}
				day++;
			}
			html += '</td>';
			holidaystyle = '';
			paydaystyle1 = '';
			paydaystyle2 = '';
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


Calendar.prototype.getHTML = function()
{
	return this.html;
}