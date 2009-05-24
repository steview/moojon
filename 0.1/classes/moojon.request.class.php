<?php
final class moojon_request extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		if (is_array($_REQUEST) == false) {
			return false;
		}
		if (array_key_exists($key, $_REQUEST) === true) {
			if ($_REQUEST[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if ($value !== null) {
			$_REQUEST[$key] = $value;
		} else {
			$_REQUEST[$key] = null;
			unset($_REQUEST[$key]);
		}
	}
	
	static public function clear() {
		if (is_array($_REQUEST) == true) {
			foreach($_REQUEST as $key) {
				$_REQUEST[$key] = null;
				unset($_REQUEST[$key]);
			}
		}
	}
	
	static public function key($key) {
		if (is_array($_REQUEST) == true) {
			if (array_key_exists($key, $_REQUEST) == true) {
				return $_REQUEST[$key];
			} else {
				throw new moojon_exception("Key does not exists in moojon_request ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists in moojon_request ($key)");
		}
	}
	
	static public function get_list() {
		if (is_array($_REQUEST) == true) {
			return $_REQUEST;
		} else {
			return array();
		}
	}
	
	static public function method() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	static public function post() {
		return (strtolower(self::method()) == 'post');
	}
}
?>