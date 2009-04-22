<?php
final class moojon_cookies extends moojon_base {
	
	private function __construct() {}
	
	static private function get_data() {
		if (is_array($_COOKIES) == ture) {
			return $_COOKIES;
		} else {
			return array();
		}
	}
	
	static public function has($key) {
		return array_key_exists($key, self::get_data());
	}
	
	static public function set($key, $value) {
		$_COOKIES[$key] = $value;
	}
	
	static public function get($key) {
		return self::key($key);
	}
	
	static public function key($key) {
		if (array_key_exists($key, self::get_data()) == true) {
			return $_COOKIES[$key];
		} else {
			self::handle_error("Key does not exists in moojon_cookies ($key)");
		}
	}
}
?>