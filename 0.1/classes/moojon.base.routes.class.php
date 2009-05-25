<?php
final class moojon_base_routes extends moojon_base {
	
	static private $instance;
	static private $routes = array();
	
	private function __construct() {}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_routes();
		}
		return self::$instance;
	}
	
	static private function get_routes() {
		$instance = self::get();
		return $instance->routes;
	}
	
	static public function route($app, $controller, $action, $params = array()) {
		$routes = self::get_routes();
	}
	
	static public function rest_route($model, $params = array()) {
		$routes = self::get_routes();
	}
	
	static public function named_route($name, $app, $controller, $action, $params = array()) {
		$routes = self::get_routes();
	}
}
?>