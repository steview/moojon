<?php
final class moojon_datetime_column extends moojon_base_column {
	public function __construct($name, $null = false, $default = null) {
		$this->name = $name;
		$this->null = $null;
		$this->default = $default;
	}
}
?>