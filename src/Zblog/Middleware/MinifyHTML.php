<?php

/**
 * MinifyHTML middleware.
 *
 * Will minify the HTML onto a single line with no comments (also remove comments from <script> and <style> tags)
 * This will not take care of <pre> tags
 */
class Zblog_Middleware_MinifyHTML {

	/**
	 * Process the response of a view.
	 *
	 *
	 * @param Pluf_HTTP_Request The request
	 * @param Pluf_HTTP_Response The response
	 * @return Pluf_HTTP_Response The response
	 */
	function process_response($request, $response) {
		if (Pluf::f('debug') === false && isset($request->view[0]['minifyHTML']) && $request->view[0]['minifyHTML'] === TRUE) {
			// write a tmp file with the original content
			$return = array();
			$tmpfname = tempnam(sys_get_temp_dir(), "minifier");
			$handle = fopen($tmpfname, "w");
			fwrite($handle, $response->content);
			fclose($handle);
			
			// minify the content and get every line in $return array
			// special case, the path here is already zeroload/www and the minifier is is zeroload/tools
			exec('node ../tools/html-minifier.js < ' . $tmpfname, $return);

			// delete the temporary file
			unlink($tmpfname);

			// this will not take care of <pre> tags atm
			$return = implode("", $return); // merge all lines on same line
			$return = str_replace("	", "", $return); // remove all indent
			$return = str_replace("  ", "", $return); // remove double space

			$response->content = $return;
		} else {
			$response->content = str_replace("CSS-JS-Booster/", "CSS-JS-Booster-old/", $response->content);
		}
		return $response;
	}
}
