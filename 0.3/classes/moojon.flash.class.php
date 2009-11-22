<?php
final class moojon_flash extends moojon_singleton_mutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	static public function set($key, $value = null, $data = null) {$data = self::get_data($data);$data[$key] = $value;self::post_set($key, $value, $data);}
	static public function clear() {$instance = self::fetch();$instance->data = null;self::post_clear();}
	static protected function post_set($key, $value = null, $data = null) {
		if (!is_array($key)) {
			$data = array($key => $value);
		} else {
			$data = $key;
		}
		foreach ($data as $key => $value) {
			$flash[$key] = $value;
		}
		moojon_session::set(moojon_config::get('flash_key'), $flash);
		moojon_cache::disable();
	}
	static public function post_clear() {$flash_key = moojon_config::get('flash_key');if (array_key_exists($flash_key, moojon_session::get_data())) {moojon_session::set($flash_key, array());}}
	
	protected function __construct() {
		$data = array();
		$flash_key = moojon_config::get('flash_key');
		if (moojon_session::has($flash_key)) {
			$flash = moojon_session::get($flash_key);
			if (is_array($flash)) {
				$data = $flash;
				self::clear();
			}
		} else {
			moojon_session::set($flash_key, $data);
		}
		$this->data = $data;
	}
}
?>