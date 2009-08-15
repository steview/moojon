<?php
final class moojon_generate_cli extends moojon_base_cli {
	public function run($arguments) {
		$command = $this->prompt_until_in(array_shift($arguments), $this->get_commands(), 'What would you like to generate?');
		switch ($command) {
			case 'model':
				self::check_arguments('moojon_generate_cli::model()', 1, $arguments);
				$table = $this->prompt_until_in($arguments[0], moojon_db_driver::list_tables(), 'What table would you like to generate a model for?');
				moojon_generator::model($table);
				break;
			case 'models':
				self::check_arguments('moojon_generate_cli::models()', 0, $arguments);
				moojon_generator::models();
				break;
			case 'migration':
				self::check_arguments('moojon_generate_cli::migration()', 1, $arguments);
				$migration = $this->prompt_until($arguments[0], 'Please enter a migration name');
				moojon_generator::migration($migration);
				break;
			case 'app':
				self::check_arguments('moojon_generate_cli::app()', 3, $arguments);
				$app = $this->prompt_until($arguments[0], 'Please enter an app name');
				$controller = $this->prompt_until($arguments[1], 'Please enter an app name', moojon_config::key('default_controller'));
				$action = $this->prompt_until($arguments[2], 'Please enter an action name', moojon_config::key('default_action'));
				moojon_generator::app($app, $controller, $action);
				break;
			case 'controller':
				self::check_arguments('moojon_generate_cli::controller()', 3, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$controller = $this->prompt_until($arguments[1], 'Please enter a controller name');
				$action = $this->prompt_until($arguments[2], 'Please enter an action name');
				moojon_generator::controller($app, $controller, $action);
				break;
			case 'test':
				self::check_arguments('moojon_generate_cli::test()', 1, $arguments);
				$test = $this->prompt_until($arguments[0], 'Please enter a name for this test');
				moojon_generator::test($test);
				break;
			case 'config':
				self::check_arguments('moojon_generate_cli::config()', 1, $arguments);
				$config = $this->prompt_until($arguments[0], 'Please enter a name for this config');
				$path = $this->prompt('Path?', $_SERVER['PWD'].'/'.'config/');
				moojon_generator::config($config, $path);
				break;
			case 'helper':
				self::check_arguments('moojon_generate_cli::helper()', 1, $arguments);
				$helper = $this->prompt_until($arguments[0], 'Please enter a name for this helper');
				moojon_generator::helper($helper);
				break;
			case 'layout':
				self::check_arguments('moojon_generate_cli::layout()', 2, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$layout = $this->prompt_until($arguments[1], 'Please enter a layout name');
				moojon_generator::layout($app, $layout);
				break;
			case 'view':
				self::check_arguments('moojon_generate_cli::view()', 3, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$controller = $this->prompt_for_controller($app, $arguments[1]);
				$view = $this->prompt_until($arguments[2], 'Please enter a view name');
				moojon_generator::view($app, $controller, $view);
				break;
			case 'partial':
				self::check_arguments('moojon_generate_cli::partial()', 3, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$controller = $this->prompt_for_controller($app, $arguments[1]);
				$partial = $this->prompt_until($arguments[1], 'Please enter a name for this partial');
				moojon_generator::partial($app, $controller, $partial);
				break;
			case 'javascript_controller':
				self::check_arguments('moojon_generate_cli::javascript_controller()', 3, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$controller = $this->prompt_until($arguments[1], 'Please enter a controller name');
				$action = $this->prompt_until($arguments[2], 'Please enter an action name');
				moojon_generator::javascript_controller($app, $controller, $action);
				break;
			case 'scaffold':
				self::check_arguments('moojon_generate_cli::scaffold()', 3, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$models = array();
				foreach (moojon_files::directory_files(moojon_paths::get_project_models_directory()) as $path) {
					$models[] = basename($path);
				}
				if (count($models) == 0) {
					if ($this->prompt_until_in(null, array('y', 'n'), 'No models found. Would you like to generate models?') == 'y') {
						moojon_generator::models();
					} else {
						throw new moojon_exception('Unable to generate scaffold (no models)');
					}
				}
				$model = $this->prompt_until_in($arguments[1], moojon_db_driver::list_tables(), 'What model would you like to generate a scaffold for?');
				
				$controller = $this->prompt_until($arguments[2], 'Please enter a controller name', $model);
				moojon_generator::scaffold($app, $model, $controller);
				break;
		}
	}
	
	private function prompt_for_app($initial) {
		$app = $this->prompt_until_in($initial, moojon_uri::get_apps(), 'Which app');
		define('APP', $app);
		return $app;
	}
	
	private function prompt_for_controller($app, $initial) {
		return $this->prompt_until_in($initial, moojon_uri::get_controllers($app), 'Which controller');
	}
	
	private function get_commands() {
		return array('model', 'models', 'migration', 'app', 'controller', 'test', 'config', 'helper', 'view', 'layout', 'partial', 'javascript_controller', 'scaffold');
	}
}
?>
