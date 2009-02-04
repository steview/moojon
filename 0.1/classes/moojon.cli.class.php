<?php
final class moojon_cli extends moojon_base {
	public function __construct() {
		if ($_SERVER['argc'] < 2) {
			echo 'Moojon version: '.$_SERVER['argc']."\n";
		} else {
			$arguments = $_SERVER['argv'];
			array_shift($arguments);
			$command = array_shift($arguments);
			$required_arguments = array();
			$class_name;			
			switch ($command) {
				case 'migrate':
					$class_name = 'moojon_migration_commands';
					$required_arguments = array('method' => 'Enter please enter a method (up, down or reset)');
					$arguments = $this->process_arguments($required_arguments, $arguments);
					$method = $arguments['method'];
					$class = new $class_name;
					$class->$method();
					break;
				case 'generate':
					$class_name = 'moojon_generator';
					$required_arguments = array('method' => 'What would you like to generate? (models, migration)', 'name' => 'Please enter a name for the generated file');
					$arguments = $this->process_arguments($required_arguments, $arguments);
					$method = $arguments['method'];
					$class = new $class_name;
					if ($arguments['name']) {
						$class->$method($arguments['name']);
					} else {
						$class->$method();
					}
					break;
				default:
					self::handle_error('Unknown class ('.$command.')');
					break;
			}
		}
		die();
	}
	
	private function process_arguments(Array $required_arguments, Array $arguments) {
		$argument_difference = (count($required_arguments) - count($arguments));
		if ($argument_difference < 0) {
			self::handle_error('Too many arguments for '.$this->class.'. Expected '.count($required_arguments).', got '.count($arguments));
		}
		$arguments_processed = array();
		$arguments_to_process = array();
		$counter = 0;
		foreach($required_arguments as $key => $value) {
			$counter ++;
			if ($couter >= count($arguments)) {
				$arguments_to_process[$key] = $value;
			} else {
				$arguments_processed[$key] = $arguments[($counter - 1)];
			}
		}
		foreach ($arguments_to_process as $key => $value) {
			echo "$value:\n";
			$arguments_processed[$key] = fread(STDIN, 80);
		}
		return $arguments_processed;
	}
}
?>