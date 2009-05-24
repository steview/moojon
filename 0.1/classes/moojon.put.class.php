<?php
final class moojon_put extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		parse_str(file_get_contents('php://input'), $put);
		if (is_array($put) == false) {
			return false;
		}
		if (array_key_exists($key, $put) === true) {
			if ($put[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		parse_str(file_get_contents('php://input'), $put);
		if ($value !== null) {
			$put[$key] = $value;
		} else {
			$put[$key] = null;
			unset($put[$key]);
		}
	}
	
	static public function clear() {
		parse_str(file_get_contents('php://input'), $put);
		if (is_array($put) == true) {
			foreach($put as $key) {
				$put[$key] = null;
				unset($put[$key]);
			}
		}
	}
	
	static public function key($key) {
		parse_str(file_get_contents('php://input'), $put);
		if (is_array($put) == true) {
			if (array_key_exists($key, $put) == true) {
				return $put[$key];
			} else {
				throw new moojon_exception("Key does not exists in moojon_put ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists in moojon_put ($key)");
		}
	}
	
	static public function get_list() {
		parse_str(file_get_contents('php://input'), $put);
		if (is_array($put) == true) {
			return $put;
		} else {
			return array();
		}
	}
}
?>