<?php
final class moojon_exception_handler extends moojon_base_exception_handler {
	protected function run() {
		require_once(moojon_paths::get_app_path(APP));
		$moojon = self::get_app_class(APP);
		$instance = new $moojon(ACTION, CONTROLLER);
		$instance->render(true);
	}
}
?>