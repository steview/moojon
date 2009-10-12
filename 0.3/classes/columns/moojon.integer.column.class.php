<?php
final class moojon_integer_column extends moojon_base_column {
	public function __construct($name, $limit = 11, $null = false, $default = null) {
		$this->data_type = moojon_db::PARAM_INT;
		$this->name = $name;
		$this->limit = $limit;
		$this->null = $null;
		$this->default = $default;
	}
}
?>