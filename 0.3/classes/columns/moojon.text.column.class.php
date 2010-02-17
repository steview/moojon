<?php
final class moojon_text_column extends moojon_base_column {
	private $binary;
	
	public function __construct($name, $null = false, $binary = false, $not_special = false) {
		$this->name = $name;
		$this->null = $null;
		$this->binary = $binary;
		$this->not_special = $not_special;
	}
	
	public function get_binary() {
		return $this->binary;
	}
}
?>