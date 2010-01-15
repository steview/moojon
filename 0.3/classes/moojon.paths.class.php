<?php
final class moojon_paths extends moojon_base {
	private function __construct() {}
	
	static public function get_classes_directory() {
		return self::get_moojon_directory().moojon_config::get('classes_directory').'/';
	}
	
	static public function get_interfaces_directory() {
		return self::get_moojon_directory().moojon_config::get('interfaces_directory').'/';
	}
	
	static public function get_db_drivers_directory() {
		return self::get_classes_directory().moojon_config::get('db_drivers_directory').'/';
	}
	
	static public function get_db_driver_directory() {
		return self::get_db_drivers_directory().moojon_config::get('db_driver').'/';
	}
	
	static public function get_columns_directory() {
		return self::get_classes_directory().moojon_config::get('columns_directory').'/';
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
		return MOOJON_DIRECTORY;
	}
	
	static public function get_moojon_templates_directory() {
		return self::get_moojon_directory().moojon_config::get('templates_directory').'/';
	}
	
	static public function get_moojon_templates_scaffolds_directory() {
		return self::get_moojon_templates_directory().moojon_config::get('scaffolds_directory').'/';
	}
	
	static public function get_moojon_templates_images_directory() {
		return self::get_moojon_templates_directory().moojon_config::get('images_directory').'/';
	}
	
	static public function get_moojon_templates_css_directory() {
		return self::get_moojon_templates_directory().moojon_config::get('css_directory').'/';
	}
	
