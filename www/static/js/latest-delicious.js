/* closure compiler compatible */
(function(){
	var latest_delicious = function(links) {
		var $delicious = $('#delicious_div'), html = '<div class="font13 textContent">';
		console.dir(links);
		$.each(links, function(i, el) {
			html+='<div class="classicBorderBottom pad4pxt pad8pxb"><a href="'+el.u+'">'+el.d+'</a></div>';
		});
		$delicious.html(html+'</div>');
	};

	window['latest_delicious'] = latest_delicious;
})();