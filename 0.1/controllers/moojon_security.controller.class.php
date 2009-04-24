<?php
final class moojon_security_controller extends moojon_base_controller {
	public function index() {
		$security = $_REQUEST['security'];
		if (is_array($security) == true && array_key_exists('remember', $security) == true) {
			$this->remember = ' checked="'.$security['remember'].'"';
		}
		$this->email = $security['email'];
		$this->password = $security['password'];
	}
	
	public function logout() {
		moojon_session::set('security_token', null);
		moojon_cookies::set('security_token', null);
	}
}
?>