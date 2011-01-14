<?php

Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

class Zblog_Template_Tags extends Pluf_Template_Tag {
	function start($tags, $params=array(), $get_params=array()) {
		$i = 1;
		$nbTag = count($tags);
		foreach ($tags as $tag) {
			$url = Pluf_HTTP_URL_urlForView('blog_tag', array(rawurlencode(strtolower($tag))));
			echo '<a href="'.$url.'" title="Tous les billets sur le tag : '.ucfirst($tag).'" class="caps serif font10">'.$tag.'</a>';
			if ($i < $nbTag) {
				echo ' | ';
			}
			$i++;
		}
	}
}