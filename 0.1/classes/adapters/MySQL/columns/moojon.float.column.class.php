<?php
final class moojon_float_column extends moojon_base_column {
	private $decimals;
	
	final public function __construct($name, $limit = 10, $decimals = 0, $null = true, $default = 0) {
		$this->name = $name;
		$this->limit = $limit;
		$this->decimals = $decimals;
		$this->null = $null;
		$this->default = $default;
	}
	
	final public function __toString() {
		return $this->name.' FLOAT('.$this->limit.', '.$this->decimals.') '.$this->get_null_string().' '.$this->get_default_string();
	}
}
?>