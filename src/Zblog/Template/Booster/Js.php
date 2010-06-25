<?php

require_once('booster/booster_inc.php');

class Zblog_Template_Booster_Js extends Pluf_Template_Tag {
	function start($js_files, $params=array(), $get_params=array()) {
		$booster = new Booster();
		if (Pluf::f('debug') !== TRUE) {
			$booster->js_hosted_minifier = true;
			$booster->js_totalparts = 1;
			$booster->js_source = $js_files;
			echo $booster->js_markup();
		}
		else {
			$sources = split(',', $js_files);
			foreach($sources as $key => $source) {
				$sources[$key] = $_SERVER['DOCUMENT_ROOT'].'/'.$source;
			}

			foreach($sources as $source) {
				if (is_dir($source)) {
					$files = $booster->getfiles($source);
					foreach ($files as $file) {
						$file = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
						echo "\n";
						echo '<script defer="defer" type="text/javascript" src="'.$file.'"></script>';
					}
				} else {
					if (is_file($source)) {
						echo "\n";
						$source = str_replace($_SERVER['DOCUMENT_ROOT'], '', $source);
						echo '<script defer="defer" type="text/javascript" src="'.$source.'"></script>';
					}
				}

			}
		}
	}
}