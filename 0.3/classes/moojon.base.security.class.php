<?php
abstract class moojon_base_security extends moojon_base {
	
	final public function __construct() {}
	
	final static protected function get_security_token($key = null) {
		if (!$key) {
			$key = moojon_config::get('security_token_key');
		}
		if (moojon_cookie::has($key)) {
			return moojon_cookie::get($key);
		} elseif (moojon_session::has($key)) {
			return moojon_session::get($key);
		} else {
			return false;
		}
	}
	
	/*//abstract static public function authenticate();
	static public function authenticate();
	
	//abstract static public function destroy();
	static public function destroy();*/
}
?>