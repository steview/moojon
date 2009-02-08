<?php
abstract class moojon_base_app extends moojon_base {
	
	private $controller;
	
	final public function __construct() {
		$this->set_location();
	}
	
	final public function set_location($action = null, $controller = null, $app = null) {
		if ($app != null) {
			$location = "/index.php/$app";
			if ($controller != null) {
				$location .= "/$controller";
			}
			if ($action != null) {
				$location .= "/$action";
			}
			header("Location: $location");
			die();
		}
		if ($action == null) {
			$action = moojon_uri::get_action();
		}
		if ($controller == null) {
			$controller = moojon_uri::get_controller();
		}
		$controller = $controller.'_controller';
		$this->controller = new $controller($this);
		$this->controller->render($action);
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