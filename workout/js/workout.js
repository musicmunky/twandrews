$(document).ready(function(){

	var dDay = new Date();

	$('.carouselcontainer').slick({
		dots: true,
		infinite: true,
		speed: 300,
		initialSlide: dDay.getDay(),
		responsive: [{
			breakpoint: 1024,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				dots: true
			}
		},
		{
			breakpoint: 600,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				dots: false,
				arrows: false
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				dots: false,
				arrows: false
			}
		}]
		// You can unslick at a given breakpoint now by adding:
		// settings: "unslick"
		// instead of a settings object
	});
});

