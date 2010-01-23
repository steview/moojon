<?php
final class moojon_minlength_validation extends moojon_base_validation {
	
	private $minlength;
	
	public function __construct($minlength, $key, $message, $required = true) {
		$this->minlength = $minlength;
		parent::__construct($key, $message, $required);
	}
	
	static public function valid($data) {
		if (strlen((string)$data['data']) < $this->minlength) {
			return false;
		} else {
			return true;
		}
	}
}
?>