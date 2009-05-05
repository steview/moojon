<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$security_token = self::get_security_token();
		$security = $_REQUEST[moojon_config::get('security_key')];
		$primary_key = moojon_primary_key::NAME;
		if ($security_token === false) {
			if (moojon_request::post() == true && moojon_request::has('security') == true) {
				$where = moojon_config::get('security_identity_key')." = '".$security[moojon_config::get('security_identity_key')]."' AND ".moojon_config::get('security_password_key')." = '".$security[moojon_config::get('security_password_key')]."'";
			} else {
				return false;
			}
		} else {
			$where = "$primary_key = '$security_token'";
		}
		$security_model = moojon_config::get('security_model');
		$security_model = new $security_model;
		$records = $security_model->read($where);
		if ($records->count < 1) {
			return false;
		} else {
			if (is_array($security) == true && array_key_exists('remember', $security) == true) {
				moojon_cookies::set(moojon_config::get('security_token'), $records->first->$primary_key);
			} else {
				moojon_session::set(moojon_config::get('security_token'), $records->first->$primary_key);
			}
			return $records->first;
		}
	}
}
?>