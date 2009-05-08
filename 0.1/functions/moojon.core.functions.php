<?php
session_start();
function __autoload($class_name) {
	$class_filename = str_replace('_', '.', $class_name).'.class.php';
	if (file_exists(moojon_paths::get_library_directory().$class_filename) == true) {
		require_once(moojon_paths::get_library_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_vendor_directory().$class_filename) == true) {
		require_once(moojon_paths::get_vendor_directory().$class_filename);
	} else {
		//moojon_base::handle_error("$class_name not found as a library or vendor item.");
	}
}
function helper($helper) {
	$helper = moojon_files::require_suffix($helper, 'helper');
	if (file_exists(moojon_paths::get_helpers_directory().$helper) == true) {
		require_once(moojon_paths::get_helpers_directory().$helper);
	} elseif (file_exists(moojon_paths::get_moojon_helpers_directory().$helper) == true) {
		require_once(moojon_paths::get_moojon_helpers_directory().$helper);
	} else {
		moojon_base::handle_error("Unknown helper ($helper)");
	}
}
function partial($partial, $variables = array()) {
	foreach ($variables as $key => $value) {
		$$key = $value;
	}
	$path = dirname($partial).'/';
	if ($path == './') {
		$path = '';
	}
	$basename = basename($partial);
	if (substr($basename, 0, 1) == '_') {
		$basename = substr($basename, 1);
	}
	$partial = $path.'_'.$basename.'.php';
	if (file_exists(moojon_paths::get_views_directory().$partial) == true) {
		require_once(moojon_paths::get_views_directory().$partial);
	} elseif (file_exists(moojon_paths::get_shared_views_directory().$partial) == true) {
		require_once(moojon_paths::get_shared_views_directory().$partial);
	} else {
		moojon_base::handle_error("Unknown partial ($partial)");
	}
}
function render() {
	$layout = moojon_paths::get_layout_path($app->get_layout());
	$view = moojon_paths::get_view_path($app->get_view());
	ob_start();
	require_once($view);
	define('YIELD', ob_get_clean());
	ob_end_clean();
	if ($layout !== false) {
		require_once($layout);
	} else {
		echo YIELD;
	}
}
function close_connection() {
	if (get_class($con) == 'moojon_connection') {
		$con->close();
	}
}
?>