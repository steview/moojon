<?php
final class moojon_paths extends moojon_base {
	
	private function __construct() {}
	
	
	
	
	
	static public function get_classes_directory() {
		return self::get_moojon_directory().moojon_config::key('classes_directory').'/';
	}
	
	static public function get_interfaces_directory() {
		return self::get_moojon_directory().moojon_config::key('interfaces_directory').'/';
	}
	
	static public function get_db_drivers_directory() {
		return self::get_classes_directory().moojon_config::key('db_drivers_directory').'/';
	}
	
	static public function get_db_driver_directory() {
		return self::get_db_drivers_directory().moojon_config::key('db_driver').'/';
	}
	
	static public function get_columns_directory() {
		return self::get_db_driver_directory().moojon_config::key('columns_directory').'/';
	}
	
	static public function get_validations_directory() {
		return self::get_classes_directory().moojon_config::key('validations_directory').'/';
	}
	
	static public function get_tags_directory() {
		return self::get_classes_directory().moojon_config::key('tags_directory').'/';
	}
	
	static public function get_tag_attributes_directory() {
		return self::get_tags_directory().moojon_config::key('tag_attributes_directory').'/';
	}
	
	
	
	
	
	static public function get_moojon_directory() {
		return MOOJON_PATH;
	}
	
	static public function get_moojon_base_models_directory() {
		return self::get_moojon_models_directory().moojon_config::key('base_models_directory').'/';
	}
	
	static public function get_moojon_models_directory() {
		return self::get_moojon_directory().moojon_config::key('models_directory').'/';
	}
	
	static public function get_moojon_migrations_directory() {
		return self::get_moojon_models_directory().moojon_config::key('migrations_directory').'/';
	}
	
	static public function get_moojon_views_app_controller_directory($app, $controller) {
		return self::get_moojon_views_app_directory($app)."$controller/";
	}
	
	static public function get_moojon_views_app_directory($app) {
		return self::get_moojon_views_directory()."$app/";
	}
	
	static public function get_moojon_views_directory() {
		return self::get_moojon_directory().moojon_config::key('views_directory').'/';
	}
	
	static public function get_moojon_layouts_directory() {
		return self::get_moojon_directory().moojon_config::key('layouts_directory').'/';
	}
	
	static public function get_moojon_controllers_app_directory($app) {
		return self::get_moojon_controllers_directory()."$app/";
	}
	
	static public function get_moojon_controllers_directory() {
		return self::get_moojon_directory().moojon_config::key('controllers_directory').'/';
	}
	
	static public function get_moojon_apps_directory() {
		return self::get_moojon_directory().moojon_config::key('apps_directory').'/';
	}
	
	static public function get_moojon_helpers_directory() {
		return MOOJON_PATH.moojon_config::key('helpers_directory').'/';
	}
	
	static public function get_moojon_config_directory() {
		return self::get_moojon_directory().'config/';
	}
	
	static public function get_moojon_app_config_directory($app, $environment) {
		return self::get_moojon_config_directory()."$app/";
	}
	
	static public function get_moojon_app_environment_config_directory($app, $environment) {
		return self::get_moojon_config_directory($app)."$environment/";
	}
	
	
	
	
	
	static public function get_project_directory() {
		return PROJECT_DIRECTORY;
	}
	
	static public function get_project_base_models_directory() {
		return self::get_project_models_directory().moojon_config::key('base_models_directory').'/';
	}
	
	static public function get_project_models_directory() {
		return self::get_project_directory().moojon_config::key('models_directory').'/';
	}
	
	static public function get_project_migrations_directory() {
		return self::get_project_models_directory().moojon_config::key('migrations_directory').'/';
	}
	
	static public function get_project_views_app_controller_directory($app, $controller) {
		return self::get_project_views_app_directory($app)."$controller/";
	}
	
	static public function get_project_views_app_directory($app) {
		return self::get_project_views_directory()."$app/";
	}
	
	static public function get_project_views_directory() {
		return self::get_project_directory().moojon_config::key('views_directory').'/';
	}
	
	static public function get_project_layouts_directory() {
		return self::get_project_directory().moojon_config::key('layouts_directory').'/';
	}
	
	static public function get_project_controllers_app_directory($app) {
		return self::get_project_controllers_directory()."$app/";
	}
	
	static public function get_project_controllers_directory() {
		return self::get_project_directory().moojon_config::key('controllers_directory').'/';
	}
	
	static public function get_project_apps_directory() {
		return self::get_project_directory().moojon_config::key('apps_directory').'/';
	}
	
