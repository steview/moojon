<?php
final class moojon_routes extends moojon_base {
	
	static private $instance;
	private $routes = array();
	
	protected function __construct() {
		foreach (require_once(moojon_paths::get_project_config_directory().'routes.php') as $route) {
			if (is_subclass_of($route, 'moojon_base_route')) {
				$this->routes[] = $route;
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
}
?>