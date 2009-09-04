<?php
final class moojon_required_validation extends moojon_base_validation {
	
	public function __construct($message) {
		$this->set_message($message);
		$this->required = true;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		if (strlen($column->get_value())) {
			return true;
		} else {
			return false;
		}
	}
}
?>