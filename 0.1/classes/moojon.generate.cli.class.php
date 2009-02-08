<?php
final class moojon_generate_cli extends moojon_base_cli {
	public function __construct($arguments) {
		$command = $this->prompt_until_in(array_shift($arguments), $this->get_commands(), 'What would you like to generate?');
		switch ($command) {
			case 'model':
				self::check_arguments('moojon_generate_cli::model()', 1, $arguments);
				$table = $this->prompt_until_in($arguments[0], moojon_adapter::list_tables(), 'What table would you like to generate a model for?');
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
				$controller = $this->prompt_until($arguments[1], 'Please enter an app name', moojon_config::get('default_controller'));
				$action = $this->prompt_until($arguments[2], 'Please enter an action name', moojon_config::get('default_action'));
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
				$path = $this->prompt('Path?', $_SERVER['PWD'].'/'.moojon_config::get('config_directory').'/');
				moojon_generator::config($config, $path);
				break;
			case 'helper':
				self::check_arguments('moojon_generate_cli::helper()', 1, $arguments);
				$helper = $this->prompt_until($arguments[0], 'Please enter a name for this helper');
				moojon_generator::helper($helper);
				break;
			case 'view':
				self::check_arguments('moojon_generate_cli::view()', 2, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$view = $this->prompt_until($arguments[1], 'Please enter a view name');
				moojon_generator::view($app, $view);
				break;
			case 'layout':
				self::check_arguments('moojon_generate_cli::layout()', 2, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$layout = $this->prompt_until($arguments[1], 'Please enter a layout name');
				moojon_generator::layout($app, $layout);
				break;
			case 'partial':
				self::check_arguments('moojon_generate_cli::partial()', 2, $arguments);
				$app = $this->prompt_for_app($arguments[0]);
				$partial = $this->prompt_until($arguments[1], 'Please enter a name for this partial');
				moojon_generator::partial($app, $partial);
				break;
			case 'shared_view':
				self::check_arguments('moojon_generate_cli::shared_view()', 1, $arguments);
				$view = $this->prompt_until($arguments[1], 'Please enter a view name');
				moojon_generator::shared_view($view);
				break;
			case 'shared_layout':
				self::check_arguments('moojon_generate_cli::shared_layout()', 1, $arguments);
				$layout = $this->prompt_until($arguments[1], 'Please enter a layout name');
				moojon_generator::shared_layout($layout);
				break;
			case 'shared_partial':
				self::check_arguments('moojon_generate_cli::shared_partial()', 1, $arguments);
				$partial = $this->prompt_until($arguments[1], 'Please enter a name for this partial');
				moojon_generator::shared_partial($partial);
				break;
		}
	}
	
	private function prompt_for_app($initial) {
		return $this->prompt_until_in($initial, $this->get_apps(), 'Which app');
	}
	
	private function get_commands() {
		return array('model', 'models', 'migration', 'app', 'controller', 'test', 'config', 'helper', 'view', 'layout', 'partial', 'shared_view', 'shared_layout', 'shared_partial');
	}
	
	private function get_apps() {
		return moojon_files::directory_directories(moojon_paths::get_apps_directory());
	}
}
?>
