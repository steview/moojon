<?php
abstract class moojon_base_validation_adpater extends moojon_base {
	protected $model;
	protected $form;
	
	final private function __construct() {}
	
	final public function init(moojon_base_model $model, moojon_form_tag $form) {
		$this->form = $form;
		$this->model = $model;
	}
	
	abstract public function execute();
}
?>
