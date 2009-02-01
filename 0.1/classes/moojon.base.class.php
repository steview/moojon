<?php
abstract class moojon_base {
	private function __construct() {}
		
	final static public function handle_error($error_text) {
		echo self::new_line().'=================================================='.self::new_line();
		echo $error_text;
		echo self::new_line().'=================================================='.self::new_line();
		foreach(debug_backtrace() as $call) {
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