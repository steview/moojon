<?php
final class moojon_exception_handler extends moojon_base_exception_handler {
	protected function run() {
		require_once(moojon_paths::get_app_path());
		$moojon = self::get_app_class(moojon_uri::get_app());
		$instance = new $moojon(moojon_uri::get_action(), moojon_uri::get_controller());
		$instance->render(true);
	}
}
?>