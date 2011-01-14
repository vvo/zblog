<?php

Pluf::loadFunction('Pluf_Text_MarkDown_parse');

class Zblog_Template_Markdown extends Pluf_Template_Tag {
	function start($string, $params=array(), $get_params=array()) {
		echo Pluf_Text_MarkDown_parse($string);
	}
}