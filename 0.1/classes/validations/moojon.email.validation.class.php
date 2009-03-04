<?php
final class moojon_email_validation extends moojon_base_validation {
	
	public function __construct($message) {
		$this->set_message($message);
	}
	
	public function validate(moojon_base_model $model, moojon_base_column $column) {
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $column->get_value());
	}
}
?>