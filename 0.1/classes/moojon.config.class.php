<?php
final class moojon_config extends moojon_base {
	static private $instance;
	static private $data = array();
	
	private function __construct() {
		$this->data = require_once(MOOJON_PATH.'config/moojon.config.php');		
	}

	static public function get($key = null) {
		if (!self::$instance) {
			self::$instance = new moojon_config();
		}
		if ($key == null) {
			return self::$instance;
		} else {
			return self::$instance->$key;
		}		
	}
	
	static public function set($key, $value = null) {
		if (!is_array($key)) {
			$data = array($key => $value);
		} else {
			$data = $key;
		}
		$instance = self::get();
		foreach ($data as $key => $value) {
			$instance->data[$key] = $value;
		}
	}
	
	static public function __get($key) {
		$data = self::get_data();
		if (array_key_exists($key, $data)) {
			return $data[$key];
		} else {
			self::handle_error("Unknown config property ($key)");
		}
	}
	
	static public function has($key) {
		return array_key_exists($key, self::get_data());
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
}
?>