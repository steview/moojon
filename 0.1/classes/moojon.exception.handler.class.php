<?php
final class moojon_exception_handler extends moojon_base_exception_handler {
	protected function run() {
		require_once(moojon_paths::get_app_path());
		$moojon = moojon_uri::get_app().'_app';
		new $moojon(moojon_uri::get_action(), moojon_uri::get_controller());
	}
}
?>