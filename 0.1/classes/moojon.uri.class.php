<?php
final class moojon_uri extends moojon_base {
	private function __construct() {}
	
	static private function get_path_info_array() {
		$path_info = $_SERVER['PATH_INFO'];
		if (strlen($path_info) > 0) {
			while (substr($path_info, 0, 1) == '/') {
				$path_info = substr($path_info, 1);
			}
			while (substr($path_info, (strlen($path_info) - 1)) == '/') {
				$path_info = substr($path_info, 0, (strlen($path_info) - 1));
			}
			return explode('/', $path_info);
		} else {
			return array();
		}
	}
	
	static private function get_apps() {
		return moojon_files::directory_directories(moojon_paths::get_apps_directory());
	}
	
	static private function get_controllers($app) {
		$controllers = array();
		foreach (moojon_files::directory_files(moojon_paths::get_apps_directory()."$app/".moojon_config::get('controllers_directory').'/') as $controller) {
			$controllers[] = basename($controller);
		}
		return $controllers;
	}
	
	static private function process() {
		$path_info = self::get_path_info_array();
		$return = array();
		switch (count($path_info)) {
			case 0:
				$return['app'] = moojon_config::get('default_app');
				$return['controller'] = moojon_config::get('default_controller');
				$return['action'] = moojon_config::get('default_action');
				break;
			case 1:
				if (in_array($path_info[0], self::get_apps())) {
					$return['app'] = $path_info[0];
					$return['controller'] = moojon_config::get('default_controller');
					$return['action'] = moojon_config::get('default_action');
				} else {
					$return['app'] = moojon_config::get('default_app');
					if (in_array($path_info[0].'controller.class.php', self::get_controllers($return['app'])) == true) {
						$return['controller'] = $path_info[0];
						$return['action'] = moojon_config::get('default_action');
					} else {
						$return['controller'] = moojon_config::get('default_controller');
						$return['action'] = $path_info[0];
					}					
				}
				break;
			case 2:
				if (in_array($path_info[0], self::get_apps())) {
					$return['app'] = $path_info[0];
					$return['controller'] = $path_info[1];
					$return['action'] = moojon_config::get('default_action');
				} else {
					$return['app'] = moojon_config::get('default_app');
					$return['controller'] = $path_info[0];
					$return['action'] = $path_info[1];
				}
				break;				
			case 3:
			default:
				if (in_array($path_info[0], self::get_apps())) {
					$return['app'] = $path_info[0];
					$return['controller'] = $path_info[1];
					$return['action'] = $path_info[2];
				} else {
					$return['app'] = moojon_config::get('default_app');
					$return['controller'] = $path_info[0];
					$return['action'] = $path_info[1];
				}
				break;
		}
		return $return;
	}
	
	static public function get_app() {
		switch (strtoupper(UI)) {
			case 'CGI':
				$path_info = self::process();
				return $path_info['app'];
				break;
			case 'CLI':
				return APP;
				break;
		}
	}
	
	static public function get_controller() {
		$path_info = self::process();
		return $path_info['controller'];
	}
	
	static public function get_action() {
		$path_info = self::process();
		return $path_info['action'];
	}
}
?>