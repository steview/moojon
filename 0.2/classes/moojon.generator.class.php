<?php
final class moojon_generator extends moojon_base {
	public function __construct() {}
	
	static private function run($template, $destination, $swaps, $overwrite, $exit) {
		if ($overwrite || self::check_file($destination, $exit)) {
			self::write($template, $destination, $swaps);
		}
	}
	
	static private function write($template, $destination, $swaps) {
		if (!$handle = fopen($template, 'r')) {
			fclose($handle);
			throw new moojon_exception("Unable to open template file for reading ($template)");
		}
		$template = fread($handle, filesize($template));
		fclose($handle);
		if (!$handle = fopen($destination, 'w')) {
			fclose($handle);
			throw new moojon_exception("Unable to open destination file for writing ($destination)");
		}
		if (!fwrite($handle, self::swap_out($template, $swaps, '<[', ']>'))) {
			fclose($handle);
			throw new moojon_exception("Unable to write destination file ($destination)");
		}
		chmod($destination, 0755);
		echo "Creating file ($destination)\n";
		fclose($handle);
	}
	
	static public function swap_out($text, $swaps, $begin = null, $end = null) {
		if (!is_array($swaps)) {
			$swaps = array($swaps);
		}
		foreach ($swaps as $key => $value) {
			$text = str_replace("$begin$key$end", $value, $text);
		}
		return $text;
	}
	
	static final private function check_directory($path, $exit = null) {
		if (is_dir($path)) {
			if ($exit) {
				throw new moojon_exception("Directory already exists ($path)");
			}
			return false;
		} else {
			return true;
		}
	}
	
	static final private function check_file($path, $exit = null) {
		if (file_exists($path)) {
			if ($exit) {
				throw new moojon_exception("File already exists ($path)");
			}
			return false;
		} else {
			return true;
		}
	}
	
	static public function project($project, $app, $controller, $action) {
		self::try_define('PWD', moojon_server::key('PWD'));
		$project_directory = PWD."/$project/";
		self::check_directory($project_directory, true);
		self::try_define('PROJECT_DIRECTORY', $project_directory);
		self::try_define('APP', $app);
		self::try_define('CONTROLLER', $controller);
		self::try_define('ACTION', $action);
		moojon_paths:attempt_mkdir(PROJECT_DIRECTORY);
		moojon_paths:attempt_mkdir(moojon_paths::get_project_models_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_base_models_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_migrations_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_layouts_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_controllers_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_controllers_app_directory(APP));
		moojon_paths:attempt_mkdir(moojon_paths::get_project_apps_directory());
		$project_config_directory = moojon_paths::get_project_config_directory();
		moojon_paths:attempt_mkdir($project_config_directory);
		moojon_paths:attempt_mkdir(moojon_paths::get_project_helpers_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_public_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_images_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_css_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_js_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_script_directory());
		self::run(moojon_paths::get_moojon_templates_directory().'constants.template', $project_config_directory.'constants.php', array('PWD' => PWD.'/'.dirname(moojon_server::key('SCRIPT_FILENAME')).'/', 'MOOJON_VERSION' => MOOJON_VERSION), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'routes.template', $project_config_directory.'routes.php', array('default_app' => APP, 'default_controller' => CONTROLLER, 'default_action' => ACTION), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'index.template', moojon_paths::get_public_directory().'index.php', array(), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'generate.template', moojon_paths::get_script_directory().'generate',  array(), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'migrate.template', moojon_paths::get_script_directory().'migrate', array(), true, false);
		self::run(moojon_paths::get_moojon_templates_images_directory().'logo.png', moojon_paths::get_images_directory().'logo.png', array(), true, false);
		self::run(moojon_paths::get_moojon_templates_css_directory().'moojon.css', moojon_paths::get_css_directory().'moojon.css', array(), true, false);
		self::run(moojon_paths::get_moojon_templates_js_directory().'project.js', moojon_paths::get_js_directory().'project.js', array(), true, false);
		self::app(APP, $controller, $action);
	}
	
