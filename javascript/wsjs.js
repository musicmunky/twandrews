var socket;

function startWs()
{
	var info = {
		"type": "POST",
		"path": "php/socklib.php",
		"data": {
			"method": 	"startWebSocket",
			"libcheck": true
		},
		"func": wsResponse
	};
	FUSION.lib.ajaxCall(info);
}


function wsResponse(h)
{
	var hash = h || "";
	if(!FUSION.lib.isBlank(hash['errmsg']))
	{
		logWs("There was an error running that command: " + hash['errmsg']);
		logWs("Result Value: " + hash['retval']);
	}
	else if(!FUSION.lib.isBlank(hash['retval']) && parseInt(hash['retval']) == 1)
	{
		logWs("Command executed - no results returned");
	}
	else if(parseInt(hash['retval']) == 0 && hash['output'].length > 0)
	{
		if(hash['output'].length > 0)
		{
			logWs("###################################<br>Server Response:");
			for(var i = 0; i < hash['output'].length; i++)
			{
				logWs("&nbsp;&nbsp;&nbsp;&nbsp;" + hash['output'][i]);
			}
		}
		else
		{
			logWs("Command run successfully, but results unexpected - please examine logs for more information");
		}
	}
	else
	{
		logWs("Invalid response - please examine logs for more information");
	}
}


function stopWs()
{
	var info = {
		"type": "POST",
		"path": "php/socklib.php",
		"data": {
			"method": 	"stopWebSocket",
			"libcheck": true
		},
		"func": wsResponse
	};
	FUSION.lib.ajaxCall(info);
}


function checkWs()
{
	var info = {
		"type": "POST",
		"path": "php/socklib.php",
		"data": {
			"method": 	"isWsRunning",
			"libcheck": true
		},
		"func": wsResponse
	};
	FUSION.lib.ajaxCall(info);
}


function restartApache()
{
	var info = {
		"type": "POST",
		"path": "php/socklib.php",
		"data": {
			"method": 	"restartApache",
			"libcheck": true
		},
		"func": restartApacheResponse
	};
	FUSION.lib.ajaxCall(info);
}


function restartApacheResponse(h)
{
	var hash = h || "";
	logWs(hash);
}


function initWs() {
	//var host = "ws://45.55.189.112:9000"; // SET THIS TO YOUR SERVER
	var host = "ws://twandrews.com:80/wschat"; // SET THIS TO YOUR SERVER
// 	var host = "ws://localhost/wschat"; // SET THIS TO YOUR SERVER

	try
	{
		socket = new WebSocket(host);
		logWs("WebSocket - status " + socket.readyState);

		socket.onopen = function(msg)
		{
			if(this.readyState == 1)
			{
				logWs("We are now connected to websocket server. readyState = " + this.readyState + "<br>MESSAGE: " + msg.data);
			}
		};

		//Message received from websocket server
		socket.onmessage = function(msg)
		{
			logWs("[+] Received: " + msg.data);
		};

		//Connection closed
		socket.onclose = function(msg)
		{
			logWs("Disconnected - status " + this.readyState);
		};

		socket.onerror = function()
		{
			logWs("Some error");
		}
	}

	catch(ex)
	{
		logWs("Some exception: "  + ex);
	}

	FUSION.get.node("msg").focus();
}

function clearWs()
{
	FUSION.get.node("log").innerHTML = "";
}

function sendWs()
{
	var txt, msg;
	txt = FUSION.get.node("msg");
	msg = txt.value;

	if(!msg)
	{
		alert("Message can not be empty");
		return;
	}

	try
	{
		socket.send(msg);
		logWs("Sent : " + msg);

 		txt.value = "";
		txt.focus();
	}
	catch(ex)
	{
		logWs(ex);
	}
}

function quitWs()
{
	if (socket != null)
	{
		logWs("Goodbye!");
		socket.close();
		socket=null;
	}
}

function reconnectWs()
{
	quitWs();
	initWs();
}


function logWs(msg)
{
	var lg = FUSION.get.node("log");
	lg.innerHTML = (FUSION.lib.isBlank(lg.innerHTML)) ? msg : lg.innerHTML + "<br>" + msg;
	lg.scrollTop = lg.scrollHeight;
}

function onkey(event)
{
	if(event.keyCode == 13)
	{
		event.preventDefault();
		sendWs();
	}
}