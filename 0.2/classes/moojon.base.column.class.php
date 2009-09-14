<?php
abstract class moojon_base_column extends moojon_base {
	protected $name;
	protected $value = null;
	protected $limit;
	protected $null;
	protected $default = null;
	protected $unsaved = false;
	protected $reset_value;
	
	final public function get_name() {
		return $this->name;
	}
		
	final public function set_value($value) {
		if (!$this->reset_value) {
			$this->reset_value = $value;
		}
		$this->value = $value;
		$this->unsaved = true;
	}
	
	final public function reset() {
		$this->value = $this->reset_value;
		$this->unsaved = false;
	}
	
	final public function get_value() {
		return $this->value;
	}
	
	final public function get_query_value() {
		if ($this->value === null) {
			return moojon_db_driver::get_null();
		}
		return $this->value;
	}
	
	final public function get_limit() {
		return $this->limit;
	}
	
	final public function get_null() {
		return $this->null;
	}
	
	final public function get_default() {
		return $this->default;
	}
	
	final public function get_unsaved() {
		return $this->unsaved;
	}
	
	final protected function get_null_string() {
		if ($this->null) {
			return 'NULL';
		} else {
			return 'NOT NULL';
		}
	}
	
	final protected function get_default_string() {
		if ($this->default) {
			switch ($this->get_data_type()) {
				case moojon_db::PARAM_STR:
					$apos = "'";
					break;
				default:
					$apos = '';
					break;
			}
			return "DEFAULT $apos".$this->default.$apos;
		} else {
			return '';
		}
	}
	
	final public function get_add_column() {
		$string = (string)$this;
		return $string;
	}
	
	abstract public function __toString();
	
	abstract public function get_data_type();
}
?>