	static public function get_moojon_templates_js_directory() {
		return self::get_moojon_templates_directory().moojon_config::get('js_directory').'/';
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
	
	static public function get_moojon_views_app_controller_directory($app, $controller) {
		return self::get_moojon_views_app_directory($app)."$controller/";
	}
	
	static public function get_moojon_views_app_directory($app) {
		return self::get_moojon_views_directory()."$app/";
	}
	
	static public function get_moojon_views_directory() {
		return self::get_moojon_directory().moojon_config::get('views_directory').'/';
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
		return MOOJON_DIRECTORY.moojon_config::get('helpers_directory').'/';
	}
	
	static public function get_project_directory() {
		return PROJECT_DIRECTORY;
	}
	
	static public function get_project_library_directory() {
		return self::get_project_directory().moojon_config::get('library_directory').'/';
	}
	
	static public function get_project_pluggins_directory() {
		return self::get_project_directory().moojon_config::get('pluggins_directory').'/';
	}
	
	static public function get_project_base_models_directory() {
		return self::get_project_models_directory().moojon_config::get('base_models_directory').'/';
	}
	
	static public function get_project_models_directory() {
		return self::get_project_directory().moojon_config::get('models_directory').'/';
	}
	
	static public function get_project_cache_directory() {
		return self::get_project_directory().moojon_config::get('cache_directory').'/';
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
	
	static public function get_project_config_environment_directory($environment) {
		return self::get_project_config_directory()."$environment/";
	}
	
	static public function get_project_config_environment_app_directory($environment, $app) {
		return self::get_project_config_environment_directory($environment)."$app/";
	}
	
	static public function get_moojon_config_directory() {
		return self::get_moojon_directory().'config/';
	}
	
	static public function get_moojon_config_app_directory($app) {
		return self::get_moojon_config_directory()."$app/";
	}
	
	
	
	static public function get_routes_path() {
		return self::get_project_config_directory().'/routes.php';
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
	
	static public function get_uploads_directory() {
		return self::get_public_directory().moojon_config::get('uploads_directory').'/';
	}
	
	static public function get_public_images_directory() {
		return '/'.moojon_config::get('images_directory').'/';
	}
	
	static public function get_public_css_directory() {
		return '/'.moojon_config::get('css_directory').'/';
	}
	
	static public function get_public_js_directory() {
		return '/'.moojon_config::get('js_directory').'/';
	}
	
	static public function get_public_upload_path() {
		return '/'.moojon_config::get('uploads_directory').'/';
	}
	
	static public function get_model_upload_directory(moojon_base_model $model, $public = false) {
		$class = moojon_inflect::pluralize(get_class($model));
		$id_column = moojon_primary_key::NAME;
		$root = (!$public) ? self::get_uploads_directory() : self::get_public_upload_path();
		return "$root$class/".$model->$id_column.'/';
	}
	
	static public function get_public_model_upload_directory(moojon_base_model $model) {
		return self::get_model_upload_directory($model, true);
	}
	
	static public function get_column_upload_directory(moojon_base_model $model, $column_name, $public = false) {
		return self::get_model_upload_directory($model, $public)."$column_name/";
	}
	
	static public function get_public_column_upload_directory(moojon_base_model $model, $column_name, $public = false) {
		return self::get_column_upload_directory($model, $column_name, true);
	}
	
	static public function get_column_upload_path(moojon_base_model $model, $column_name, $public = false) {
		$return =  $model->$column_name;
		if (is_file($return)) {
			return $return;
		} else if (is_file(self::get_uploads_directory().$return)) {
			$root = (!$public) ? self::get_uploads_directory() : self::get_public_upload_path();
			return "$root/$return";
		} else {
			$return = self::get_column_upload_directory($model, $column_name, $public).$return;
			return $return;
		}
	}
	
	static public function get_public_column_upload_path(moojon_base_model $model, $column_name) {
		return self::get_column_upload_path($model, $column_name, true);
	}
	
	static public function get_column_upload_paths(moojon_base_model $model, $paths = array(), $exceptions = array()) {
		$return = array();
		$columns = moojon_request::get(get_class($model));
		foreach ($model->get_file_column_names($exceptions) as $column_name) {
			if (moojon_files::has($column_name, $columns) && $model->$column_name) {
				$column = moojon_files::get($column_name, $columns);
				if (array_key_exists($column_name, $paths)) {
					$value = $paths[$column_name];
				} else {
					$value = self::get_column_upload_path($model, $column_name);
				}
				if ($column->get_error() == UPLOAD_ERR_OK) {
					$return[$column_name] = $value;
				}
			}
		}
		return $return;
	}
	
	static public function get_class_paths() {
		$paths = array();
		if (defined('PROJECT_DIRECTORY')) {
			$library_directory = self::get_project_library_directory();
			if (is_dir($library_directory)) {
				foreach (moojon_files::directory_directories($library_directory, false, true) as $directory) {
					$paths[] = $directory;
					$paths = array_merge($paths, moojon_files::directory_directories($directory, true, true));
				}
			}
			$pluggins_directory = self::get_project_pluggins_directory();
			if (is_dir($pluggins_directory)) {
				foreach (moojon_files::directory_directories($pluggins_directory, false, true) as $directory) {
					$paths[] = $directory;
					$paths = array_merge($paths, moojon_files::directory_directories($directory, true, true));
				}
			}
			if (moojon_config::has('db_driver')) {
				$paths[] = self::get_db_driver_directory();
				$paths[] = self::get_columns_directory();
			}
			$paths[] = self::get_project_migrations_directory();
			$paths[] = self::get_moojon_migrations_directory();
		}
		$paths[] = self::get_classes_directory();
		$paths[] = self::get_validations_directory();
		$paths[] = self::get_tags_directory();
		$paths[] = self::get_tag_attributes_directory();
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
	
	static public function get_column_paths() {
		$paths = array();
		if (moojon_config::has('db_driver')) {
			$paths[] = self::get_columns_directory();
		}
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
		return self::get_path(self::get_base_model_paths(), 'base.'.self::strip_base($model).'.model.class.php');
	}
	
	static public function get_column_path($column) {
		return self::get_path(self::get_column_paths(), str_replace('_', '.', $column).'.column.class.php');
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
	
	static public function get_partial_path($app, $controller, $partial) {
		return self::get_path(self::get_view_paths($app, $controller), "_$partial.php");
	}
	
	static public function get_helper_path($helper) {
		return self::get_path(self::get_helper_paths(), "$helper.helper.php");
	}

	static public function get_cache_path($uri) {
		return self::get_project_cache_directory()."$uri/cache";
	}
	
	static public function get_image_path($path) {
		if (is_file(self::get_images_directory().$path)) {
			return self::get_images_directory().$path;
		} else if (is_file(self::get_public_directory().$path)) {
			return self::get_public_directory().$path;
		} else {
			return $path;
		}
	}
	
	static public function get_public_image_path($path) {
		if (is_file(self::get_public_images_directory().$path)) {
			return self::get_public_images_directory().$path;
		} else {
			return "$path";
		}
	}
}
?>