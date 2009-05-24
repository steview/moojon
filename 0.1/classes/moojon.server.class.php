<?php
final class moojon_server extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		if (is_array($_SERVER) == false) {
			return false;
		}
		if (array_key_exists($key, $_SERVER) === true) {
			if ($_SERVER[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if ($value !== null) {
			$_SERVER[$key] = $value;
		} else {
			$_SERVER[$key] = null;
			unset($_SERVER[$key]);
		}
	}
	
	static public function clear() {
		if (is_array($_SERVER) == true) {
			foreach($_SERVER as $key) {
				$_SERVER[$key] = null;
				unset($_SERVER[$key]);
			}
		}
	}
	
	static public function key($key) {
		if (is_array($_SERVER) == true) {
			if (array_key_exists($key, $_SERVER) == true) {
				return $_SERVER[$key];
			} else {
				throw new moojon_exception("Key does not exists in moojon_server ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists in moojon_server ($key)");
		}
	}
	
	static public function get_list() {
		if (is_array($_SERVER) == true) {
			return $_SERVER;
		} else {
			return array();
		}
	}
}
?>