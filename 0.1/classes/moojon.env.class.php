<?php
final class moojon_env extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		if (is_array($_ENV) == false) {
			return false;
		}
		if (array_key_exists($key, $_ENV) === true) {
			if ($_ENV[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if ($value !== null) {
			$_ENV[$key] = $value;
		} else {
			$_ENV[$key] = null;
			unset($_ENV[$key]);
		}
	}
	
	static public function clear() {
		if (is_array($_ENV) == true) {
			foreach($_ENV as $key) {
				$_ENV[$key] = null;
				unset($_ENV[$key]);
			}
		}
	}
	
	static public function key($key) {
		if (is_array($_ENV) == true) {
			if (array_key_exists($key, $_ENV) == true) {
				return $_ENV[$key];
			} else {
				throw new moojon_exception("Key does not exists in moojon_env ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists in moojon_env ($key)");
		}
	}
	
	static public function get_list() {
		if (is_array($_ENV) == true) {
			return $_ENV;
		} else {
			return array();
		}
	}
}
?>