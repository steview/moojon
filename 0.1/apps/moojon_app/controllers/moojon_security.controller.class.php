<?php
final class moojon_security_controller extends moojon_base_controller {
	public function login() {
		$this->security_identity_label = moojon_config::get('security_identity_label');
		$this->security_password_label = moojon_config::get('security_password_label');
		$this->security_remember_label = moojon_config::get('security_remember_label');
		$this->security_identity_key = moojon_config::get('security_identity_key');
		$this->security_password_key = moojon_config::get('security_password_key');
		$this->security_remember_key = moojon_config::get('security_remember_key');
		$this->security_key = moojon_config::get('security_key');
		$security = $_REQUEST[$this->security_key];
		if (is_array($security) == true && moojon_request::post() == true) {
			$this->security_identity_value = $security[$this->security_identity_key];
			$this->security_password_value = $security[$this->security_password_key];
			$this->security_remember_value = $security[$this->security_remember_key];
			if (strlen($this->security_remember_value) > 0) {
				$this->security_remember_value = ' checked="'.$this->security_remember_value.'"';
			}
			if (moojon_authentication::authenticate() === false) {
				$this->security_failure_message = sprintf(moojon_config::get('security_failure_message'), strtolower($this->security_identity_label), strtolower($this->security_password_label));
			}
		}
	}
	
	public function logout() {
		moojon_authentication::destroy();
		moojon_flash::set('message', 'You have been logged out');
		$this->forward(moojon_config::get('security_action'), moojon_config::get('security_controller'), moojon_uri::get_app());
	}
}
?>