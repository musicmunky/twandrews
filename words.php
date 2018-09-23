<?php ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="viewport" content="initial-scale=1, maximum-scale=1" />
		<title>WordStuff</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel='stylesheet' type="text/css" href='css/fusionlib.css' media="screen" charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script language="javascript" type="text/javascript" src="javascript/fusionlib.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/wordlist.js"></script>
        <style>
            html,body {
                padding:0;
                margin:0;
                height:100%;
            }

            li {
                padding: 10px;
                list-style: inside decimal-leading-zero;
                float: left;
                width: 150px;
                margin-right: 20px;
                border-radius: 10px;
                font-family: monospace;
                font-size: 18px;
            }

            li:hover {
                background-color:#eee;
                cursor:pointer;
            }
        </style>
	</head>
	<body>
		<div id="mainwrapper" class="container" style="padding-top:30px;">
            <form style="margin-bottom:50px;">
                <div class="form-group">
                    <label for="letters">Search String:</label>
                    <input type="text" class="form-control" id="letters" placeholder="Enter search string">
                </div>
                <div class="form-group">
                    <label for="minlength">Minimum Length:</label>
                    <input type="text" class="form-control" id="minlength" placeholder="Minimum word length">
                </div>
                <button type="button" class="btn btn-primary" onclick="parseWords()">Search!</button>
            </form>
            <div style="width:100%;margin: 30px 0px;border-bottom: 1px solid #ccc;padding: 10px 0px;font-size: 24px;">
                Words Found: <span id="numresults">0</span>
            </div>
            <div style="width:100%;height:500px;" id="wordlist"></div>
		</div>
	</body>
</html>