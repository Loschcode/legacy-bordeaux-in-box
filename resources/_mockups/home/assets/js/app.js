$(document).ready(function() {

	// Scroll smooth
	$('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
	        || location.hostname == this.hostname) {

	        var target = $(this.hash);
	        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	           if (target.length) {
	             $('html,body').animate({
	                 scrollTop: target.offset().top
	            }, 1000);
	            return false;
	        }
	    }
	});

	$('#slogan').fadeIn(1500);


	var positionFooter = 0;

	var loopFooter = function () {

		positionFooter = positionFooter - 0.3;

		$('.footer').stop().animate({
			backgroundPosition: positionFooter + 'px'
		}, 1, 'linear', loopFooter);

	};

	loopFooter();

	//

	// Menu select

	// Fetch all links in the navbar
	$('.subnav a').each(function() {

		var anchor = $(this).attr('href');
		
		if (anchor.substr(0, 1) == '#') {
			
			console.log(anchor);

			$(anchor).appear();

			$(document).on('appear', anchor, function() {

				console.log('appear');

				$('.subnav a').removeClass('select');

				$(anchor + '-link').addClass('select');

			});


		}

	});

	$('#top-home').appear();
	$(document).on('appear', '#top-home', function() {

		$('.subnav a').removeClass('select');

	});


	$('#logo-text').appear();

	$(document).on('disappear', '#logo-text', function() {

		$('#subnav').addClass('fixed');

	});

	$(document).on('appear', '#logo-text', function() {

		console.log('appear bro');
		$('#subnav').removeClass('fixed');

	});

	/*
	console.log($('#community').appear());
	$(document).on('appear', '#community', function() {

		console.log('appear');

		console.log($.inArray('#community', appeared));

		if ($.inArray('#community', appeared) == -1) {

			console.log('not found');
			$('#community-link').addClass('select');

		} 

	});
	*/
});
