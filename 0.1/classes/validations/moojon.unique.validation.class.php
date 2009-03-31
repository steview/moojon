<?php
final class moojon_unique_validation extends moojon_base_validation {
	
	private $model;
	
	public function __construct($message, moojon_base_model $model, $required = true) {
		$this->set_message($message);
		$this->model = $model;
		$this->required = $required;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		if ($this->model->read($column->get_name()." = '".$column->get_value()."'")->count == 0) {
			return true;
		} else {
			return false;
		}
	}
}
?>