<?php
final class moojon_maxlength_validation extends moojon_base_validation {
	
	private $maxlength;
	
	public function __construct($maxlength, $key, $message, $required = true) {
		$this->maxlength = $maxlength;
		parent::__construct($key, $message, $required);
	}
	
	public function valid($data) {
		if (strlen((string)$data['data']) > $this->maxlength) {
			return false;
		} else {
			return true;
		}
	}
}
?>