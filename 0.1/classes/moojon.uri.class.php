<?php
final class moojon_uri extends moojon_base {
	private function __construct() {}

	static private function get_uri() {
		if (array_key_exists('REQUEST_URI', $_SERVER)) {
			return $_SERVER['REQUEST_URI'];
		} else {
			return $_SERVER['PATH_INFO'];
		}
	}
	
	static public function get_apps() {
		$apps = array();
		foreach (moojon_files::directory_directories(moojon_paths::get_moojon_apps_directory()) as $app) {
			$apps[] = $app;
		}
		foreach (moojon_files::directory_directories(moojon_paths::get_apps_directory()) as $app) {
			$apps[] = $app;
		}
		return $apps;
	}
	
	static public function get_controllers($app) {
		$controllers = array();
		if (is_dir(moojon_paths::get_moojon_app_controllers_directory($app))) {
			foreach (moojon_files::directory_files(moojon_paths::get_moojon_app_controllers_directory($app)) as $controller) {
				$controllers[] = $controller;
			}
		}
		if (is_dir(moojon_paths::get_app_controllers_directory($app))) {
			foreach (moojon_files::directory_files(moojon_paths::get_app_controllers_directory($app)) as $controller) {
				$controllers[] = $controller;
			}
		}
		return $controllers;
	}
	
	static public function get_actions($controller) {
		$actions = get_class_methods(self::get_controller_class($controller));
		//if ($actions)
		return $actions;
	}
	
	static public function process() {
		foreach (moojon_routes::get_routes() as $route) {
			if ($return = $route->map_uri(self::get_uri())) {
				break;
			}
		}
		if (!$return) {
			die('404');
			//throw new moojon_excepetion('404');
		}
		print_r($return);
		die();
		if (defined('EXCEPTION') && EXCEPTION === true) {
			$return['app'] = moojon_config::get('exception_app');
			$return['controller'] = moojon_config::get('exception_controller');
			$return['action'] = moojon_config::get('exception_action');
			return $return;
		}
		if (moojon_config::has('security') && moojon_config::get('security') && !moojon_authentication::authenticate()) {
			$return['app'] = moojon_config::get('security_app');
			$return['controller'] = moojon_config::get('security_controller');
			$return['action'] = moojon_config::get('security_action');
			return $return;
		}
		return $return;
	}
	
	static public function get_app() {
		switch (strtoupper(UI)) {
			case 'CGI':
				$request_uri = self::process();
				return $request_uri['app'];
				break;
			case 'CLI':
				return moojon_config::get('default_app');
				break;
		}
	}
	
	static public function get_controller() {
		switch (strtoupper(UI)) {
			case 'CGI':
				$request_uri = self::process();
				return $request_uri['controller'];
				break;
			case 'CLI':
				return CONTROLLER;
				break;
		}
		
	}
	
	static public function get_action() {
		$request_uri = self::process();
		return $request_uri['action'];
	}
	
	static public function get_querystring() {
		$request_uri = self::process();
		return $request_uri['querystring'];
	}
	
	static public function get($key) {
		$request = $_REQUEST;
		$querystring = self::get_querystring();
		for ($i = 0; $i < count($querystring); $i += 2) {
			$request[$querystring[$i]] = $querystring[($i + 1)];
		}
		return $request[$key];
	}
}
?>