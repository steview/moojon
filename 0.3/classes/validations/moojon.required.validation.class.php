<?php
final class moojon_required_validation extends moojon_base_validation {
	
	public function __construct($key, $message) {
		parent::__construct($key, $message, true);
	}
	
	public function valid($data) {
		if ($data['data']) {
			return true;
		} else {
			return false;
		}
	}
}
?>