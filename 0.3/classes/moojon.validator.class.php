<?php
final class moojon_validator extends moojon_base {
	
	private $validations = array();
	private $messages = array();
	
	public function __construct($validations = array()) {
		foreach ($validations as $validation) {
			$this->add_validation($validation);
		}
	}
	
	public function add_validation(moojon_base_validation $validation) {
		$this->validations[] = $validation;
	}
	
	public function get_validations() {
		return $this->validations;
	}
	
	public function get_messages() {
		return $this->messages;
	}
	
	public function get_key_validations($keys) {
		$return = array();
		foreach ($this->validations as $validation) {
			if (in_array($validation->get_key(), $keys)) {
				$return[] = $validation;
			}
		}
		return $return;
	}
	
	public function validate($keys = array(), $data = array()) {
		$validations = ($keys) ? $this->get_key_validations($keys) : $this->validations;
		$messages = array();
		foreach ($validations as $validation) {
			$key = $validation->get_key();
			if (!array_key_exists($key, $messages) && !$validation->validate($data[$key])) {
				$messages[$key] = $validation->get_message();
			}
		}
		$this->messages = $messages;
		return (count($this->messages) < 1);
	}
}
?>