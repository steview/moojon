<?php
final class moojon_equal_to_validation extends moojon_base_validation {
	
	private $value;
	
	public function __construct($message, $value) {
		$this->set_message($message);
		$this->value = $value;
	}
	
	public function validate(moojon_base_model $model, moojon_base_column $column) {
		return ($this->value == $column->get_value());
	}
}
?>