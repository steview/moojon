<?php
abstract class moojon_base_column extends moojon_base {
	protected $name;
	protected $value;
	protected $limit;
	protected $null;
	protected $default;
	
	final public function get_name() {
		return $this->name;
	}
		
	final public function set_value($value) {
		$this->value = $value;
	}
	
	final public function get_value() {
		if (!$this->value && $this->default) {
			return $this->default;
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
	
	protected function get_query_value() {
		return $this->value;
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
			return 'DEFAULT '.$this->default;
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