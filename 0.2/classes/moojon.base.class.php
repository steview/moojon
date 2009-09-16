<?php
abstract class moojon_base {
	final static public function log($text) {
		$text = date('Y-m-d H:i:s e: ').$text."\n\n";
		$log_dir = PROJECT_DIRECTORY.'log/';
		if (!is_dir($log_dir)) {
			if (!mkdir($log_dir)) {
				throw new moojon_exception("Unable to open / create log directory ($log_dir)");
			}
		}
		$log_file = $log_dir.strtolower(ENVIRONMENT).'.log';
		if (!file_exists($log_file)) {
			touch($log_file);
			chmod($log_file, 0666);
		}
		if (!$handle = fopen($log_file, 'a')) {
			fclose($handle);
			throw new moojon_exception("Unable to open / create log file ($log_file)");
		}
		if (!fwrite($handle, $text)) {
			fclose($handle);
			throw new moojon_exception("Unable to write to log file ($log_file)");
		}
		fclose($handle);
	}
	
	final static public function try_define($name, $value) {
		if (!defined($name)) {
			define($name, $value);
		}
	}
	
	final static public function remove_prefix($subject, $prefix) {
		if (is_object($subject)) {
			$subject = get_class($subject);
		}
		if (substr($subject, 0, strlen($prefix)) == $prefix) {
			return substr($subject, strlen($prefix));
		}
		return $subject;
	}
	
	final static public function remove_suffix($subject, $suffix) {
		if (is_object($subject)) {
			$subject = get_class($subject);
		}
		if (substr($subject, (strlen($subject) - strlen($suffix))) == $suffix) {
			return substr($subject, 0, (strlen($subject) - strlen($suffix)));
		}
		return $subject;
	}
	
	static public function get_app_class($app) {
		return $app.'_app';
	}
	
	static public function get_controller_class($controller) {
		return $controller.'_controller';
	}
	
	static public function get_app_name($app) {
		$app_class = get_class($app);
		if (substr($app_class, -4) == '_app') {
			return substr($app_class, 0, (strlen($app_class) - 4));
		} else {
			return $app_class;
		}
	}
	
	static public function get_controller_name($controller) {
		$controller_class = get_class($controller);
		if (substr($controller_class, -11) == '_controller') {
			return substr($controller_class, 0, (strlen($controller_class) - 11));
		} else {
			return $controller_class;
		}
	}
	
	final protected function is_symbol($subject) {
		return (substr($subject, 0, 1) == ':');
	}
	
	final static protected function require_view_functions() {
		if (defined('PROJECT_DIRECTORY')) {
			include_once(MOOJON_DIRECTORY.'/functions/moojon.view.functions.php');
			foreach (helpers() as $helper) {
				helper($helper);
			}
		}
	}
	
	final static protected function dump_array(Array $array) {
		switch (strtoupper(UI)) {
			case 'CGI':
				foreach ($array as $key => $value) {
					echo "$key: $value<br />";
				}
				break;
			case 'CLI':
				print_r($array);
				break;
			default:
				throw new moojon_exception('Invalid UI ('.UI.')');
				break;
		}
		
	}
}
?>