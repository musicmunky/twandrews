<?php

// 	phpinfo();

 	define('LIBRARY_CHECK',true);
	require 'php/library.php';
// 	require 'golf/php/golflib.php';

$sResults = "";

/*
$aAllDates = array();

$query = mysql_query("SELECT ID, DATE FROM timesheet ORDER BY ID asc");
while($row = mysql_fetch_assoc($query))
{
    $sDate = $row['DATE'];
    $aDate = explode("-", $sDate);
    $aDateData = array(
        "ID" => $row['ID'],
        "YEAR" => $aDate[0],
        "MONTH" => intval($aDate[1]),
        "DAY" => intval($aDate[2])
    );

    $aAllDates[] = $aDateData;
}*/
/*
for($i = 0;$i < count($aAllDates); $i++)
{
    $aDate = $aAllDates[$i];
    $sResults .= "OLD RESULT: " . print_r($aDate, true) . "<br>";
    $sUpdateQuery = "UPDATE timesheet SET TSYEAR = " . $aDate['YEAR'] . ", TSMONTH = " . $aDate['MONTH'] . ", TSDAY = " . $aDate['DAY'] . "
                     WHERE ID = " . $aDate['ID'] . ";";
    try
    {
        $sResults .= "RUNNING QUERY '" . $sUpdateQuery . "'<br>";
        mysql_query($sUpdateQuery);
    }
    catch(Exception $e)
    {
        $sResults .= "ERROR: " . $e->getMessage();
    }
}*/




/*
	date_default_timezone_set('America/New_York');
	$p1  = array(
			"status"  => "success",
			"message" => "",
			"content" => array(
				"table" => "table1",
				"title" => 33
			)
	);
*/
	//$p2 = escapeArray($p1);
	//print_r($p2);
	//$pswd = md5("foobar");
	//$sql = mysqli_query($mysqli, "INSERT INTO golfusers (GOLFNAME, FIRSTNAME, LASTNAME, GOLFPASSWORD, EMAILADDRESS)
	//			VALUES ('musicmunky', 'Timothy', 'Andrews', '" . $pswd . "', 'musicmunky@gmail.com');");

/*
			$yrs = mysql_query("SELECT DISTINCT YEAR FROM scheddates ORDER BY YEAR");
			while($row = mysql_fetch_row($yrs))
			{
				$t .= "YEAR: " . $row[0] . "<br>";
			}
			echo $t;
*/
/*
			$year = 2015;
			$i = 2;

			$fw = mysql_query("SELECT DAY FROM scheddates WHERE YEAR=" . $year . " AND MONTH=" . $i . " AND DAYTYPE=40 ORDER BY DAY;");
			$hl = mysql_query("SELECT DAY FROM scheddates WHERE YEAR=" . $year . " AND MONTH=" . $i . " AND DAYTYPE=10 ORDER BY DAY;");
			$kl = mysql_query("SELECT DAY FROM scheddates WHERE YEAR=" . $year . " AND MONTH=" . $i . " AND DAYTYPE=20 ORDER BY DAY;");
			$pd = mysql_query("SELECT DAY FROM scheddates WHERE YEAR=" . $year . " AND MONTH=" . $i . " AND DAYTYPE=30 ORDER BY DAY;");

			$kell = array();
			$hols = array();
			$pays = array();

			while($row = mysql_fetch_assoc($kl))
			{
				array_push($kell, $row['DAY']);
			}
			while($row = mysql_fetch_assoc($hl))
			{
				array_push($hols, $row['DAY']);
			}
			while($row = mysql_fetch_assoc($pd))
			{
				array_push($pays, $row['DAY']);
			}
			$fwsize = mysql_num_rows($fw);
			$klsize = count($kell);
			$hlsize = count($hols);
			$pdsize = count($pays);

			echo "FW SIZE: " . $fwsize . "<br>HL SIZE: " . $hlsize . "<br>KL SIZE: " . $klsize . "<br>PD SIZE: " . $pdsize;
*/


