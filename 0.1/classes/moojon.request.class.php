<?php
final class moojon_request extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		return array_key_exists($key, $_REQUEST);
	}
	
	static public function set($key, $value) {
		$_REQUEST[$key] = $value;
	}
	
	static public function key($key) {
		if (array_key_exists($key, $_REQUEST) == true) {
			return $_REQUEST[$key];
		} else {
			throw new moojon_exception("Key does not exists in moojon_request ($key)");
		}
	}
	
	static public function method() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	static public function post() {
		return (strtolower(self::method()) == 'post');
	}
	
	static public function get() {
		return (strtolower(self::method()) == 'get');
	}
}
?>