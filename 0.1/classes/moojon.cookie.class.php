<?php
final class moojon_cookie extends moojon_base {
	
	static private $instance;
	static private $data = array();
	
	private function __construct() {
		$this->data = $_COOKIE;
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_cookie();
		}
		return self::$instance;
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function has($key) {
		$data = self::get_data();
		if (is_array($data) == false) {
			return false;
		}
		if (array_key_exists($key, $data) === true) {
			if ($data[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if (moojon_config::has('cookie_expiry') == true) {
			if (is_int(moojon_config::get('cookie_expiry')) == true) {
				$cookie_expiry = (time() + moojon_config::get('cookie_expiry'));
			} else {
				throw new moojon_exception('cookie_expiry must be an integer ('.moojon_config::get('cookie_expiry').')');
			}
		}
		$instance = self::get();
		$instance->data[$key] = $value;
		if ($value !== null) {
			$_COOKIE[$key] = $value;
			setcookie($key, $value, $cookie_expiry, '/');
		} else {
			$_COOKIE[$key] = null;
			setcookie($key, null, -1, '/');
			unset($_COOKIE[$key]);
		}
	}
	
	static public function clear() {
		$data = self::get_data();
		if (is_array($data) == true) {
			foreach($data as $key => $value) {
				self::set($key, $value);
			}
		} else {
			$instance = self::get();
			$instance->data = array();
		}
	}
	
	static public function key($key) {
		$data = self::get_data();
		if (is_array($data) == true) {
			if (array_key_exists($key, $data) == true) {
				return $data[$key];
			} else {
				throw new moojon_exception("Key does not exists ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists ($key)");
		}
	}
}
?>