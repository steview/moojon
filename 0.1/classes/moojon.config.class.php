<?php
final class moojon_config extends moojon_base {
	static private $instance;
	private $data = array();
	
	private function __construct() {
		$this->data = require_once(MOOJON_PATH.'config/moojon.config.php');
	}
	
	static public function update($directory) {
		if (is_dir($directory)) {
			foreach (moojon_files::directory_files($directory, true) as $file) {
				if (moojon_files::has_suffix($file, 'config')) {
					$array = require_once($file);
					if (is_array($array)) {
						foreach ($array as $key => $value) {
							self::set($key, $value);
						}
					}
				}
			}
		} else {
			throw new moojon_exception("Not a directory ($directory)");
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
		if (array_key_exists($key, $data) && isset($data[$key])) {
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