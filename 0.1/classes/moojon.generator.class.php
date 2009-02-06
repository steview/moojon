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
		self::try_define('PROJECT_PATH', $_SERVER['PWD']."/$project/");
		self::attempt_mkdir(PROJECT_PATH);
		self::attempt_mkdir(moojon_config::get_apps_directory());
		self::attempt_mkdir(moojon_config::get_public_directory());
		self::attempt_mkdir(moojon_config::get_images_directory());
		self::attempt_mkdir(moojon_config::get_css_directory());
		self::attempt_mkdir(moojon_config::get_js_directory());
		self::run(MOOJON_PATH.'templates/index.template', moojon_config::get_public_directory().'index.php', array('MOOJON_VERSION' => MOOJON_VERSION, 'MOOJON_PATH' => MOOJON_PATH, 'PROJECT_PATH' => PROJECT_PATH), true, false);
		self::app($app, $controller, $action);
	}
	
	static public function model($table) {
		self::attempt_mkdir(moojon_config::get_models_directory());
		self::attempt_mkdir(moojon_config::get_base_models_directory());
		$model = moojon_inflect::singularize($table);
		$swaps = array('model' => $model);
		$model_path = moojon_config::get_models_directory()."$model.model.class.php";
		self::run(MOOJON_PATH.'/templates/model.template', $model_path, $swaps, false, false);
		$swaps['columns'] = moojon_adapter::get_add_columns($table);
		self::run(MOOJON_PATH.'templates/base.model.template', moojon_config::get_base_models_directory()."base.$model.model.class.php", $swaps, true, false);
	}
	
	static public function models() {
		self::attempt_mkdir(moojon_config::get_models_directory());
		self::attempt_mkdir(moojon_config::get_base_models_directory());
		foreach (moojon_adapter::list_tables() as $table) {
			self::model($table);
		}
	}
	
	static public function migration($migration) {
		$filename = date('YmdHis').".$migration.migration.class.php";
		self::attempt_mkdir(moojon_config::get_models_directory());
		self::attempt_mkdir(moojon_config::get_base_models_directory());
		self::attempt_mkdir(moojon_config::get_migrations_directory());
		self::run(MOOJON_PATH.'templates/migration.template', moojon_config::get_migrations_directory()."$filename", array('name' => $name), false, true);
	}
	
	static public function app($app, $controller = null, $action = null) {
		self::try_define('APP', $app);
		self::attempt_mkdir(moojon_config::get_app_directory());
		self::run(MOOJON_PATH.'templates/app.template', moojon_config::get_app_directory()."$app.app.class.php", array('app' => $app), false, true);
		if ($controller != null) {
			self::controller($app, $controller, $action);
		}
		self::layout($app);
	}
	
	static public function controller($app, $controller = null, $action = null) {
		if ($controller == null) {
			$controller = moojon_config::get_default_controller();
		}
		self::attempt_mkdir(moojon_config::get_controllers_directory());
		self::run(MOOJON_PATH.'templates/controller.template', moojon_config::get_controllers_directory()."$controller.controller.class.php", array('controller' => $controller), false, true);
		if ($action) {
			self::view($app, $action);
		}
	}
	
	static public function view($app, $view = null) {
		if ($view == null) {
			$view = moojon_config::get_default_action();
		}
		self::attempt_mkdir(moojon_config::get_views_directory());
		self::run(MOOJON_PATH.'templates/view.template', moojon_config::get_views_directory()."$view.view.class.php", array(), false, true);
	}
	
	static public function layout($app, $layout = null) {
		if ($layout == null) {
			$layout = $app;
		}
		self::attempt_mkdir(moojon_config::get_layouts_directory());
		self::run(MOOJON_PATH.'templates/layout.template', moojon_config::get_layouts_directory()."$layout.layout.class.php", array(), false, true);
	}
	
	static public function test($test) {}
}
?>