<?php
final class moojon_cli extends moojon_base_cli {
	public function run($arguments) {
		if (!count($arguments)) {
			echo 'Moojon version: '.MOOJON_VERSION."\n";
		} else {
			$arguments = self::check_arguments('moojon_cli::run()', 4, $arguments);
			$project = $this->prompt_until($arguments[0], 'Please enter a project name');
			$this->try_define('PROJECT_DIRECTORY', $_SERVER['PWD'].'/'.$project.'/');
			$app = $this->prompt_until($arguments[1], 'Please enter an app name', moojon_config::key('default_app'));
			$controller = $this->prompt_until($arguments[2], 'Please enter a controller name', moojon_config::key('default_controller'));
			$action = $this->prompt_until($arguments[3], 'Please enter an action name', moojon_config::key('default_action'));
			$this->try_define('APP', $app);
			moojon_generator::project($project, $app, $controller, $action);
		}
	}
}
?>
