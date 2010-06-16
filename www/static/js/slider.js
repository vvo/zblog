(function(window, undefined){

	$(function(){
		var $liens = $('#sliderMenu a'),
			$slides = $('#slides > div'),
			selectedClasses = 'selected black',
			normalClass = 'yellow';

		$liens.click(function(e){
			var slideNumber = $liens.index(this);
			$(this)
				.removeClass(normalClass)
				.addClass(selectedClasses)
				.siblings().removeClass(selectedClasses)
				.addClass(normalClass);
			$slides.eq(slideNumber).show().siblings().hide();
			this.blur();
			return false;
		}).eq(0).trigger('click');
	});

})(this);
