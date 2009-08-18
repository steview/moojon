<?php
function __autoload($class) {
	$class_path = moojon_paths::get_class_path($class);
	if (!$class_path) {
		$class_path = moojon_paths::get_interface_path($class);
	}
	if ($class_path) {
		require_once($class_path);
	} else {
		throw new moojon_exception("Not found ($class)");
	}
}
function exception_handler(Exception $exception) {
	$exception_handler_class = moojon_config::key('exception_handler_class');
	new $exception_handler_class($exception);
	moojon_db::close();
}
set_exception_handler('exception_handler');
function exception_error_handler($code, $message, $file, $line) {
	exception_handler(new moojon_exception($message, 0, $code, $file, $line));
}
set_error_handler('exception_error_handler');
date_default_timezone_set(moojon_config::key('timezone'));
?>