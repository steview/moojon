<?php
abstract class moojon_base_tag_attribute extends moojon_base {
	
	protected $name;
	protected $value;
	protected $legal_values;
	
	final protected function __construct($name, $value = null, $legal_values = null) {
		$this->name = $name;
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
	
	final protected function render() {
		return $this->name.'="'.$this->value.'"'; 
	}
	
	final static protected function base_initr($name, $value = null, $legal_values = null) {
		$tag = self::base_init($name, $value, $legal_values);
		return $tag->render();
	}
	
	final static protected function base_init($name, $value = null, $legal_values = null) {
		$class = $name.'_attribute';
		return new $class($name, $value, $legal_values);
	}
	
	final public function set_value($value) {
		if (count($this->legal_values) > 0 && in_array($value, $this->legal_values) == false) {
			self::handle_error("Illegal value for tag ($value). Please use any of the following values (".implode(',', $this->legal_values).')');
		}
		$this->value = $value;
	}
}
?>