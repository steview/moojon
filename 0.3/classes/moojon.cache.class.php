<?php
final class moojon_cache extends moojon_base {
	static private $instance;
	private $data;
	private $cache = true;
	
	private function __construct() {
		$this->data = moojon_paths::get_project_cache_directory();
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_cache();
		}
		return self::$instance;
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function enable() {
		$instance = self::get();
		$instance->cache = true;
	}
	
	static public function disable() {
		$instance = self::get();
		$instance->cache = false;
	}
	
	static public function get_cache() {
		if (!moojon_config::key('cache_for')) {
			return false;
		}
		$instance = self::get();
		return $instance->cache;
	}
	
	static private function create_cache(moojon_base_app $app, $uri) {
		$data = self::get_data();
		$cache_file_path = "$data/$uri/cache";
		if (file_exists($cache_file_path) && (time() > (filectime($cache_file_path) + moojon_config::key('cache_for')))) {
			self::remove($uri);
		}
		if (!file_exists($cache_file_path)) {
			$app->set_location($uri);
			$render = $app->render();
			if (!self::get_cache()) {
				echo $render;
				return false;
			} else {
				moojon_paths::attempt_mkdir("$data/$uri");
				if (!$handle = fopen($cache_file_path, 'w')) {
					fclose($handle);
					throw new moojon_exception("Unable to open / create cache file ($cache_file_path)");
				}
				if (!fwrite($handle, $render)) {
					fclose($handle);
					throw new moojon_exception("Unable to write to cache file ($cache_file_path)");
				}
				self::log("Creating cache ($cache_file_path)");
				fclose($handle);
			}
		}
		return $cache_file_path;
	}
	
	static public function clear() {
		exec('rm -rf '.moojon_config::get_project_cache_directory());
	}
	
	static public function remove($uri) {
		$data = self::get_data();
		$cache_file_path = "$data/$uri/cache";
		unlink($cache_file_path);
		self::log("Clearing cache ($cache_file_path)");
	}
	
	static public function process(moojon_base_app $app, $uri) {
		if ($cache_file_path = self::create_cache($app, $uri)) {
			echo moojon_files::get_file_contents($cache_file_path);
		}
	}
}
?>