<?php
final class moojon_get extends moojon_base {
	
	static private $instance;
	static private $data = array();
	
	private function __construct() {}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_get();
		}
		return self::$instance;
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function has($key) {
		if (is_array($_GET) == false) {
			return false;
		}
		if (array_key_exists($key, $_GET) === true) {
			if ($_GET[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if ($value !== null) {
			$_GET[$key] = $value;
		} else {
			$_GET[$key] = null;
			unset($_GET[$key]);
		}
	}
	
	static public function clear() {
		if (is_array($_GET) == true) {
			foreach($_GET as $key) {
				$_GET[$key] = null;
				unset($_GET[$key]);
			}
		}
	}
	
	static public function key($key) {
		if (is_array($_GET) == true) {
			if (array_key_exists($key, $_GET) == true) {
				return $_GET[$key];
			} else {
				throw new moojon_exception("Key does not exists in moojon_get ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists in moojon_get ($key)");
		}
	}
}
?>