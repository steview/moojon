<?php
final class moojon_equal_to_validation extends moojon_base_validation {
	
	private $column;
	
	public function __construct($message, moojon_base_column $column) {
		$this->set_message($message);
		$this->column = $column;
	}
	
	public function validate(moojon_base_column $column) {
		return ($this->column->get_value() == $column->get_value());
	}
}
?>