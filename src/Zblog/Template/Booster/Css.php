<?php

require_once('booster/booster_inc.php');

class Zblog_Template_Booster_Css extends Pluf_Template_Tag {
//	Pluf::f('mail_to')
	function start($css_files, $params=array(), $get_params=array()) {
		$booster = new Booster();
		if (Pluf::f('debug') !== TRUE) {
			$booster->css_hosted_minifier = true;
			$booster->css_totalparts = 1;
			$booster->css_source = $css_files;
			echo $booster->css_markup();
		}
		else {
			$sources = split(',', $css_files);
			foreach($sources as $key => $source) {
				$sources[$key] = $_SERVER['DOCUMENT_ROOT'].'/'.$source;
			}

			foreach($sources as $source) {
				if (is_dir($source)) {
					$files = $booster->getfiles($source);
					foreach ($files as $file) {
						$file = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
						echo "\n";
						echo '<link rel="stylesheet" media="all" type="text/css" href="'.$file.'" />';
					}
				} else {
					if (is_file($source)) {
						echo "\n";
						$source = str_replace($_SERVER['DOCUMENT_ROOT'], '', $source);
						echo '<link rel="stylesheet" media="all" type="text/css" href="'.$source.'" />';
					}
				}

			}
		}
	}
}