	static public function model($table) {
		moojon_paths:attempt_mkdir(moojon_paths::get_project_models_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_base_models_directory());
		$model = moojon_inflect::singularize($table);
		$swaps = array('model' => $model);
		$model_path = moojon_paths::get_project_models_directory()."$model.model.class.php";
		self::run(moojon_paths::get_moojon_templates_directory().'model.template', $model_path, $swaps, false, false);
		$swaps['columns'] = moojon_db_driver::get_add_columns($table);
		$swaps['read_all_bys'] = moojon_db_driver::get_read_all_bys($table);
		$swaps['read_bys'] = moojon_db_driver::get_read_bys($table);
		$swaps['destroy_bys'] = moojon_db_driver::get_destroy_bys($table);
		$swaps['read_or_create_bys'] = moojon_db_driver::get_read_or_create_bys($table);
		self::run(moojon_paths::get_moojon_templates_directory().'base.model.template', moojon_paths::get_project_base_models_directory()."base.$model.model.class.php", $swaps, true, false);
	}
	
	static public function helper($helper) {
		moojon_paths:attempt_mkdir(moojon_paths::get_project_helpers_directory());
		self::run(moojon_paths::get_moojon_templates_directory().'helper.template', moojon_paths::get_project_helpers_directory()."$helper.helper.php", array(), false, true);
	}
	
	static public function models() {
		moojon_paths:attempt_mkdir(moojon_paths::get_project_models_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_base_models_directory());
		foreach (moojon_db::show_tables() as $table) {
			self::model($table);
		}
	}
	
	static public function migration($migration) {
		$filename = date('YmdHis').".$migration.migration.class.php";
		moojon_paths:attempt_mkdir(moojon_paths::get_project_models_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_migrations_directory());
		self::run(moojon_paths::get_moojon_templates_directory().'migration.template', moojon_paths::get_project_migrations_directory()."$filename", array('migration' => $migration), false, true);
	}
	
	static public function app($app, $controller = null, $action = null) {
		self::try_define('APP', $app);
		self::try_define('CONTROLLER', $controller);
		self::try_define('ACTION', $action);
		moojon_paths:attempt_mkdir(moojon_paths::get_project_apps_directory());
		$project_app_config_directory = moojon_paths::get_project_app_config_directory(APP);
		moojon_paths:attempt_mkdir($project_app_config_directory);
		self::run(moojon_paths::get_moojon_templates_directory().'app.template', moojon_paths::get_project_apps_directory().APP.'.app.class.php', array('app' => APP), false, true);
		self::run(moojon_paths::get_moojon_templates_directory().'project.config.template', moojon_paths::get_project_config_directory().'development.config.php', array('default_app' => APP, 'default_controller' => CONTROLLER, 'default_action' => ACTION), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'project.config.template', moojon_paths::get_project_config_directory().'testing.config.php', array('default_app' => APP, 'default_controller' => CONTROLLER, 'default_action' => ACTION), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'project.config.template', moojon_paths::get_project_config_directory().'production.config.php', array('default_app' => APP, 'default_controller' => CONTROLLER, 'default_action' => ACTION), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'app.config.template', $project_app_config_directory.'development.config.php', array(), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'app.config.template', $project_app_config_directory.'testing.config.php', array(), true, false);
		self::run(moojon_paths::get_moojon_templates_directory().'app.config.template', $project_app_config_directory.'production.config.php', array(), true, false);
		if ($controller) {
			self::controller(APP, $controller, $action);
		}
		self::layout(APP);
	}
	
	static public function controller($app, $controller = null, $action = null) {
		self::try_define('APP', $app);
		if (!$controller) {
			$controller = moojon_config::key('default_controller');
		}
		moojon_paths:attempt_mkdir(moojon_paths::get_project_controllers_app_directory(APP));
		self::run(moojon_paths::get_moojon_templates_directory().'controller.template', moojon_paths::get_project_controllers_app_directory(APP)."$controller.controller.class.php", array('controller' => $controller), false, true);
		if ($action) {
			self::view(APP, $controller, $action);
		}
	}
	
