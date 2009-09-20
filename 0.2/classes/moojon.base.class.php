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
	
	final static public function strip_base($class) {
		if (substr($class, 0, 5) == 'base_') {
			$class = substr($class, 5);
		}
		return $class;
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
	
	final static public function get_time($datetime, $format = null) {
		if (is_array($datetime)) {
			if (!array_key_exists('Y', $datetime) && array_key_exists('year', $datetime)) {
				$datetime['Y'] = $datetime['year'];
			}
			if (!array_key_exists('m', $datetime) && array_key_exists('month', $datetime)) {
				$datetime['m'] = $datetime['month'];
			}
			if (!array_key_exists('d', $datetime) && array_key_exists('day', $datetime)) {
				$datetime['d'] = $datetime['day'];
			}
			if (!array_key_exists('H', $datetime) && array_key_exists('hour', $datetime)) {
				$datetime['H'] = $datetime['hour'];
			}
			if (!array_key_exists('i', $datetime) && array_key_exists('minute', $datetime)) {
				$datetime['i'] = $datetime['minute'];
			}
			if (!array_key_exists('s', $datetime) && array_key_exists('second', $datetime)) {
				$datetime['s'] = $datetime['second'];
			}
			$string = '';
			for ($i = 0; $i < strlen($format); $i ++) {
				$f = substr($format, $i, 1);
				if (array_key_exists($f, $datetime)) {
					$string .= $datetime[$f];
				} else {
					$string .= $f;
				}
			}
		} else {
			$string = $datetime;
		}
		return strtotime($string);
	}
	
	final static public function get_datetime_format($datetime, $format = null) {
		if (!$format) {
			$format = moojon_config::key('datetime_format');
		}
		return date($format, self::get_time($datetime, $format));
	}
	
	final static public function get_rest_route_resources() {
		$rest_route_resources = array();
		foreach (moojon_routes::get_rest_routes() as $rest_route) {
			$rest_route_resources[] = $rest_route->get_resource();
		}
		return $rest_route_resources;
	}
}
?>