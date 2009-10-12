<?php
final class moojon_boolean_column extends moojon_base_column {
	public function __construct($name, $null = false, $default = null) {
		$this->data_type = moojon_db::PARAM_INT;
		$this->name = $name;
		$this->limit = 1;
		$this->null = $null;
		$this->default = $default;
	}
}
?>