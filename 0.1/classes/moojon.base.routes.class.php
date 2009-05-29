<?php
abstract class moojon_base_routes extends moojon_base {
	
	static private $instance;
	static private $routes = array();
	
	final private function __construct() {}
	
	final static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_routes();
		}
		return self::$instance;
	}
	
	final public function get_routes() {}
	
	final static public function route($pattern) {
		$instance = self::get();
		$instance->routes[] = new moojon_route($pattern);
	}
	
	final static public function rest_route($pattern) {
		$instance = self::get();
		$instance->routes[] = new moojon_rest_route($pattern);
	}
	
	final static public function named_route($pattern) {
		$instance = self::get();
		$instance->routes[] = new moojon_named_route($pattern);
	}
}
?>