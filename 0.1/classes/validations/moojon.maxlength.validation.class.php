<?php
final class moojon_maxlength_validation extends moojon_base_validation {
	
	private $maxlength;
	
	public function __construct($message, $maxlength) {
		$this->set_message($message);
		$this->maxlength = $maxlength;
	}
	
	public function validate(moojon_base_model $model, moojon_base_column $column) {
		if (strlen($column->get_value()) > $this->maxlength) {
			return false;
		} else {
			return true;
		}
	}
}
?>