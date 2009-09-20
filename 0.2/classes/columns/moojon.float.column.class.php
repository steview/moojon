<?php
final class moojon_float_column extends moojon_base_column {
	protected $decimals;
	
	public function __construct($name, $limit = 10, $decimals = 0, $null = false, $default = null) {
		$this->data_type = moojon_db::PARAM_INT;
		$this->name = $name;
		$this->limit = $limit;
		$this->decimals = $decimals;
		$this->null = $null;
		$this->default = $default;
	}
	
	final public function get_decimals() {
		return $this->decimals;
	}
}
?>