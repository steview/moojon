<?php
final class moojon_maxlength_validation extends moojon_base_validation {
	
	private $maxlength;
	
	public function __construct($message, $maxlength, $required = true) {
		$this->set_message($message);
		$this->maxlength = $maxlength;
		$this->required = $required;
	}
	
	public function get_maxlength() {
		return $this->maxlength;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		if (strlen($column->get_value()) > $this->maxlength) {
			return false;
		} else {
			return true;
		}
	}
}
?>