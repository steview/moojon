<?php
final class moojon_security extends moojon_base_security {
	static public function authenticate() {
		$security_token = self::get_security_token();
		$security_class = moojon_config::get('security_class');
		$security_model = moojon_config::get('security_model');
		$primary_key = moojon_primary_key::NAME;
		if ($security_token === false) {
			if (moojon_request::post() == true && moojon_post::has('login') == true) {
				$login = moojon_request::get('login');
				$where = "email = '".$login['email']."' AND password = '".$login['password']."'";
			} else {
				return false;
			}
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