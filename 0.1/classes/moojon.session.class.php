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
		$data = self::get_data();
		if (array_key_exists($key, $data) === true) {
			if ($data[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		$data = self::get_data();
		if ($value !== null) {
			$data[$key] = $value;
		} else {
			$data[$key] = null;
			unset($data[$key]);
		}
	}
	
	static public function clear() {
		$data = self::get_data();
		foreach($data as $key) {
			$data[$key] = null;
			unset($data[$key]);
		}
	}
	
	static public function key($key) {
		$data = self::get_data();
		if (array_key_exists($key, $data) == true) {
			return $data[$key];
		} else {
			self::handle_error("Key does not exists in moojon_session ($key)");
		}
	}
	
	static public function get_list() {
		return self::get_data();
	}
}
?>