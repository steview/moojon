<?php
final class moojon_timestamp_column extends moojon_base_column {
	final public function __construct($name, $null = false, $default = '0000-00-00 00:00:00') {
		$this->name = $name;
		$this->null = $null;
		$this->default = $default;
	}
	
	final public function __toString() {
		return $this->name.' TIMESTAMP '.$this->get_null_string().' '.$this->get_default_string();
	}
}
?>