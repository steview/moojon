<?php
final class moojon_post extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		if (is_array($_POST) == false) {
			return false;
		}
		if (array_key_exists($key, $_POST) === true) {
			if ($_POST[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if ($value !== null) {
			$_POST[$key] = $value;
		} else {
			$_POST[$key] = null;
			unset($_POST[$key]);
		}
	}
	
	static public function clear() {
		if (is_array($_POST) == true) {
			foreach($_POST as $key) {
				$_POST[$key] = null;
				unset($_POST[$key]);
			}
		}
	}
	
	static public function key($key) {
		if (is_array($_POST) == true) {
			if (array_key_exists($key, $_POST) == true) {
				return $_POST[$key];
			} else {
				throw new moojon_exception("Key does not exists in moojon_post ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists in moojon_post ($key)");
		}
	}
	
	static public function get_list() {
		if (is_array($_POST) == true) {
			return $_POST;
		} else {
			return array();
		}
	}
}
?>