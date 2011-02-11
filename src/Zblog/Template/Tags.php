<?php

Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

class Zblog_Template_Tags extends Pluf_Template_Tag {
	function start($tags, $params=array(), $get_params=array()) {
		$i = 1;
		$nbTag = count($tags);
		foreach ($tags as $tag) {
			echo '<a href="'.$tag->getFriendlyUrl().'" title="Tous les billets sur le tag : '.ucfirst($tag).'" class="caps serif font10">'.$tag.'</a>';
			if ($i < $nbTag) {
				echo ' | ';
			}
			$i++;
		}
	}
}