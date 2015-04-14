$(document).ready(function(){

	try{
		$('#trainingtable').DataTable({
			"paging": false,
			"searching": false,
			"bInfo": false
		});
	}
	catch(err){}

	//var w = screen.width;
	var w = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	var h = (window.innerHeight > 0) ? window.innerHeight : screen.height;
	//var h = screen.height;

//	alert("WIDTH IS: " + w + "\n\nHEIGHT IS: " + h);


	var d = new Date();
	var s = d.toISOString().slice(0,10);
	var clmnth = FUSION.lib.padZero(parseInt(d.getMonth())+1, 2);
	var clyear = d.getFullYear();
	var strday = "01";
	var endday = FUSION.lib.daysInMonth(clmnth, clyear);
	var str = clyear + "-" + clmnth + "-" + strday;
	var end = clyear + "-" + clmnth + "-" + endday;

	$('#calendar').fullCalendar({
		theme: true,
		header: {
			left:	'prev,next today',
			center: 'title',
			right:	'month,agendaWeek,agendaDay'
		},
		defaultDate:s,
		editable:	true,

		/*
		events: function(str, end, timezone, callback) {
			FUSION.set.overlayMouseWait();
			$.ajax({
				type: "POST",
				url: 'php/library.php',
				data: {
					method:  'getRunInfo',
					libcheck: true,
					userid: ui,
					firstload: 0,
					start: str,
					end: end
				},
				success: function(result){
					var response = JSON.parse(result);
					var events = [];
					if(response['status'] == "success")
					{
						events = response['content'];
					}
					FUSION.set.overlayMouseNormal();
					callback(events);
				},
				error: function(){
					FUSION.set.overlayMouseNormal();
					FUSION.error.logError("","Error during AJAX request!");
				}
			});
		}*/

		events: [
			{
				title: 'All Day Event',
				start: '2014-06-01'
			},
			{
				title: 'Long Event',
				start: '2014-06-07',
				end: '2014-06-10'
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: '2014-06-09T16:00:00'
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: '2014-06-16T16:00:00'
			},
			{
				title: 'Meeting',
				start: '2014-06-12T10:30:00',
				end: '2014-06-12T12:30:00'
			},
			{
				title: 'Lunch',
				start: '2014-06-12T12:00:00'
			},
			{
				title: 'Birthday Party',
				start: '2014-06-13T07:00:00'
			},
			{
				title: 'Click for Google',
				url: 'http://google.com/',
				start: '2014-06-28'
			}
		]
	});

});