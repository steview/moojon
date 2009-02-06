<?php
final class moojon_cli extends moojon_base_cli {
	public function __construct($arguments) {
		if (count($arguments) < 2) {
			echo 'Moojon version: '.MOOJON_VERSION.self::new_line();
		} else {
			$project = ($arguments[0]) ? $arguments[0] : $this->prompt('Please enter a project name');
			while (strlen($project) == 0) {
				echo '(invalid command) ';
				$project = $this->prompt('Please enter a project name');
			}
			$this->try_define('PROJECT_PATH', $project);
			
			$app = ($arguments[1]) ? $arguments[1] : $this->prompt('Please enter an app name', moojon_config::get_default_app());
			while (strlen($app) == 0) {
				echo '(invalid command) ';
				$app = $this->prompt('Please enter an app name');
			}
			$this->try_define('APP', $app);
			
			$controller = ($arguments[2]) ? $arguments[2] : $this->prompt('Please enter a controller name', moojon_config::get_default_controller());
			while (strlen($controller) == 0) {
				echo '(invalid command) ';
				$controller = $this->prompt('Please enter a controller name');
			}
			
			$action = ($arguments[3]) ? $arguments[3] : $this->prompt('Please enter an action name', moojon_config::get_default_action());
			while (strlen($action) == 0) {
				echo '(invalid command) ';
				$action = $this->prompt('Please enter an action name');
			}
			
			moojon_generator::project($project, $app, $controller, $action);
			$create_migration = strtolower($this->prompt('Would you like to create a migration? y / n', 'n', 1));
			while ($create_migration != 'yes' && $create_migration != 'no' && $create_migration != 'y' && $create_migration != 'n') {
				$create_migration = strtolower($this->prompt('Would you like to create a migration? y / n', 'n', 1));
			}
			if ($create_migration == 'y') {
				moojon_generator::migration($this->prompt('Would you like to create a migration?', 'first'));
			}
		}
	}
}
?>
