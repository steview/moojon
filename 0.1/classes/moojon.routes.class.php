<?php
final class moojon_routes extends moojon_base {
	static private $instance;
	private $routes = array();
	
	protected function __construct() {
		foreach (require_once(moojon_paths::get_project_config_directory().'routes.php') as $route) {
			if (is_subclass_of($route, 'moojon_base_route')) {
				$this->routes[] = $route;
			} else {
				throw new moojon_exception('Only moojon_base_route derived objects may be included in '.moojon_paths::get_project_config_directory().'routes.php ('.get_class($route).' found)');
			}
		}
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_routes();
		}
		return self::$instance;
	}
	
	static public function get_routes() {
		$instance = self::get();
		return $instance->routes;
	}
	
	static public function get_rest_routes() {
		$return  = array();
		foreach (self::get_routes() as $route) {
			if (get_class($route) == 'moojon_rest_route') {
				$return[] = $route;
			}
		}
		return $return;
	}
}
?>