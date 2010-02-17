<?php
abstract class moojon_base_column extends moojon_base {
	protected $not_special = false;
	protected $data_type = moojon_db::PARAM_STR;
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
		if ($this->value !== $value) {
			$this->value = $value;
			$this->unsaved = true;
			return true;
		} else {
			return false;
		}
	}
	
	final public function reset() {
		$this->value = $this->reset_value;
		$this->unsaved = false;
	}
	
	final public function set_reset_value() {
		$this->reset_value = $this->value;
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
	
	final public function get_data_type() {
		if ($this->value === null) {
			return moojon_db::PARAM_NULL;
		} else {
			return $this->data_type;
		}
	}
	
	public function is_order() {
		if (strpos($this->name, 'position') !== false && !$this->not_special) {
			return true;
		} else {
			return false;
		}
	}
}
?>