<?php
final class moojon_rangelength_validation extends moojon_base_validation {
	
	private $minlength;
	private $maxlength;
	
	public function __construct($minlength, $maxlength, $key, $message, $required = true) {
		$this->minlength = $minlength;
		$this->maxlength = $maxlength;
		parent::__construct($key, $message, $required);
	}
	
	static public function valid($data) {
		$value = (string)$data['data'];
		if (strlen($value) < $this->minlength || strlen($value) > $this->maxlength) {
			return false;
		} else {
			return true;
		}
	}
}
?>