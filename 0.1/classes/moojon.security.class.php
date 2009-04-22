<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$security_token = self::get_security_token();
		$security_class = moojon_config::get('security_class');
		$primary_key = moojon_primary_key::NAME;
		if ($security_token === false) {
			$where = "email = '".moojon_request::get('email')."' AND password = PASSWORD('".moojon_request::get('password')."')";
		} else {
			$where = "$primary_key = '$security_token'";
		}
		$records = $security_class->read($where);
		if ($records->count > 0) {
			moojon_cookies::set('security_token', $records->first->$primary_key);
			return true;
		} else {
			return false;
		}
	}
	
	static public function fetch_profile() {
		
	}
}
?>