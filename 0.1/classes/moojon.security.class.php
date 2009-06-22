<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$security_token = self::get_security_token();
		$security_token_key = moojon_config::key('security_token_key');
		$security = $_REQUEST[moojon_config::key('security_key')];
		$primary_key = moojon_primary_key::NAME;
		$security_identity_key = moojon_config::key('security_identity_key');
		$security_password_key = moojon_config::key('security_password_key');
		$log_message = 'Log in attempt';
		if ($security_token === false) {
			if (moojon_server::is_post() == true && moojon_request::has('security') == true) {
				$security_identity_value = $security[$security_identity_key];
				$security_password_value = $security[$security_password_key];
				$where = sprintf(moojon_config::key('security_login_condition_string'), $security_identity_key, $security_identity_value, $security_password_key, $security_password_value);
			} else {
				return false;
			}
		} else {
				$where = sprintf(moojon_config::key('security_check_condition_string'), $primary_key, $security_token);
			$log_message = 'Security check';
		}
		$security_model = moojon_config::key('security_model');
		$security_model = new $security_model;
		$records = $security_model->read($where);
		self::log('------------------------------');
		if ($records->count < 1) {
			self::log("$log_message failure, $security_token");
			self::destroy();
			return false;
		} else {
			$security_remember_key = moojon_config::key('security_remember_key');
			$security_token = $records->first->$primary_key;
			if (is_array($security) == true && moojon_server::is_post() == true) {
				if (array_key_exists($security_remember_key, $security) == true) {
					if (strlen($security[$security_remember_key]) > 0) {
						moojon_cookie::set($security_token_key, $security_token);
						self::log("$log_message, creating cookie: ".$security_token);
					}
				}
			}
			moojon_session::set($security_token_key, $security_token);
			self::log("$log_message, creating session: ".$security_token);
			return $records->first;
		}
	}
	
	static public function destroy() {
		$security_token = self::get_security_token();
		self::log("logout: $security_token");
		$security_token_key = moojon_config::key('security_token_key');
		moojon_session::set($security_token_key, null);
		moojon_cookie::set($security_token_key, null);
		$_REQUEST[moojon_config::key('security_identity_key')] = null;
		$_REQUEST[moojon_config::key('security_password_key')] = null;
	}
}
?>