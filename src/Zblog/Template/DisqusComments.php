<?php

Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

class Zblog_Template_DisqusComments extends Pluf_Template_Tag {

	function start($post, $params=array(), $get_params=array()) {
		echo '<a data-disqus-identifier="'.$post->getDisqusId().'" class="floatRight commentsCount font11" href="'.$post->getFriendlyUrl().'#disqus_thread"></a>';
	}
}