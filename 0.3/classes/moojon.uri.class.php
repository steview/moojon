<?php
final class moojon_uri extends moojon_singleton_immutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	
	private $match;
	private $uri;
	
	protected function __construct() {
		$uri = (array_key_exists('REQUEST_URI', $_SERVER)) ? $_SERVER['REQUEST_URI'] : $_SERVER['PATH_INFO'];
		$this->uri = self::clean_uri($uri);
		if ($match = moojon_routes::map($this->uri)) {
			$config = moojon_config::read(moojon_paths::get_project_app_config_directory($match->get('app')));
			if (array_key_exists('secure', $config) && $config['secure'] === true && !moojon_security::authenticate()) {
				self::log('please login');
				moojon_cache::disable();
				$match = new moojon_route_match(':app/:controller/:action', array_merge($match->get_params(), array('app' => moojon_config::get('security_app'), 'controller' => moojon_config::get('security_controller'), 'action' => moojon_config::get('security_action'))));
				$this->uri = moojon_config::get('security_app').'/'.moojon_config::get('security_controller').'/'.moojon_config::get('security_action');
			}
			$this->match = $match;
			$this->data = $this->match->get_params();
			self::try_define('APP', $this->data['app']);
			self::try_define('CONTROLLER', $this->data['controller']);
			self::try_define('ACTION', $this->data['action']);
		} else {
			self::_404();
		}
	}
	
	static public function _404() {
		throw new moojon_exception('404');
		die();
	}
	
	static public function get_match() {
		$instance = self::fetch();
		return $instance->match;
	}
	
	static public function get_match_pattern() {
		$instance = self::fetch();
		return $instance->match->get_pattern();
	}
	
	static public function get_match_params() {
		$instance = self::fetch();
		return $instance->match->get_params();
	}
	
	static public function get_uri() {
		$instance = self::fetch();
		return $instance->uri;
	}
	
	static public function clean_uri($uri) {
		while (strpos($uri, '//')) {
			$uri = str_replace('//', '/', $uri);
		}
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