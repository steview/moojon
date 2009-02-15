<?php
abstract class moojon_base_empty_tag extends moojon_base_tag {
	
	final public function __construct($attributes = null) {
		$this->init();
		parent::__construct(self::NAME, $attribute);
	}
	
	public function render() {
		
	}
	
	final protected function get_property($key) {
		self::handle_error("No such attribute or child ($key)");
	}
}
?>