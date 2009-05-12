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
				throw new Exception('Invalid UI ('.UI.')');
				break;
		}
		try {
			//throw new Exception('Division by zero.');
			new $moojon;
		} catch(Exception $e) {
			self::try_define('EXCEPTION', true);
			$moojon = moojon_uri::get_app().'_app';
			new $moojon(moojon_uri::get_action(), moojon_uri::get_controller());
			//self::handle_exception($e->getMessage());
		}
	}
	
	final static public function handle_exception($exception_message) {
		echo self::new_line().'=================================================='.self::new_line();
		echo $exception_message;
		echo self::new_line().'=================================================='.self::new_line();
		$backtrace = debug_backtrace();
		array_shift($backtrace);
		foreach($backtrace as $call) {
			echo 'function: '.$call['function'].self::new_line();
			echo 'line: '.$call['line'].self::new_line();
			echo 'file: '.$call['file'].self::new_line();
			echo 'class: '.$call['class'].self::new_line();
			echo 'object:';
			echo self::new_line().'------------------------------------------------------------'.self::new_line();
			var_dump($call['object']);
			echo self::new_line().'------------------------------------------------------------'.self::new_line();
			echo 'type: ';
			switch ($call['type']) {
				case '->':
					echo 'method call';
					break;
				case '::':
					echo 'static method call';
					break;
				default:
					echo 'function call';
					break;
			}
			echo self::new_line();
			echo 'args:';
			echo self::new_line().'------------------------------------------------------------'.self::new_line();
			print_r($call['args']);
			echo self::new_line().'------------------------------------------------------------'.self::new_line();
			switch (UI) {
				case 'CGI':
					echo '<hr />';
					break;
				case 'CLI':
					echo "\n++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n";
					break;
			}
		}
		die();
	}
	
	final static public function new_line() {
		switch (UI) {
			case 'CGI':
				return '<br />';
				break;
			case 'CLI':
				return "\n";
				break;
		}
	}
}
?>