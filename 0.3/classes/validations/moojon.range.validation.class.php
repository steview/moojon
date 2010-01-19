<?php
final class moojon_range_validation extends moojon_base_validation {
	
	private $min;
	private $max;
	
	public function __construct($min, $max, $key, $message, $required = true) {
		$this->min = $min;
		$this->max = $max;
		parent::__construct($key, $message, $required);
	}
	
	public function valid($data) {
		if ($data['data'] < $this->min || $data['data'] > $this->max) {
			return false;
		} else {
			return true;
		}
	}
}
?>