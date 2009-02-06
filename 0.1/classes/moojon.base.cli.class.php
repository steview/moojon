<?php
abstract class moojon_base_cli extends moojon_base {
	static final protected function prompt($message, $default = null, $characters = null) {
		if ($default != null) {
			$message = "$message (default: $default)";
		}
		echo "$message > ";
		if ($characters == null) {
			$characters = 80;
		}
		$read = str_replace(chr(10), '', fread(STDIN, $characters));
		if (strlen($read) == 0) {
			$read = $default;
		} else {
			while (strlen($read) == 0) {
				$read = str_replace(chr(10), '', fread(STDIN, $characters));
				echo "$message > ";
			}
		}
		return $read;
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
