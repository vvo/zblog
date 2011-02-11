$(function(){
	var $article = $('#article_content');
	if ($article[0]) {

		var $nav = $('<ul id="article_navigation" class="marg0 font11 pad0 hide_nav">'),
			$titres = $article.find('h1, h2, h3');

		$nav.append($('<li class="pointer caps sansRounded">+ navigation</li>')
		.toggle(function() {
			$(this).text('- navigation');
			$nav.removeClass("hide_nav");
		}, function() {
			$(this).text('+ navigation');
			$nav.addClass("hide_nav");
		})
		.hover(function(){$(this).addClass('orange')}, function(){$(this).removeClass('orange')}));

		$titres.each(function(i){
			var $titre = $(this),
				tag_name = this.tagName.toLowerCase();

			$titre.attr('id', 'title-'+i);
			$('<li>', {
				"href" : "#title-"+i,
				"text" : $titre.text(),
				"click" : function() {
					$.scrollTo($titre, 300);
				},
				"css" : {
					"marginLeft" : function() {
						var value;

						if(tag_name === 'h2') {
							value = '2'
						} else if (tag_name === 'h3') {
							value = '3'
						} else {
							value = '1';
						}
						
						return value+'em';
					}
				}
			})
			.hover(function(){$(this).addClass('orange')}, function(){$(this).removeClass('orange')})
			.addClass('pointer')
			.appendTo($nav);
		});

		$nav.appendTo('body');

	}
});