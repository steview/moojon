<?php
final class moojon_authentication extends moojon_base {
	static private $instance;
	static private $profile = false;
	
	private function __construct() {
		$security_class = moojon_config::get('security_class');
		$security = new $security_class;
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
		$security_class = moojon_config::get('security_class');
		$security = new $security_class;
		$security->destroy();
		self::$instance = null;
	}
}
?>