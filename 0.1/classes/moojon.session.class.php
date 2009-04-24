<?php
final class moojon_session extends moojon_base {
	
	private function __construct() {}
	
	static private function get_data() {
		if (is_array($_SESSION) == ture) {
			return $_SESSION;
		} else {
			return array();
		}
	}
	
	static public function has($key) {
		return array_key_exists($key, self::get_data());
	}
	
	static public function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	static public function clear() {
		foreach($_SESSION as $key) {
			$_SESSION[$key] = null;
		}
		session_unset();
	}
	
	static public function get($key) {
		return self::key($key);
	}
	
	static public function key($key) {
		if (array_key_exists($key, self::get_data()) == true) {
			return $_SESSION[$key];
		} else {
			self::handle_error("Key does not exists in moojon_session ($key)");
		}
	}
}
?>