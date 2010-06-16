<?php

require_once('booster/booster_inc.php');

class Zblog_Template_Booster_Js extends Pluf_Template_Tag {
	function start($js_files, $params=array(), $get_params=array()) {
		$booster = new Booster();
		if (Pluf::f('debug') === TRUE) {
			$booster->debug = TRUE;
		}
		$booster->js_hosted_minifier = true;
		$booster->js_totalparts = 1;
		$booster->js_source = $js_files;
		echo $booster->js_markup();
	}
}