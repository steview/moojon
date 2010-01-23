<?php
final class moojon_min_validation extends moojon_base_validation {
	
	private $min;
	
	public function __construct($min, $key, $message, $required = true) {
		$this->min = $min;
		parent::__construct($key, $message, $required);
	}
	
	public function valid($data) {
		$value = $data['data'];
		if ($value < $this->min) {
			return false;
		} else {
			return true;
		}
	}
}
?>