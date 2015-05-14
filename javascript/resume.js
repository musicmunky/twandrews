//start with the jQuery stuff...
jQuery(document).ready(function() {

	setHighlight();

	jQuery('.sidebar-nav').click(function(e) {
		var navdiv = this;
		jQuery('html,body').animate({
			scrollTop: jQuery("#" + jQuery(navdiv).attr("id") + "-content").offset().top
		}, 250, function(){
			jQuery('.sidebar-nav').each(function(i) {
				jQuery(this).removeClass("sidebar-nav-active");
			});
			jQuery(navdiv).addClass("sidebar-nav-active");
		});
		return false;
	});

});

window.onscroll = function onScroll(event) { setHighlight(); }

function setHighlight()
{
	var scrollPos = jQuery(window).scrollTop();
	jQuery('.sidebar-nav').each(function() {

		var currdiv = jQuery("#" + jQuery(this).attr("id") + "-content");

		if((currdiv.position().top <= (scrollPos + 200)) && (currdiv.position().top + currdiv.height() - 50) > scrollPos)
		{
			jQuery('.sidebar-nav').each(function(){
				jQuery(this).removeClass("sidebar-nav-active");
			});
			jQuery(this).addClass("sidebar-nav-active");
		}
	});
}




/*****************************************/
/* code to play with later...for science */
/*****************************************/
function smoothScroll(eID, tf)
{
	var startY = currentYPosition();
	var stopY = elmYPosition(eID);

	var distance = (stopY > startY) ? (stopY - startY) : (startY - stopY);
	if(distance < 100)
	{
		window.scrollTo(0, stopY);
		return;
	}

	var speed = Math.round(distance / 100);
	speed = (speed >= 20) ? 20 : speed;

	var step  = Math.round(distance / 25);
	var leapY = (stopY > startY) ? (startY + step) : (startY - step);
	var timer = 0;
	if(stopY > startY)
	{
		for( var i = startY; i < stopY; i += step )
		{
			setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
			leapY += step; if (leapY > stopY) leapY = stopY; timer++;
		}
		return;
	}

	for( var i = startY; i > stopY; i -= step )
	{
		setTimeout("window.scrollTo(0, " + leapY + ")", timer * speed);
		leapY -= step;
		if (leapY < stopY)
			leapY = stopY;
		timer++;
	}
}

function elmYPosition(eID)
{
	var elm  = FUSION.get.node(eID);
	var y 	 = elm.offsetTop;
	var node = elm;
	while (node.offsetParent && node.offsetParent != document.body)
	{
		node = node.offsetParent;
		y += node.offsetTop;
	}
	return y;
}


function currentYPosition()
{
	var cyp = document.documentElement.scrollTop || document.body.scrollTop;
	return cyp;
}

