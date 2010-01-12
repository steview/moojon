<?php
final class moojon_cache extends moojon_singleton {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	
	private $enabled = true;
	
	protected function __construct() {}
	
	static public function expired($path, $absolute = false, $cache_for = null) {
		if (!$cache_for) {
			$cache_for = moojon_config::get('cache_for');
		}
		if (!$absolute) {
			$path = moojon_paths::get_cache_path($path);
		}
		if (file_exists($path)) {
			if (time() > (filectime($path) + $cache_for)) {
				//moojon_files::unlink($path);
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	static public function get_enabled() {
		$instance = self::fetch();
		return $instance->enabled;
	}
	
	static public function enable() {
		$instance = self::fetch();
		$instance->enabled = true;
	}
	
	static public function disable() {
		$instance = self::fetch();
		$instance->enabled = false;
	}
}
?>