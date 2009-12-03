<?php
final class moojon_uri extends moojon_singleton_immutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	
	protected $route;
	
	protected function __construct() {
		$uri = self::clean_uri(self::get_uri());
		if ($route = moojon_routes::map($uri)) {
			$this->route = $route;
			$this->data = $this->route->get_params();
			self::try_define('APP', $this->data['app']);
			self::try_define('CONTROLLER', $this->data['controller']);
			self::try_define('ACTION', $this->data['action']);
		} else {
			throw new moojon_exception('404');
			die();
		}
	}
	
	static public function get_route() {
		$instance = self::fetch();
		return $instance->route;
	}
	
	static public function get_uri() {
		if (array_key_exists('REQUEST_URI', $_SERVER)) {
			$uri = $_SERVER['REQUEST_URI'];
		} else {
			$uri = $_SERVER['PATH_INFO'];
		}
		return self::clean_uri($uri);
	}
	
	static public function clean_uri($uri) {
		if (substr($uri, 0, (strlen($uri) - 1)) != '/') {
			$uri .= '/';
		}
		$uri = str_replace(moojon_config::get('index_file'), '', $uri);
		if (substr($uri, (strlen($uri) - 1)) == '/') {
			$uri = substr($uri, 0, (strlen($uri) - 1));
		}
		if (substr($uri, -1) == '/') {
			$uri = substr($uri, 0, (strlen($uri) - 1));
		}
		if (strpos($uri, '?')) {
			$uri = substr($uri, 0, strpos($uri, '?'));
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