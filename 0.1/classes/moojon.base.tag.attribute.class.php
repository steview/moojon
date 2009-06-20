<?php
abstract class moojon_base_tag_attribute extends moojon_base {
	
	protected $name;
	protected $value;
	protected $legal_values = array();
	
	final public function __construct($value = null) {
		$this->init();
		$this->set_value($value);
	}
	
	abstract protected function init();
	
	final public function render() {
		return $this->name.'="'.$this->value.'"'; 
	}
	
	final public function set_value($value) {
		if (count($this->legal_values) > 0 && in_array($value, $this->legal_values) == false) {
			throw new moojon_exception("Illegal value for tag ($value). Please use any of the following values (".implode(',', $this->legal_values).')');
		}
		$this->value = $value;
	}
	
	final public function get_name() {
		return $this->name;
	}
	
	final public function get_value() {
		return $this->value;
	}
	
	final public function __toString() {
		return $this->get_value();
	}
}
?>