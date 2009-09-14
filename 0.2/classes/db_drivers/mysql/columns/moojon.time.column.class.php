<?php
final class moojon_time_column extends moojon_base_column {
	public function __construct($name, $null = false, $default = null) {
		$this->name = $name;
		$this->null = $null;
		$this->default = $default;
	}
	
	public function __toString() {
		return $this->name.' TIME '.$this->get_null_string().' '.$this->get_default_string();
	}
	
	public function get_data_type() {
		return moojon_db::PARAM_STR;
	}
	
	protected function process_value($value) {
		if (is_array($value)) {
			return moojon_db_driver::array_to_time_format($value);
		} else {
			return $value;
		}
	}
}
?>