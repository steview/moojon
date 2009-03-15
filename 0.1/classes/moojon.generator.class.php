<?php
final class moojon_generator extends moojon_base {	
	public function __construct() {}
	
	static private function run($template, $destination, $swaps, $overwrite, $exit) {
		if ($overwrite == true || self::check_file($destination, $exit) == true) {
			self::write($template, $destination, $swaps);
		}
	}
	
	static private function write($template, $destination, $swaps) {
		if (!$handle = fopen($template, 'r')) {
			fclose($handle);
			self::handle_error("Unable to open template file for reading ($template)");
		}
		$template = fread($handle, filesize($template));
		fclose($handle);
		if (!$handle = fopen($destination, 'w')) {
			fclose($handle);
			self::handle_error("Unable to open destination file for writing ($destination)");
		}
		if (fwrite($handle, self::swap_out($template, $swaps, '<[', ']>')) === false) {
			fclose($handle);
			self::handle_error("Unable to write destination file ($destination)");
		}
		chmod($destination, 0755);
		echo "Creating file ($destination)".moojon_base::new_line();
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
	
	static final protected function attempt_mkdir($path, $mode = null) {
		if ($mode == null) {
			$mode = 0755;
		}
		if (is_dir($path) == false) {
			mkdir($path, $mode, true);
			echo "Creating directory ($path)".moojon_base::new_line();
		}
	}	
	
	static final private function check_directory($path, $exit = null) {
		if (is_dir($path)) {
			if ($exit === true) {
				self::handle_error("Directory already exists ($path)");
			}
			return false;
		} else {
			return true;
		}
	}
	
	static final private function check_file($path, $exit = null) {
		if (file_exists($path)) {
			if ($exit === true) {
				self::handle_error("File already exists ($path)");
			}
			return false;
		} else {
			return true;
		}
	}
	
	static public function project($project, $app, $controller, $action) {
		self::check_directory($_SERVER['PWD']."/$project/", true);
		self::try_define('PROJECT_DIRECTORY', $_SERVER['PWD']."/$project/");
		self::try_define('APP', $app);
		self::attempt_mkdir(PROJECT_DIRECTORY);
		self::attempt_mkdir(moojon_paths::get_apps_directory());
		self::attempt_mkdir(moojon_paths::get_project_config_directory());
		self::attempt_mkdir(moojon_paths::get_app_config_directory());
		self::attempt_mkdir(moojon_paths::get_public_directory());
		self::attempt_mkdir(moojon_paths::get_images_directory());
		self::attempt_mkdir(moojon_paths::get_css_directory());
		self::attempt_mkdir(moojon_paths::get_js_directory());
		self::attempt_mkdir(moojon_paths::get_script_directory());
		self::run(MOOJON_PATH.'templates/project.config.template', moojon_paths::get_project_config_directory().'project.config.php', array('default_app' => $app), true, false);
		self::run(MOOJON_PATH.'templates/app.config.template', moojon_paths::get_app_config_directory()."$app.config.php", array('default_controller' => $controller, 'default_action' => $action), true, false);
		self::run(MOOJON_PATH.'templates/index.template', moojon_paths::get_public_directory().'index.php', array('MOOJON_VERSION' => MOOJON_VERSION, 'MOOJON_PATH' => MOOJON_PATH, 'PROJECT_DIRECTORY' => PROJECT_DIRECTORY), true, false);
		self::run(MOOJON_PATH.'templates/generate.template', moojon_paths::get_script_directory().'generate', array('MOOJON_VERSION' => MOOJON_VERSION, 'MOOJON_PATH' => MOOJON_PATH, 'PROJECT_DIRECTORY' => PROJECT_DIRECTORY), true, false);
		self::run(MOOJON_PATH.'templates/migrate.template', moojon_paths::get_script_directory().'migrate', array('MOOJON_VERSION' => MOOJON_VERSION, 'MOOJON_PATH' => MOOJON_PATH, 'PROJECT_DIRECTORY' => PROJECT_DIRECTORY), true, false);		
		self::run(MOOJON_PATH.'templates/images/dot.gif', moojon_paths::get_images_directory().'dot.gif', array(), true, false);
		self::run(MOOJON_PATH.'templates/images/logo.gif', moojon_paths::get_images_directory().'logo.gif', array(), true, false);		
		self::run(MOOJON_PATH.'templates/css/core.css', moojon_paths::get_css_directory().'core.css', array(), true, false);
		self::run(MOOJON_PATH.'templates/css/form.css', moojon_paths::get_css_directory().'form.css', array(), true, false);
		self::run(MOOJON_PATH.'templates/css/layout.css', moojon_paths::get_css_directory().'layout.css', array(), true, false);
		self::run(MOOJON_PATH.'templates/css/print.css', moojon_paths::get_css_directory().'print.css', array(), true, false);
		self::run(MOOJON_PATH.'templates/js/jquery.flash.js', moojon_paths::get_js_directory().'jquery.flash.js', array(), true, false);
		self::run(MOOJON_PATH.'templates/js/jquery.js', moojon_paths::get_js_directory().'jquery.js', array(), true, false);
		self::run(MOOJON_PATH.'templates/js/project.js', moojon_paths::get_js_directory().'project.js', array(), true, false);
		self::app($app, $controller, $action);
	}
	
	static public function model($table) {
		self::attempt_mkdir(moojon_paths::get_models_directory());
		self::attempt_mkdir(moojon_paths::get_base_models_directory());
		$model = moojon_inflect::singularize($table);
		$swaps = array('model' => $model);
		$model_path = moojon_paths::get_models_directory()."$model.model.class.php";
		self::run(MOOJON_PATH.'templates/model.template', $model_path, $swaps, false, false);
		$swaps['columns'] = moojon_adapter::get_add_columns($table);
		self::run(MOOJON_PATH.'templates/base.model.template', moojon_paths::get_base_models_directory()."base.$model.model.class.php", $swaps, true, false);
	}
	
	static public function helper($helper) {
		self::attempt_mkdir(moojon_paths::get_helpers_directory());
		self::run(MOOJON_PATH.'templates/helper.template', moojon_paths::get_helpers_directory()."$helper.helper.php", array(), false, true);
	}
	
	static public function models() {
		self::attempt_mkdir(moojon_paths::get_models_directory());
		self::attempt_mkdir(moojon_paths::get_base_models_directory());
		foreach (moojon_adapter::list_tables() as $table) {
			self::model($table);
		}
	}
	
	static public function migration($migration) {
		$filename = date('YmdHis').".$migration.migration.class.php";
		self::attempt_mkdir(moojon_paths::get_models_directory());
		self::attempt_mkdir(moojon_paths::get_migrations_directory());
		self::run(MOOJON_PATH.'templates/migration.template', moojon_paths::get_migrations_directory()."$filename", array('migration' => $migration), false, true);
	}
	
	static public function app($app, $controller = null, $action = null) {
		self::try_define('APP', $app);
		self::attempt_mkdir(moojon_paths::get_app_directory());
		self::run(MOOJON_PATH.'templates/app.template', moojon_paths::get_app_directory()."$app.app.class.php", array('app' => $app), false, true);
		if ($controller != null) {
			self::controller($app, $controller, $action);
		}
		self::layout($app);
	}
	
	static public function controller($app, $controller = null, $action = null) {
		self::try_define('APP', $app);
		if ($controller == null) {
			$controller = moojon_config::get('default_controller');
		}
		self::attempt_mkdir(moojon_paths::get_controllers_directory());
		self::run(MOOJON_PATH.'templates/controller.template', moojon_paths::get_controllers_directory()."$controller.controller.class.php", array('controller' => $controller), false, true);
		if ($action) {
			self::view($app, $controller, $action);
		}
	}
	
	static public function view($app, $controller = null, $view = null) {
		self::try_define('APP', $app);
		if ($controller == null) {
			$controller = moojon_config::get('default_controller');
		}
		self::try_define('CONTROLLER', $controller);
		if ($view == null) {
			$view = moojon_config::get('default_action');
		}
		self::attempt_mkdir(moojon_paths::get_views_directory());
		self::run(MOOJON_PATH.'templates/view.template', moojon_paths::get_views_directory()."$view.view.php", array(), false, true);
	}
	
	static public function partial($app, $controller = null, $partial) {
		self::try_define('APP', $app);
		if ($controller == null) {
			$controller = moojon_config::get('default_controller');
		}
		self::try_define('CONTROLLER', $controller);
		self::attempt_mkdir(moojon_paths::get_views_directory());
		self::run(MOOJON_PATH.'templates/partial.template', moojon_paths::get_views_directory().'_'.$partial.'.php', array(), false, true);
	}
	
	static public function layout($app, $layout = null) {
		self::try_define('APP', $app);
		if ($layout == null) {
			$layout = $app;
		}
		self::attempt_mkdir(moojon_paths::get_layouts_directory());
		self::run(MOOJON_PATH.'templates/layout.template', moojon_paths::get_layouts_directory()."$layout.layout.php", array(), false, true);
	}
	
	static public function shared_controller($controller = null) {
		if ($controller == null) {
			$controller = moojon_config::get('default_controller');
		}
		self::attempt_mkdir(moojon_paths::get_shared_controllers_directory());
		self::run(MOOJON_PATH.'templates/controller.template', moojon_paths::get_shared_controllers_directory()."$controller.controller.class.php", array('controller' => $controller), false, true);
	}
	
	static public function shared_view($view = null) {
		if ($view == null) {
			$view = moojon_config::get('default_action');
		}
		self::attempt_mkdir(moojon_paths::get_shared_views_directory());
		self::run(MOOJON_PATH.'templates/view.template', moojon_paths::get_shared_views_directory()."$view.view.php", array(), false, true);
	}
	
	static public function shared_partial($partial) {
		self::attempt_mkdir(moojon_paths::get_shared_views_directory());
		self::run(MOOJON_PATH.'templates/partial.template', moojon_paths::get_shared_views_directory().'_'.$partial.'.php', array(), false, true);
	}
	
	static public function shared_layout($layout = null) {
		if ($layout == null) {
			$layout = $app;
		}
		self::attempt_mkdir(moojon_paths::get_shared_layouts_directory());
		self::run(MOOJON_PATH.'templates/layout.template', moojon_paths::get_shared_layouts_directory()."$layout.layout.php", array(), false, true);
	}
	
	
	
	static public function config($config, $path) {
		self::attempt_mkdir($path);
		self::run(MOOJON_PATH.'templates/config.template', "$path$config.config.php", array(), false, true);
	}
	
	static public function scaffold($app, $model, $controller) {
		$swaps = array();
		$swaps['Plural'] = ucfirst(moojon_inflect::pluralize($model));
		$swaps['plural'] = moojon_inflect::pluralize($model);
		$swaps['Singular'] = ucfirst(moojon_inflect::singularize($model));
		$swaps['singular'] = moojon_inflect::singularize($model);
		$swaps['Human'] = str_replace('_', ' ', ucfirst(moojon_inflect::singularize($model)));
		$swaps['human'] = str_replace('_', ' ', moojon_inflect::singularize($model));
		$swaps['Humans'] = str_replace('_', ' ', ucfirst(moojon_inflect::pluralize($model)));
		$swaps['humans'] = str_replace('_', ' ', moojon_inflect::pluralize($model));
		$swaps['app'] = $app;
		$swaps['controller'] = $controller;
		self::try_define('APP', $app);
		self::try_define('CONTROLLER', $controller);
		$views_path = moojon_paths::get_views_directory();
		self::attempt_mkdir(moojon_paths::get_controllers_directory());
		self::attempt_mkdir($views_path);
		self::run(MOOJON_PATH.'templates/scaffold/controller.template', moojon_paths::get_controllers_directory()."$controller.controller.class.php", $swaps, false, true);
		self::run(MOOJON_PATH.'templates/scaffold/_destroy_form.template', $views_path.'_destroy_form.php', $swaps, false, true);
		self::run(MOOJON_PATH.'templates/scaffold/_dl.template', $views_path.'_dl.php', $swaps, false, true);
		self::run(MOOJON_PATH.'templates/scaffold/_form.template', $views_path.'_form.php', $swaps, false, true);
		self::run(MOOJON_PATH.'templates/scaffold/_table.template', $views_path.'_table.php', $swaps, false, true);		
		self::run(MOOJON_PATH.'templates/scaffold/create.view.template', $views_path.'create.view.php', $swaps, false, true);
		self::run(MOOJON_PATH.'templates/scaffold/destroy.view.template', $views_path.'destroy.view.php', $swaps, false, true);
		self::run(MOOJON_PATH.'templates/scaffold/index.view.template', $views_path.'index.view.php', $swaps, false, true);
		self::run(MOOJON_PATH.'templates/scaffold/read.view.template', $views_path.'read.view.php', $swaps, false, true);
		self::run(MOOJON_PATH.'templates/scaffold/update.view.template', $views_path.'update.view.php', $swaps, false, true);
	}
		
	static public function test($test) {}
}
?>