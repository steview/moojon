<?php
final class moojon_cli extends moojon_base_cli {
	public function __construct($arguments) {
		if (count($arguments) < 1) {
			echo 'Moojon version: '.MOOJON_VERSION.self::new_line();
		} else {
			$project = $this->prompt_until($arguments[0], 'Please enter a project name');
			$app = $this->prompt_until($arguments[1], 'Please enter an app name', moojon_config::get_default_app());
			$controller = $this->prompt_until($arguments[2], 'Please enter a controller name', moojon_config::get_default_controller());
			$action = $this->prompt_until($arguments[3], 'Please enter an action name', moojon_config::get_default_action());			
			$this->try_define('PROJECT_PATH', $_SERVER['PWD'].'/'.$project.'/');
			$this->try_define('APP', $app);			
			moojon_generator::project($project, $app, $controller, $action);
			$create_migration = strtolower($this->prompt('Would you like to create a migration? y / n', 'n', 1));
			while ($create_migration != 'y' && $create_migration != 'n') {
				$create_migration = strtolower($this->prompt('Would you like to create a migration? y / n', 'n', 1));
			}
			if ($create_migration == 'y') {
				moojon_generator::migration($this->prompt('Would you like to create a migration?', 'first'));
			}
		}
	}
}
?>
