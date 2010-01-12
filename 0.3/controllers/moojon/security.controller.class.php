<?php
final class security_controller extends moojon_base_controller {
	public function login() {
		$this->security_identity_label = moojon_config::get('security_identity_label');
		$this->security_password_label = moojon_config::get('security_password_label');
		$this->security_remember_label = moojon_config::get('security_remember_label');
		$this->security_identity_key = moojon_config::get('security_identity_key');
		$this->security_password_key = moojon_config::get('security_password_key');
		$this->security_remember_key = moojon_config::get('security_remember_key');
		$this->security_failure_message = null;
		$this->security_remember_value = null;
		$this->security_identity_value = null;
		$this->security_password_value = null;
		$this->security_key = moojon_config::get('security_key');
		if (moojon_server::is_post() && moojon_request::has($this->security_key)) {
			$security = moojon_request::get($this->security_key);
			$this->security_identity_value = $security[$this->security_identity_key];
			$this->security_password_value = $security[$this->security_password_key];
			$this->security_remember_value = $security[$this->security_remember_key];
			if ($this->security_remember_value) {
				$this->security_remember_value = ' checked="'.$this->security_remember_value.'"';
			}
			if (!moojon_security::authenticate()) {
				$this->security_failure_message = sprintf(moojon_config::get('security_failure_message'), strtolower($this->security_identity_label), strtolower($this->security_password_label));
			} else {
				moojon_flash::set('notification', 'You have been logged in');
				$this->redirect(moojon_uri::get_uri());
			}
		}
	}
	
	public function logout() {
		moojon_authentication::destroy();
		moojon_flash::set('notification', 'You have been logged out');
		$this->forward(moojon_config::get('security_action'), moojon_config::get('security_controller'), APP);
	}
}
?>