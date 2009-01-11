<?php
class moojon_base
{
	private function __construct() {}
		
	final public static function handle_error($error_text)
	{
		echo '<br />==================================================<br />';
		echo $error_text;
		echo '<br />==================================================<br />';
		foreach(debug_backtrace() as $call) {
			echo 'function: '.$call['function'].'<br />';
			echo 'line: '.$call['line'].'<br />';
			echo 'file: '.$call['file'].'<br />';
			echo 'class: '.$call['class'].'<br />';
			echo 'object:';
			echo '<br />------------------------------------------------------------<br />';
			var_dump($call['object']);
			echo '<br />------------------------------------------------------------<br />';
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
			echo '<br />';
			echo 'args:';
			echo '<br />------------------------------------------------------------<br />';
			print_r($call['args']);
			echo '<br />------------------------------------------------------------<br />';
			echo '<hr />';
		}
		die();
	}
}
?>