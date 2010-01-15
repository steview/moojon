<?php
final class moojon_flash extends moojon_singleton_mutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	static public function set($key, $value = null, $data = null) {$instance = self::fetch();$instance->data[$key] = $value;self::post_set($key, $value, $data);}
	static public function clear() {$instance = self::fetch();$instance->data = array();self::post_clear();}
	static protected function post_set($key, $value = null, $data = null) {
		$flash_key = moojon_config::get('flash_key');
		$flash = array();
		if (!$flash = moojon_session::get_or_null($flash_key)) {
			$flash = array();
		}
		$flash[$key] = $value;
		moojon_session::set($flash_key, $flash);
		moojon_cache::disable();
	}
	static public function post_clear() {$flash_key = moojon_config::get('flash_key');if (array_key_exists($flash_key, moojon_session::get_data())) {moojon_session::set($flash_key, array());}}
	
	protected function __construct() {
		$flash_key = moojon_config::get('flash_key');
		if (!$data = moojon_session::get_or_null($flash_key)) {
			$data = array();
		}
		if (!is_array($data)) {
			$data = array($data);
		}
		moojon_session::set($flash_key, array());
		$this->data = $data;
	}
}
?>