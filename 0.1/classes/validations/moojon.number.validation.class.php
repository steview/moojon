<?php
final class moojon_number_validation extends moojon_base_validation {
	
	public function __construct($message) {
		$this->set_message($message);
	}
	
	public function validate(moojon_base_column $column) {
		return is_float($column->get_value());
	}
}
?>