<?php
final class moojon_range_validation extends moojon_base_validation {
	
	private $min;
	private $max;
	
	public function __construct($message, $min, $max) {
		$this->set_message($message);
		$this->min = $min;
		$this->max = $max;
	}
	
	public function validate(moojon_base_column $column) {
		$value = (integer)$column->get_value();
		if ($value < $this->min || $value > $this->max) {
			return false;
		} else {
			return true;
		}
	}
}
?>