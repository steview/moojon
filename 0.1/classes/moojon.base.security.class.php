<?php
abstract class moojon_base_security extends moojon_base {
	
	final public function __construct() {}
	
	final static protected function get_security_token($key = 'security_token') {
		if (moojon_cookies::has($key) == true) {
			return moojon_cookies::get($key);
		} elseif (moojon_session::has($key) == true) {
			return moojon_session::get($key);
		} else {
			return false;
		}
	}
	
	abstract static public function authenticate();
	
	final static public function get_profile() {
		if (self::authenticate() !== true) {
			self::handle_error('No profile available authentication failed.');
		} else {
			return self::fetch_profile();
		}
	}
	
	abstract static public function fetch_profile();
}
?>