<?php
final class moojon_range_validation extends moojon_base_validation {
	
	private $min;
	private $max;
	
	public function __construct($message, $min, $max, $required = true) {
		$this->set_message($message);
		$this->min = $min;
		$this->max = $max;
		$this->required = $required;
	}
	
	public function get_min() {
		return $this->min;
	}
	
	public function get_max() {
		return $this->max;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		$value = (integer)$column->get_value();
		if ($value < $this->min || $value > $this->max) {
			return false;
		} else {
			return true;
		}
	}
}
?>