<?php
final class moojon_text_column extends moojon_base_column {
	private $binary;
	
	final public function __construct($name, $null = true, $binary = false) {
		$this->name = $name;
		$this->null = $null;
		$this->binary = $binary;
	}
	
	final public function __toString() {
		if ($this->binary) {
			return $this->name.' TEXT BINARY '.$this->get_null_string();
		} else {
			return $this->name.' TEXT '.$this->get_null_string();
		}
	}
}
?>