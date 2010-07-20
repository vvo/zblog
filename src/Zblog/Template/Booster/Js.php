<?php

require_once('booster/booster_inc.php');

class Zblog_Template_Booster_Js extends Pluf_Template_Tag {
	function start($js_files, $params=array(), $get_params=array()) {
		$booster = new Booster();
		if (Pluf::f('debug') !== TRUE) {
			$js_files = explode(',', $js_files);
			$js_files = implode(',../../', $js_files);
			$js_files = '../../'.$js_files;
			$booster->js_hosted_minifier = TRUE;
			$booster->js_totalparts = 1;
			$booster->js_source = $js_files;
			$booster->booster_cachedir_autocleanup = FALSE;
			$booster->js_executionmode = "defer";
			echo $booster->js_markup();
		}
		else {
			$sources = split(',', $js_files);
			$sources_full_path = array();
			foreach($sources as $key => $source) {
				$sources_full_path[$key] = $_SERVER['DOCUMENT_ROOT'].'/'.$source;
			}

			foreach($sources as $key => $source) {
				if (is_dir($sources_full_path[$key])) {
					$files = $booster->getfiles('../../'.$sources[$key], 'js', true);
					foreach ($files as $file) {
						$file = str_replace('../../', '', $file);
						echo "\n";
						echo '<script defer type="text/javascript" src="/'.$file.'"></script>';
					}
				} else {
					if (is_file($source)) {
						echo "\n";
//						$source = str_replace($_SERVER['DOCUMENT_ROOT'], '', $source);
						echo '<script defer type="text/javascript" src="/'.$source.'"></script>';
					}
				}

			}
		}
	}
}