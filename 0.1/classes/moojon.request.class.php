<?php
final class moojon_request extends moojon_base {
	static private $instance;
	private $data = array();
	
	private function __construct() {
		$this->data = $_REQUEST;
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_request();
		}
		return self::$instance;
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function has($key) {
		$data = self::get_data();
		if (!is_array($data)) {
			return false;
		}
		if (array_key_exists($key, $data)) {
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
			$_REQUEST[$key] = $value;
		} else {
			$_REQUEST[$key] = null;
			unset($_REQUEST[$key]);
		}
	}
	
	static public function clear() {
		$data = self::get_data();
		if (is_array($data)) {
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
		if (is_array($data)) {
			if (array_key_exists($key, $data)) {
				return $data[$key];
			} else {
				throw new moojon_exception("Key does not exists ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists ($key)");
		}
	}
}
?>