//			$tst = mysql_query("SELECT * FROM scheddates WHERE YEAR=" . $year . " AND MONTH=" . $i . ";");
//			$tstsize = mysql_num_rows($tst);
//			echo "SIZE IS: " . $tstsize;

/*
				$r = "";
				$mysqlresult = mysql_query("SELECT * FROM scheddates;");
				while($row = mysql_fetch_assoc($mysqlresult))
				{
					$r .= "ID: " . $row['ID'] . "  YEAR: " . $row['YEAR'] . 
							"  MONTH: " . $row['MONTH'] . " DAY: " . $row['DAY'] . " TYPE: " . $row['DAYTYPE'] . "<br>";
				}
				echo $r;
*/

/*
				$result = mysql_query("SHOW COLUMNS FROM scheddates;");
				$t = "";
				if (!$result) {
					echo 'Could not run query: ' . mysql_error();
				}
				else
				{
					while($row = mysql_fetch_row($result))
					{
						$t .= "COLUMN: " . $row[0] . "<br>";
					}
					echo $t;
				}
*/


/*
				$result = mysql_query("SHOW TABLES FROM andrewsdb;");
				$t = "";
				if (!$result) {
					echo 'Could not run query: ' . mysql_error();
				}
				else
				{
					while($row = mysql_fetch_row($result))
					{
						$t .= "TABLE: " . $row[0] . "<br>";
					}
					echo $t;
				}
				*/
				// eventadmin
				// events
				// payperiodinfo
				// timesheet
				// schedtypes

