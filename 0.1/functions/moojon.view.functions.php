<?php
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
?>