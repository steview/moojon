<?php
final class moojon_boolean_column extends moojon_base_column {
	public function __construct($name, $null = false, $default = 0) {
		$this->name = $name;
		$this->limit = 1;
		$this->null = $null;
		$this->default = $default;
	}
	
	public function __toString() {
		return $this->name.' TINYINT(1) '.$this->get_null_string().' '.$this->get_default_string();
	}
	
	public function get_data_type() {
		return moojon_db::PARAM_INT;
	}
}
?>