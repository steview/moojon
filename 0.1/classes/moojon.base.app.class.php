<?php
abstract class moojon_base_app extends moojon_base {
	
	protected $controller;
	
	public function __construct() {
		$controller_class_name = moojon_uri::get_controller().'_controller';
		$this->controller = new $controller_class_name;
		$this->render();
	}
	
	final public function render() {
		$this->controller->render();
	}
}
?>