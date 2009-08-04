<?php
function helper($helper) {
	if ($helper_path = moojon_paths::get_helper_path($helper)) {
		require_once($helper_path);
	} else {
		throw moojon_exception::create("Unknown helper ($helper)");
	}
}

function helpers() {
	$helpers = array();
	if (moojon_config::has('helpers') == true) {
		$helpers = explode(', ', moojon_config::key('helpers'));
	}
	foreach (explode(', ', moojon_config::key('default_helpers')) as $helper) {
		if (in_array($helper, $helpers) === false) {
			$helpers[] = $helper;
		}
	}
	return $helpers;
}

function partial($partial, $variables = array()) {
	if ($partial_path = moojon_paths::get_view_path(APP, CONTROLLER, $partial)) {
		foreach ($variables as $key => $value) {
			$$key = $value;
		}
		require_once($partial_path);
	} else {
		throw moojon_exception::create("Unknown partial ($partial)");
	}
}
?>