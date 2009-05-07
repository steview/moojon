<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$security_token = self::get_security_token();
		$security_token_key = moojon_config::get('security_token');
		$security = $_REQUEST[moojon_config::get('security_key')];
		$primary_key = moojon_primary_key::NAME;
		$log_message = 'Log in attempt';
		if ($security_token === false) {
			if (moojon_request::post() == true && moojon_request::has('security') == true) {
				$where = moojon_config::get('security_identity_key')." = '".$security[moojon_config::get('security_identity_key')]."' AND ".moojon_config::get('security_password_key')." = '".$security[moojon_config::get('security_password_key')]."'";
			} else {
				return false;
			}
		} else {
			$where = "$primary_key = '$security_token'";
			$log_message = 'Security check';
		}
		$security_model = moojon_config::get('security_model');
		$security_model = new $security_model;
		$records = $security_model->read($where);
		if ($records->count < 1) {
			return false;
		} else {
			$security_remember_key = moojon_config::get('security_remember_key');
			$security_token = $records->first->$primary_key;
			moojon_base::log('------------------------------');
			if (is_array($security) == true && moojon_request::post() == true) {
				if (array_key_exists($security_remember_key, $security) == true) {
					if (strlen($security[$security_remember_key]) > 0) {
						moojon_cookies::set($security_token_key, $security_token);
						moojon_base::log("$log_message, creating cookie: ".$security_token);
					}
				}
			}
			moojon_session::set($security_token_key, $security_token);
			moojon_base::log("$log_message, creating session: ".$security_token);
			return $records->first;
		}
	}
	
	static public function destroy() {
		$security_token = self::get_security_token();
		self::log("logout: $security_token");
		$security_token_key = moojon_config::get('security_token');
		moojon_session::set($security_token_key, null);
		moojon_cookies::set($security_token_key, null);
	}
}
?>