	static public function view($app, $controller = null, $view = null) {
		self::try_define('APP', $app);
		if (!$controller) {
			$controller = moojon_config::key('default_controller');
		}
		self::try_define('CONTROLLER', $controller);
		if (!$view) {
			$view = moojon_config::key('default_action');
		}
		moojon_paths:attempt_mkdir(moojon_paths::get_project_views_directory());
		moojon_paths:attempt_mkdir(moojon_paths::get_project_views_app_directory(APP));
		moojon_paths:attempt_mkdir(moojon_paths::get_project_views_app_controller_directory(APP, CONTROLLER));
		self::run(moojon_paths::get_moojon_templates_directory().'view.template', moojon_paths::get_project_views_app_controller_directory(APP, CONTROLLER)."$view.view.php", array(), false, true);
	}
	
	static public function partial($app, $controller, $partial) {
		self::try_define('APP', $app);
		self::try_define('CONTROLLER', $controller);
		moojon_paths:attempt_mkdir(moojon_paths::get_project_views_app_controller_directory(APP, CONTROLLER));
		self::run(moojon_paths::get_moojon_templates_directory().'partial.template', moojon_paths::get_project_views_app_controller_directory(APP, CONTROLLER).'_'.$partial.'.php', array(), false, true);
	}
	
	static public function layout($layout) {
		moojon_paths:attempt_mkdir(moojon_paths::get_project_layouts_directory());
		self::run(moojon_paths::get_moojon_templates_directory().'layout.template', moojon_paths::get_project_layouts_directory()."$layout.layout.php", array(), false, true);
	}
	
	static public function scaffold($app, $model, $controller) {
		self::try_define('APP', $app);
		self::try_define('CONTROLLER', $controller);
		if (moojon_routes::has_rest_route($model)) {
			$route = moojon_routes::get_rest_route($model);
			if (APP != $route->get_app()) {
				throw new moojon_exception("Scaffold app & route app must be the same (".APP.' != '.$route->get_app().")");
			}
			$id_property = $route->get_id_property();
		} else {
			$id_property = moojon_primary_key::NAME;
			self::add_route("new moojon_rest_route('$model', array('app' => '".APP."')),");
		}
		$swaps = array();
		$swaps['plural'] = moojon_inflect::pluralize($model);
		$swaps['singular'] = moojon_inflect::singularize($model);
		$swaps['Human'] = str_replace('_', ' ', ucfirst(moojon_inflect::singularize($model)));
		$swaps['human'] = str_replace('_', ' ', moojon_inflect::singularize($model));
		$swaps['Humans'] = str_replace('_', ' ', ucfirst(moojon_inflect::pluralize($model)));
		$swaps['humans'] = str_replace('_', ' ', moojon_inflect::pluralize($model));
		$swaps['id_property'] = $id_property;
		$views_path = moojon_paths::get_project_views_app_controller_directory(APP, CONTROLLER);
		moojon_paths:attempt_mkdir($views_path);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'controller.template', moojon_paths::get_project_controllers_app_directory(APP, CONTROLLER)."$controller.controller.class.php", $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'_delete_form.template', $views_path.'_delete_form.php', $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'_dl.template', $views_path.'_dl.php', $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'_form.template', $views_path.'_form.php', $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'_table.template', $views_path.'_table.php', $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'new.view.template', $views_path.'new.view.php', $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'delete.view.template', $views_path.'delete.view.php', $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'index.view.template', $views_path.'index.view.php', $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'show.view.template', $views_path.'show.view.php', $swaps, false, true);
		self::run(moojon_paths::get_moojon_templates_scaffolds_directory().'edit.view.template', $views_path.'edit.view.php', $swaps, false, true);
	}
	
	
	static public function add_route($route) {
		$routes_path = moojon_paths::get_routes_path();
		$read_file_handle = fopen($routes_path, 'r');
		$routes = '';
		while ($line = fgets($read_file_handle)) {
			$routes .= "$line";
			if (preg_match("/return\s+array\s*\(/", $line)) {
				$routes .= "\t$route\n";
			}
		}
		fclose($read_file_handle);
		$write_file_handle = fopen($routes_path, 'w');
		fwrite($write_file_handle, $routes);
		fclose($write_file_handle);
		echo "Adding route ($route)\n";
	}
}
?>