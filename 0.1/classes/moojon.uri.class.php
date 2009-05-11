<?php
final class moojon_uri extends moojon_base {
	private function __construct() {}

	static private function get_request_uri_array() {
		if (array_key_exists('REQUEST_URI', $_SERVER)) {
			$request_uri = $_SERVER['REQUEST_URI'];
		} else {
			$request_uri = $_SERVER['PATH_INFO'];
		}
		while (substr($request_uri, 0, 1) == '/') {
			$request_uri = substr($request_uri, 1);
		}
		if (strlen($request_uri) > 0) {
			while (substr($request_uri, (strlen($request_uri) - 1)) == '/') {
				$request_uri = substr($request_uri, 0, (strlen($request_uri) - 1));
			}
			$return = explode('/', $request_uri);
			while (strpos($return[0], '.') !== false) {
				array_shift($return);
			}
			
			return $return;
		} else {
			return array();
		}
	}
	
	static public function get_apps() {
		return moojon_files::directory_directories(moojon_paths::get_apps_directory());
	}
	
	static public function get_controllers($app) {
		$controllers = array();
		foreach (moojon_files::directory_files(moojon_paths::get_apps_directory()."$app/".moojon_config::get('controllers_directory').'/') as $controller) {
			$controllers[] = substr(basename($controller), 0, strpos(basename($controller), '.'));
		}
		foreach (moojon_files::directory_files(moojon_paths::get_shared_directory().moojon_config::get('controllers_directory').'/') as $controller) {
			$controllers[] = substr(basename($controller), 0, strpos(basename($controller), '.'));
		}
		return $controllers;
	}
	
	static public function process() {
		$request_uri = self::get_request_uri_array();
		$return = array();
		$counter;
		$default_app = moojon_config::get('default_app');
		$default_controller = moojon_config::get('default_controller');
		$default_action = moojon_config::get('default_action');
		switch (count($request_uri)) {
			case 0:
				$return['app'] = $default_app;
				$return['controller'] = $default_controller;
				$return['action'] = $default_action;
				$counter = 0;
				break;
			case 1:
				if (in_array($request_uri[0], self::get_apps())) {
					$return['app'] = $request_uri[0];
					$return['controller'] = $default_controller;
					$return['action'] = $default_action;
				} else {
					$return['app'] = $default_app;
					if (in_array($request_uri[0], self::get_controllers($default_app)) == true) {
						$return['controller'] = $request_uri[0];
						$return['action'] = $default_action;
					} else {
						$return['controller'] = $default_controller;
						$return['action'] = $request_uri[0];
					}
				}
				$counter = 1;
				break;
			case 2:
				if (in_array($request_uri[0], self::get_apps())) {
					$return['app'] = $request_uri[0];
					$return['controller'] = $request_uri[1];
					$return['action'] = $default_action;
				} else {
					$return['app'] = $default_app;
					$return['controller'] = $request_uri[0];
					$return['action'] = $request_uri[1];
				}
				$counter = 2;
				break;
			case 3:
				if (in_array($request_uri[0], self::get_apps())) {
					$return['app'] = $request_uri[0];
					$return['controller'] = $request_uri[1];
					$return['action'] = $request_uri[2];
					$counter = 3;
				} else {
					$return['app'] = $default_app;
					$return['controller'] = $request_uri[0];
					$return['action'] = $request_uri[1];
					$counter = 2;
				}
				break;
			default:
				if (in_array($request_uri[0], self::get_apps())) {
					$return['app'] = $request_uri[0];
					$return['controller'] = $request_uri[1];
					$return['action'] = $request_uri[2];
					$counter = 3;
				} elseif (in_array($request_uri[0], self::get_controllers($default_app))) {
					$return['app'] = $default_app;
					$return['controller'] = $request_uri[0];
					$return['action'] = $request_uri[1];
					$counter = 2;
				} else {
					$return['app'] = $default_app;
					$return['controller'] = $default_controller;
					$return['action'] = $request_uri[0];
					$counter = 1;
				}
				break;
		}
		for ($i = 0; $i < $counter; $i ++) {
			array_shift($request_uri);
		}
		$return['querystring'] = $request_uri;
		if (moojon_config::has('security') === true) {
			if (moojon_config::get('security') === true) {
				if (moojon_authentication::authenticate() === false) {
					$return['controller'] = moojon_config::get('security_controller');
					$return['action'] = moojon_config::get('security_action');
				}
			}
		}
		moojon_config::get('security');
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