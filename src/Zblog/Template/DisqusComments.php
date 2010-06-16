<?php

Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

class Zblog_Template_DisqusComments extends Pluf_Template_Tag {
	function start($post, $params=array(), $get_params=array()) {
		echo '<a class="floatRight commentsCount font11" href="'.Pluf_HTTP_URL_urlForView('blog_post', strtolower($post->title)).'#disqus_thread"></a>';
	}
}