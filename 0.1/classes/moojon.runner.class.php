<?php
final class moojon_runner extends moojon_base {
	private function __construct() {}
	
	static public function run() {
		switch (strtoupper(UI)) {
			case 'CGI':
				require_once(moojon_paths::get_app_path());
				$moojon = moojon_uri::get_app().'_app';
				break;
			case 'CLI':
				$moojon = $cli;
				break;
			default:
				throw new Exception('Invalid UI ('.UI.')');
				break;
		}
		try {
			new $moojon;
		} catch(exception $e) {
			var_dump($e);
		}
	}
}
?>