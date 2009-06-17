<?php
final class moojon_paths extends moojon_base {
	
	private function __construct() {}
	
	
	
	
	
	static public function get_classes_directory() {
		return self::get_moojon_directory().moojon_config::get('classes_directory').'/';
	}
	
	static public function get_adapters_directory() {
		return self::get_classes_directory().moojon_config::get('adapters_directory').'/';
	}
	
	static public function get_adapter_directory() {
		return self::get_adapters_directory().moojon_config::get('adapter').'/';
	}
	
	static public function get_columns_directory() {
		return self::get_adapter_directory().moojon_config::get('columns_directory').'/';
	}
	
	static public function get_validations_directory() {
		return self::get_classes_directory().moojon_config::get('validations_directory').'/';
	}
	
	static public function get_tags_directory() {
		return self::get_classes_directory().moojon_config::get('tags_directory').'/';
	}
	
	static public function get_tag_attributes_directory() {
		return self::get_tags_directory().moojon_config::get('tag_attributes_directory').'/';
	}
	
	
	
	
	
	static public function get_moojon_directory() {
		return MOOJON_PATH;
	}
	
	static public function get_moojon_base_models_directory() {
		return self::get_moojon_models_directory().moojon_config::get('base_models_directory').'/';
	}
	
	static public function get_moojon_models_directory() {
		return self::get_moojon_directory().moojon_config::get('models_directory').'/';
	}
	
	static public function get_moojon_migrations_directory() {
		return self::get_moojon_models_directory().moojon_config::get('migrations_directory').'/';
	}
	
	static public function get_moojon_app_controller_views_directory($app, $controller) {
		return self::get_moojon_app_views_directory($app)."$controller/";
	}
	
	static public function get_moojon_app_views_directory($app) {
		return self::get_moojon_views_directory()."$app/";
	}
	
	static public function get_moojon_views_directory() {
		return self::get_moojon_views_directory().moojon_config::get('views_directory').'/';
	}
	
	static public function get_moojon_layouts_directory() {
		return self::get_moojon_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_moojon_controllers_app_directory($app) {
		return self::get_moojon_controllers_directory()."$app/";
	}
	
	static public function get_moojon_controllers_directory() {
		return self::get_moojon_directory().moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_moojon_apps_directory() {
		return self::get_moojon_directory().moojon_config::get('apps_directory').'/';
	}
	
	static public function get_moojon_helpers_directory() {
		return MOOJON_PATH.moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_moojon_config_directory() {
		return self::get_moojon_directory().'config/';
	}
	
	static public function get_moojon_app_config_directory($app) {
		return self::get_moojon_config_directory()."$app/";
	}
	
	
	
	
	
	static public function get_project_directory() {
		return PROJECT_DIRECTORY;
	}
	
	static public function get_project_base_models_directory() {
		return self::get_project_models_directory().moojon_config::get('base_models_directory').'/';
	}
	
	static public function get_project_models_directory() {
		return self::get_project_directory().moojon_config::get('models_directory').'/';
	}
	
	static public function get_project_migrations_directory() {
		return self::get_project_models_directory().moojon_config::get('migrations_directory').'/';
	}
	
	static public function get_project_views_app_controller_directory($app, $controller) {
		return self::get_project_views_app_directory($app)."$controller/";
	}
	
	static public function get_project_views_app_directory($app) {
		return self::get_project_views_directory()."$app/";
	}
	
	static public function get_project_views_directory() {
		return self::get_project_directory().moojon_config::get('views_directory').'/';
	}
	
	static public function get_project_layouts_directory() {
		return self::get_project_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_project_controllers_app_directory($app) {
		return self::get_project_controllers_directory()."$app/";
	}
	
	static public function get_project_controllers_directory() {
		return self::get_project_directory().moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_project_apps_directory() {
		return self::get_project_directory().moojon_config::get('apps_directory').'/';
	}
	
	static public function get_project_helpers_directory() {
		return self::get_project_directory().moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_project_config_directory() {
		return self::get_project_directory().'config/';
	}
	
	static public function get_project_app_config_directory($app) {
		return self::get_project_config_directory()."$app/";
	}
	
	
	
	
	
	static public function get_script_directory() {
		return self::get_project_directory().moojon_config::get('script_directory').'/';
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
	
	
	
	
	
	static private function get_path($paths) {
		foreach ($paths as $path) {
			if (file_exists($path)) {
				return $path;
			}
		}
		return false;
	}
	
	static public function get_app_path($app) {
		$paths = array(
			self::get_project_apps_directory()."$app.app.class.php",
			self::get_moojon_apps_directory()."$app.app.class.php",
		);
		return self::get_path($paths);
	}
	
	static public function get_controller_path($app, $controller) {
		$paths = array(
			self::get_project_controllers_app_directory($app)."$app/$controller.controller.class.php",
			self::get_moojon_controllers_app_directory($app)."$app/$controller.controller.class.php"
		);
		return self::get_path($paths);
	}
	
	static public function get_layout_path($layout) {
		$paths = array(
			self::get_layouts_directory()."$layout.layout.php",
			self::get_moojon_layouts_directory()."$layout.layout.php"
		);
		return self::get_path($paths);
	}
	
	static public function get_view_path($view, $app, $controller) {
		$paths = array(
			self::get_project_views_app_controller_directory($app, $controller)."$view.view.php",
			self::get_project_views_app_directory($app)."$view.view.php",
			self::get_project_views_directory()."$view.view.php",
			self::get_moojon_views_app_controller_directory($app, $controller)."$view.view.php",
			self::get_moojon_views_app_directory($app)."$view.view.php",
			self::get_moojon_views_directory()."$view.view.php"
		);
		return self::get_path($paths);
	}
	
	static public function get_helper_path($helper) {
		$paths = array(
			self::get_helpers_directory()."$helper.helper.php",
			self::get_moojon_helpers_directory()."$helper.helper.php"
		);
		return self::get_path($paths);
	}
}
?>