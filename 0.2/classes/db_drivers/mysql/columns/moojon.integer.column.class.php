<?php
final class moojon_integer_column extends moojon_base_column {
	final public function __construct($name, $limit = 11, $null = false, $default = 0) {
		$this->name = $name;
		$this->limit = $limit;
		$this->null = $null;
		$this->default = $default;
	}
	
	final public function __toString() {
		return $this->name.' INTEGER('.$this->limit.') '.$this->get_null_string().' '.$this->get_default_string();
	}
	
	final function get_data_type() {
		return moojon_db::PARAM_INT;
	}
}
?>