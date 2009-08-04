<?php
final class moojon_env extends moojon_base {
	
	static private $instance;
	static private $data = array();
	
	private function __construct() {
		$this->data = $_ENV;
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_env();
		}
		return self::$instance;
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function has($key) {
		$data = self::get_data();
		if (is_array($data) == false) {
			return false;
		}
		if (array_key_exists($key, $data) === true) {
			if ($data[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		$instance = self::get();
		$instance->data[$key] = $value;
		if ($value !== null) {
			$_ENV[$key] = $value;
		} else {
			$_ENV[$key] = null;
			unset($_ENV[$key]);
		}
	}
	
	static public function clear() {
		$data = self::get_data();
		if (is_array($data) == true) {
			foreach($data as $key => $value) {
				self::set($key, $value);
			}
		} else {
			$instance = self::get();
			$instance->data = array();
		}
	}
	
	static public function key($key) {
		$data = self::get_data();
		if (is_array($data) == true) {
			if (array_key_exists($key, $data) == true) {
				return $data[$key];
			} else {
				throw moojon_exception::create("Key does not exists ($key)");
			}
		} else {
			throw moojon_exception::create("Key does not exists ($key)");
		}
	}
}
?>