<?php
final class moojon_debug extends moojon_base {
	private function __construct() {}
	
	static public function render() {
		if (ENVIRONMENT != 'production') {
			$div = new moojon_div_tag(null, array('id' => 'debug'));

			$div->add_child(new moojon_h2_tag('Session'));
			$ul = new moojon_ul_tag();
			foreach ($_SESSION as $key => $value) {
				if (is_array($value) == true) {
					$value = 'array('.implode(', ', $value).')';
				}
				$ul->add_child(new moojon_li_tag("$key: $value"));
			}
			$div->add_child($ul);
			$div->add_child(new moojon_h2_tag('Cookie'));
			$ul = new moojon_ul_tag();
			foreach ($_COOKIE as $key => $value) {
				if (is_array($value) == true) {
					$value = 'array('.implode(', ', $value).')';
				}
				$ul->add_child(new moojon_li_tag("$key: $value"));
			}
			$div->add_child($ul);
			$div->add_child(new moojon_h2_tag('URL'));
			$ul = new moojon_ul_tag();
			$app = moojon_uri::get_app();
			$controller = moojon_uri::get_controller();
			$action = moojon_uri::get_action();
			$ul->add_child(new moojon_li_tag("App: $app"));
			$ul->add_child(new moojon_li_tag("Controller: $controller"));
			$ul->add_child(new moojon_li_tag("Action: $action"));
			$div->add_child($ul);
			$div->add_child(new moojon_h2_tag('Paths'));
			$ul = new moojon_ul_tag();
			$app_path = moojon_paths::get_app_path();
			$controller_path = moojon_paths::get_controller_path($controller);
			//$view_path = moojon_paths::get_view_path($action);
			$ul->add_child(new moojon_li_tag("app_path: $app_path"));
			$ul->add_child(new moojon_li_tag("controller_path: $controller_path"));
			$ul->add_child(new moojon_li_tag("view_path: $view_path"));
			$div->add_child($ul);
			if (defined('REQUEST_START_TIME') == true) {
				$div->add_child(new moojon_h2_tag('Request time: '.(time() - REQUEST_START_TIME)));
			}
			echo $div->render();
		}
	}
}