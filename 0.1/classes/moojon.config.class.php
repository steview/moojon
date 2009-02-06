<?php
final class moojon_config extends moojon_base {
	private function __construct() {}
	
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
		return 'bloodbowl';
	}
	
	static 	public function get_apps_directory() {
		return PROJECT_PATH.'apps/';
	}
	
	static 	public function get_app_directory() {
		return self::get_apps_directory().APP.'/';
	}
	
	static 	public function get_controllers_directory() {
		return self::get_app_directory().'controllers/';
	}
	
	static 	public function get_views_directory() {
		return self::get_app_directory().'views/';
	}
	
	static 	public function get_layouts_directory() {
		return self::get_app_directory().'layouts/';
	}
	
	static 	public function get_models_directory() {
		return PROJECT_PATH.'models/';
	}
	
	static 	public function get_base_models_directory() {
		return self::get_models_directory().'base/';
	}
	
	static 	public function get_migrations_directory() {
		return self::get_models_directory().'migrations/';
	}
	
	static 	public function get_public_directory() {
		return PROJECT_PATH.'public/';
	}
	
	static 	public function get_images_directory() {
		return self::get_public_directory().'images/';
	}
	
	static 	public function get_css_directory() {
		return self::get_public_directory().'css/';
	}
	
	static 	public function get_js_directory() {
		return self::get_public_directory().'js/';
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