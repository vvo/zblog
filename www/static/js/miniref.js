(function(window, undefined){
	$(function(){

		$('.minilogoRef').click(function(){
			$(this).addClass('active').siblings().removeClass('active');
			$('#'+this.id+'B').show().siblings().hide();
		});

	});

})(this);