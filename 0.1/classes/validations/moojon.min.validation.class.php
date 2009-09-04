<?php
final class moojon_min_validation extends moojon_base_validation {
	
	private $min;
	
	public function __construct($message, $min, $required = true) {
		$this->set_message($message);
		$this->min = $min;
		$this->required = $required;
	}
	
	public function get_min() {
		return $this->min;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		$value = (integer)$column->get_value();
		if ($value < $this->min) {
			return false;
		} else {
			return true;
		}
	}
}
?>