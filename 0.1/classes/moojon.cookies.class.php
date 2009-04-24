<?php
final class moojon_cookies extends moojon_base {
	
	private function __construct() {}
	
	static private function get_data() {
		if (is_array($_COOKIE) == ture) {
			return $_COOKIE;
		} else {
			return array();
		}
	}
	
	static public function has($key) {
		return array_key_exists($key, self::get_data());
	}
	
	static public function set($key, $value) {
		setcookie($key, $value);
	}
	
	static public function clear() {
		foreach($_COOKIE as $key) {
			setcookie($key, null);
		}
	}
	
	static public function get($key) {
		return self::key($key);
	}
	
	static public function key($key) {
		if (array_key_exists($key, self::get_data()) == true) {
			return $_COOKIE[$key];
		} else {
			self::handle_error("Key does not exists in moojon_cookies ($key)");
		}
	}
}
?>