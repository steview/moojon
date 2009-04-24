<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$security_token = self::get_security_token();
		$security_class = moojon_config::get('security_class');
		$security_model = moojon_config::get('security_model');
		$primary_key = moojon_primary_key::NAME;
		$security = $_REQUEST['security'];
		if ($security_token === false) {
			if (moojon_request::post() == true && moojon_request::has('security') == true) {
				$where = "email = '".$security['email']."' AND password = '".$security['password']."'";
			} else {
				return false;
			}
		} else {
			$where = "$primary_key = '$security_token'";
		}
		$security_model = new $security_model;
		$records = $security_model->read($where);
		if ($records->count > 0) {
			$primary_key_value = $records->first->$primary_key;
			moojon_session::set(moojon_config::get('security_token'), $primary_key_value);
			if (is_array($security) == true && array_key_exists('remember', $security) == true) {
				moojon_cookies::set(moojon_config::get('security_token'), $primary_key_value);
			}
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