<?php

require_once('booster/booster_inc.php');

class Zblog_Template_Booster_Css extends Pluf_Template_Tag {
//	Pluf::f('mail_to')
	function start($css_files, $params=array(), $get_params=array()) {
		$booster = new Booster();
		if (Pluf::f('booster') === TRUE) {
			$css_files = explode(',', $css_files);
			$css_files = implode(',../../', $css_files);
			$css_files = '../../'.$css_files;
//			$booster->invert_datauri_mode = TRUE;
			$booster->css_hosted_minifier = TRUE;
			$booster->css_totalparts = 1;
			$booster->css_source = $css_files;
			$booster->booster_cachedir_autocleanup = FALSE;
			echo $booster->css_markup();
		}
		else {
			$sources = split(',', $css_files);
			$sources_full_path = array();
			foreach($sources as $key => $source) {
				$sources_full_path[$key] = $_SERVER['DOCUMENT_ROOT'].'/'.$source;
			}

			foreach($sources as $key => $source) {
				if (is_dir($sources_full_path[$key])) {
					$files = $booster->getfiles('../../'.$sources[$key], 'css', true);
					foreach ($files as $file) {
						$file = str_replace('../../', '', $file);
						echo "\n";
						echo '<link rel="stylesheet" media="all" type="text/css" href="/'.$file.'" />';
					}
				} else {
					if (is_file($source)) {
						echo "\n";
						echo '<link rel="stylesheet" media="all" type="text/css" href="/'.$source.'" />';
					}
				}

			}
		}
	}
}