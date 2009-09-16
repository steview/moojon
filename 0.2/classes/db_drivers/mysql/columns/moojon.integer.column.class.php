<?php
final class moojon_integer_column extends moojon_base_column {
	protected $data_type = moojon_db::PARAM_INT;
	
	public function __construct($name, $limit = 11, $null = false, $default = null) {
		$this->name = $name;
		$this->limit = $limit;
		$this->null = $null;
		$this->default = $default;
	}
	
	public function __toString() {
		return $this->name.' INTEGER('.$this->limit.') '.moojon_db_driver::get_null_string($this).' '.moojon_db_driver::get_default_string($this);
	}
}
?>