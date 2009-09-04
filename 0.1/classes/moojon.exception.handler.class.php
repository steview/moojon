<?php
final class moojon_exception_handler extends moojon_base_exception_handler {
	protected function run() {
		switch (UI) {
			case 'CGI':
				$app = moojon_config::key('exception_app');
				$uri = "$app/".moojon_config::key('exception_controller').'/'.moojon_config::key('exception_action');
				require_once(moojon_paths::get_app_path($app));
				$moojon = self::get_app_class($app);
				$instance = new $moojon($uri);
				break;
			case 'CLI':
				echo $this->exception;
				break;
		}
	}
}
?>