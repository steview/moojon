<?php
final class moojon_minlength_validation extends moojon_base_validation {
	
	private $minlength;
	
	public function __construct($message, $minlength) {
		$this->set_message($message);
		$this->minlength = $minlength;
	}
	
	public function validate(moojon_base_model $model, moojon_base_column $column) {
		if (strlen($column->get_value()) < $this->minlength) {
			return false;
		} else {
			return true;
		}
	}
}
?>