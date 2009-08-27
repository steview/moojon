<?php
final class moojon_runner extends moojon_base {
	static private $instance;
	
	private function __construct() {
		if (defined('PROJECT_DIRECTORY')) {
			include_once(MOOJON_PATH.'/functions/moojon.view.functions.php');
			foreach (helpers() as $helper) {
				helper($helper);
			}
		}
	}
	
	static public function run() {
		self::get();
		switch (strtoupper(UI)) {
			case 'CGI':
				moojon_session::get();
				moojon_uri::get();
				moojon_config::update(moojon_paths::get_project_app_config_directory(APP));
				require_once(moojon_paths::get_app_path(APP));
				$app_class = self::get_app_class(APP);
				new $app_class(moojon_uri::get_uri());
				break;
			case 'CLI':
				$cli_class = CLI;
				new $cli_class;
				break;
			default:
				throw new moojon_exception('Invalid UI ('.UI.')');
				break;
		}
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_runner();
		}
		return self::$instance;
	}
	
	static public function render($path, $variables = array()) {
		foreach ($variables as $key => $value) {
			$$key = $value;
		}
		ob_start();
		require($path);
		$return = ob_get_clean();
		if (ob_get_length()) {
			ob_end_clean();
		}
		return $return;
	}
}
?>