<?php
final class moojon_exception_handler extends moojon_base_exception_handler {
	protected function run() {
		self::try_define('EXCEPTION', true);
		echo "\n".moojon_uri::get_app()."\n";
		require_once(moojon_paths::get_app_path(moojon_uri::get_app()));
		$moojon = self::get_app_class(moojon_uri::get_app());
		$instance = new $moojon(moojon_uri::get_action(), moojon_uri::get_controller());
		$instance->render(true);
	}
}
?>