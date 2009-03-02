<?php
abstract class moojon_base_validation extends moojon_base {
	
	private $message;
	
	final public function set_message($message) {
		$this->message = $message;
	}
	
	final public function get_message() {
		return $this->message;
	}
	
	abstract public function validate(moojon_base_column $column);
}
?>