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

	function __toString() {
		return $this->name;
	}
}
