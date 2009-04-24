<?php
final class moojon_paths extends moojon_base {
	static private $instance;
	
	private $project_directory;
	
	private function __construct() {
		$this->project_directory = PROJECT_DIRECTORY;
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_paths();
		}
		return self::$instance;
	}
	
	static public function __get($key) {
		if (substr($key, 0, 4) != 'get_') {
			$key = "get_$key";
		}
		$instance = self::get();
		if (in_array($key, get_class_methods('moojon_paths'))) {
			return $instance->$key();
		} else {
			self::handle_error('Unknown path property ($key)');
		}
	}
	
	static public function get_shared_directory() {
		return self::get_project_directory().moojon_config::get('shared_directory').'/';
	}

	static public function get_moojon_directory() {
		return MOOJON_PATH;
	}
	
	static public function get_project_directory() {
		return PROJECT_DIRECTORY;
	}
	
	static public function get_project_config_directory() {
		return self::get_project_directory().moojon_config::get('config_directory').'/';
	}
	
	static public function get_apps_directory() {
		return self::get_project_directory().moojon_config::get('apps_directory').'/';
	}
	
	static public function get_app_directory() {
		return self::get_apps_directory().moojon_uri::get_app().'/';
	}
	
	static public function get_app_config_directory() {
		return self::get_app_directory().moojon_config::get('config_directory').'/';
	}
	
	static public function get_controllers_directory() {
		return self::get_app_directory().moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_views_directory() {
		return self::get_app_directory().moojon_config::get('views_directory').'/'.moojon_uri::get_controller().'/';
	}
	
	static public function get_layouts_directory() {
		return self::get_app_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_shared_controllers_directory() {
		return self::get_shared_directory().moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_shared_views_directory() {
		return self::get_shared_directory().moojon_config::get('views_directory').'/';
	}
	
	static public function get_shared_layouts_directory() {
		return self::get_shared_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_moojon_controllers_directory() {
		return self::get_moojon_directory().moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_moojon_views_directory() {
		return self::get_moojon_directory().moojon_config::get('views_directory').'/';
	}
	
	static public function get_moojon_layouts_directory() {
		return self::get_moojon_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_models_directory() {
		return self::get_project_directory().moojon_config::get('models_directory').'/';
	}
	
	static public function get_base_models_directory() {
		return self::get_models_directory().moojon_config::get('base_models_directory').'/';
	}
	
	static public function get_helpers_directory() {
		return self::get_project_directory().moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_moojon_helpers_directory() {
		return MOOJON_PATH.moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_migrations_directory() {
		return self::get_models_directory().moojon_config::get('migrations_directory').'/';
	}
	
	static public function get_public_directory() {
		return self::get_project_directory().moojon_config::get('public_directory').'/';
	}
	
	static public function get_images_directory() {
		return self::get_public_directory().moojon_config::get('images_directory').'/';
	}
	
	static public function get_css_directory() {
		return self::get_public_directory().moojon_config::get('css_directory').'/';
	}
	
	static public function get_js_directory() {
		return self::get_public_directory().moojon_config::get('js_directory').'/';
	}
	
	static public function get_script_directory() {
		return self::get_project_directory().moojon_config::get('script_directory').'/';
	}
	
	static public function get_library_directory() {
		return self::get_project_directory().moojon_config::get('library_directory').'/';
	}
	
	static public function get_vendor_directory() {
		return self::get_project_directory().moojon_config::get('vendor_directory').'/';
	}
}
?>