<?php
final class moojon_runner extends moojon_base {
	static private $instance;
	static private $exception;
	
	private function __construct() {}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_runner();
		}
		return self::$instance;
	}
	
	static public function set_exception(Exception $exception) {
		$instance = self::get();
		$instance->exception = $exception;
	}
	
	static public function get_exception() {
		$instance = self::get();
		return $instance->exception;
	}
	
	static public function run() {
		switch (strtoupper(UI)) {
			case 'CGI':
				moojon_config::update(moojon_paths::get_app_config_directory());
				moojon_config::update(moojon_paths::get_project_config_directory());
				require_once(moojon_paths::get_app_path());
				$moojon = moojon_uri::get_app().'_app';
				break;
			case 'CLI':
				$moojon = CLI;
				break;
			default:
				throw new moojon_exception('Invalid UI ('.UI.')');
				break;
		}
		try {
			//throw new moojon_exception('Division by zero.');
			new $moojon;
		} catch (Exception $exception) {
			self::try_define('EXCEPTION', true);
			self::set_exception($exception);
			require_once(moojon_paths::get_app_path());
			$moojon = moojon_uri::get_app().'_app';
			new $moojon(moojon_uri::get_action(), moojon_uri::get_controller());
		}
	}
}
?>