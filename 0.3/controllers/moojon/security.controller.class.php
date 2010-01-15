<?php
final class security_controller extends moojon_base_controller {
	public function login() {
		$this->authenticated = moojon_security::authenticate();
		if ($this->authenticated && moojon_server::is_post()) {
			moojon_flash::set('notification', 'You have been logged in');
			$this->redirect(moojon_server::get('PHP_SELF'));
		}
	}
	
	public function logout() {
		moojon_security::destroy();
		moojon_flash::set('notification', 'You have been logged out');
		$this->redirect(moojon_config::get('index_file').moojon_config::get('security_app').'/'.moojon_config::get('security_controller').'/'.moojon_config::get('security_action'));
	}
}
?>