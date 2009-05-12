<?php
final class moojon_config extends moojon_base {
	static private $instance;
	static private $data = array();
	
	private function __construct() {
		$data = require_once(MOOJON_PATH.'config/moojon.config.php');
		$this->data = $data;
	}
	
	static public function update($directory) {
		foreach (moojon_files::directory_files($directory, true) as $file) {
			$array = require_once($file);
			if (is_array($array) === true) {
				foreach ($array as $key => $value) {
					self::set($key, $value);
				}
			}
		}
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
		if (is_array($key) == false) {
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
		if (array_key_exists($key, $data) == true && $data[$key] != null) {
			return $data[$key];
		} else {
			if (array_key_exists($key, $data)) {
				return $data[$key];
			} else {
				throw new Exception("Unknown config property ($key)");
			}
		}
	}
	
	static public function has($key) {
		return array_key_exists($key, self::get_data());
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function clear() {
		$instance = self::get();
		$instance->data = array();
	}
	
	static public function dump() {
		print_r($data = self::get_data());
	}
}
?>