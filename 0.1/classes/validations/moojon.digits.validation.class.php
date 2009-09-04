<?php
final class moojon_digits_validation extends moojon_base_validation {
	
	public function __construct($message, $required = true) {
		$this->set_message($message);
		$this->required = $required;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		return ctype_digit($column->get_value());
	}
}
?>