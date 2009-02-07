<?php
final class moojon_config extends moojon_base {
	static private $instance;
	static private $data = array();
	
	private function __construct() {
		$this->data = array(
			'apps_directory' => 'apps',
			'controllers_directory' => 'controllers',
			'views_directory' => 'views',
			'layouts_directory' => 'layouts',
			'models_directory' => 'models',
			'base_models_directory' => 'base',
			'migrations_directory' => 'migrations',
			'public_directory' => 'public',
			'images_directory' => 'layouts',
			'css_directory' => 'css',
			'js_directory' => 'js',
			'script_directory' => 'script'
		);
	}

	static public function get($key = null) {
		if (!self::$instance) {
			self::$instance = new moojon_config();
		}
		if ($key == null) {
			return self::$instance;
		} else {
			return self::$instance->$key;
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
	
	static public function __get($key) {
		$data = self::get_data();
		if (array_key_exists($key, $data)) {
			return $data[$key];
		} else {
			self::handle_error("Unknown config property ($key)");
		}
	}
	
	static private function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function get_db_host() {
		return 'localhost';
	}
	
	static public function get_db_username() {
		return 'bloodbowl';
	}
	
	static public function get_db_password() {
		return 'bloodbowl99';
	}
	
	static 	public function get_db() {
		return 'bloodbowl2';
	}
	
	static public function get_default_app() {
		return 'client';
	}
	
	static public function get_default_controller() {
		return 'index';
	}
	
	static public function get_default_action() {
		return 'index';
	}
}
?>