<?php

	$REQ = $_REQUEST;

	if(isset($REQ['libcheck']) && !empty($REQ['libcheck'])){
		define('LIBRARY_CHECK', true);
	}
	if(!defined('LIBRARY_CHECK')){
		die ('<div style="width:100%;height:100%;text-align:center;">
				<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:100px;">
					Sorry, this isn\'t a real page, so I have nothing to show you :-(
				</div>
				<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:30px;margin-bottom:30px;">Wait, here\'s a funny cat!</div>
				<div style="background-repeat:no-repeat;margin-left:auto;margin-right:auto;width:500px;height:280px;background:url(../images/cat.gif)"></div>
			</div>');
	}

	define('INCLUDE_CHECK',true);
	require 'connect.php';
	require 'yweather.php';
	date_default_timezone_set('America/New_York');

	$webaddress = "http://twandrews.com/";

	if(isset($REQ['method']) && !empty($REQ['method']))
	{
		$method = $REQ['method'];
		$method = urldecode($method);
		$method = mysql_real_escape_string($method);

		switch($method)
		{
			case 'getActivityInfo': getActivityInfo($REQ);
				break;
			case 'getMonthInfo': getMonthInfo($REQ);
				break;
			case 'getDateInfo': getDateInfo($REQ);
				break;
			case 'addUpdateTimeEntry': addUpdateTimeEntry($REQ);
				break;
			case 'updateUser': updateUser($REQ);
				break;
			case 'createUser': createUser($REQ);
				break;
			case 'updatePassword': updatePassword($REQ);
				break;
			case 'getWorkingDays': getWorkingDays($REQ);
				break;
			case 'getStephScheduleHtml': getStephScheduleHtml($REQ);
				break;
			case 'getStephDateInfo': getStephDateInfo($REQ);
				break;
			case 'changeStephDateInfo': changeStephDateInfo($REQ);
				break;
			case 'getFwInfo': getFwInfo($REQ);
				break;
			case 'setFwInfo': setFwInfo($REQ);
				break;
			case 'getWeatherInfo': getWeatherInfo($REQ);
				break;
			default: noFunction($REQ['method']);
				break;
		}
		mysql_close($link);
	}


	function noFunction($m)
	{
		$func = $m;
		$result = array(
				"status"	=> "failure",
				"message"	=> "User attempted to call function: " . $func . " which does not exist",
				"content"	=> "You seem to have encountered an error - Contact the DHD web admin if this keeps happening!"
		);
		echo json_encode($result);
	}


	function getActivityInfo($P)
	{
		$P = escapeArray($P);
		$rangebeg = $P['strdate'];
		$rangeend = $P['enddate'];
		$result  = "";
		$content = "";
		$message = "Data returned successfully!";
		$status  = "success";

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> array()
		);

		if($firstload){
			return $result;
		}
		else{
			echo json_encode($result);
		}
	}


	function getMonthInfo($P)
	{
		$P = escapeArray($P);

		$firstload = (isset($P['firstload']) && !empty($P['firstload']) && $P['firstload'] == 1) ? 1 : 0;
		$userid  = $P['userid'];
		$result  = "";
		$content = "";
		$message = "Data returned successfully!";
		$status  = "success";

		$cyear 	  = $P['year'];
		$nummonth = $P['month'];

		$datestr = $cyear . "-" . $nummonth . "-" . "01";
		$mname 	 = date("F", strtotime($datestr));

		$headstring = $mname . " " . $cyear;
		$firstdate 	= $cyear . "-" . $nummonth . "-" . "01";
		$prevmonth 	= date("n", strtotime($mname . " " . $cyear . " -5 days"));
		$nextmonth 	= date("n", strtotime($mname . " " . $cyear . " +35 days"));
		$nextyear  	= date("Y", strtotime($mname . " " . $cyear . " +35 days"));
		$prevyear 	= ($nummonth == 1) ? ($cyear - 1) : $cyear;
		$prevnumdays = cal_days_in_month(CAL_GREGORIAN, $prevmonth, $prevyear);
		$currnumdays = date("t", strtotime($firstdate));
		$firstday  	 = date("l", strtotime($firstdate));
		$lastday 	 = date("l", strtotime($cyear . "-" . $nummonth . "-" . $currnumdays));

		$pp1start 	= $cyear . "-" . $nummonth . "-01";
		$pp1end 	= $cyear . "-" . $nummonth . "-15";
		$pp2start 	= $cyear . "-" . $nummonth . "-16";
		$pp2end 	= $cyear . "-" . $nummonth . "-" . $currnumdays;

		//number of days to add/remove at the beginning of the month, depending on which day the month starts
		//if the month starts on a Thursday (like Jan 1, 2015), you'll add 3 days so that there are the correct
		//number of rows.  The signs are reversed (negative) in the hash to help with the logic below
		//it's easier to think of it as a number line - if the month starts on a Thurs, then to get the correct
		//table display, you'll need to start like this:  -3  -2  -1  1  2  3, etc  (no "0" day, obviously)
		//the reverse is true for the "endadddays" hash.  You'll need to tack on days to the end of the table
		//if the month ends on, say, a Tuesday
		$begadddays = array(
				"Sunday" 	=>  2, //don't print the Sunday row, start on a Monday (the 2nd)
				"Monday" 	=>  1, //Monday is the first day of the month
				"Tuesday" 	=>  0, //start the counter at 0, padding the previous Monday as a red row...
				"Wednesday" => -1, //...and so on for the rest of the week
				"Thursday" 	=> -2,
				"Friday" 	=> -3,
				"Saturday" 	=>  3  //unless the 1st is a Saturday, then leave out the
								   //weekend rows and begin the following Monday
		);

		//number of days to add/remove at the end of the month, depending on which day the month ends
		$endadddays = array(
				"Sunday" 	=> -2, //if the last day is a Sunday, no need to print the last two rows so stop 2 early
				"Monday" 	=>  4, //if the last day is a Monday, print 4 extra red rows, same the rest of the week
				"Tuesday" 	=>  3,
				"Wednesday" =>  2,
				"Thursday" 	=>  1,
				"Friday" 	=>  0,
				"Saturday" 	=> -1  //as with Sunday, stop early, no need for an extra red row
		);

		$did = 0;
		$mid = 0;
		$daysback = $begadddays[$firstday];
		$daysfrwd = $currnumdays + $endadddays[$lastday];
		$maintablehtml = "";
		$sidetablehtml = "";
		$monthhours = 0;

		for($i = $daysback; $i <= $daysfrwd; $i++)
		{
			$class = "tablerow";
			$sideclass = "";
			$btnclass  = "tdbtn";
			$onclick = "onclick='showNewTimeForm(this.id)'";
			$addtime = false;

			if($i <= 0)
			{
				$day   = $prevnumdays + $i;
				$date  = $prevmonth . "/" . $day . "/" . $prevyear;
				$class = "redtablerow";
				$did = 0;
				$mid = 0;
				$onclick = "";
				$btnclass = "";
			}
			elseif($i > $currnumdays)
			{
				$date  = $nextmonth . "/" . ($i - $currnumdays) . "/" . $nextyear;
				$class = "redtablerow";
				$did = 0;
				$mid = 0;
				$onclick = "";
				$btnclass = "";
			}
			else
			{
				$date = $nummonth . "/" . $i . "/" . $cyear;
				$did  = $i;
				$mid  = $nummonth;
				$addtime = true;
			}
			$day  = date("l", strtotime($date));
			$date = date("m/d/Y", strtotime($date));

			$mydate = date("Y-m-d", strtotime($date));

			if($day == "Monday")
			{
				$class .= " mondayrow";
			}
			elseif($day == "Friday")
			{
				$class .= " fridayrow";
			}
			elseif($day == "Saturday" || $day == "Sunday")
			{
				$sideclass  = " weekendrow";
				$onclick 	= "";
				$btnclass 	= "";
			}

			$dateinfo = mysql_fetch_assoc(mysql_query("SELECT * FROM timesheet WHERE DATE='" . $mydate . "' AND USERID=" . $userid . ";"));

			$stime 	= "";
			$etime 	= "";
			$sbtime = "";
			$ebtime = "";
			$hours 	= "";
			$pto 	= "";
			$leave 	= "";
			$totpto = "";
			$tothours = "";
			$note 	= "";

			if(isset($dateinfo['ID']))
			{
				$stime 		= (isset($dateinfo['STARTTIME']) && $dateinfo['STARTTIME'] != "") ? $dateinfo['STARTTIME'] : "";
				$etime 		= (isset($dateinfo['ENDTIME']) && $dateinfo['ENDTIME'] != "") ? $dateinfo['ENDTIME'] : "";
				$sbtime 	= (isset($dateinfo['BEGINBREAK']) && $dateinfo['BEGINBREAK'] != "") ? $dateinfo['BEGINBREAK'] : "";
				$ebtime 	= (isset($dateinfo['ENDBREAK']) && $dateinfo['ENDBREAK'] != "") ? $dateinfo['ENDBREAK'] : "";
				$hours 		= (isset($dateinfo['HOURS']) && $dateinfo['HOURS'] != "") ? $dateinfo['HOURS'] : 0;
				$pto 		= (isset($dateinfo['PTO']) && $dateinfo['PTO'] != "") ? $dateinfo['PTO'] : 0;
				$leave		= (isset($dateinfo['VACATION']) && $dateinfo['VACATION'] != "") ? $dateinfo['VACATION'] : 0;
				$totpto		= $pto + $leave;
				$tothours 	= $hours + $totpto;
				$note 		= (isset($dateinfo['NOTE']) && $dateinfo['NOTE'] != "") ? $dateinfo['NOTE'] : "";

				if($addtime)
				{
					$monthhours += $tothours;
				}

				$stime  = date("h:i:s A", strtotime($stime));
				$etime  = date("h:i:s A", strtotime($etime));
				$sbtime = date("h:i:s A", strtotime($sbtime));
				$ebtime = date("h:i:s A", strtotime($ebtime));
				if($sbtime == $ebtime)
				{
					$sbtime = "";
					$ebtime = "";
				}
				if($stime == $etime)
				{
					$stime = "";
					$etime = "";
				}
			}

			$maintablehtml .= "<tr class='" . $class . "'>
					<td class='" 		. $btnclass . "' " . $onclick . " id='date_" . $mid . "_" . $did . "'>" . $date . "</td>
					<td id='start_" 	. $mid . "_" . $did . "'>" . $stime  . "</td>
					<td id='begbreak_" 	. $mid . "_" . $did . "'>" . $sbtime . "</td>
					<td id='endbreak_" 	. $mid . "_" . $did . "'>" . $ebtime . "</td>
					<td id='end_" 		. $mid . "_" . $did . "'>" . $etime  . "</td>
					<td id='hours_" 	. $mid . "_" . $did . "'>" . $hours  . "</td>
					<td id='pto_" 		. $mid . "_" . $did . "'>" . $totpto . "</td></tr>";

			$sidetablehtml .= "<tr class='" . $class . $sideclass . "'>
					<td id='day_" . $mid . "_" . $did . "'>" . $day . "</td>
					<td id='totalhours_" . $mid . "_" . $did . "'>" . $tothours . "</td>
					<td style='text-align:left;' id='note_" . $mid . "_" . $did . "'>" . $note . "</td></tr>";
		}

		$pp1exp  = getWorkingDays($pp1start, $pp1end) * 8;
		$pp2exp  = getWorkingDays($pp2start, $pp2end) * 8;

		$pp1tot = getPayPeriodTotal($pp1start, $pp1end, $userid);
		$pp2tot = getPayPeriodTotal($pp2start, $pp2end, $userid);

		$totexp  = $pp1exp + $pp2exp;
		$pp1diff = $pp1exp - $pp1tot;
		$pp2diff = $pp2exp - $pp2tot;

		$totdiff = ($pp1tot + $pp2tot) - ($pp1exp + $pp2exp);

		$tdcolor  = ($totdiff < 0) ? "redtext" : "blacktext";
		$pp1color = ($pp1diff > 0) ? "redtext" : "blacktext";
		$pp2color = ($pp2diff > 0) ? "redtext" : "blacktext";

		$finalsidetablehtml = "
			<tr class='tablerow' style='border:2px solid;'><td>Total:</td><td>" . $monthhours . "</td><td></td></tr>
			<tr class='tablerow' style='border-top:2px solid;'>
				<td>PP1 Total:</td><td id='pp1total' class='" . $pp1color . "'>" . $pp1tot . "</td><td></td>
			</tr>
			<tr class='tablerow' style='border-bottom:2px solid;'>
				<td>PP1 Exp:</td><td id='pp1exp'>" . $pp1exp . "</td><td></td>
			</tr>
			<tr class='tablerow'>
				<td>PP2 Total:</td><td id='pp2total' class='" . $pp2color . "'>" . $pp2tot . "</td><td></td>
			</tr>
			<tr class='tablerow' style='border-bottom:2px solid;'>
				<td>PP2 Exp:</td><td id='pp2exp'>" . $pp2exp . "</td><td></td>
			</tr>
			<tr class='tablerow'><td>Total Req:</td><td id='totexp'>" . $totexp . "</td><td></td></tr>
			<tr class='tablerow' style='border-bottom:2px solid;'>
				<td>Difference:</td><td id='ppdiff' class='" . $tdcolor . "'>" . $totdiff . "</td><td></td>
			</tr>";

		//$maintablehtml .= $ppstring;
		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> array(
				"mainhtml"	 => $maintablehtml,
				"sidehtml"	 => $sidetablehtml,
				"finalhtml"	 => $finalsidetablehtml,
				"headstr"	 => $headstring
			)
		);

		if($firstload){
			return $result;
		}
		else{
			echo json_encode($result);
		}
	}


	function changeStephDateInfo($P)
	{
		$P = escapeArray($P);

		$result = array();
		$year = $P['year'];
		$mnth = $P['month'];
		$day  = $P['day'];
		$daytypes = $P['daytypes'];

		$status = "success";
		$message = "";
		$types = array("holiday" => 10, "kelly" => 20, "payday" => 30);

		$cssarray = explode("-", $P['cssclass']);
		$csstype = $cssarray[2];

		$remdates = mysql_query("DELETE FROM scheddates
								 WHERE YEAR=" . $year . "
								 	AND MONTH=" . $mnth . "
									AND DAY=" . $day . "
									AND DAYTYPE != 40;");

		foreach($types as $key => $val)
		{
			if($daytypes[$key] == 1)
			{
				$newentry = mysql_query("INSERT INTO scheddates (YEAR, MONTH, DAY, DAYTYPE)
										 VALUES (" . $year . ", " . $mnth . ", " . $day . ", " . $val . ");");
			}
		}

		$tdcolor = $daytypes['holiday'] == 1 ? "#FFFF88" : "#FFFFFF";
		$spanstyle = $daytypes['payday'] == 1 ? "italic" : "normal";
		$tdclass = "calendar-day-off";

		if($daytypes['kelly'] == 1)
		{
			$tdclass = "calendar-day-kelly";
		}
		elseif($csstype == "work" || $csstype == "kelly")
		{
			$tdclass = "calendar-day-work";
		}

		$begq = mysql_fetch_assoc(mysql_query("SELECT DISTINCT YEAR FROM scheddates ORDER BY YEAR ASC LIMIT 1;"));
		$endq = mysql_fetch_assoc(mysql_query("SELECT DISTINCT YEAR FROM scheddates ORDER BY YEAR DESC LIMIT 1;"));
		$beg = $begq['YEAR'] - 1;
		$end = $endq['YEAR'] + 1;

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> array(
				"tdclass" 	=> $tdclass,
				"tdcolor" 	=> $tdcolor,
				"tdid"		=> "td_" . $mnth . "_" . $day,
				"spanstyle"	=> $spanstyle,
				"minyear"	=> $beg,
				"maxyear"	=> $end
			)
		);

		echo json_encode($result);
	}


	function getFwInfo($P)
	{
		$P = escapeArray($P);

		$result = array();
		$year 	= $P['year'];
		$mnth 	= $P['month'];
		$mn 	= $P['mname'];
		$status = "success";
		$message = "";

		$fwinfo = mysql_query( "SELECT * FROM scheddates
								WHERE YEAR=" . $year . " AND MONTH=" . $mnth . " AND DAYTYPE=40;");
		$fw = 0;
		if(mysql_num_rows($fwinfo) > 0)
		{
			$row = mysql_fetch_assoc($fwinfo);
			$fw = $row['DAY'];
		}

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> array(
				"monthnum" 	=> $mnth,
				"monthname"	=> $mn,
				"firstwork"	=> $fw
			)
		);

		echo json_encode($result);
	}


	function setFwInfo($P)
	{
		$P = escapeArray($P);
		$result = array();
		$year 	= $P['year'];
		$mnth 	= $P['month'];
		$mn 	= $P['mname'];
		$fw 	= $P['firstwork'];
		$status = "success";
		$message = "";

		$delfw = mysql_query("DELETE FROM scheddates WHERE YEAR=" . $year . " AND MONTH=" . $mnth . " AND DAYTYPE=40;");
		$newfw = mysql_query("INSERT INTO scheddates (YEAR, MONTH, DAY, DAYTYPE)
								VALUES (" . $year . ", " . $mnth . ", " . $fw . ", 40);");

		$html = getStephMonthHtml($year, $mnth, $mn);

		$begq = mysql_fetch_assoc(mysql_query("SELECT DISTINCT YEAR FROM scheddates ORDER BY YEAR ASC LIMIT 1;"));
		$endq = mysql_fetch_assoc(mysql_query("SELECT DISTINCT YEAR FROM scheddates ORDER BY YEAR DESC LIMIT 1;"));
		$beg = $begq['YEAR'] - 1;
		$end = $endq['YEAR'] + 1;

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> array(
				"year"		=> $year,
				"monthhtml" => $html,
				"monthnum" 	=> $mnth,
				"monthname"	=> $mn,
				"firstwork"	=> $fw,
				"minyear"	=> $beg,
				"maxyear"	=> $end
			)
		);

		echo json_encode($result);
	}


	function getStephDateInfo($P)
	{
		$P = escapeArray($P);

		$result = array();
		$year 	= $P['year'];
		$mnth 	= $P['month'];
		$day  	= $P['day'];
		$status = "success";
		$message = "";
		$types 	= array("kelly" => 0, "holiday" => 0, "payday" => 0);

		$dinfo = mysql_query(  "SELECT N.TYPENAME FROM scheddates T, schedtypes N
								WHERE T.DAYTYPE=N.TYPEVAL
								AND T.YEAR=" . $year . "
									AND T.MONTH=" . $mnth . "
										AND DAY=" . $day . ";");
		if(mysql_num_rows($dinfo) > 0)
		{
			while($row = mysql_fetch_assoc($dinfo))
			{
				switch($row['TYPENAME'])
				{
					case "HOLIDAY": //holiday
						$types['holiday'] = 1;
						break;
					case "KELLY": //kelly day
						$types['kelly'] = 1;
						break;
					case "PAYDAY": //payday
						$types['payday'] = 1;
						break;
					default: break;
				}
			}
		}

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> array(
				"datetypes" => $types,
				"month" => $mnth,
				"day"	=> $day,
				"year"	=> $year
			)
		);

		echo json_encode($result);
	}


	function getDateInfo($P)
	{
		$P = escapeArray($P);

		$year = $P['year'];
		$mnth = $P['month'];
		$day  = $P['day'];
		$uid  = $P['userid'];

		$date = $mnth . "/" . $day . "/" . $year;
		$dbdate = date("Y-m-d", strtotime($date));
		$dateinfo = mysql_fetch_assoc(mysql_query("SELECT * FROM timesheet WHERE DATE='" . $dbdate . "' AND USERID=" . $uid . ";"));

		$starttime 	= (isset($dateinfo['STARTTIME']) 	&& $dateinfo['STARTTIME'] != "")	? $dateinfo['STARTTIME'] 	: "";
		$endtime 	= (isset($dateinfo['ENDTIME']) 		&& $dateinfo['ENDTIME'] != "") 		? $dateinfo['ENDTIME'] 		: "";
		$startbreak = (isset($dateinfo['BEGINBREAK']) 	&& $dateinfo['BEGINBREAK'] != "") 	? $dateinfo['BEGINBREAK'] 	: "";
		$endbreak 	= (isset($dateinfo['ENDBREAK']) 	&& $dateinfo['ENDBREAK'] != "") 	? $dateinfo['ENDBREAK'] 	: "";
		$note 		= (isset($dateinfo['NOTE']) 		&& $dateinfo['NOTE'] != "") 		? $dateinfo['NOTE'] 		: "";
		$pto 		= (isset($dateinfo['PTO']) 			&& $dateinfo['PTO'] != "") 			? $dateinfo['PTO'] 			: 0;
		$leave		= (isset($dateinfo['VACATION']) 	&& $dateinfo['VACATION'] != "") 	? $dateinfo['VACATION'] 	: 0;

		$starttime 	= date("h:i:s A", strtotime($starttime));
		$endtime 	= date("h:i:s A", strtotime($endtime));
		$startbreak = date("h:i:s A", strtotime($startbreak));
		$endbreak 	= date("h:i:s A", strtotime($endbreak));

		if($startbreak == $endbreak)
		{
			$startbreak = "";
			$endbreak = "";
		}
		if($starttime == $endtime)
		{
			$starttime = "";
			$endtime = "";
		}

		$result = array(
				"status"  => "success",
				"message" => "",
				"content" => array(
					"start" => $starttime,
					"end"	=> $endtime,
					"begbr" => $startbreak,
					"endbr" => $endbreak,
					"pto"	=> $pto,
					"leave" => $leave,
					"note"	=> $note
				)
		);
		echo json_encode($result);
	}


	function getStephScheduleHtml($P)
	{
		$P = escapeArray($P);

		//is this the initial page load (called from steph.php) or an AJAX request? determines echo or return
		$firstload 	= (isset($P['firstload']) && !empty($P['firstload']) && $P['firstload'] == 1) ? 1 : 0;
		$year 		= $P['year'];

		$html = "<div id='caltable' style='width:100%;height:100%;'>";
		for($i = 1; $i <= 12; $i++)
		{
			$datestr = $year . "-" . $i . "-" . "01";
			$mname 	 = date("F", strtotime($datestr));
			$html 	.= "<div id='div" . $mname . "' class='monthFloat'>" 	. getStephMonthHtml($year, $i, $mname) . "</div>";
		}
		$html .= "</div>";

		$result = array(
				"status"  => "success",
				"message" => "",
				"content" => array(
					"table" => $html,
					"title" => $year
				)
		);

		if($firstload){
			return $result;
		}
		else{
			echo json_encode($result);
		}
	}


	function getStephMonthHtml($y, $mnum, $name)
	{
		$m_html = "";
		$num 	= $mnum;
		$year 	= $y;
		$mn 	= $name;
		$firstwork 	= 0;
		$fstr 		= $year . "-" . $num . "-" . "01";
		$numdays 	= date("t", strtotime($fstr));
		$startday 	= date("w", strtotime($fstr));

		$dy = mysql_query(	"SELECT T.DAY,N.TYPENAME
							FROM scheddates T, schedtypes N
							WHERE T.DAYTYPE=N.TYPEVAL
								AND T.YEAR=" . $year . "
									AND T.MONTH=" . $num . " ORDER BY T.DAY;");
		$kell = array();
		$hols = array();
		$pays = array();

		//fill the individual day type arrays
		while($row = mysql_fetch_assoc($dy))
		{
			switch($row['TYPENAME'])
			{
				case "HOLIDAY": //holiday
					array_push($hols, $row['DAY']);
					break;
				case "KELLY": //kelly day
					array_push($kell, $row['DAY']);
					break;
				case "PAYDAY": //payday
					array_push($pays, $row['DAY']);
					break;
				case "FIRSTWORK": //first workday of the month...should only be one entry for each month
					$firstwork = $row['DAY'];
					break;
				default: break;
			}
		}

		$m_html = "<table class='calendar-table'><tbody><tr><th colspan='7'>
					<a href='javascript:void(0);' id='month_" . $num . "'
						style='text-decoration:none;color:#000;'
						onclick='getFwInfo(" . $num . ", \"" . $mn . "\")'>" . $mn . " " . $year . "</a>
					</th></tr><tr class='calendar-header'>
						<td class='calendar-header-day'>Sun</td>
						<td class='calendar-header-day'>Mon</td>
						<td class='calendar-header-day'>Tue</td>
						<td class='calendar-header-day'>Wed</td>
						<td class='calendar-header-day'>Thr</td>
						<td class='calendar-header-day'>Fri</td>
						<td class='calendar-header-day'>Sat</td>
					</tr>";
		//ititialize the day counter...all months start with 1
		$day = 1;
		$cssclass = "";
		$holstyle = "";
		$dayhtml  = "";
		$daystyl  = "";
		$onclick  = "";
		$spancls  = "";
		$workday  = $firstwork;

		//six possible weeks covered in a month (eg, the month has 31 days and starts on a Saturday)
		for($j = 0; $j < 6; $j++)
		{
			//open the row...
			$m_html .= "<tr>";
			//seven days in a week...duh
			for($k = 0; $k < 7; $k++)
			{
				//set the default values for a given day - no work, no kelly, etc
				$holstyle 	= "#FFFFFF";
				$cssclass 	= "calendar-day-off";
				$tdid 		= "td_" . $num . "_" . $day;
				$onclick 	= "onclick='getDateInfo(\"" . $tdid . "\");' ";
				$daystyl 	= "cursor:pointer;";
				$spancls 	= "clickclass";
				$valid 		= ($j > 0 || $k >= $startday) ? true : false;

				if($workday == $day && $valid)
				{
					//if it's both a workday AND a kelly day, set the kelly class
					$cssclass = in_array($day, $kell) ? "calendar-day-kelly" : "calendar-day-work";
					$workday += 3;
				}

				//if we are still within the range of days for the month, check for various conditions
				if($day <= $numdays && $valid)
				{
					//if it's a holiday, set the background to yellow, if it's a payday, make the text italic
					$dayhtml  = $day;
					$holstyle = in_array($day, $hols) ? "#FFFF88" : "#FFFFFF";
					$daystyl .= in_array($day, $pays) ? "font-style:italic;" : "";
					//don't start incrementing the day counter until we have hit the first day of the month
					//this accounts for months when the first day isn't a Sunday...soooo...most months.
					$day++;
				}
				else
				{
					//otherwise blank out the cell and set the background to grey
					//so if the month starts on a Monday, the previous day would be empty with a grey background
					$dayhtml 	= "";
					$daystyl 	= "";
					$onclick 	= "";
					$spancls 	= "";
					$holstyle	= "#EAEAEA";
					$tdid 		= "";
				}
				//build the td for the day
				$m_html .= "<td " . $onclick . "id='" . $tdid . "' class='" . $cssclass . "' " .
									"style='height:28px;background-color:" . $holstyle . ";'>" .
								"<span class='" . $spancls . "' style='" . $daystyl . "'>" . $dayhtml . "</span>
							</td>";
			}
			//...close the row
			$m_html .= "</tr>";
		}
		$m_html .= "</tbody></table>";

		return $m_html;
	}


	function addUpdateTimeEntry($P)
	{
		$P = escapeArray($P);

		$userid  = $P['userid'];
		$status  = "success";
		$message = "Record successfully update";
		$content = array();

		$datearr  = explode("_", $P['dateid']);
		$nummonth = $datearr[1];
		$numday   = $datearr[2]; //keeping as number...just in case...

		$month 	= sprintf("%02d", $datearr[1]);
		$day 	= sprintf("%02d", $datearr[2]);
		$suffix = $datearr[1] . "_" . $datearr[2];
		$year 	= $P['year'];

		$date 	 = $year . "-" . $month . "-" . $day;
		$wordday = date("l", strtotime($date));

		$start  = "";
		$end 	= "";
		$sbreak = "";
		$ebreak = "";

		$pto 	= (isset($P['pto'])		&& $P['pto'] != "" 	 && $P['pto'] > 0) 	 ? $P['pto']   : 0;
		$leave  = (isset($P['leave'])	&& $P['leave'] != "" && $P['leave'] > 0) ? $P['leave'] : 0;
		$note 	= (isset($P['note'])) ? $P['note'] : "";

		if(isset($P['starthour']))
		{
			$shr = $P['starthour'];
			if(isset($P['startampm']) && $P['startampm'] == "pm" && $shr != 12)
			{
				$shr = $P['starthour'] + 12;
			}
			elseif(isset($P['startampm']) && $P['startampm'] == "am" && $shr == 12)
			{
				$shr = "00";
			}
			$start = $date . " " . $shr . ":" . $P['startminute'] . ":" . "00";

			$ehr = $P['endhour'];
			if(isset($P['endampm']) && $P['endampm'] == "pm" && $ehr != 12)
			{
				$ehr = $P['endhour'] + 12;
			}
			elseif(isset($P['endampm']) && $P['endampm'] == "am" && $ehr == 12)
			{
				$ehr = "00";
			}
			$end = $date . " " . $ehr . ":" . $P['endminute'] . ":" . "00";
		}

		if(isset($P['startbrhour']) && isset($P['endbrhour']))
		{
			$sbhr = $P['startbrhour'];
			if(isset($P['startbrampm']) && $P['startbrampm'] == "pm" && $sbhr != 12)
			{
				$sbhr = $P['startbrhour'] + 12;
			}
			elseif(isset($P['startbrampm']) && $P['startbrampm'] == "am" && $sbhr == 12)
			{
				$sbhr = "00";
			}

			$sbmn = (isset($P['startbrminute']) && $P['startbrminute'] != "") ? $P['startbrminute'] : "00";
			$sbreak = $date . " " . $sbhr . ":" . $sbmn . ":" . "00";

			$ebhr = $P['endbrhour'];
			if(isset($P['endbrampm']) && $P['endbrampm'] == "pm" && $ebhr != 12)
			{
				$ebhr = $P['endbrhour'] + 12;
			}
			elseif(isset($P['endbrampm']) && $P['endbrampm'] == "am" && $ebhr == 12)
			{
				$ebhr = "00";
			}

			$ebmn = (isset($P['endbrminute']) && $P['endbrminute'] != "") ? $P['endbrminute'] : "00";
			$ebreak = $date . " " . $ebhr . ":" . $ebmn . ":" . "00";
		}

		if($sbreak == $ebreak)
		{
			$sbreak = "";
			$ebreak = "";
		}

		if($start == $end)
		{
			$start = "";
			$end = "";
		}

		//CALCULATE HOURS
		$hours = strtotime($end) - strtotime($start);
		$break = 0;
		if($sbreak != "" && $ebreak != "")
		{
			$break = strtotime($ebreak) - strtotime($sbreak);
		}
		$hours = ($hours - $break) / 3600;

		//update old entry or add new??
		$checkdate = mysql_fetch_assoc(mysql_query("SELECT ID FROM timesheet WHERE DATE='" . $date . "' AND USERID='" . $userid . "';"));

		$mysqlresult = "";
		if(isset($checkdate['ID']))
		{
			//update existing record
			$mysqlresult = mysql_query("UPDATE timesheet SET
											DATE='" . $date . "',
											STARTTIME='" . $start . "',
											BEGINBREAK='" . $sbreak . "',
											ENDBREAK='" . $ebreak . "',
											ENDTIME='" . $end . "',
											HOURS='" . $hours . "',
											PTO='" . $pto . "',
											VACATION='" . $leave . "',
											NOTE='" . $note . "'
										WHERE ID='" . $checkdate['ID'] . "';");
		}
		else
		{
			//insert new record
			$mysqlresult = mysql_query("
								INSERT INTO timesheet (DATE, STARTTIME, BEGINBREAK, ENDBREAK, ENDTIME, HOURS, PTO, VACATION, NOTE, USERID)
								VALUES ('" . $date . "', '" . $start . "', '" . $sbreak . "', '" . $ebreak . "',
										'" . $end . "', '" . $hours . "', '" . $pto . "', " . $leave . ", '" . $note . "', '" . $userid . "');");
		}

		$firstdate 	 = $year . "-" . $month . "-01";
		$currnumdays = date("t", strtotime($firstdate));

		$pp1start 	= $year . "-" . $month . "-01";
		$pp1end 	= $year . "-" . $month . "-15";
		$pp2start 	= $year . "-" . $month . "-16";
		$pp2end 	= $year . "-" . $month . "-" . $currnumdays;

		$pp1exp = getWorkingDays($pp1start, $pp1end) * 8;
		$pp2exp = getWorkingDays($pp2start, $pp2end) * 8;

		$pp1tot = getPayPeriodTotal($pp1start, $pp1end, $userid);
		$pp2tot = getPayPeriodTotal($pp2start, $pp2end, $userid);

		$pp1diff = $pp1exp - $pp1tot;
		$pp2diff = $pp2exp - $pp2tot;

		$pptotal = $pp1tot + $pp2tot;
		$ppexpct = $pp1exp + $pp2exp;
		$ppdiff  = $pptotal - $ppexpct;

		$tdcolor  = ($ppdiff  < 0) ? "redtext" : "blacktext";
		$pp1color = ($pp1diff > 0) ? "redtext" : "blacktext";
		$pp2color = ($pp2diff > 0) ? "redtext" : "blacktext";

		if($mysqlresult)
		{
			$content['ID'] 		 = mysql_insert_id();
			$content['date'] 	 = date("m/d/Y", strtotime($date));
			$content['start'] 	 = ($start != "")  ? date("h:i:s A", strtotime($start))  : "";
			$content['sbreak'] 	 = ($sbreak != "") ? date("h:i:s A", strtotime($sbreak)) : "";
			$content['ebreak'] 	 = ($ebreak != "") ? date("h:i:s A", strtotime($ebreak)) : "";
			$content['end'] 	 = ($end != "")? date("h:i:s A", strtotime($end)) : "";
			$content['hours'] 	 = $hours;
			$content['leave']	 = $leave;
			$content['pto'] 	 = $pto;
			$content['tothours'] = $hours + $pto + $leave;
			$content['note'] 	 = $note;
			$content['wordday']  = $wordday;
			$content['suffix']	 = $suffix;
			$content['pp1total'] = ($pp1tot == 0) ? "" : $pp1tot;
			$content['pp2total'] = ($pp2tot == 0) ? "" : $pp2tot;
			$content['ppdiff']	 = $ppdiff;
			$content['ppcol']	 = $tdcolor;
			$content['pp1col']	 = $pp1color;
			$content['pp2col']	 = $pp2color;
		}
		else
		{
			$status = "failure";
			$message = "ERROR NUMBER: " . mysql_errno($mysqlresult) . ":\n" . mysql_error($mysqlresult);
		}

		$result = array(
				"status" => $status,
				"message" => $message,
				"content" => $content
		);
		echo json_encode($result);
	}

	//need the following:
	//pp start (date string), pp end (date string), userid
	function getPayPeriodTotal($s, $e, $u)
	{
		$info = mysql_fetch_assoc(mysql_query(
								"SELECT COALESCE(SUM(HOURS),0) AS HOURS, COALESCE(SUM(PTO),0) AS PTO,
								COALESCE(SUM(VACATION),0) AS VACATION FROM timesheet
								WHERE DATE >='" . $s . "' AND DATE <= '" . $e . "'
									AND USERID='" . $u . "';"));

		$total = $info['HOURS'] + $info['PTO'] + $info['VACATION'];
		return $total;
	}


	function createUser($P)
	{
		global $webaddress;

		$P = escapeArray($P);

		$uname  = $P['username'];
		$fname  = $P['firstname'];
		$lname  = $P['lastname'];
		$email  = $P['useremail'];

		$status  = "success";
		$message = "";
		$content = "";

		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);
		$password = "";
		$length = 8;
		for ($i = 0, $password = ''; $i < $length; $i++)
		{
			$index = rand(0, $count - 1);
			$password .= mb_substr($chars, $index, 1);
		}
		$hashedpassword = md5($password);

		$insertuser = mysql_query( "INSERT INTO
									eventadmin (USER, FIRST, LAST, EMAIL, PASSWORD)
									VALUES ('" . $uname . "', '" . $fname . "', '" . $lname . "', '" . $email . "', '" . $hashedpassword . "');");
		$userid = mysql_insert_id();
		if(mysql_errno())
		{
			$status = "error";
			$message = "There was a problem with the database - please call your administrator";
			//$message = "MySQL error " . mysql_errno() . ": " . mysql_error();
		}
		else
		{
			$message = "New user created!";
			$to      =  $email;
			$subject =  "New Account Created";
			$emailmessage =  "Hello,\r\n\r\nYour account has been created!\r\n\r\nYour login information is:\r\n" .
					"username: " . $uname . "\r\npassword: " . $password . "\r\n\r\n" .
					"Please go here to login and change your password:\r\n" .
					$webaddress . "tim/login.php";
			$headers =  "From: admins@doghousediaries.com" . "\r\n" .
					"Reply-To: admins@doghousediaries.com" . "\r\n" .
					"X-Mailer: PHP/" . phpversion();
			mail($to, $subject, $emailmessage, $headers);

			$content = "";
			$userquery = mysql_query("SELECT * FROM eventadmin ORDER BY ID ASC;");
			$content = "<table style='border-collapse:collapse;width:100%;'>
							<tr class='headerrow'>
								<td>username</td>
								<td>first name</td>
								<td>last name</td>
								<td>email</td><td></td><td></td></tr>";
			$count = 0;
			while($row = mysql_fetch_assoc($userquery))
			{
				$count++;
				$altclass = ($count % 2) ? "" : "altrow";
				$btnhtml  = ($row['ID'] == $_SESSION['userid']) ? 
								"<input type='button' class='updateuserbtn' value='Update' onclick='showUpdateUserForm(" . $row['ID'] . ")' />" : 
									"";
				$passhtml = ($row['ID'] == $_SESSION['userid']) ? 
								"<input type='button' class='passbtn' value='Change Password' onclick='showUpdatePasswordForm(" . $row['ID'] . ")' />" : 
									"";
				$content .= "   <input type='hidden' id='unamehdn" . $row['ID'] . "' value='" . $row['USER'] . "' />
								<input type='hidden' id='firsthdn" . $row['ID'] . "' value='" . $row['FIRST'] . "' />
								<input type='hidden' id='lasthdn" . $row['ID'] . "' value='" . $row['LAST'] . "' />
								<input type='hidden' id='emailhdn" . $row['ID'] . "' value='" . $row['EMAIL'] . "' />
								<tr class='tablerow'" . $altclass . ">
									<td id='tduname" . $row['ID'] . "'>" . $row['USER'] . "</td>
									<td id='tdfname" . $row['ID'] . "'>" . $row['FIRST'] . "</td>
									<td id='tdlname" . $row['ID'] . "'>" . $row['LAST'] . "</td>
									<td id='tdemail" . $row['ID'] . "'>" . $row['EMAIL'] . "</td>
									<td>" . $btnhtml . "</td>
									<td>" . $passhtml . "</td></tr>";
			}
			$content .= "</table>";
		}

		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> $content
		);

		echo json_encode($result);
	}


	function updateUser($P)
	{
		$P = escapeArray($P);

		$userid = $P['userid'];
		$uname  = $P['username'];
		$fname  = $P['firstname'];
		$lname  = $P['lastname'];
		$email  = $P['useremail'];

		$status  = "success";
		$message = "";
		$content = "";

		$update = mysql_query( "UPDATE eventadmin
								SET USER='"  . $uname . "',
									FIRST='" . $fname . "',
									LAST='"  . $lname . "',
									EMAIL='" . $email . "'
								WHERE ID=" . $userid . ";");
		if(mysql_errno())
		{
			$status = "error";
			$message = "There was a problem with the database - please call your administrator";
			//$message = "MySQL error " . mysql_errno() . ": " . mysql_error();
		}
		else
		{
			$message = "Your information has been updated!";
			$_SESSION['username'] = $uname;
		}

		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> $content
		);

		echo json_encode($result);
	}


	function updatePassword($P)
	{
		$P = escapeArray($P);

		$userid 	= $P['userid'];
		$currpass  	= $P['currpass'];
		$newpass  	= $P['newpass'];

		$status  = "success";
		$message = "";
		$content = "";

		$checkpass = mysql_fetch_assoc(mysql_query("SELECT ID FROM eventadmin WHERE ID='" . $userid . "' AND PASSWORD='" . md5($currpass) . "';"));

		if(isset($checkpass['ID']) && $checkpass['ID'] != "")
		{
			$update = mysql_query( "UPDATE eventadmin
									SET PASSWORD='"  . md5($newpass) . "'
									WHERE ID=" . $userid . ";");
			if(mysql_errno())
			{
				$status = "error";
				$message = "There was a problem with the database - please call your administrator";
				//$message = "MySQL error " . mysql_errno() . ": " . mysql_error();
			}
			else
			{
				$message = "Your password has been updated!";
			}
		}
		else
		{
			$status = "error";
			$message = "Please check your current password!";
		}

		$result = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> $content
		);

		echo json_encode($result);
	}


	function escapeArray($post)
	{
		//recursive function called on the POST object sent back by an AJAX call
		//it accounts for nested arrays/hashes (these were being nulled out previously)
		foreach($post as $key => $val)
		{
			if(gettype($val) == "array") {
				escapeArray($val);
			}
			else {
				$val = urldecode($val);
				$val = mysql_real_escape_string($val);
				$post[$key] = $val;
			}
		}
		return $post;
	}

	//The function returns the no. of business days between two dates and it skips the holidays
	//echo getWorkingDays("2014-12-22","2015-01-02")
	function getWorkingDays($startDate, $endDate)
	{
		//echo "<script>alert('SD: " . $startDate . "   ED: " . $endDate . "');</script>";
		// do strtotime calculations just once
		$endDate = strtotime($endDate);
		$startDate = strtotime($startDate);

		//The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
		//We add one to inlude both dates in the interval.
		$days = floor(($endDate - $startDate) / 86400) + 1;
		$no_full_weeks = floor($days / 7);
		$no_remaining_days = fmod($days, 7);

		//It will return 1 if it's Monday,.. ,7 for Sunday
		$the_first_day_of_week = date("N", $startDate);
		$the_last_day_of_week = date("N", $endDate);

		//---->The two can be equal in leap years when february has 29 days, the equal sign is added here
		//In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
		if ($the_first_day_of_week <= $the_last_day_of_week)
		{
			if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
			if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
		}
		else
		{
			// (edit by Tokes to fix an edge case where the start day was a Sunday
			// and the end day was NOT a Saturday)
			// the day of the week for start is later than the day of the week for end
			if ($the_first_day_of_week == 7)
			{
				// if the start date is a Sunday, then we definitely subtract 1 day
				$no_remaining_days--;
				if ($the_last_day_of_week == 6)
				{
					// if the end date is a Saturday, then we subtract another day
					$no_remaining_days--;
				}
			}
			else
			{
				// the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
				// so we skip an entire weekend and subtract 2 days
				$no_remaining_days -= 2;
			}
		}

		//The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
		//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
		$workingDays = $no_full_weeks * 5;
		if ($no_remaining_days > 0 )
		{
			$workingDays += $no_remaining_days;
		}
		return $workingDays;
	}


	function getWeatherInfo($P)
	{
		$P = escapeArray($P);

		//are we just loading existing info?
		$load = isset($P['load']) ? $P['load'] : false;
		//should (can) we create a new localStorage entry?
		$lcst = isset($P['localstore']) ? $P['localstore'] : true;

		//all pretty self-explanatory
		$yw = new yWeather();
		$id = $load ? $P['woeid'] : $yw->getWoeidByZip($P['zipcode']);
		$yw->setUrl("http://weather.yahooapis.com/forecastrss?w=" . $id);
		$yw->loadFeed();

		$content = array();
		$ast = $yw->getAstronomy();
		$con = $yw->getConditions();
		$loc = $yw->getLocation();
		$atm = $yw->getAtmosphere();
		$wnd = $yw->getWind();
		$frc = $yw->getForecast();

		$content['astronomy'] 	= $ast;
		$content['conditions'] 	= $con;
		$content['location'] 	= $loc;
		$content['atmosphere'] 	= $atm;
		$content['wind'] 		= $wnd;
		$content['forecast'] 	= $frc;
		$content['woeid'] 		= $id;
		$content['adddiv'] 		= $load ? false : true;
		$content['localstore']  = $lcst;

		//clear the weather object for garbage collection
		unset($yw);

		$result = array(
				"status" => "success",
				"message" => "",
				"content" => $content
		);
		echo json_encode($result);
	}

?>
