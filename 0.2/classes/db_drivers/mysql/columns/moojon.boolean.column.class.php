<?php
final class moojon_boolean_column extends moojon_base_column {
	protected $data_type = moojon_db::PARAM_INT;
	
	public function __construct($name, $null = false, $default = null) {
		$this->name = $name;
		$this->limit = 1;
		$this->null = $null;
		$this->default = $default;
	}
	
	public function __toString() {
		return $this->name.' TINYINT(1) '.moojon_db_driver::get_null_string($this).' '.moojon_db_driver::get_default_string($this);
	}
}
?>