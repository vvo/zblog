(function(window, undefined){

	$(function(){
		var $slider = $('#slider'),
			$liens = $('#sliderMenu a'),
			$slides = $('#slides > .wrapper > div'),
			selectedClasses = 'selected black',
			normalClass = 'yellow',
			$loader = $('.loader'),
			timer, /* next slide */
			timer2, /* load event OR 2500ms */
//			timer3, /* mouseleave / enter */
			nextLink,
			currentSlide = 0,
			timelapse = 10000,
			nbSlides = $liens.length;

		$slider.bind('mouseenter', function(){
//			clearTimeout(timer3);
			clearTimeout(timer);
			$slides.eq(currentSlide).stop(true, true).show().siblings().hide();
		});

		$slider.bind('mouseleave', function() {
			timer = setTimeout(function(){
				nextLink((currentSlide < (nbSlides-1) ? currentSlide+1 : 0));
			}, timelapse-2000);
		});


		$liens.click(function(e){
			var slideNumber = $liens.index(this);
			currentSlide = slideNumber;
			$(this)
				.removeClass(normalClass)
				.addClass(selectedClasses)
				.siblings().removeClass(selectedClasses)
				.addClass(normalClass);

			$slides.eq(slideNumber).fadeIn().siblings().fadeOut();
			this.blur();
			return false;
		});

		timer2 = setTimeout(function(){
			$loader.fadeOut(function(){
				$(this).remove();
			});
		}, 800);

		$(window).load(function(){
			$loader.fadeOut(function(){
				clearTimeout(timer2);
				$(this).remove();
			});
		});

		nextLink = function(i) {
			currentSlide = i;
			$liens.eq(i).trigger('click');
			timer = setTimeout(function(){
				nextLink((i < (nbSlides-1) ? i+1 : 0));
			}, timelapse);
		}

		nextLink(currentSlide);

	});

})(this);
