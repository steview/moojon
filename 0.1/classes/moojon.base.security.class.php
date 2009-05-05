<?php
abstract class moojon_base_security extends moojon_base {
	
	final public function __construct() {}
	
	final static protected function get_security_token($key = null) {
		if ($key == null) {
			$key = moojon_config::get('security_token');
		}
		if (moojon_cookies::has($key) == true) {
			return moojon_cookies::key($key);
		} elseif (moojon_session::has($key) == true) {
			return moojon_session::key($key);
		} else {
			return false;
		}
	}
	
	abstract static public function authenticate();
}
?>