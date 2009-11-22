<?php
final class moojon_config extends moojon_singleton_immutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	
	protected function __construct() {
		$this->data = require_once(MOOJON_DIRECTORY.'config/moojon.config.php');
		$environment_config = moojon_paths::get_project_config_directory().ENVIRONMENT.'.config.php';
		if (defined('PROJECT_DIRECTORY') && is_file($environment_config)) {
			foreach (require_once($environment_config) as $key => $value) {
				$this->data[$key] = $value;
			}
		}
		date_default_timezone_set($this->data['timezone']);
	}
	
	static public function update($directory) {
		if (is_dir($directory)) {
			$instance = self::fetch();
			$data = $instance->data;
			foreach (moojon_files::directory_files($directory, true) as $file) {
				if (moojon_files::has_suffix($file, 'config')) {
					$array = require_once($file);
					if (is_array($array)) {
						foreach ($array as $key => $value) {
							self::log("$key: $value");
							$data[$key] = $value;
						}
					}
				}
			}
			$instance->data = $data;
		} else {
			throw new moojon_exception("Not a directory ($directory)");
		}
	}
}
?>