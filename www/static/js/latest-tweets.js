/* closure compiler compatible */
(function(){
	var twitterCallback2 = function(twitters) {
  var statusHTML = [];
  for (var i=0; i<twitters.length; i++){
    var username = twitters[i]['user']['screen_name'];
    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
      return '<a href="'+url+'">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
    });

	var when = new Date(twitters[i]['created_at']);
	var when_date = when.getDate();
	var when_month = when.getMonth();

    statusHTML.push('<div><a class="font12 gray sansRounded" href="http://twitter.com/'+username+'/statuses/'+twitters[i]['id']+'">'+(when_date < 10 ? '0'+when_date : when_date)+'/'+(when_month < 9 ? '0'+(when_month+1) : when_month+1)+'</a> <span class="textContent">'+status+'</span></div>');
	if (i < twitters.length - 1) {
		statusHTML.push('<div class="classicBorderTop pad8pxt marg8pxt"></div>');
	}
  }
  document.getElementById('twitter_div').innerHTML = statusHTML.join('');
};

window['twitterCallback2'] = twitterCallback2;
})();