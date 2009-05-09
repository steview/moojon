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
	$helper = trim($helper);
	$helper = moojon_files::require_suffix($helper, 'helper');
	if (file_exists(moojon_paths::get_helpers_directory().$helper) == true) {
		require_once(moojon_paths::get_helpers_directory().$helper);
	} elseif (file_exists(moojon_paths::get_moojon_helpers_directory().$helper) == true) {
		require_once(moojon_paths::get_moojon_helpers_directory().$helper);
	} else {
		moojon_base::handle_error("Unknown helper ($helper)");
	}
}
function helpers() {
	$helpers = array();
	if (moojon_config::has('helpers') == true) {
		$helpers = explode(', ', moojon_config::get('helpers'));
	}
	foreach (explode(', ', moojon_config::get('default_helpers')) as $helper) {
		if (in_array($helper, $helpers) === false) {
			$helpers[] = $helper;
		}
	}
	return $helpers;
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
function render_cgi() {
	require_once(moojon_paths::get_app_path(moojon_uri::get_app()));
	$app_class = moojon_uri::get_app().'_app';
	$app = new $app_class;	
	foreach ($app->get_controller_properties() as $key => $value) {
		if ($key != 'app') {
			$$key = $value;
		}
	}
	foreach (helpers() as $helper) {
		helper($helper);
	}
	ob_start();
	require_once(moojon_paths::get_view_path($app->get_view()));
	define('YIELD', ob_get_clean());
	ob_end_clean();
	if (moojon_paths::get_layout_path($app->get_layout()) !== false) {
		require_once(moojon_paths::get_layout_path($app->get_layout()));
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