(function(window, undefined){
	$(function(){

		$.get("/slider", function(data) {
			$('#homepage').append(data);
			$('#slider').fadeIn("normal")
			init();
		});

		function init() {
			var $slider = $('#slider'),
			$liens = $('#sliderMenu a'),
			$slides = $('#slides > .wrapper > div'),
			selectedClasses = 'selected black',
			normalClass = 'yellow',
			timer, /* next slide */
			nextLink,
			currentSlide = 0,
			timelapse = 7000,
			nbSlides = $liens.length;

			$slider.bind('mouseenter', function(){
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

			nextLink = function(i) {
				currentSlide = i;
				$liens.eq(i).trigger('click');
				timer = setTimeout(function(){
					nextLink((i < (nbSlides-1) ? i+1 : 0));
				}, timelapse);
			}

			nextLink(currentSlide);

		}

	});

})(this);
