<?php
final class moojon_timestamp_column extends moojon_base_column {
	protected $data_type = moojon_db::PARAM_STR;
	
	public function __construct($name, $null = false, $default = null) {
		$this->name = $name;
		$this->null = $null;
		$this->default = $default;
	}
	
	public function __toString() {
		return $this->name.' TIMESTAMP '.moojon_db_driver::get_null_string($this).' '.moojon_db_driver::get_default_string($this);
	}
}
?>