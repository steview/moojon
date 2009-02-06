<?php
final class moojon_cli extends moojon_base_cli {
	public function __construct() {
		if ($_SERVER['argc'] < 2) {
			echo 'Moojon version: '.MOOJON_VERSION."\n";
		} else {
			$arguments = $_SERVER['argv'];
			array_shift($arguments);
			self::check_arguments('moojon_generator::project()', 3, $arguments);
			$project = array_shift($arguments);
			if (count($arguments) == 0) {
				$app = $this->prompt('Please enter an app name', moojon_config::get_default_app());
				$controller = $this->prompt('Please enter a controller name', moojon_config::get_default_controller());
				$action = $this->prompt('Please enter a action name', moojon_config::get_default_action());
			} elseif (count($arguments) == 1) {
				$app = $arguments[0];
				$controller = $this->prompt('Please enter an controller name', moojon_config::get_default_controller());
				$action = $this->prompt('Please enter a action name', moojon_config::get_default_action());
			} elseif (count($arguments) == 2) {
				$app = $arguments[0];
				$controller = $arguments[1];
				$action = $this->prompt('Please enter a action name', moojon_config::get_default_action());
			} else {
				$app = $arguments[0];
				$controller = $arguments[1];
				$action = $arguments[2];
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
