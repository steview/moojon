<?php
final class moojon_unique_validation extends moojon_base_validation {
	
	private $model;
	
	public function __construct($message, moojon_base_model $model, $required = true) {
		$this->set_message($message);
		$this->model = $model;
		$this->required = $required;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		$column_name = $column->get_name();
		if (!$this->model->read("$column_name = :$column_name", null, null, array(":$column_name" => $column->get_value()), array(":$column_name" => $column->get_data_type()))->count) {
			return true;
		} else {
			return false;
		}
	}
}
?>