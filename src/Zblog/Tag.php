<?php
/**
 *
 */
class Zblog_Tag extends Pluf_Model {
	public $_model = __CLASS__;

	function init() {
		$this->_a['table'] = 'zblog_tag';

		$this->_a['model'] = 'Zblog_Tag';

		$this->_a['cols'] = array(
				'id' =>
				array(
						'type' => 'Pluf_DB_Field_Sequence'
				),
				'name' =>
				array(
						'type' => 'Pluf_DB_Field_Varchar',
						'size' => 100,
						'verbose' => __('nom'),
						'unique' => true
				)
		);

		$this->_a['idx'] = array();
		$this->_a['views'] = array();
	}

	public function getFriendlyUrl($more_params=array()) {
		$string = urlize($this->name);
		$base_params = array($this->id, $string);
		$params = array_merge($base_params, $more_params);
//		echo "</div></div></div></div></div>";
//		var_dump($params);
//		exit;
		if(count($params) === 3) {
			$url = 'blog_tag_page';
		} else {
			$url = 'blog_tag';
		}
		$url = Pluf_HTTP_URL_urlForView($url, $params);
		return $url;
	}

	function __toString() {
		return $this->name;
	}
}
