var socket;

function initWs() {
	//var host = "ws://45.55.189.112:9000"; // SET THIS TO YOUR SERVER
	var host = "ws://twandrews.com/chat/test"; // SET THIS TO YOUR SERVER

	try
	{
		socket = new WebSocket(host);
		logWs("WebSocket - status " + socket.readyState);

		socket.onopen = function(msg)
		{
			if(this.readyState == 1)
			{
				logWs("We are now connected to websocket server. readyState = " + this.readyState);
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