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
		$name = $this->name;
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
	
	final public function get_value_query_format() {
		if ($this->value === null) {
			return moojon_db_driver::get_null();
		} else {
			return moojon_db_driver::get_value_query_format($this);
		}
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
	
	final public function get_add_column() {
		$string = (string)$this;
		return $string;
	}
	
	abstract public function __toString();
	
	final public function get_data_type() {
		if ($this->value === null) {
			return moojon_db::PARAM_NULL;
		} else {
			return $this->data_type;
		}
	}
}
?>