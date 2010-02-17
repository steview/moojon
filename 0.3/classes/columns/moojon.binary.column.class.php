<?php
final class moojon_binary_column extends moojon_base_column {
	public function __construct($name, $limit = 255, $null = false, $default = null, $not_special = false) {
		$this->name = $name;
		$this->limit = $limit;
		$this->null = $null;
		$this->default = $default;
		$this->not_special = $not_special;
	}
}
?>