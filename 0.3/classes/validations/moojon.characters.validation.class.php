<?php
final class moojon_characters_validation extends moojon_base_validation {
	
	private $characters = array();
	
	public function __construct($characters, $key, $message, $required = true) {
		if (!is_array($characters)) {
			$characters = explode(', ', $characters);
			for ($i = 0; $i < count($characters); $i ++) {
				$characters[$i] = trim(substr($characters[$i], 0, 1));
			}
		}
		$this->characters = $characters;
		parent::__construct($key, $message, $required);
	}
	
	public function valid($data) {
		$characters = array();
		$data = $data['data'];
		for ($i = 0; $i < strlen($data); $i ++) {
			$characters[] = substr($data, $i, 1);
		}
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