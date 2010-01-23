<?php
final class moojon_max_validation extends moojon_base_validation {
	
	private $max;
	
	public function __construct($max, $key, $message, $required = true) {
		$this->max = $max;
		parent::__construct($key, $message, $required);
	}
	
	static public function valid($data) {
		$value = $data['data'];
		if ($value > $this->max) {
			return false;
		} else {
			return true;
		}
	}
}
?>