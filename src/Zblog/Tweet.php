<?php
class Zblog_Tweet extends Pluf_Model {
	public $_model = __CLASS__;

	function init() {
		$this->_a['table'] = 'zblog_tweet';

		$this->_a['model'] = 'Zblog_Tweet';

		$this->_a['cols'] = array(
				'id' =>
				array(
						'type' => 'Pluf_DB_Field_Sequence'
				),
				'content' =>
				array(
						'type' => 'Pluf_DB_Field_Varchar',
						'size' => 140,
						'widget' => 'Pluf_Form_Widget_TextareaInput',
						'verbose' => __('say it'),
				),
				'post_date' =>
				array(
						'type' => 'Pluf_DB_Field_Datetime',
						'verbose' => __('publication date'),
						'index' => true
				),
				'draft' =>
				array(
						'type' => 'Pluf_DB_Field_Boolean',
						'default' => true
				)
		);

		$this->_a['idx'] = array();

		$this->_a['views'] = array();
	}

	function __toString() {
		return $this->content;
	}
}
