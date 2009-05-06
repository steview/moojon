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
			$security_remember_key = moojon_config::get('security_remember_key');
			moojon_base::log('Log in attempt');
			if (is_array($security) == true && moojon_request::post() == true) {
				if (array_key_exists($security_remember_key, $security) == true) {
					if (strlen($security[$security_remember_key]) > 0) {
						moojon_cookies::set($security_token, $records->first->$primary_key);
						moojon_base::log($security_token);
						moojon_base::log('Log in attempt, creating cookie: ' + $records->first->$primary_key);
						moojon_base::log('Log in attempt, cookie value: ' + moojon_cookies::key($security_token));
					}
				}
			}
			moojon_session::set($security_token, $records->first->$primary_key);
			moojon_base::log('Log in attempt, creating session: ' + $records->first->$primary_key);
			moojon_base::log('Log in attempt, session value: ' + moojon_session::key($security_token));
			return $records->first;
		}
	}
}
?>