<?php
class Zblog_Post extends Pluf_Model {
	public $_model = __CLASS__;

	function init() {
		$this->_a['table'] = 'zblog_post';

		$this->_a['model'] = 'Zblog_Post';

		$this->_a['cols'] = array(
				'id' =>
				array(
						'type' => 'Pluf_DB_Field_Sequence'
				),
				'title' =>
				array(
						'type' => 'Pluf_DB_Field_Varchar',
						'size' => 200,
						'verbose' => __('title'),
						'index' => true
				),
				'content' =>
				array(
						'type' => 'Pluf_DB_Field_Text',
						'widget' => 'Zblog_Form_Widget_MarkitupInput',
						'verbose' => __('post'),
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
				),
				'image' =>
				array(
						'type' => 'Pluf_DB_Field_Varchar',
						'size' => 200,
						'verbose' => __('article image')
				),
				'summary' =>
				array(
						'type' => 'Pluf_DB_Field_Text',
						'verbose' => __('summary of article')
				),
				'tags' =>
				array(
						'type' => 'Pluf_DB_Field_Manytomany',
						'model' => 'Zblog_Tag',
						'verbose' => __('tags'),
						'relate_name' => 'posts'
				)
		);

		$this->_a['idx'] = array();

		$this->_a['views'] = array();
	}

	public function getDisqusId() {
		if (Pluf::f('debug') === true || Pluf::f('is_beta') === true) {
			return Pluf::f('disqus_prefix').'DEV'.$this->id;
		} else {
			return Pluf::f('disqus_prefix').$this->id;
		}
	}

	public function getFriendlyUrl() {
		$string = urlize($this->title);
		$url = Pluf_HTTP_URL_urlForView('blog_post', array($this->id, $string));
		return $url;
	}

	function __toString() {
		return $this->title;
	}
}
