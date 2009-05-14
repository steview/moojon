<?php
final class moojon_cookies extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		if (is_array($_COOKIE) == false) {
			return false;
		}
		if (array_key_exists($key, $_COOKIE) === true) {
			if ($_COOKIE[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if (moojon_config::has('cookie_expiry') == true) {
			if (is_int(moojon_config::get('cookie_expiry')) == true) {
				$cookie_expiry = (time() + moojon_config::get('cookie_expiry'));
			} else {
				throw new moojon_exception('cookie_expiry must be an integer ('.moojon_config::get('cookie_expiry').')');
			}
		}
		if ($value !== null) {
			$_COOKIE[$key] = $value;
			setcookie($key, $value, $cookie_expiry, '/');
		} else {
			$_COOKIE[$key] = null;
			setcookie($key, null, -1, '/');
			unset($_COOKIE[$key]);
		}
	}
	
	static public function clear() {
		if (is_array($_COOKIE) == true) {
			foreach($_COOKIE as $key) {
				$_COOKIE[$key] = null;
				setcookie($key, null, time());
				unset($_COOKIE[$key]);
			}
		}
	}
	
	static public function key($key) {
		if (is_array($_COOKIE) == true) {
			if (array_key_exists($key, $_COOKIE) == true) {
				return $_COOKIE[$key];
			} else {
				throw new moojon_exception("Key does not exists in moojon_cookies ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists in moojon_cookies ($key)");
		}
	}
	
	static public function get_list() {
		if (is_array($_COOKIE) == true) {
			return $_COOKIE;
		} else {
			return array();
		}
	}
}
?>