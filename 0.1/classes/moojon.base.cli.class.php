<?php
abstract class moojon_base_cli extends moojon_base {
	final protected function prompt($message, $default = null, $characters = null) {
		if ($default != null) {
			$message .= " (default: $default)";
		}
		if ($characters == null) {
			$characters = 80;
		}
		echo "$message > ";
		$return = fread(STDIN, $characters);
		if ($return == '' && $default != null) {
			$return = $default;
		}
		while ($return == '') {
			echo "$message > ";
			$return = fread(STDIN, $characters);
		}
		return $return;
	}
	
	final protected function attempt_mkdir($path, $mode = null) {
		if ($mode == null) {
			$mode = 0755;
		}
		if (!mkdir($path, $mode, true)) {
			self::handle_error("Unable to create directory path ($path)");
		} else {
			echo "Creating directory ($path)".moojon_base::new_line();
		}
	}
	
	final protected function check_arguments($method, $expected, Array $arguments) {
		if (count($arguments) > $expected) {
			self::handle_argument_mismatch_error($method, $expected, $arguments);
		}
	}
	
	final protected function handle_argument_mismatch_error($method, $expected, Array $arguments) {
		self::handle_error("Argument mismatch error for ($method). Expected $expected, got ".count($arguments).' ('.implode(', ', $arguments).')');
	}
}
?>
