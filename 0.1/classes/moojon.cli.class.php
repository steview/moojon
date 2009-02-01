<?php
final class moojon_cli extends moojon_base {
	private $class;
	private $file;
	
	public function __construct() {
		if ($_SERVER['argc'] < 2) {
			echo 'Moojon version: '.$_SERVER['argc']."\n";
		} else {
			$arguments = array();
			$required_arguments = array();
			$class_name;
			switch ($_SERVER['argv'][1]) {
				case 'migration':
					require_once('base.schema_migration.model.class.php');
					require_once('schema_migration.model.class.php');
					require_once('moojon.base.migration.class.php');
					require_once('moojon.migration.commands.class.php');
					$class_name = 'moojon_migration_commands';
					$required_arguments = array('method' => 'Enter please enter a method (run, roll_back or reset)');
					break;
				default:
					self::handle_error('Unknown class ('.$this->class.')');
					break;
			}
			$arguments = $this->process_arguments($required_arguments);
			$method = $arguments['method'];
			$class = new $class_name;
			echo "**$method**";
			$class->$method();
		}		
	}
	
	private function process_arguments(Array $args) {
		if ((count($_SERVER['argv']) - 2) > count($args)) {
			self::handle_error('Too many arguments for '.$this->class.'. Expected '.count($args).', got '.(count($_SERVER['argv']) - 2));
		}
		$args_processed = array();
		$args_to_process = array();
		$counter = 1;
		foreach($args as $key => $value) {
			if ($counter > ($_SERVER['argc'] - 3)) {
				$args_to_process[$key] = $value;
			} else {
				$args_processed[$key] = $_SERVER['argv'][$counter];
			}
			$counter ++;
		}
		foreach ($args_to_process as $key => $value) {
			echo "$value:\n";
			$args_processed[$key] = fread(STDIN, 80);
		}
		print_r($args_processed);
		return $args_processed;
	}
}
?>