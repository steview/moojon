<?php
final class moojon_string_column extends moojon_base_column {
	public function __construct($name, $limit = 255, $null = false, $default = null, $not_special = false) {
		$this->name = $name;
		$this->limit = $limit;
		$this->null = $null;
		$this->default = $default;
		$this->not_special = $not_special;
	}
	
	public function is_file() {
		if (strpos($this->name, 'file') !== false && !$this->not_special) {
			return true;
		} else {
			return false;
		}
	}
	
	public function is_password() {
		if (strpos($this->name, 'password') !== false && !$this->not_special) {
			return true;
		} else {
			return false;
		}
	}
}
?>