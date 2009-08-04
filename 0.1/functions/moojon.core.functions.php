<?php
function __autoload($class) {
	$class_path = moojon_paths::get_class_path($class);
	if ($class_path) {
		require_once($class_path);
	} else {
		throw moojon_exception::create("Not found ($class)");
	}
}
function exception_handler(Exception $exception) {
	$exception_handler_class = moojon_config::key('exception_handler_class');
	new $exception_handler_class($exception);
	moojon_connection::close();
}
set_exception_handler('exception_handler');
function exception_error_handler($code, $message, $file, $line) {
    exception_handler(moojon_exception::create($message, 0, $code, $file, $line));
}
set_error_handler('exception_error_handler');
?>