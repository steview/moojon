<?php
final class moojon_generate_cli extends moojon_base_cli {
	public function __construct($arguments) {
		$commands = $this->get_commands();
		if ($arguments > 0) {
			$command = array_shift($arguments);
		} else {
			$command = strtolower($this->prompt('What would you like to generate? ('.implode(', ', $commands).')'));
		}
		while (!in_array($command, $commands)) {
			echo '(invalid command) ';
			$command = strtolower($this->prompt('What would you like to generate? ('.implode(', ', $commands).')'));
		}
		switch ($command) {
			case 'model':
				self::check_arguments('moojon_generate_cli::model()', 1, $arguments);
				$tables = moojon_adapter::list_tables();
				$table = ($arguments[0]) ? $arguments[0] : $this->prompt('What table would you like to generate a model for? ('.implode(', ', $tables).')');
				while (!in_array($table, $tables)) {
					echo '(invalid command) ';
					$table = $this->prompt('What table would you like to generate a model for? ('.implode(', ', $tables).')');
				}
				moojon_generator::model($table);
				break;
			case 'models':
				self::check_arguments('moojon_generate_cli::models()', 0, $arguments);
				moojon_generator::models();
				break;
			case 'migration':
				self::check_arguments('moojon_generate_cli::migration()', 1, $arguments);
				$migration = ($arguments[0]) ? $arguments[0] : $this->prompt('Please enter a migration name');
				while (strlen($migration) == 0) {
					echo '(invalid command) ';
					$migration = $this->prompt('Please enter a migration name');
				}
				moojon_generator::migration($migration);
				break;
			case 'app':
				self::check_arguments('moojon_generate_cli::app()', 3, $arguments);
				$app = ($arguments[0]) ? $arguments[0] : $this->prompt('Please enter an app name');
				while (strlen($app) == 0) {
					echo '(invalid command) ';
					$app = $this->prompt('Please enter an app name');
				}
				$controller = ($arguments[1]) ? $arguments[1] : $this->prompt('Please enter a controller name', moojon_config::get_default_controller());
				$action = ($arguments[2]) ? $arguments[2] : $this->prompt('Please enter an action name', moojon_config::get_default_action());
				moojon_generator::app($app, $controller, $action);
				break;
			case 'controller':
				self::check_arguments('moojon_generate_cli::controller()', 3, $arguments);
				$app = ($arguments[0]) ? $arguments[0] : $this->prompt_for_app();
				$controller = ($arguments[1]) ? $arguments[1] : $this->prompt('Please enter a controller name');
				while (strlen($controller) == 0) {
					echo '(invalid command) ';
					$controller = $this->prompt('Please enter a controller name');
				}
				$action = ($arguments[2]) ? $arguments[2] : $this->prompt('Please enter an action name');
				moojon_generator::controller($app, $controller, $action);
				break;
			case 'view':
				self::check_arguments('moojon_generate_cli::view()', 2, $arguments);
				$app = ($arguments[0]) ? $arguments[0] : $this->prompt_for_app();
				$view = ($arguments[1]) ? $arguments[1] : $this->prompt('Please enter a view name');
				moojon_generator::view($app, $view);
				break;
			case 'layout':
				self::check_arguments('moojon_generate_cli::layout()', 2, $arguments);
				$app = ($arguments[0]) ? $arguments[0] : $this->prompt_for_app();
				$layout = ($arguments[1]) ? $arguments[1] : $this->prompt('Please enter a layout name');
				moojon_generator::layout($app, $layout);
				break;
			case 'test':
				self::check_arguments('moojon_generate_cli::test()', 1, $arguments);
				$test = $this->prompt('Please enter a name for this test');
				while (strlen($test) == 0) {
					$test = ($arguments[0]) ? $arguments[0] : $this->prompt('Please enter a name for this test');
				}
				moojon_generator::test($test);
				break;
		}
	}
	
	private function prompt_for_app() {
		$apps = $this->get_apps();
		$message = 'Which app? ('.implode(', ', $apps).')';
		$app = $this->prompt($message);
		while (!in_array($app, $apps)) {
			echo '(invalid command) ';
			$app = $this->prompt($message);
		}
		$this->try_define('APP', $app);
		return $app;
	}
	
	private function get_commands() {
		return array('model', 'models', 'migration', 'app', 'controller', 'view', 'layout', 'test');
	}
	
	private function get_apps() {
		return moojon_files::directory_directories(moojon_config::get_apps_directory());
	}
}
?>
