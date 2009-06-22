<?php
final class moojon_config extends moojon_base {
	static private $instance;
	static private $data = array();
	
	private function __construct() {
		$data = require_once(MOOJON_PATH.'config/moojon.config.php');
		$this->data = $data;
	}
	
	static public function update($directory) {
		if (is_dir($directory)) {
			foreach (moojon_files::directory_files($directory, true) as $file) {
				if (moojon_files::has_suffix($file, 'config')) {
					$array = require_once($file);
					if (is_array($array) === true) {
						foreach ($array as $key => $value) {
							self::set($key, $value);
						}
					}
				}
			}
		}
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_config();
		}
		return self::$instance;
	}
	
	static public function key($key) {
		if (self::has($key)) {
			$data = self::get_data();
			return $data[$key];
		} else {
			throw new moojon_exception("Unknown config property ($key)");
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
	
	static public function has($key) {
		$data = self::get_data();
		if (array_key_exists($key, $data) && $data[$key] != null) {
			return true;
		} else {
			return false;
		}
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function clear() {
		$instance = self::get();
		self::$instance->data = array();
		self::$instance = null;
	}
}
?>