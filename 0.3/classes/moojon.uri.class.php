<?php
final class moojon_uri extends moojon_singleton_immutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	
	protected function __construct() {
		$this->data = self::find(self::get_uri());
		self::try_define('APP', $this->data['app']);
		self::try_define('CONTROLLER', $this->data['controller']);
		self::try_define('ACTION', $this->data['action']);
	}
	
	static public function find($uri) {
		$data = array();
		foreach (moojon_routes::get_data() as $route) {
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
	
	static public function get_uri() {
		if (array_key_exists('REQUEST_URI', $_SERVER)) {
			$uri = $_SERVER['REQUEST_URI'];
		} else {
			$uri = $_SERVER['PATH_INFO'];
		}
		if (substr($uri, 0, (strlen($uri) - 1)) != '/') {
			$uri .= '/';
		}
		$uri = str_replace(moojon_config::get('index_file'), '', $uri);
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