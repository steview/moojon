<?php
final class moojon_server extends moojon_base {
	
	static private $instance;
	static private $data = array();
	
	private function __construct() {
		$this->data = $_SERVER;
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_server();
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
			$_SERVER[$key] = $value;
		} else {
			$_SERVER[$key] = null;
			unset($_SERVER[$key]);
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
	
	static public function method() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	static public function is_get() {
		return (strtolower(self::method()) == 'get');
	}
	
	static public function is_post() {
		return (strtolower(self::method()) == 'post');
	}
	
	static public function is_put() {
		return (strtolower(self::method()) == 'put');
	}
	
	static public function is_delete() {
		return (strtolower(self::method()) == 'delete');
	}
}
?>