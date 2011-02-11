<?php

Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

class Zblog_Template_Jsmin extends Pluf_Template_Tag {
	function start($file, $params=array(), $get_params=array()) {
		$compiler = realpath(dirname(__FILE__).'/../../../www/compiler.jar');
		$file = realpath(dirname(__FILE__).'/../../../www/static/'.$file);
//		$minified_code = `java -jar $compiler --compilation_level ADVANCED_OPTIMIZATIONS --charset utf-8 --js $file`;
		$minified_code = `java -jar $compiler --charset utf-8 --js $file`;
		echo '<script type="text/javascript">';
		echo $minified_code;
		echo '</script>';
	}
}