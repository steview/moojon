<?php
final class moojon_server extends moojon_singleton_mutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	static public function set($key, $value = null, $data = null) {$data = self::get_data($data);$instance = self::fetch();$instance->data[$key] = $value;self::post_set($key, $value, $data);}
	static public function clear() {$instance = self::fetch();$instance->data = array();self::post_clear();}
	static protected function post_set($key, $value = null, $data = null) {
		if ($value !== null) {
			$_SERVER[$key] = $value;
		} else {
			$_SERVER[$key] = null;
			unset($_SERVER[$key]);
		}
	}
	static public function post_clear() {$_SERVER = array();}
	
	protected function __construct() {
		$this->data = $_SERVER;
	}
	
	static public function method() {
		return (moojon_post::has(moojon_config::get('method_key'))) ? moojon_post::get(moojon_config::get('method_key')) : self::get_or_null('REQUEST_METHOD');
	}
	
	static public function is_get() {
		return (strtolower(self::method()) == 'get');
	}
	
	static public function is_post() {
		return (strtolower(self::method()) == 'post');
	}
	
	static public function is_put() {
		return (strtolower(self::method()) == 'put');
	}
	
	static public function is_delete() {
		return (strtolower(self::method()) == 'delete');
	}

	static public function is_ajax() {
		return (array_key_exists('X-Requested-With', $_SERVER) && $_SERVER['X-Requested-With'] == 'XMLHttpRequest');
	}

	static public function redirection($fallback = null) {
		$redirection = moojon_request::get_or_null(moojon_config::get('redirection_key'));
		$referer = self::get_or_null('HTTP_REFERER');
		if ($redirection) {
			$return = $redirection;
		} else if ($fallback) {
			$return = $fallback;
		} else if ($referer) {
			$return = $referer;
		} else {
			$return = '#';
		}
		return $return;
	}
}
?>