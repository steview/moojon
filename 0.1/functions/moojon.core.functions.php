<?php
function __autoload($class) {
	$class_path = moojon_paths::get_class_path($class);
	if ($class_path) {
		require_once($class_path);
	} else {
		throw new moojon_exception("Not found ($class)");
	}
}
?>