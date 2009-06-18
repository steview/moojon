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
		switch (strtoupper(UI)) {
			case 'CGI':
				moojon_config::update(moojon_paths::get_project_config_directory());
				moojon_config::update(moojon_paths::get_project_app_config_directory(moojon_uri::get_app()));
				require_once(moojon_paths::get_app_path());
				$moojon = moojon_uri::get_app().'_app';
				break;
			case 'CLI':
				$moojon = CLI;
				break;
			default:
				$exception_handler_class = moojon_config::get('exception_handler_class');
				new $exception_handler_class(new moojon_exception('Invalid UI ('.UI.')'));
				die();
				break;
		}
		try {
			$instance = new $moojon;
			$instance->render(true);
			moojon_connection::close();
		} catch (moojon_exception $exception) {
			moojon_connection::close();
			$exception_handler_class = moojon_config::get('exception_handler_class');
			new $exception_handler_class($exception);
		}
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_runner();
		}
		return self::$instance;
	}
	
	static public function render($path, moojon_base_controller $controller) {
		self::get();
		foreach (get_object_vars($controller) as $key => $value) {
			$$key = $value;
		}
		ob_start();
		require($path);
		$return = ob_get_clean();
		ob_end_clean();
		return $return;
	}
}
?>