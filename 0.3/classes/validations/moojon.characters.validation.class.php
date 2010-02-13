<?php
final class moojon_characters_validation extends moojon_base_validation {
	
	private $characters = array();
	
	public function __construct($characters, $key, $message, $required = true) {
		if (!is_array($characters)) {
			$characters = explode('', $characters);
		}
		$this->characters = $characters;
		parent::__construct($key, $message, $required);
	}
	
	public function valid($data) {
		$characters = explode('', $data['data']);
		$return = true;
		foreach ($characters as $character) {
			if (!in_array($character, $this->$characters)) {
				$return = false;
				break;
			}
		}
		return $return;
	}
}
?>