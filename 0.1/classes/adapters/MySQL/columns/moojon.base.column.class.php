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
		if ($this->value == null && $this->default != null) {
			return $this->default;
		}
		return $this->value;
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
		if ($default) {
			return 'DEFAULT '.$this->default;
		} else {
			return '';
		}
	}
	
	final public function get_add_column() {
		$string = (string)$this;
		return "ADD COLUMN $tring";
	}
	
	abstract public function __toString();
}
?>