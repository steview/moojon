<?php
abstract class moojon_base_app extends moojon_base {
	
	private $controller;
	
	final public function __construct() {
		$controller = moojon_uri::get_controller().'_controller';
		$this->controller = new $controller;
		$this->controller->render();
	}
	
	final public function get_layout() {
		return $this->controller->get_layout();
	}
	
	final public function get_view() {
		return $this->controller->get_view();
	}
	
	final public function get_controller_properties() {
		return get_object_vars($this->controller);
	}
}
?>