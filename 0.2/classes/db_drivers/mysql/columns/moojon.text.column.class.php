<?php
final class moojon_text_column extends moojon_base_column {
	protected $data_type = moojon_db::PARAM_STR;
	private $binary;
	
	public function __construct($name, $null = false, $binary = false) {
		$this->name = $name;
		$this->null = $null;
		$this->binary = $binary;
	}
	
	public function __toString() {
		if ($this->binary) {
			return $this->name.' TEXT BINARY '.moojon_db_driver::get_null_string($this);
		} else {
			return $this->name.' TEXT '.moojon_db_driver::get_null_string($this);
		}
	}
}
?>