	static public function get_project_helpers_directory() {
		return self::get_project_directory().moojon_config::key('helpers_directory').'/';
	}
	
	static public function get_project_config_directory() {
		return self::get_project_directory().'config/';
	}
	
	static public function get_project_app_config_directory($app) {
		return self::get_project_config_directory()."$app/";
	}
	
	static public function get_project_app_environment_config_directory($app, $environment) {
		return self::get_project_app_config_directory($app)."$environment/";
	}
	
	
	
	
	
	static public function get_script_directory() {
		return self::get_project_directory().moojon_config::key('script_directory').'/';
	}
	
	static public function get_public_directory() {
		return self::get_project_directory().moojon_config::key('public_directory').'/';
	}
	
	static public function get_images_directory() {
		return self::get_public_directory().moojon_config::key('images_directory').'/';
	}
	
	static public function get_css_directory() {
		return self::get_public_directory().moojon_config::key('css_directory').'/';
	}
	
	static public function get_js_directory() {
		return self::get_public_directory().moojon_config::key('js_directory').'/';
	}
	
	
	
	
	
	static public function get_class_paths() {
		$paths = array(
			self::get_classes_directory()
		);
		if (moojon_config::has('db_driver')) {
			$paths[] = self::get_db_driver_directory();
			$paths[] = self::get_columns_directory();
		}
		$paths[] = self::get_validations_directory();
		$paths[] = self::get_tags_directory();
		$paths[] = self::get_tag_attributes_directory();
		$paths[] = self::get_project_migrations_directory();
		$paths[] = self::get_moojon_migrations_directory();
		return $paths;
	}
	
	static public function get_model_paths() {
		$paths = array(
			self::get_moojon_models_directory(),
			self::get_project_models_directory(),
		);
		return $paths;
	}
	
	static public function get_base_model_paths() {
		$paths = array(
			self::get_moojon_base_models_directory(),
			self::get_project_base_models_directory()
		);
		return $paths;
	}
	
	static public function get_interface_paths() {
		$paths = array(
			self::get_interfaces_directory()
		);
		return $paths;
	}
	
	static public function get_app_paths() {
		return array(
			self::get_project_apps_directory(),
			self::get_moojon_apps_directory(),
		);
	}
	
	static public function get_controller_paths($app) {
		return array(
			self::get_project_controllers_app_directory($app),
			self::get_moojon_controllers_app_directory($app)
		);
	}
	
	static public function get_layout_paths() {
		return array(
			self::get_project_layouts_directory(),
			self::get_moojon_layouts_directory()
		);
	}
	
	static public function get_view_paths($app, $controller) {
		return array(
			self::get_project_views_app_controller_directory($app, $controller),
			self::get_project_views_app_directory($app),
			self::get_project_views_directory(),
			self::get_moojon_views_app_controller_directory($app, $controller),
			self::get_moojon_views_app_directory($app),
			self::get_moojon_views_directory()
		);
	}
	
	static public function get_helper_paths() {
		return array(
			self::get_project_helpers_directory(),
			self::get_moojon_helpers_directory()
		);
	}
	
	static private function get_path($paths, $file) {
		foreach ($paths as $path) {
			if (file_exists("$path$file")) {
				return "$path$file";
			}
		}
		return false;
	}
	
	static public function get_class_path($class) {
		return self::get_path(self::get_class_paths(), str_replace('_', '.', $class).'.class.php');
	}
	
	static public function get_model_path($model) {
		return self::get_path(self::get_model_paths(), "$model.model.class.php");
	}
	
	static public function get_base_model_path($model) {
		return self::get_path(self::get_base_model_paths(), 'base.'.moojon_base_model::strip_base($model).'.model.class.php');
	}
	
	static public function get_interface_path($interface) {
		return self::get_path(self::get_interface_paths(), str_replace('_', '.', $interface).'.interface.php');
	}
	
	static public function get_app_path($app) {
		return self::get_path(self::get_app_paths(), "$app.app.class.php");
	}
	
	static public function get_controller_path($app, $controller) {
		return self::get_path(self::get_controller_paths($app), "$controller.controller.class.php");
	}
	
	static public function get_layout_path($layout) {
		return self::get_path(self::get_layout_paths(), "$layout.layout.php");
	}
	
	static public function get_view_path($app, $controller, $view) {
		return self::get_path(self::get_view_paths($app, $controller), "$view.view.php");
	}
	
	static public function get_helper_path($helper) {
		return self::get_path(self::get_helper_paths(), "$helper.helper.php");
	}
}
?>