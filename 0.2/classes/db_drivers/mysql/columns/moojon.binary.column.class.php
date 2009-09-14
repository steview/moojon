<?php
final class moojon_binary_column extends moojon_base_column {
	public function __construct($name, $limit = 255, $null = false, $default = '') {
		$this->name = $name;
		$this->limit = $limit;
		$this->null = $null;
		$this->default = $default;
	}
	
	public function __toString() {
		return $this->name.' BINARY('.$this->limit.') '.$this->get_null_string().' '.$this->get_default_string();
	}
	
	public function get_data_type() {
		return moojon_db::PARAM_STR;
	}
}
?>