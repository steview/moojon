<?php
final class moojon_routes extends moojon_singleton_immutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	
	protected function __construct() {
		foreach (require_once(moojon_paths::get_routes_path()) as $route) {
			if (is_subclass_of($route, 'moojon_base_route')) {
				$this->data[$route->get_pattern()] = $route;
			} else {
				throw new moojon_exception("Only moojon_base_route derived objects may be included in $routes_path (".get_class($route).' found)');
			}
		}
	}
	
	static public function map($uri, $data = null, $validate = true) {
		foreach (self::get_data($data) as $route) {
			if ($route_match = $route->map($uri, $validate)) {
				return $route_match;
			}
		}
		return false;
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
		$return = null;
		foreach (self::get_rest_routes() as $rest_route) {
			if ($resource == $rest_route->get_pattern()) {
				$return = $rest_route;
			}
		}
		if (!$return) {
			throw new moojon_exception("Invalid rest route ($resource)");
		} else {
			return $return;
		}
	}
	
	static public function has_rest_route($resource) {
		foreach (self::get_rest_routes() as $rest_route) {
			if ($resource == $rest_route->get_pattern()) {
				return true;
			}
		}
		return false;
	}
}
?>