<?php
final class moojon_session extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		if (is_array($_SESSION) == false) {
			return false;
		}
		if (array_key_exists($key, $_SESSION) === true) {
			if ($_SESSION[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if ($value !== null) {
			$_SESSION[$key] = $value;
		} else {
			$_SESSION[$key] = null;
			unset($_SESSION[$key]);
		}
	}
	
	static public function clear() {
		if (is_array($_SESSION) == true) {
			foreach($_SESSION as $key) {
				$_SESSION[$key] = null;
				unset($_SESSION[$key]);
			}
		}
	}
	
	static public function key($key) {
		if (is_array($_SESSION) == true) {
			if (array_key_exists($key, $_SESSION) == true) {
				return $_SESSION[$key];
			} else {
				throw new Exception("Key does not exists in moojon_session ($key)");
			}
		} else {
			throw new Exception("Key does not exists in moojon_session ($key)");
		}
	}
	
	static public function get_list() {
		if (is_array($_SESSION) == true) {
			return $_SESSION;
		} else {
			return array();
		}
	}
}
?>