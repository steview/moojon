<?php
final class moojon_time_column extends moojon_base_column {
	final public function __construct($name, $null = true, $default = '00:00') {
		$this->name = $name;
		$this->null = $null;
		$this->default = $default;
	}
	
	final public function __toString() {
		return $this->name.' TIME '.$this->get_null_string().' '.$this->get_default_string();
	}
}
?>