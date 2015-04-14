<?php ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Tim's Training Schedule</title>
		<link rel="icon" type="image/png" href="images/calicon.png" />
		<link rel='stylesheet' href='css/trainstyle.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/jquery-ui.min.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css' type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script type="text/javascript" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/training.js"></script>
	</head>
	<body>

		<div style="width:750px;margin-left:auto;margin-right:auto;margin-top:25px;">
			<table id="trainingtable" class="display">
				<thead>
					<tr>
						<th class="mainheader" colspan="10">Training Schedule</th>
					</tr>
					<tr>
						<th class="tdheader">Week</th>
						<th class="tdheader">Date</th>
						<th class="tdheader">Sun</th>
						<th class="tdheader">Mon</th>
						<th class="tdheader">Tue</th>
						<th class="tdheader">Wed</th>
						<th class="tdheader">Thu</th>
						<th class="tdheader">Fri</th>
						<th class="tdheader">Sat</th>
						<th class="tdheader">Total</th>
					</tr>
				</thead>
				<tbody>
					<tr class="">
						<td class="weekcol">1</td>
						<td class="">04/05</td>
						<td class="">Off</td><td class="">3</td><td class="">4</td><td class="">4</td>
						<td class="">Off</td><td class="">3</td><td class="">6</td>
						<td class="">20</td>
					</tr>

					<tr class="">
						<td class="weekcol">2</td>
						<td class="">04/12</td>
						<td class="">Off</td><td class="">3</td><td class="">5</td><td class="">3</td>
						<td class="">Off</td><td class="">3</td><td class="">7</td>
						<td class="">21</td>
					</tr>

					<tr class="">
						<td class="weekcol">3</td>
						<td class="">04/19</td>
						<td class="">Off</td><td class="">4</td><td class="">5</td><td class="">3</td>
						<td class="">Off</td><td class="">3</td><td class="">8</td>
						<td class="">23</td>
					</tr>

					<tr class="">
						<td class="weekcol">4</td>
						<td class="">04/26</td>
						<td class="">Off</td><td class="">4</td><td class="">3</td><td class="">4</td>
						<td class="">Off</td><td class="">3</td><td class="">10</td>
						<td class="">24</td>
					</tr>

					<tr class="">
						<td class="weekcol">5</td>
						<td class="">05/03</td>
						<td class="">Off</td><td class="">5</td><td class="">3</td><td class="">4</td>
						<td class="">Off</td><td class="">3</td><td class="">12</td>
						<td class="">27</td>
					</tr>

					<tr class="">
						<td class="weekcol">6</td>
						<td class="">05/10</td>
						<td class="">Off</td><td class="">4</td><td class="">4</td><td class="">6</td>
						<td class="">Off</td><td class="">5</td><td class="">5</td>
						<td class="">24</td>
					</tr>

					<tr class="">
						<td class="weekcol">7</td>
						<td class="">05/17</td>
						<td class="">Off</td><td class="">3</td><td class="">3</td><td class="">5</td>
						<td class="">Off</td><td class="">3</td><td class="">15</td>
						<td class="">29</td>
					</tr>

					<tr class="">
						<td class="weekcol">8</td>
						<td class="">05/24</td>
						<td class="">Off</td><td class="">6</td><td class="">5</td><td class="">6</td>
						<td class="">Off</td><td class="">5</td><td class="">7</td>
						<td class="">29</td>
					</tr>

					<tr class="">
						<td class="weekcol">9</td>
						<td class="">05/31</td>
						<td class="">Off</td><td class="">6</td><td class="">4</td><td class="">7</td>
						<td class="">Off</td><td class="">3</td><td class="">18</td>
						<td class="">38</td>
					</tr>

					<tr class="">
						<td class="weekcol">10</td>
						<td class="">06/07</td>
						<td class="">Off</td><td class="">7</td><td class="">6</td><td class="">7</td>
						<td class="">Off</td><td class="">6</td><td class="">9</td>
						<td class="">25</td>
					</tr>

					<tr class="">
						<td class="weekcol">11</td>
						<td class="">06/14</td>
						<td class="">Off</td><td class="">5</td><td class="">5</td><td class="">8</td>
						<td class="">Off</td><td class="">3</td><td class="">20</td>
						<td class="">41</td>
					</tr>

					<tr class="">
						<td class="weekcol">12</td>
						<td class="">06/21</td>
						<td class="">Off</td><td class="">3</td><td class="">5</td><td class="">8</td>
						<td class="">Off</td><td class="">3</td><td class="">10</td>
						<td class="">29</td>
					</tr>

					<tr class="">
						<td class="weekcol">13</td>
						<td class="">06/28</td>
						<td class="">Off</td><td class="">6</td><td class="">6</td><td class="">8</td>
						<td class="">Off</td><td class="">3</td><td class="">22</td>
						<td class="">45</td>
					</tr>

					<tr class="">
						<td class="weekcol">14</td>
						<td class="">07/05</td>
						<td class="">Off</td><td class="">7</td><td class="">5</td><td class="">8</td>
						<td class="">Off</td><td class="">5</td><td class="">10</td>
						<td class="">35</td>
					</tr>

					<tr class="">
						<td class="weekcol">15</td>
						<td class="">07/12</td>
						<td class="">Off</td><td class="">6</td><td class="">6</td><td class="">8</td>
						<td class="">Off</td><td class="">3</td><td class="">24</td>
						<td class="">47</td>
					</tr>

					<tr class="">
						<td class="weekcol">16</td>
						<td class="">07/19</td>
						<td class="">Off</td><td class="">4</td><td class="">7</td><td class="">10</td>
						<td class="">Off</td><td class="">4</td><td class="">10</td>
						<td class="">35</td>
					</tr>

					<tr class="">
						<td class="weekcol">17</td>
						<td class="">07/26</td>
						<td class="">Off</td><td class="">5</td><td class="">3</td><td class="">5</td>
						<td class="">Off</td><td class="">3</td><td class="">26</td>
						<td class="">42</td>
					</tr>

					<tr class="">
						<td class="weekcol">18</td>
						<td class="">08/02</td>
						<td class="">Off</td><td class="">6</td><td class="">5</td><td class="">8</td>
						<td class="">Off</td><td class="">4</td><td class="">12</td>
						<td class="">35</td>
					</tr>

					<tr class="">
						<td class="weekcol">19</td>
						<td class="">08/09</td>
						<td class="">Off</td><td class="">5</td><td class="">4</td><td class="">6</td>
						<td class="">Off</td><td class="">3</td><td class="">12</td>
						<td class="">30</td>
					</tr>

					<tr class="">
						<td class="weekcol">20</td>
						<td class="">08/16</td>
						<td class="">Off</td><td class="">5</td><td class="">4</td><td class="">Off</td>
						<td class="">Off</td><td class="">2</td><td class="">26.2</td>
						<td class="">37</td>
					</tr>
				</tbody>
			</table>
		</div>

	</body>
</html>