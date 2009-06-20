<?php
final class moojon_uri extends moojon_base {
	static private $instance;
	static private $data = array();
	
	private function __construct() {
		foreach (moojon_routes::get_routes() as $route) {
			if ($data = $route->map_uri(self::get_uri())) {
				break;
			}
		}
		if (!$data) {
			die('404');
			//throw new moojon_excepetion('404');
		}
		if (defined('EXCEPTION') && EXCEPTION === true) {
			$data['app'] = moojon_config::get('exception_app');
			$data['controller'] = moojon_config::get('exception_controller');
			$data['action'] = moojon_config::get('exception_action');
			$this->data = $data;
			return;
		}
		if (moojon_config::has('security') && moojon_config::get('security') && !moojon_authentication::authenticate()) {
			$data['app'] = moojon_config::get('security_app');
			$data['controller'] = moojon_config::get('security_controller');
			$data['action'] = moojon_config::get('security_action');
			$this->data = $data;
			return;
		}
		$this->data = $data;
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_uri();
		}
		return self::$instance;
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function has($key) {
		$data = self::get_data();
		return array_key_exists($key, $data);
	}
	
	static public function key($key) {
		$data = self::get_data();
		return $data[$key];
	}
	
	static private function get_uri() {
		if (array_key_exists('REQUEST_URI', $_SERVER)) {
			$uri = $_SERVER['REQUEST_URI'];
		} else {
			$uri = $_SERVER['PATH_INFO'];
		}
		if (substr($uri, 0, strlen(moojon_config::get('index_file'))) == moojon_config::get('index_file')) {
			$uri = substr($uri, strlen(moojon_config::get('index_file')));
		}
		return $uri;
	}
	
	static public function get_apps() {
		$apps = array();
		$project_apps_directory = moojon_paths::get_project_apps_directory();
		if (is_dir($project_apps_directory)) {
			foreach (moojon_files::directory_directories($project_apps_directory) as $app) {
				$apps[] = $app;
			}
		}
		$moojon_apps_directory = moojon_paths::get_moojon_apps_directory();
		if (is_dir($moojon_apps_directory)) {
			foreach (moojon_files::directory_directories($moojon_apps_directory) as $app) {
				$apps[] = $app;
			}
		}
		return $apps;
	}
	
	static public function get_controllers($app) {
		$controllers = array();
		$project_controllers_app_directory = moojon_paths::get_project_controllers_app_directory($app);
		if (is_dir($project_controllers_app_directory)) {
			foreach (moojon_files::directory_files($project_controllers_app_directory) as $controller) {
				$controllers[] = $controller;
			}
		}
		$moojon_controllers_app_directory = moojon_paths::get_moojon_controllers_app_directory($app);
		if (is_dir($moojon_controllers_app_directory)) {
			foreach (moojon_files::directory_files($moojon_controllers_app_directory) as $controller) {
				$controllers[] = $controller;
			}
		}
		return $controllers;
	}
	
	static public function get_actions($controller) {
		$data = self::get_data();
		require_once(moojon_paths::get_controller_path($data['app'], $data['controller']));
		return get_class_methods(self::get_controller_class($data['controller']));
	}
	
	static public function get_app() {
		switch (strtoupper(UI)) {
			case 'CGI':
				$data = self::get_data();
				return $data['app'];
				break;
			case 'CLI':
				return moojon_config::get('default_app');
				break;
		}
	}
	
	static public function get_controller() {
		switch (strtoupper(UI)) {
			case 'CGI':
				$data = self::get_data();
				return $data['controller'];
				break;
			case 'CLI':
				return CONTROLLER;
				break;
		}
		
	}
	
	static public function get_action() {
		$data = self::get_data();
		return $data['action'];
	}
}
?>