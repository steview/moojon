<?php
final class moojon_flash extends moojon_base {
	static private $instance;
	static private $data;
	
	private function __construct() {
		$data = array();
		$flash_key = moojon_config::key('flash_key');
		if (moojon_session::has($flash_key) == true) {
			$flash = moojon_session::key($flash_key);
			if (is_array($flash) == true) {
				$data = $flash;
				self::clear();
			}
		} else {
			moojon_session::set($flash_key, $data);
		}
		$this->data = $data;
	}
	
	static public function get($key = null) {
		if (!self::$instance) {
			self::$instance = new moojon_flash();
		}
		if ($key == null) {
			return self::$instance;
		} else {
			return self::$instance->$key;
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
				throw new moojon_exception("Unknown flash property ($key)");
			}
		}
	}
	
	static public function set($key, $value = null) {
		if (is_array($key) == false) {
			$data = array($key => $value);
		} else {
			$data = $key;
		}
		$instance = self::get();
		$flash = moojon_session::key(moojon_config::key('flash_key'));
		foreach ($data as $key => $value) {
			$flash[$key] = $value;
		}
		moojon_session::set(moojon_config::key('flash_key'), $flash);
	}
	
	static public function has($key) {
		return array_key_exists($key, self::get_data());
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function clear() {
		moojon_session::set(moojon_config::key('flash_key'), array());
	}
}
?>