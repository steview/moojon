<?php
function link_to($content, $action, $controller = null, $app = null, $attributes = null) {
	if ($app == null) {
		$app = APP;
	}
	if ($controller == null) {
		$controller = CONTROLLER;
	}
	return '<a href="'.moojon_config::key('index_file')."$app/$controller/$action\">$content</a>";
}
?>