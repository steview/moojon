<?php
final class moojon_runner extends moojon_base {
	static private $instance;
	
	private function __construct() {
		include_once(MOOJON_PATH.'/functions/moojon.view.functions.php');
		foreach (helpers() as $helper) {
			helper($helper);
		}
	}
	
	static public function run() {
		self::get();
		switch (strtoupper(UI)) {
			case 'CGI':
				moojon_uri::get();
				moojon_config::update(moojon_paths::get_project_config_directory());
				moojon_config::update(moojon_paths::get_project_app_config_directory(APP));
				moojon_config::update(moojon_paths::get_project_app_environment_config_directory(APP, ENVIRONMENT));
				require_once(moojon_paths::get_app_path(APP));
				$moojon = APP.'_app';
				break;
			case 'CLI':
				moojon_config::update(moojon_paths::get_project_config_directory());
				$moojon = CLI;
				break;
			default:
				throw new moojon_exception('Invalid UI ('.UI.')');
				break;
		}
		$instance = new $moojon;
		$instance->render(true);
		moojon_connection::close();
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_runner();
		}
		return self::$instance;
	}
	
	static public function render($path, moojon_base_controller $controller) {
		foreach (get_object_vars($controller) as $key => $value) {
			$$key = $value;
		}
		ob_start();
		try {
			require($path);
		} catch (Exception $exception) {
			throw $exception;
		}
		$return = ob_get_clean();
		if (ob_get_length()) {
			ob_end_clean();
		}
		return $return;
	}
}
?>