<?php
class Zblog_Form_Contact extends Pluf_Form {
	public function initFields($extra=array()) {
		$this->fields['name'] = new Pluf_Form_Field_Varchar(
				array(
						'label' => 'Prénom Nom '
		));

		$this->fields['phone'] = new Pluf_Form_Field_Varchar(
				array('label' => 'Téléphone '
		));

		$this->fields['email'] = new Pluf_Form_Field_Email(
				array('label' => 'Email ',
						'required' => true
		));

		$this->fields['message'] = new Pluf_Form_Field_Varchar(
				array('required' => true,
						'label' => 'Message ',
						'widget' => 'Pluf_Form_Widget_TextareaInput',
						'widget_attrs' => array('rows' => 5,
								'cols' => 75),
		));
	}
}

