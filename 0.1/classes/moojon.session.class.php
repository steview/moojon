<?php
final class moojon_session extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		return array_key_exists($key, $_SESSION);
	}
	
	static public function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	static public function get($key) {
		return self::key($key);
	}
	
	static public function key($key) {
		if (array_key_exists($key, $_SESSION) == true) {
			return $_SESSION[$key];
		} else {
			self::handle_error("Key does not exists in moojon_session ($key)");
		}
	}
}
?>