//				try {
/*					$hols = array();
					$hols[2013] = array(array(0,1), array(0,21), array(1,18), array(4,27), array(6,4),
										array(8,2), array(9,14), array(10,11), array(10,28), array(11,25));

					$hols[2014] = array(array(0,1), array(0,20), array(1,17), array(4,26), array(6,4),
										array(8,1), array(9,13), array(10,11), array(10,27), array(11,25));

					$hols[2015] = array(array(0,1), array(0,19), array(1,16), array(4,25), array(6,3),
										array(8,7), array(9,12), array(10,11), array(10,26), array(11,25));
*/
/*					$kells = array();
					$kells[2013] = array(array(0,30), array(1,23), array(2,19), array(3,12), array(3,15),
										 array(4,9), array(5,2), array(5,26), array(6,20), array(7,13),
										 array(8,6), array(8,9), array(9,3), array(9,27), array(10,20),
										 array(11,14));

					$kells[2014] = array(array(0,7), array(0,31), array(1,3), array(1,27), array(2,23),
										 array(3,16), array(4,10), array(5,3), array(5,27), array(5,30),
										 array(6,24), array(7,17), array(8,10), array(9,4), array(9,28),
										 array(10,21), array(10,24), array(11,18));

					$kells[2015] = array(array(0,11), array(1,4), array(1,28), array(2,24), array(3,17),
										 array(3,20), array(4,14), array(5,7), array(6,1), array(6,25),
										 array(7,18), array(8,11), array(8,14), array(9,8), array(10,1),
										 array(10,25), array(11,19));
*/
/*					$pays = array();
					$pays[2013] = array(array(0,4), array(0,18), array(1,1), array(1,15), array(2,1),
										array(2,15), array(2,29), array(3,12), array(3,26), array(4,10),
										array(4,24), array(5,7), array(5,21), array(6,5), array(6,19),
										array(7,2), array(7,16), array(7,30), array(8,13), array(8,27),
										array(9,11), array(9,25), array(10,8), array(10,22), array(11,6),
										array(11,20));

					$pays[2014] = array(array(0,3), array(0,17), array(0,31), array(1,14), array(1,28),
										array(2,14), array(2,28), array(3,11), array(3,25), array(4,9),
										array(4,23), array(5,6), array(5,20), array(6,4), array(6,18),
										array(7,1), array(7,15), array(7,29), array(8,12), array(8,26),
										array(9,10), array(9,24), array(10,7), array(10,21), array(11,5),
										array(11,19));

					$pays[2015] = array(array(0,9), array(0,26), array(1,10), array(1,25), array(2,10),
										array(2,25), array(3,10), array(3,24), array(4,11), array(4,25),
										array(5,10), array(5,25), array(6,10), array(6,24), array(7,10),
										array(7,25), array(8,10), array(8,25), array(9,9), array(9,26),
										array(10,10), array(10,25), array(11,10), array(11,24));
*/
/*
					$fs = array(
						2013 => array(3, 2, 1, 3, 3, 2, 2, 1, 3, 3, 2, 2),
						2014 => array(1, 3, 2, 1, 1, 3, 3, 2, 1, 1, 3, 3),
						2015 => array(2, 1, 3, 2, 2, 1, 1, 3, 2, 2, 1, 1),
					);

					foreach($fs as $key => $val)
					{
						$yr = $key;
						$arr = $fs[$key];
						for($i = 0; $i < count($arr); $i++)
						{
							$mnth = $i + 1;
							$dy = $arr[$i];
							$type = 40;
							$sql = mysql_query("INSERT INTO scheddates (YEAR, MONTH, DAY, DAYTYPE)
												VALUES (" . $yr . ", " . $mnth . ", " . $dy . ", " . $type . ");");
						}
					}
*/
/*
					$s = "";
					foreach($pays as $key => $val)
					{
						$yr = $key;
						$arr = $pays[$key];
//						$s .= "YEAR: " . $yr;
						//echo "SIZE IS: " . count($arr);
						for($i = 0; $i < count($arr); $i++)
						{
							$mnth = $arr[$i][0] + 1;
							$dy = $arr[$i][1];
							$type = 30;
//							$sql = mysql_query("INSERT INTO scheddates (YEAR, MONTH, DAY, DAYTYPE)
//												VALUES (" . $yr . ", " . $mnth . ", " . $dy . ", " . $type . ");");
//							$s .= "<br>MONTH: " . $arr[$i][0] . " DAY: " . $arr[$i][1];
						}

//						$s .= "<br><br>";
					}
//					echo $s;
				}
				catch(Exception $e) {
					echo 'Caught exception: ' . $e->getMessage() . "<br>";
				}
*/
//				$mysqlresult = mysql_query("INSERT INTO schedtypes (TYPENAME, TYPEVAL) VALUES ('FIRSTWORK', 40);");
//			$mysql = mysql_query("ALTER TABLE schedtypes CHANGE TYPE TYPENAME VARCHAR(30) ;");
/*
				$pp1info = mysql_query("CREATE TABLE scheddates (
										ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
										YEAR INT(10) NOT NULL,
										MONTH INT(10) NOT NULL,
										DAY INT(10) NOT NULL,
										DAYTYPE INT(10) NOT NULL);");
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>Tim's Test Page</title>
		<link rel="icon" type="image/png" href="images/calicon.png">
		<link rel='stylesheet' href='css/style.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/jquery-ui.min.css' type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/moment.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/tsjs.js"></script>
		<script>
			/*function closePopup()
			{
				$( "#generalerrorform" ).dialog( "close" );
			}*/

            /*$('strong.badge').click(function(event)
            {
                event.preventDefault();
                $('#RandomDivId').toggleClass("randomClassName");
                return false;
            });*/




		</script>
	</head>
	<body>
		<div style="width:1000px;margin-top:50px;height:750px;margin-left:auto;margin-right:auto;">
			<input type="button" value="Show Popup" onclick="callError();" style="padding:5px;" /><br>
			<input type="button" value="Show Vdump" onclick="testvardump();" style="padding:5px;" /><br>
			<input type="button" value="testalert"  onclick="showAlert();" style="padding:5px;" />
			<div id="textdiv" style="float:left;width:100%;margin-top:20px;">
                <strong class="badge" style="cursor:pointer;">CLICK HERE</strong>
                <div id="RandomDivId"></div>
                <div>
                    <?php echo $sResults ?>
                </div>
            </div>
		</div>
	</body>
</html>
