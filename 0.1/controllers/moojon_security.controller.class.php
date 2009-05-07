<?php
final class moojon_security_controller extends moojon_base_controller {
	public function login() {
		$security = $_REQUEST[moojon_config::get('security_key')];
		if (is_array($security) == true && moojon_request::post() == true) {
			$security_remember_key = moojon_config::get('security_remember_key');
			if (array_key_exists($security_remember_key, $security) == true) {
				if (strlen($security[$security_remember_key]) > 0) {
					$this->$security_remember_key = ' checked="'.$security[$security_remember_key].'"';
				}
			}
			$security_identity_key = moojon_config::get('security_identity_key');
			$security_password_key = moojon_config::get('security_password_key');
			$this->$security_identity_key = $security[$security_identity_key];
			$this->$security_password_key = $security[$security_password_key];
		}
	}
	
	public function logout() {
		moojon_authentication::destroy();
		$this->forward(moojon_config::get('security_action'), moojon_config::get('security_controller'), moojon_uri::get_app());
	}
}
?>