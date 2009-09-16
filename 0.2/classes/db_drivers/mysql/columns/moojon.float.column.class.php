<?php
final class moojon_float_column extends moojon_base_column {
	protected $data_type = moojon_db::PARAM_INT;
	private $decimals;
	
	public function __construct($name, $limit = 10, $decimals = 0, $null = false, $default = null) {
		$this->name = $name;
		$this->limit = $limit;
		$this->decimals = $decimals;
		$this->null = $null;
		$this->default = $default;
	}
	
	public function __toString() {
		return $this->name.' FLOAT('.$this->limit.', '.$this->decimals.') '.moojon_db_driver::get_null_string($this).' '.moojon_db_driver::get_default_string($this);
	}
}
?>