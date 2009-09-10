<?php
final class moojon_routes extends moojon_base {
	static private $instance;
	private $data = array();
	
	protected function __construct() {
		$routes_path = moojon_paths::get_routes_path();
		foreach (require_once($routes_path) as $route) {
			if (is_subclass_of($route, 'moojon_base_route')) {
				$this->data[$route->get_pattern()] = $route;
			} else {
				throw new moojon_exception("Only moojon_base_route derived objects may be included in $routes_path (".get_class($route).' found)');
			}
		}
	}
	
	static private function get() {
		if (!self::$instance) {
			self::$instance = new moojon_routes();
		}
		return self::$instance;
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static private function get_all() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function has($key) {
		$data = self::get_data();
		if (!is_array($data)) {
			return false;
		}
		if (array_key_exists($key, $data)) {
			if ($data[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function key($key) {
		$data = self::get_data();
		return $data[$key];
	}
	
	static public function get_rest_routes() {
		$return  = array();
		foreach (self::get_data() as $route) {
			if (get_class($route) == 'moojon_rest_route') {
				$return[] = $route;
			}
		}
		return $return;
	}
	
	static public function get_rest_route($resource) {
		foreach (self::get_rest_routes() as $rest_route) {
			if ($resource == $rest_route->get_resource()) {
				return $rest_route;
			}
		}
		throw new moojon_exception("Invalid rest route ($resource)");
	}
	
	static public function has_rest_route($resource) {
		foreach (self::get_rest_routes() as $rest_route) {
			if ($resource == $rest_route->get_resource()) {
				return true;
			}
		}
		return false;
	}
}
?>