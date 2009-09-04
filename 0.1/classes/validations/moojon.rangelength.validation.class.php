<?php
final class moojon_rangelength_validation extends moojon_base_validation {
	
	private $minlength;
	private $maxlength;
	
	public function __construct($message, $minlength, $maxlength, $required = true) {
		$this->set_message($message);
		$this->minlength = $minlength;
		$this->maxlength = $maxlength;
		$this->required = $required;
	}
	
	public function get_minlength() {
		return $this->minlength;
	}
	
	public function get_maxlength() {
		return $this->maxlength;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		if (strlen($column->get_value()) < $this->minlength || strlen($column->get_value()) > $this->maxlength) {
			return false;
		} else {
			return true;
		}
	}
}
?>