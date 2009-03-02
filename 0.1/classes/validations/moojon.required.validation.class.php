<?php
final class moojon_required_validation extends moojon_base_validation {
	
	public function __construct($message) {
		$this->set_message($message);
	}
	
	public function validate(moojon_base_column $column) {
		if (strlen($column->get_value()) > 0) {
			return true;
		} else {
			return false;
		}
	}
}
?>