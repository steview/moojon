<?php
abstract class moojon_base_cli extends moojon_base {
	
	final public function __construct() {
		if (!defined("STDIN")) {
			define("STDIN", fopen('php://stdin','r'));
		}
		$arguments = $_SERVER['argv'];
		array_shift($arguments);
		$this->run($arguments);
	}
	
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
	
	abstract protected function run($arguments);
	
	final protected function check_arguments($method, $expected, Array $arguments) {
		if (count($arguments) > $expected) {
			self::handle_argument_mismatch_error($method, $expected, $arguments);
		}
	}
	
	final protected function handle_argument_mismatch_error($method, $expected, Array $arguments) {
		self::handle_error("Argument mismatch error for ($method). Expected $expected, got ".count($arguments).' ('.implode(', ', $arguments).')');
	}
	
	final protected function prompt_until($initial, $message, $default = null) {
		$return = ($initial) ? $initial : $this->prompt($message, $default);
		while (strlen($return) == 0) {
			echo '(invalid command) ';
			$return = $this->prompt($message, $default);
		}
		return $return;
	}
	
	final protected function prompt_until_in($initial, $collection, $message) {
		$message .= ' ('.implode(', ', $collection).')';
		if (count($collection) == 1) {
			$default = $collection[0];
		} else {
			$default = null;
		}
		$return = ($initial) ? $initial : $this->prompt($message, $default);
		while (in_array($return, $collection) == false) {
			echo '(invalid command) ';
			$return = $this->prompt($message, $default);
		}
		return $return;
	}
}
?>
