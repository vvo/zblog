/* closure compiler compatible */
(function(w){
	var latest_delicious = function(links) {
		var $delicious = $('#delicious_div'), html = '<div class="textContent">';
		$.each(links, function(i, el) {
			html+='<div class="classicBorderBottom pad4pxt pad8pxb"><a href="'+el.u+'">'+el.d+'</a></div>';
		});
		$delicious.html(html+'</div>');
	};

	w['latest_delicious'] = latest_delicious;
})(this);