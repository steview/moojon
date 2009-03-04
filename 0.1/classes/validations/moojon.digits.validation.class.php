<?php
final class moojon_digits_validation extends moojon_base_validation {
	
	public function __construct($message) {
		$this->set_message($message);
	}
	
	public function validate(moojon_base_model $model, moojon_base_column $column) {
		return ctype_digit($column->get_value());
	}
}
?>