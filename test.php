<?php ?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="viewport" content="initial-scale=1, maximum-scale=1">
		<title>Testing</title>
		<link rel='stylesheet' type="text/css" href='css/weather.css'  media="screen" charset="utf-8">
		<link rel='stylesheet' type="text/css" href='css/fusionlib.css' media="screen" charset="utf-8">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">

			function setStyle()
			{
				var a = FUSION.get.node("styles").value;
				var t = FUSION.get.node("txtstyle").value;
				var s = FUSION.get.node("colordiv");
				try {
					s.style[a] = t;
				}
				catch(err) {
					FUSION.error.logError(err, "Problem updating style: ");
				}
			}

		</script>
	</head>
	<body>
		<div style="width:800px;top:0;left:calc(50% - 400px);bottom:0;position:absolute;background-color:#FFF;">
			<div style="width:100%;float:left;height:200px;">
				<select id="styles" style="float:left;width:200px;">
					<option></option>
					<option value="backgroundColor">Background Color</option>
					<option value="color">Color</option>
					<option value="fontSize">Font Size</option>
				</select>
				<textarea id="txtstyle" style="width:100%;height:100px;float:left;"></textarea>
				<input type="button" style="width:100px;float:left;" value="Update Style" onclick="setStyle()" />
			</div>
			<div id="colordiv"
				 style="width:50%;background-color:#00F;height:100px;float:left;left:calc(50% - 200px);position:relative;border:1px solid;font-size:30px;">
				Showing Text Here
			</div>
		</div>
	</body>
</html>