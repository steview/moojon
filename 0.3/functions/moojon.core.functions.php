<?php
function __autoload($class) {
	$class_path = moojon_paths::get_class_path($class);
	if (!$class_path) {
		$class_path = moojon_paths::get_model_path($class);
	}
	if (!$class_path) {
		$class_path = moojon_paths::get_base_model_path($class);
	}
	if (!$class_path) {
		$class_path = moojon_paths::get_interface_path($class);
	}
	if (!$class_path) {
		$class_path = moojon_paths::get_column_path($class);
	}
	moojon_base::log("$class: $class_path");
	if ($class_path) {
		require_once($class_path);
	} else {
		throw new moojon_exception("Not found ($class)");
	}
}
function exception_handler(Exception $exception) {
	if (!is_subclass_of($exception, 'moojon_exception')) {
		$message = ($exception->getMessage()) ? $exception->getMessage() : 'No message available for exception';
		$code = ($exception->getCode()) ? $exception->getCode() : -1;
		$file = ($exception->getFile()) ? $exception->getFile() : 'Unable to determine file';
		$line = ($exception->getLine()) ? $exception->getLine() : -1;
		$exception = new moojon_exception($message, 0, 0, $file, $line);
	}
	$exception_handler_class = moojon_config::get('exception_handler_class');
	new $exception_handler_class($exception);
}
set_exception_handler('exception_handler');
function exception_error_handler($code, $message, $file, $line) {
	exception_handler(new moojon_exception($message, $code, 0, $file, $line));
}
set_error_handler('exception_error_handler');
?>