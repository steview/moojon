<?php
final class moojon_date_column extends moojon_base_column {
	final public function __construct($name, $null = false, $default = null) {
		$this->name = $name;
		$this->null = $null;
		$this->default = $default;
	}
	
	final public function __toString() {
		return $this->name.' DATE '.$this->get_null_string().' '.$this->get_default_string();
	}
	
	final function get_data_type() {
		return moojon_db::PARAM_STR;
	}
}
?>