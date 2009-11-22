<?php
final class moojon_exception_handler extends moojon_base_exception_handler {
	protected function run() {
		switch (UI) {
			case 'CGI':
				$app = moojon_config::get('exception_app');
				require_once(moojon_paths::get_app_path($app));
				$moojon_class = self::get_app_class($app);
				$instance = new $moojon_class("$app/".moojon_config::get('exception_controller').'/'.moojon_config::get('exception_action'));
				break;
			case 'CLI':
				echo $this->exception;
				break;
		}
	}
}
?>