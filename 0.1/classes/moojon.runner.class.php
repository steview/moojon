<?php
final class moojon_runner extends moojon_base {
	private function __construct() {}
	
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
			$instance = new $moojon;
			switch (strtoupper(UI)) {
				case 'CGI':
					echo $instance->render();
					break;
			}
			moojon_connection::close();
		} catch (moojon_exception $exception) {
			moojon_connection::close();
			$exception_handler_class = moojon_config::get('exception_handler_class');
			new $exception_handler_class($exception);
		}
	}
}
?>