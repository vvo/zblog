<?php

require_once('booster/booster_inc.php');

class Zblog_Template_Booster_Js extends Pluf_Template_Tag {
	function start($js_files, $params=array(), $get_params=array()) {
		if($params["defer"] === TRUE) {
			$defer = " defer";
		} else {
			$defer = "";
		}

		if($params["async"] === TRUE) {
			$async = " async";
		} else {
			$async = "";
		}

		$booster = new Booster();
		if (Pluf::f('booster') === TRUE) {
			$js_files = explode(',', $js_files);
			$js_files = implode(',../../', $js_files);
			$js_files = '../../'.$js_files;
			$booster->js_hosted_minifier = TRUE;
			$booster->js_totalparts = 1;
			$booster->js_source = $js_files;
			$booster->booster_cachedir_autocleanup = FALSE;
			$booster->js_executionmode = "defer";
			$markup = $booster->js_markup();
			if($params["cjs"] === TRUE) {
				$markup = str_replace(array('type="text/javascript"', 'src="', ' defer="defer"'), array('type="text/cjs"', 'data-cjssrc="', ''), $markup);
			}
			echo $markup;
		}
		else {
			$sources = explode(',', $js_files);
			$sources_full_path = array();
			foreach($sources as $key => $source) {
				$sources_full_path[$key] = $_SERVER['DOCUMENT_ROOT'].'/'.$source;
			}

			foreach($sources as $key => $source) {
				if (is_dir($sources_full_path[$key])) {
					$files = $booster->getfiles('../../'.$sources[$key], 'js', true);
					// on n'ira pas voir dans les sous dossiers
					foreach ($files as $file) {
						$file = str_replace('../../', '', $file);
						echo "\n";
						if($params["cjs"] === TRUE) {
							echo '<script type="text/cjs" data-cjssrc="/'.$file.'"></script>';
						} else {
							echo '<script'.$defer.$async.' defer src="/'.$file.'"></script>';
						}
					}
				} else {
					if (is_file($source)) {
						echo "\n";
						if($params["cjs"] === TRUE) {
							echo '<script type="text/cjs" data-cjssrc="/'.$source.'"></script>';
						} else {
							echo '<script defer src="/'.$source.'"></script>';
						}
					}
				}

			}
		}
	}
}