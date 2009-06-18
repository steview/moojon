<?php
session_start();
function __autoload($class) {
	if ($class_path = moojon_paths::get_class_path($class)) {
		require_once($class_path);
	} else {
		require_once(moojon_paths::get_classes_directory().'/moojon.exception.class.php');
		throw new moojon_exception("Not found ($class_filename)");
	}
}
?>