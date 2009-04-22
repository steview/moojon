<?php
final class moojon_cookies extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		return array_key_exists($key, $_COOKIES);
	}
	
	static public function set($key, $value) {
		$_COOKIES[$key] = $value;
	}
	
	static public function get($key) {
		return self::key($key);
	}
	
	static public function key($key) {
		if (array_key_exists($key, $_COOKIES) == true) {
			return $_COOKIES[$key];
		} else {
			self::handle_error("Key does not exists in moojon_cookies ($key)");
		}
	}
}
?>