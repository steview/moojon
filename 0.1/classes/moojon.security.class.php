<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$authenticated = self::get_authenticated();
		if ($authenticated === false) {
			echo 'Not authenticated<br />';
			return false;
		} else {
			if (is_array($security) == true && array_key_exists('remember', $security) == true) {
				moojon_cookies::set(moojon_config::get('security_token'), $primary_key_value);
			} else {
				moojon_session::set(moojon_config::get('security_token'), $primary_key_value);
			}
			echo 'Authenticated<br />';
			return true;
		}
	}
	
	static public function get_authenticated() {
		$security_token = self::get_security_token();
		$security_class = moojon_config::get('security_class');
		$primary_key = moojon_primary_key::NAME;
		$security = $_REQUEST[moojon_config::get('security_key')];
		if ($security_token === false) {
			if (moojon_request::post() == true && moojon_request::has('security') == true) {
				$where = "email = '".$security['email']."' AND password = '".$security['password']."'";
			} else {
				return false;
			}
		} else {
			$where = "$primary_key = '$security_token'";
		}
		$security_model = moojon_config::get('security_model');
		$security_model = new $security_model;
		$records = $security_model->read($where);
		if ($records->count > 0) {
			return $primary_key_value = $records->first;
		} else {
			return false;
		}
	}
	
	static protected function fetch_profile() {
		return $security_class->read(moojon_primary_key::NAME." = '".self::get_security_token()."'")->first;
	}
}
?>