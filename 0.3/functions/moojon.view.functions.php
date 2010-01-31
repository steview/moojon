<?php
function helper($helper) {
	if ($helper_path = moojon_paths::get_helper_path($helper)) {
		require_once($helper_path);
	} else {
		throw new moojon_exception("Unknown helper ($helper)");
	}
}

function helpers() {
	$helpers = array();
	if (moojon_config::has('helpers')) {
		$helpers = explode(', ', moojon_config::get('helpers'));
	}
	foreach (explode(', ', moojon_config::get('default_helpers')) as $helper) {
		if (!in_array($helper, $helpers)) {
			$helpers[] = $helper;
		}
	}
	return $helpers;
}

function partial($partial, $variables = array()) {
	$backtrace = debug_backtrace();
	$view_directory = dirname($backtrace[0]['file']).'/';
	$controller = basename($view_directory, '/');
	$app = basename(dirname($view_directory));
	if (!$partial_path = moojon_paths::get_partial_path($app, $controller, $partial)) {
		throw new moojon_exception("Unknown partial ($partial)");
	}
	moojon_runner::partial($partial_path, $variables);
}
?>