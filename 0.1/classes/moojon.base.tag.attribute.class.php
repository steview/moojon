<?php
abstract class moojon_base_tag_attribute extends moojon_base {
	
	protected $name;
	protected $value;
	protected $legal_values;
	
	final public function __construct($value = null) {
		$this->init();
		if ($legal_values != null) {
			if (is_array($legal_values) == true) {
				$this->legal_values = $legal_values;
			} else {
				self::handle_error("Legal values must be an array");
			}
		} else {
			$this->legal_values = array();
		}
		$this->set_value($value);
	}
	
	abstract protected function init();
	
	final public function render() {
		return $this->name.'="'.$this->value.'"'; 
	}
	
	final public function set_value($value) {
		if (count($this->legal_values) > 0 && in_array($value, $this->legal_values) == false) {
			self::handle_error("Illegal value for tag ($value). Please use any of the following values (".implode(',', $this->legal_values).')');
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