<?php

require_once('booster/booster_inc.php');

class Zblog_Template_Booster_Css extends Pluf_Template_Tag {
//	Pluf::f('mail_to')
	function start($css_files, $params=array(), $get_params=array()) {
		$booster = new Booster();
		if (Pluf::f('debug') === TRUE) {
			$booster->debug = TRUE;
		}
		$booster->css_hosted_minifier = true;
		$booster->css_totalparts = 1;
		$booster->css_source = $css_files;
		echo $booster->css_markup();
	}
}