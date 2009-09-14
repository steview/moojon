<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$security_token = self::get_security_token();
		$security_token_key = moojon_config::key('security_token_key');
		$security = $_REQUEST[moojon_config::key('security_key')];
		$primary_key = moojon_primary_key::NAME;
		$security_identity_key = moojon_config::key('security_identity_key');
		$security_password_key = moojon_config::key('security_password_key');
		$security_model_class = moojon_config::key('security_model');
		$security_model = new $security_model_class;
		$security_identity_data_type = $security_model->get_column($security_identity_key)->get_data_type();
		$security_password_data_type = $security_model->get_column($security_password_key)->get_data_type();
		$log_message = 'Log in attempt';
		if (!$security_token) {
			if (moojon_server::is_post() && moojon_request::has('security')) {
				$security_identity_value = $security[$security_identity_key];
				$security_password_value = $security[$security_password_key];
				$where = "$security_identity_key = :$security_identity_key AND $security_password_key = :$security_password_key";
				$param_values = array(":$security_identity_key" => $security_identity_value, ":$security_password_key" => $security_password_value);
				$param_data_types = array(":$security_identity_key" => $security_identity_data_type, ":$security_password_key" => $security_password_data_type);
			} else {
				return false;
			}
		} else {
			$where = "$primary_key = :$primary_key";
			$param_values = array(":$primary_key" => $security_token);
			$column = new moojon_primary_key;
			$param_data_types = array(":$primary_key" => $column->get_data_type());
			$log_message = 'Security check';
		}
		$records = $security_model->read($where, null, null, $param_values, $param_data_types);
		self::log('------------------------------');
		if (!$records->count) {
			self::log("$log_message failure, $security_token");
			self::destroy();
			return false;
		} else {
			$security_remember_key = moojon_config::key('security_remember_key');
			$security_token = $records->first->$primary_key;
			if (is_array($security) && moojon_server::is_post()) {
				if (array_key_exists($security_remember_key, $security)) {
					if (strlen($security[$security_remember_key])) {
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