<?php
abstract class moojon_base {
	private function __construct() {}
	
	final static public function log($text) {
		$text = date('Y-m-d H:i:s e: ').$text."\n\n";
		$log_dir = PROJECT_DIRECTORY.'log/';
		if (!is_dir($log_dir)) {
			if (!mkdir($log_dir)) {
				throw new Exception("Unable to open / create log directory ($log_dir)");
			}
		}
		$log_file = $log_dir.strtolower(ENVIRONMENT).'.log';
		if (file_exists($log_file) == false) {
			touch($log_file);
			chmod($log_file, 0666);
		}
		if (!$handle = fopen($log_file, 'a')) {
			fclose($handle);
			throw new Exception("Unable to open / create log file ($log_file)");
		}
		if (fwrite($handle, $text) === false) {
			fclose($handle);
			throw new Exception("Unable to write to log file ($log_file)");
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