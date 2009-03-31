<?php
final class moojon_max_validation extends moojon_base_validation {
	
	private $max;
	
	public function __construct($message, $max, $required = true) {
		$this->set_message($message);
		$this->max = $max;
		$this->required = $required;
	}
	
	public function get_max() {
		return $this->max;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		$value = (integer)$column->get_value();
		if ($value > $this->max) {
			return false;
		} else {
			return true;
		}
	}
}
?>