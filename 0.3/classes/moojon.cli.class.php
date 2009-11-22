<?php
final class moojon_cli extends moojon_base_cli {
	public function run($arguments) {
		if (!count($arguments)) {
			echo 'Moojon version: '.MOOJON_VERSION."\n";
		} else {
			$arguments = self::check_arguments('moojon_cli::run()', 4, $arguments);
			$this->try_define('PROJECT_DIRECTORY', $_SERVER['PWD'].'/'.$this->prompt_until($arguments[0], 'Please enter a project name').'/');
			$app = $this->prompt_until($arguments[1], 'Please enter an app name', moojon_config::get('default_app'));
			$controller = $this->prompt_until($arguments[2], 'Please enter a controller name', moojon_config::get('default_controller'));
			$action = $this->prompt_until($arguments[3], 'Please enter an action name', moojon_config::get('default_action'));
			moojon_files::attempt_mkdir(PROJECT_DIRECTORY);
			moojon_generator::project($app, $controller, $action);
		}
	}
}
?>
