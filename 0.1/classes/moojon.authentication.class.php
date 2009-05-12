<?php
final class moojon_authentication extends moojon_base {
	static private $instance;
	static private $profile = false;
	
	private function __construct() {
		$security = $this->get_security();
		$this->profile = $security->authenticate();
	}
	
	static private function get() {
		if (!self::$instance) {
			self::$instance = new moojon_authentication();
		}
		return self::$instance;
	}
	
	static private function set_profile($profile) {
		$instance = self::get();
		$instance->profile = $profile;
	}
	
	static private function get_profile() {
		$instance = self::get();
		return $instance->profile;
	}
	
	static public function authenticate() {
		$instance = self::get();
		return $instance->get_profile();
	}
	
	static public function destroy() {
		$instance = self::get();
		$instance->destroy();
		self::$instance = null;
	}
	
	static private function get_security() {
		$security_class = moojon_config::get('security_class');
		$security = new $security_class;
		if (is_subclass_of($security, 'moojon_base_security') === false) {
			throw new moojon_exception('Invalid security class ('.get_class($security).')');
		}
		return $security;
	}
}
?>