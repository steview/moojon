<?php
final class moojon_cli extends moojon_base_cli {
	public function __construct() {
		if ($_SERVER['argc'] < 2) {
			echo 'Moojon version: '.MOOJON_VERSION."\n";
		} else {
			$arguments = $_SERVER['argv'];
			array_shift($arguments);
			$this->generate_project($arguments);
		}
	}
	
	private function generate_project(Array $arguments) {
		self::check_arguments('generate_project', 3, $arguments);
		$project_name = array_shift($arguments);
		if (is_dir($_SERVER['PWD']."/$project_name")) {
			self::handle_error("Directory already exists ($project_name)");
		} else {
			if (count($arguments) < 1) {
				$arguments[] = $this->prompt('Please enter an app name', 'client');
				$arguments[] = $this->prompt('Please enter a controller name', 'index');
			}
			if (count($arguments) < 2) {
				$arguments[] = $this->prompt('Please enter an controller name', 'client');
			}
			define('PROJECT_PATH', $_SERVER['PWD']."/$project_name/");
			define('APP', $arguments[0]);
			define('CONTROLLER', $arguments[1]);
			$this->attempt_mkdir(PROJECT_PATH);
			$this->attempt_mkdir(moojon_config::get_apps_directory());
			$this->attempt_mkdir(moojon_config::get_app_directory());
			$this->attempt_mkdir(moojon_config::get_controllers_directory());
			$this->attempt_mkdir(moojon_config::get_views_directory());
			$this->attempt_mkdir(moojon_config::get_models_directory());
			$this->attempt_mkdir(moojon_config::get_base_models_directory());
			$this->attempt_mkdir(moojon_config::get_images_directory());
			$this->attempt_mkdir(moojon_config::get_css_directory());
			$this->attempt_mkdir(moojon_config::get_js_directory());
		}
	}
}
?>
