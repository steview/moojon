<?php
final class moojon_min_validation extends moojon_base_validation {
	
	private $min;
	
	public function __construct($message, $min) {
		$this->set_message($message);
		$this->min = $min;
	}
	
	public function validate(moojon_base_model $model, moojon_base_column $column) {
		$value = (integer)$column->get_value();
		if ($value < $this->min) {
			return false;
		} else {
			return true;
		}
	}
}
?>