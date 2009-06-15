<?php
final class moojon_paths extends moojon_base {
	static private $instance;
	
	private $project_directory;
	
	private function __construct() {
		$this->project_directory = PROJECT_DIRECTORY;
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	static public function __get($key) {
		if (substr($key, 0, 4) != 'get_') {
			$key = "get_$key";
		}
		$instance = self::get();
		if (in_array($key, get_class_methods('self'))) {
			return $instance->$key();
		} else {
			throw new moojon_exception('Unknown path property ($key)');
		}
	}
	
	static public function get_project_directory() {
		return PROJECT_DIRECTORY;
	}
	
	static public function get_library_directory() {
		return self::get_project_directory().moojon_config::get('library_directory').'/';
	}
	
	static public function get_vendor_directory() {
		return self::get_project_directory().moojon_config::get('vendor_directory').'/';
	}
	
	static public function get_moojon_directory() {
		return MOOJON_PATH;
	}
	
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
	
	static public function get_moojon_migrations_directory() {
		return self::get_moojon_models_directory().moojon_config::get('migrations_directory').'/';
	}
	
	static public function get_project_config_directory() {
		return self::get_project_directory().'config/';
	}
	
	static public function get_app_config_directory($app) {
		return self::get_app_directory($app).'config/';
	}
	
	static public function get_library_config_directory() {
		return self::get_library_directory().'config/';
	}
	
	static public function get_vendor_config_directory() {
		return self::get_vendor_directory().'config/';
	}
	
	static public function get_moojon_config_directory() {
		return self::get_moojon_directory().'config/';
	}
	
	static public function get_apps_directory() {
		return self::get_project_directory().moojon_config::get('apps_directory').'/';
	}
	
	static public function get_library_apps_directory() {
		return self::get_library_directory().moojon_config::get('apps_directory').'/';
	}
	
	static public function get_vendor_apps_directory() {
		return self::get_vendor_directory().moojon_config::get('apps_directory').'/';
	}
	
	static public function get_moojon_apps_directory() {
		return self::get_moojon_directory().moojon_config::get('apps_directory').'/';
	}
	
	static public function get_app_directory($app) {
		return self::get_apps_directory()."$app/";
	}
	
	static public function get_library_app_directory($app) {
		return self::get_library_apps_directory()."$app/";
	}
	
	static public function get_vendor_app_directory($app) {
		return self::get_vendor_apps_directory()."$app/";
	}
	
	static public function get_moojon_app_directory($app) {
		return self::get_moojon_apps_directory()."$app/";
	}
	
	static public function get_controllers_directory($app) {
		return self::get_app_directory($app).moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_library_controllers_directory($app) {
		return self::get_library_app_directory($app).moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_vendor_controllers_directory($app) {
		return self::get_vendor_app_directory($app).moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_moojon_controllers_directory($app) {
		return self::get_moojon_app_directory($app).moojon_config::get('controllers_directory').'/';
	}
	
	static public function get_controller_views_directory($controller, $app) {
		return self::get_app_directory($app).moojon_config::get('views_directory')."/$controller/";
	}
	
	static public function get_views_directory($app) {
		return self::get_app_directory($app).moojon_config::get('views_directory');
	}
	
	static public function get_library_controller_views_directory($controller, $app) {
		return self::get_library_app_directory($app).moojon_config::get('views_directory')."/$controller/";
	}
	
	static public function get_library_views_directory($app) {
		return self::get_library_app_directory($app).moojon_config::get('views_directory');
	}
	
	static public function get_vendor_controller_views_directory($controller, $app) {
		return self::get_vendor_app_directory($app).moojon_config::get('views_directory')."/$controller/";
	}
	
	static public function get_vendor_views_directory($controller, $app) {
		return self::get_vendor_app_directory($app).moojon_config::get('views_directory')."/$controller/";
	}
	
	static public function get_moojon_controller_views_directory($controller, $app) {
		return self::get_moojon_app_directory($app).moojon_config::get('views_directory')."/$controller/";
	}
	
	static public function get_moojon_views_directory($controller, $app) {
		return self::get_moojon_app_directory($app).moojon_config::get('views_directory')."/$controller/";
	}
	
	static public function get_layouts_directory() {
		return get_project_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_library_layouts_directory() {
		return self::get_library_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_vendor_layouts_directory() {
		return self::get_vendor_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_moojon_layouts_directory() {
		return self::get_moojon_directory().moojon_config::get('layouts_directory').'/';
	}
	
	static public function get_helpers_directory() {
		return self::get_project_directory().moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_library_helpers_directory() {
		return self::get_library_directory().moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_vendor_helpers_directory() {
		return self::get_vendor_directory().moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_moojon_helpers_directory() {
		return MOOJON_PATH.moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_models_directory() {
		return self::get_project_directory().moojon_config::get('models_directory').'/';
	}
	
	static public function get_moojon_models_directory() {
		return self::get_moojon_directory().moojon_config::get('models_directory').'/';
	}
	
	static public function get_base_models_directory() {
		return self::get_models_directory().moojon_config::get('base_models_directory').'/';
	}
	
	static public function get_moojon_base_models_directory() {
		return self::get_moojon_models_directory().moojon_config::get('base_models_directory').'/';
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
	
	static public function get_app_path($app) {
		$paths = array(
			self::get_apps_directory()."$app/$app.app.class.php",
			self::get_library_apps_directory()."$app/$app.app.class.php",
			self::get_vendor_apps_directory()."$app/$app.app.class.php",
			self::get_moojon_apps_directory()."$app/$app.app.class.php",
		);
		foreach ($paths as $path) {
			if (file_exists($path)) {
				return $path;
			}
		}
		throw new moojon_exception("404 app not found ($app)");
	}
	
	static public function get_controller_path($controller, $app) {
		$paths = array(
			self::get_controllers_directory($app)."$controller.controller.class.php",
			self::get_library_controllers_directory($app)."$controller.controller.class.php",
			self::get_vendor_controllers_directory($app)."$controller.controller.class.php",
			self::get_moojon_controllers_directory($app)."$controller.controller.class.php"
		);
		foreach ($paths as $path) {
			if (file_exists($path)) {
				return $path;
			}
		}
		throw new moojon_exception("404 controller not found ($controller)");
	}
	
	static public function get_layout_path($layout) {
		$paths = array(
			self::get_layouts_directory().$layout,
			self::get_library_layouts_directory().$layout,
			self::get_vendor_layouts_directory().$layout,
			self::get_moojon_layouts_directory().$layout
		);
		foreach ($paths as $path) {
			if (file_exists($path)) {
				return $path;
			}
		}
		throw new moojon_exception("404 layout not found ($layout)");
	}
	
	static public function get_view_path($view) {
		$paths = array(
			self::get_controller_views_directory(moojon_uri::get_controller(), moojon_uri::get_app()).$view,
			self::get_views_directory(moojon_uri::get_app()).$view,
			self::get_library_controller_views_directory(moojon_uri::get_controller(), moojon_uri::get_app()).$view,
			self::get_library_views_directory().$view,
			self::get_vendor_controller_views_directory().$view,
			self::get_vendor_views_directory().$view,
			self::get_moojon_controller_views_directory().$view,
			self::get_moojon_views_directory().$view
		);
		foreach ($paths as $path) {
			if (file_exists($path)) {
				return $path;
			}
		}
		throw new moojon_exception("404 view not found ($view)");
	}
	
	static public function get_partial_path($partial) {
		$paths = array(
			
		);
		foreach ($paths as $path) {
			if (file_exists($path)) {
				return $path;
			}
		}
		throw new moojon_exception("404 partial not found ($partial)");
	}
	
	static public function get_helper_path($helper) {
		$paths = array(
			
		);
		foreach ($paths as $path) {
			if (file_exists($path)) {
				return $path;
			}
		}
		throw new moojon_exception("404 helper not found ($helper)");
	}
}
?>