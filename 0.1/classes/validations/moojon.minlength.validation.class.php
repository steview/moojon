<?php
final class moojon_minlength_validation extends moojon_base_validation {
	
	private $minlength;
	
	public function __construct($message, $minlength, $required = true) {
		$this->set_message($message);
		$this->minlength = $minlength;
		$this->required = $required;
	}
	
	public function get_minlength() {
		return $this->minlength;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		if (strlen($column->get_value()) < $this->minlength) {
			return false;
		} else {
			return true;
		}
	}
}
?>