<?php

require_once('booster/booster_inc.php');

class Zblog_Template_Booster_Css extends Pluf_Template_Tag {
	function start($css_files, $params=array(), $get_params=array()) {
		$booster = new Booster();
		if (Pluf::f('booster') === TRUE) {
			$css_files = explode(',', $css_files);
			$css_files = implode(',../../', $css_files);
			$css_files = '../../'.$css_files;
			// for this to work I will have to pass it to GET parameters
//			$booster->invert_datauri_mode = TRUE;
			$booster->css_hosted_minifier = TRUE;
			$booster->css_totalparts = 1;
			$booster->css_source = $css_files;
			$booster->booster_cachedir_autocleanup = FALSE;
			$markup = $booster->css_markup();

			// this params can remove all the conditionnal comments used by CSS-JS-Booster
			// mostly used for deployment feature
			// because we use wget, it will not try to download the IE CSS that is in conditionnal comments!
			// this should be updated when CSS-JS-Booster changes, too bad
			if($params["clean_markup"] === TRUE) {
				$replace_array = array("<!--[if IE]><![endif]-->", "<!--[if (gte IE 8)|!(IE)]><!-->", "<!--<![endif]-->",
					"<!--[if lte IE 7 ]>", "<![endif]-->");
				$markup = str_replace($replace_array, "", $markup);
			}

			echo $markup;
		}
		else {
			$sources = explode(',', $css_files);
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