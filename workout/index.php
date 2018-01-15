<?php
    $sExReps = "<tr><th class='exercise'>Exercise</th><th class='reps'>Reps</th></tr>";
    $sCardio = "<tr><td>Running (5 miles)</td><td>N/A</td></tr>";
    $sWeight = "<tr><td>Standing Curls</td><td>20 / 16 / 15</td></tr>
                <tr><td>Pushups</td><td>30 / 30 / 30</td></tr>
                <tr><td>Skull Crushers</td><td>30 / 40 / 30</td></tr>
                <tr><td>Forearm Curls</td><td>30 / 30 / 30</td></tr>
                <tr><td>Dumbell Shrugs</td><td>40 / 40 / 40</td></tr>
                <tr><td>Kettle Bell Throws</td><td>20 / 20 / 20</td></tr>
                <tr><td>Sidebends</td><td>20 / 20 / 20</td></tr>
                <tr><td>Woodchop</td><td>20 / 20 / 20</td></tr>";

?>
<html>
	<head>
		<title>Workout Schedule</title>
		<link rel="icon" type="image/icon" href="images/favicon.ico">
		<link rel='stylesheet' href='css/workout.css' type="text/css" media="screen" charset="utf-8">
		<link rel='stylesheet' href='css/fusionlib.css' type="text/css" media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
		<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans">
		<script language="javascript" type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
		<script language="javascript" type="text/javascript" src="js/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="slick/slick.min.js"></script>
		<script language="javascript" type="text/javascript" src="js/workout.js"></script>
	</head>
	<body>
		<div id="header" class="header">
			<div id="headercont" class="header-content">
				<div class="innerheader">DAILY WORKOUT</div>
			</div>
		</div>
		<div class="maindiv">
			<div class="carouselcontainer">
				<div class="tablewrapper">
					<table class="wotable">
						<thead>
							<tr><th colspan="2">SUNDAY - Weights / Core</th></tr>
							<?php echo $sExReps; ?>
						</thead>
						<tbody>
							<?php echo $sWeight; ?>
						</tbody>
					</table>
				</div>
				<div class="tablewrapper">
					<table class="wotable">
						<thead>
							<tr><th colspan="2">MONDAY - Cardio</th></tr>
							<?php echo $sExReps; ?>
						</thead>
						<tbody>
							<?php echo $sCardio; ?>
						</tbody>
					</table>
				</div>
				<div class="tablewrapper">
					<table class="wotable">
						<thead>
							<tr><th colspan="2">TUESDAY - Cardio</th></tr>
							<?php echo $sExReps; ?>
						</thead>
						<tbody>
							<?php echo $sCardio; ?>
						</tbody>
					</table>
				</div>
				<div class="tablewrapper">
					<table class="wotable">
						<thead>
							<tr><th colspan="2">WEDNESDAY - Weights / Core / Cardio</th></tr>
							<?php echo $sExReps; ?>
						</thead>
						<tbody>
							<?php echo $sCardio; ?>
							<?php echo $sWeight; ?>
						</tbody>
					</table>
				</div>
				<div class="tablewrapper">
					<table class="wotable">
						<thead>
							<tr><th colspan="2">THURSDAY - Cardio</th></tr>
							<?php echo $sExReps; ?>
						</thead>
						<tbody>
							<?php echo $sCardio; ?>
						</tbody>
					</table>
				</div>
				<div class="tablewrapper">
					<table class="wotable">
						<thead>
							<tr><th colspan="2">FRIDAY - Cardio</th></tr>
							<?php echo $sExReps; ?>
						</thead>
						<tbody>
							<?php echo $sCardio; ?>
						</tbody>
					</table>
				</div>
				<div class="tablewrapper">
					<table class="wotable">
						<thead>
							<tr><th colspan="2">SATURDAY - Weights / Core</th></tr>
							<?php echo $sExReps; ?>
						</thead>
						<tbody>
							<?php echo $sWeight; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
