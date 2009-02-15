<?php
abstract class moojon_base_empty_tag extends moojon_base_tag {
	
	final public function __construct($attributes = null) {
		$this->init();
		parent::__construct(null, $attributes);
	}
	
	public function render() {
		$render = '<'.$this->name;
		foreach ($this->attributes as $attribute) {
			$render .= ' '.$attribute->render();
		}
		return $render.' />';
	}
	
	final protected function get_property($key) {
		self::handle_error("No such attribute or child ($key)");
	}
}
?>