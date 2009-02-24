<?php
function link_to($content, $action, $controller = null, $app = null, $attributes = null) {
	if ($app == null) {
		$app = moojon_uri::get_app();
	}
	if ($controller == null) {
		$controller = moojon_uri::get_controller();
	}
	return '<a href="'.moojon_config::get('index_file')."$app/$controller/$action\">$content</a>";
}
?>