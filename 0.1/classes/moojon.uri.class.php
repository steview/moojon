<?php
final class moojon_uri extends moojon_base {
	static private $instance;
	private $data = array();
	
	private function __construct() {
		if (moojon_config::has('security') && moojon_config::key('security') && !moojon_authentication::authenticate()) {
			$data['app'] = moojon_config::key('security_app');
			$data['controller'] = moojon_config::key('security_controller');
			$data['action'] = moojon_config::key('security_action');
			$this->set_data($data);
			return;
		}
		foreach (moojon_routes::get_routes() as $route) {
			if ($data = $route->map_uri(self::get_uri())) {
				break;
			}
		}
		if (!$data) {
			throw new moojon_exception('404');
			return;
		} else {
			$this->set_data($data);
		}
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_uri();
		}
		return self::$instance;
	}
	
	private function set_data($data) {
		self::try_define('APP', $data['app']);
		self::try_define('CONTROLLER', $data['controller']);
		self::try_define('ACTION', $data['action']);
		$this->data = $data;
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
		if (substr($uri, 0, (strlen($uri) - 1)) != '/') {
			$uri .= '/';
		}
		$uri = str_replace(moojon_config::key('index_file'), '', $uri);
		if (substr($uri, (strlen($uri) - 1)) == '/') {
			$uri = substr($uri, 0, (strlen($uri) - 1));
		}
		if (!$uri) {
			$uri = '/';
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
	
	static public function get_actions() {
		$data = self::get_data();
		require_once(moojon_paths::get_controller_path($data['app'], $data['controller']));
		$actions = get_class_methods(self::get_controller_class($data['controller']));
		$paths = array(
			moojon_paths::get_moojon_views_directory(),
			moojon_paths::get_moojon_views_app_directory(moojon_uri::get_app()),
			moojon_paths::get_moojon_views_app_controller_directory(moojon_uri::get_app(), moojon_uri::get_controller()),
			moojon_paths::get_project_views_directory(),
			moojon_paths::get_project_views_app_directory(moojon_uri::get_app()),
			moojon_paths::get_project_views_app_controller_directory(moojon_uri::get_app(), moojon_uri::get_controller())
		);
		foreach (self::colate_view($paths) as $view) {
			if (!in_array($actions, $view)) {
				$actions[] = $view;
			}
		}
		return $actions;
	}
	
	static public function get_app() {
		$data = self::get_data();
		return $data['app'];
	}
	
	static public function get_controller() {
		$data = self::get_data();
		return $data['controller'];
	}
	
	static public function get_action() {
		$data = self::get_data();
		return $data['action'];
	}
	
	static private function colate_views($paths) {
		$views = array();
		foreach ($paths as $path) {
			if (is_dir($path)) {
				foreach (moojon_files::directory_files($path, false, false) as $file) {
					if (moojon_files::has_suffix($file, 'view', 'php')) {
						$views[] = $file;
					}
				}
			}
		}
		return $views;
	}
}
?>