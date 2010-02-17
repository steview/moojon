<?php
final class moojon_date_column extends moojon_base_column {
	public function __construct($name, $null = false, $default = null, $not_special = false) {
		$this->name = $name;
		$this->null = $null;
		$this->default = $default;
		$this->not_special = $not_special;
	}
}
?>