<?php
final class moojon_string_column extends moojon_base_column {
	final public function __construct($name, $limit = null, $null = true, $default = null) {
		$this->name = $name;
		$this->limit = $limit;
		$this->null = $null;
		$this->default = $default;
	}
	
	final public function __toString() {
		return $this->name.' VARCHAR('.$this->limit.') '.$this->get_null_string().' '.$this->get_default_string();
	}
}
?>