<?php
abstract class moojon_base {
	private function __construct() {}
		
	final static public function handle_error($error_text) {
		echo self::new_line().'=================================================='.self::new_line();
		echo $error_text;
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
	
	final static public function log($text) {
		$text = date('Y-m-d H:i:s e: ').$text."\n\n";
		$log_dir = PROJECT_DIRECTORY.'log/';
		if (!is_dir($log_dir)) {
			if (!mkdir($log_dir)) {
				self::handle_error("Unable to open / create log directory ($log_dir)");
			}
		}
		$log_file = $log_dir.strtolower(ENVIRONMENT).'.log';
		if (file_exists($log_file) == false) {
			touch($log_file);
			chmod($log_file, 0666);
		}
		if (!$handle = fopen($log_file, 'a')) {
			fclose($handle);
			self::handle_error("Unable to open / create log file ($log_file)");
		}
		if (fwrite($handle, $text) === false) {
			fclose($handle);
			self::handle_error("Unable to write to log file ($log_file)");
		}
		fclose($handle);
	}
	
	final static public function try_define($name, $value) {
		if (!defined($name)) {
			define($name, $value);
		}
	}
	
	final static public function remove_prefix($subject, $prefix) {
		if (is_object($subject) == true) {
			$subject = get_class($subject);
		}
		if (substr($subject, 0, strlen($prefix)) == $prefix) {
			return substr($subject, strlen($prefix));
		}
		return $subject;
	}
	
	final static public function remove_suffix($subject, $suffix) {
		if (is_object($subject) == true) {
			$subject = get_class($subject);
		}
		if (substr($subject, (strlen($subject) - strlen($suffix))) == $suffix) {
			return substr($subject, 0, (strlen($subject) - strlen($suffix)));
		}
		return $subject;
	}
}
?>