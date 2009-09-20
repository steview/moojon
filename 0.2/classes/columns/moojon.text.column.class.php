<?php
final class moojon_text_column extends moojon_base_column {
	private $binary;
	
	public function __construct($name, $null = false, $binary = false) {
		$this->name = $name;
		$this->null = $null;
		$this->binary = $binary;
	}
	
	public function get_binary() {
		return $this->binary;
	}
}
?>