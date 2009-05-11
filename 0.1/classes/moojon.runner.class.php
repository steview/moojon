<?php
final class moojon_runner extends moojon_base {
	private function __construct() {}
	
	static public function run() {
		require_once(moojon_paths::get_app_path());
		switch (strtoupper(UI)) {
			case 'CGI':
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
			//throw new Exception('Division by zero.');
			new $moojon;
		} catch(exception $e) {
			$moojon = moojon_uri::get_app().'_app';
			echo 'here '.$moojon.'<br />';
			new $moojon(moojon_config::get('500'), moojon_config::get('exception_controller'));
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