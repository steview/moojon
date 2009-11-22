<?php
final class moojon_env extends moojon_singleton_mutable_collection {
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
		if ($value !== null) {
			$_ENV[$key] = $value;
		} else {
			$_ENV[$key] = null;
			unset($_ENV[$key]);
		}
	}
	static public function post_clear() {$_ENV = array();}
	
	protected function __construct() {
		$this->data = $_ENV;
	}
}
?>