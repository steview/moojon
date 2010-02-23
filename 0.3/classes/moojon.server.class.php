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
		if (array_key_exists('SERVER_PROTOCOL', $_SERVER)) {
			if (array_key_exists('REDIRECT_HTTPS', $_SERVER) && $_SERVER['REDIRECT_HTTPS'] == 'on') {
				$_SERVER['SCHEME'] = 'https://';
			} else {
				$_SERVER['SCHEME'] = self::process_scheme($_SERVER['SERVER_PROTOCOL']);
			}
		}
		if (array_key_exists('SCHEME', $_SERVER) && array_key_exists('HTTP_HOST', $_SERVER)) {
			$_SERVER['SCHEME_HTTP_HOST'] = $_SERVER['SCHEME'].$_SERVER['HTTP_HOST'].moojon_config::get('index_file');
		}
		if (array_key_exists('PATH_INFO', $_SERVER) && substr($_SERVER['PATH_INFO'], 0, 1) == '/') {
			$_SERVER['PATH_INFO'] = substr($_SERVER['PATH_INFO'], 1);
		}
		if (array_key_exists('SCHEME_HTTP_HOST', $_SERVER) && array_key_exists('PATH_INFO', $_SERVER)) {
			$_SERVER['FULL_ADDRESS'] = $_SERVER['SCHEME_HTTP_HOST'].$_SERVER['PATH_INFO'];
		}
		$this->data = $_SERVER;
	}
	
	static public function process_uri($uri) {
		if (!is_array($uri)) {
			$uri_segments = parse_url($uri);
		}
		if (!array_key_exists('host', $uri_segments)) {
			$uri_segments['host'] = self::get('HTTP_HOST');
			$uri_segments['scheme'] = self::get('SCHEME');
			$uri_segments['port'] = '';
		}
		$uri_segments['port'] = '';
		if (array_key_exists('port', $uri_segments) && $uri_segments['port']) {
			$uri_segments['port'] = ':'.$uri_segments['port'];
		}
		if (!array_key_exists('scheme', $uri_segments)) {
			$uri_segments['scheme'] = self::get('SCHEME');
		} else {
			$uri_segments['scheme'] = self::get('SCHEME');
		}
		if (!array_key_exists('path', $uri_segments)) {
			$uri_segments['path'] = '';
		}
		if (array_key_exists('path', $uri_segments) && substr($uri_segments['path'], 0, 1) == '/') {
			$uri_segments['path'] = substr($uri_segments['path'], 1);
		}
		$port = (array_key_exists('port', $uri_segments)) ? $uri_segments['port'] : '';
		return $uri_segments['scheme'].$uri_segments['host']."$port/".$uri_segments['path'];
	}
	
	static public function process_scheme($scheme) {
		return strtolower(substr($scheme, 0, strpos($scheme, '/'))).'://';
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