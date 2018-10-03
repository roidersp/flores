jQuery(document).ready(function($){
	/* Countdown timer circle */
	ftc_timer_circles();
	$(window).bind('resize', $.throttle(250, function(){
		ftc_timer_circles();
	}));

});
function ftc_timer_circles(){
		jQuery('.ftc-timer-circles').TimeCircles();
	}


