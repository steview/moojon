<?php
final class moojon_uri extends moojon_base {
	static private $instance;
	private $data = array();
	
	private function __construct() {
		$data = array();
		if (moojon_config::has('security') && !moojon_authentication::authenticate()) {
			$data['app'] = moojon_config::key('security_app');
			$data['controller'] = moojon_config::key('security_controller');
			$data['action'] = moojon_config::key('security_action');
		} else {
			$data = self::find(self::get_uri());
		}
		$this->set_data($data);
	}
	
	static public function find($uri) {
		$data = array();
		foreach (moojon_routes::get_routes() as $route) {
			if ($data = $route->map_uri($uri)) {
				break;
			}
		}
		if (!$data) {
			throw new moojon_exception('404');
			return;
		} else {
			return $data;
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
	
	static public function get_uri() {
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
}
?>