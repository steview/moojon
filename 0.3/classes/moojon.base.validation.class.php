<?php
abstract class moojon_base_validation extends moojon_base {
	
	private $key;
	private $message;
	private $required;
	
	public function __construct($key, $message, $required = true) {
		$this->key = $key;
		$this->message = $message;
		$this->required = $required;
	}
	
	final public function get_key() {
		return $this->key;
	}
	
	public function get_data_keys() {
		return array('data');
	}
	
	final public function get_message() {
		return $this->message;
	}
	
	final public function validate($data) {
		if ($this->required) {
			if ($data) {
				return $this->valid($data);
			} else {
				return false;
			}
		} else {
			if (!$data) {
				return true;
			} else {
				return $this->valid($data);
			}
		}
	}
	
	static public function valid($data) {}
}
?>