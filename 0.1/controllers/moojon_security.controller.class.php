<?php
final class moojon_security_controller extends moojon_base_controller {
	public function login() {
		$security = $_REQUEST[moojon_config::get('security_key')];
		if (is_array($security) == true && array_key_exists('remember', $security) == true) {
			$this->remember = ' checked="'.$security['remember'].'"';
		}
		$this->email = $security['email'];
		$this->password = $security['password'];
	}
	
	public function logout() {
		moojon_session::clear();
		moojon_cookies::clear();
		$this->forward(moojon_config::get('security_action'), moojon_config::get('security_controller'), moojon_uri::get_app());
	}
}
?>