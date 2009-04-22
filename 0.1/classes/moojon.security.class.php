<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$security_token = self::get_security_token();
		$security_class = moojon_config::get('security_class');
		$security_model = moojon_config::get('security_model');
		$primary_key = moojon_primary_key::NAME;
		if ($security_token === false) {
			//$where = "email = '".moojon_request::get('email')."' AND password = PASSWORD('".moojon_request::get('password')."')";
			$where = "email = '".moojon_request::key('email')."' AND password = '".moojon_request::key('password')."'";
		} else {
			$where = "$primary_key = '$security_token'";
		}
		$security_model = new $security_model;
		$records = $security_model->read($where);
		if ($records->count > 0) {
			moojon_session::set('security_token', $records->first->$primary_key);
			return true;
		} else {
			return false;
		}
	}
	
	static protected function fetch_profile() {
		return $security_class->read(moojon_primary_key::NAME." = '".self::get_security_token()."'")->first;
	}
}
?>