(function(window, undefined){

	$(function(){
		var $slider = $('#slider'),
			$liens = $('#sliderMenu a'),
			$slides = $('#slides .wrapper > div'),
			selectedClasses = 'selected black',
			normalClass = 'yellow',
			$loader = $('.loader'),
			timer,
			timer2,
			nextLink,
			timelapse = 2500,
			nbSlides = $liens.length;

		$slider.bind('mouseenter', function(){
			clearTimeout(timer);
			$slider.unbind('mouseenter');
		});

		$liens.click(function(e){
			var slideNumber = $liens.index(this);
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
		}, 2500);

		$(window).load(function(){
			$loader.fadeOut(function(){
				clearTimeout(timer2);
				$(this).remove();
			});
		});

		nextLink = function(i) {
			$liens.eq(i).trigger('click');
			timer = setTimeout(function(){
				nextLink((i < (nbSlides-1) ? i+1 : 0));
			}, timelapse);
		}

		nextLink(0);

	});

})(this);
