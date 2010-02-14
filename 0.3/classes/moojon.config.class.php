<?php
final class moojon_config extends moojon_singleton_immutable_collection {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	static public function get_data($data = null) {if ($data) {return $data;}$instance = self::fetch();return $instance->data;}
	static public function has($key, $data = null) {$data = self::get_data($data);if (!is_array($data)) {return false;}if (array_key_exists($key, $data) && $data[$key] !== null) {return true;}return false;}
	static public function get($key, $data = null) {$data = self::get_data($data);if (self::has($key, $data)) {return $data[$key];} else {throw new moojon_exception("Key does not exists ($key) in ".get_class());}}
	static public function get_or_null($key, $data = null) {$data = self::get_data($data);return (array_key_exists($key, $data)) ? $data[$key] : null;}
	
	protected $data_archive = array();
	
	protected function __construct() {
		$this->data = require_once(MOOJON_DIRECTORY.'config/moojon.config.php');
		$project_config_path = moojon_paths::get_project_config_directory().'project.config.php';
		if (defined('PROJECT_DIRECTORY') && file_exists($project_config_path)) {
			$this->data = array_merge($this->data, require_once($project_config_path));
		}
		$config_environment = moojon_paths::get_project_config_environment_directory(ENVIRONMENT).'environment.config.php';
		if (defined('PROJECT_DIRECTORY') && is_file($config_environment)) {
			foreach (require_once($config_environment) as $key => $value) {
				$this->data[$key] = $value;
			}
		}
		date_default_timezone_set($this->data['timezone']);
	}
	
	static public function update($directory) {
		$instance = self::fetch();
		$instance->data = array_merge($instance->data, self::read($directory));
	}
	
	static public function read($directory) {
		$data = array();
		$instance = self::fetch();
		if (is_dir($directory)) {
			foreach (moojon_files::directory_files($directory, true) as $file) {
				if (moojon_files::has_suffix($file, 'config')) {
					if (!array_key_exists($file, $instance->data_archive)) {
						$instance->data_archive[$file] = require_once($file);
					}
					$array = $instance->data_archive[$file];
					if (is_array($array)) {
						foreach ($array as $key => $value) {
							$data[$key] = $value;
						}
					}
				}
			}
		}
		return $data;
	}
}
?>