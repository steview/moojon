<?php
final class moojon_decimal_column extends moojon_base_column {
	protected $decimals;
	
	public function __construct($name, $limit = 10, $decimals = 0, $null = false, $default = null, $not_special = false) {
		$this->data_type = moojon_db::PARAM_INT;
		$this->name = $name;
		$this->limit = $limit;
		$this->decimals = $decimals;
		$this->null = $null;
		$this->default = $default;
		$this->not_special = $not_special;
	}
	
	final public function get_decimals() {
		return $this->decimals;
	}
}
?>