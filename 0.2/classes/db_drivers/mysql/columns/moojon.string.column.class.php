<?php
final class moojon_string_column extends moojon_base_column {
	public function __construct($name, $limit = 255, $null = false, $default = null) {
		$this->name = $name;
		$this->limit = $limit;
		$this->null = $null;
		$this->default = $default;
	}
	
	public function __toString() {
		return $this->name.' VARCHAR('.$this->limit.') '.$this->get_null_string().' '.$this->get_default_string();
	}
	
	public function get_data_type() {
		return moojon_db::PARAM_STR;
	}
}
?>