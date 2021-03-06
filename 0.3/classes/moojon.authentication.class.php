<?php
final class moojon_authentication extends moojon_singleton {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	
	private $profile = false;
	
	protected function __construct() {
		$security = $this->get_security();
		$this->profile = $security->authenticate();
	}
	
	static private function set_profile($profile) {
		$instance = self::fetch();
		$instance->profile = $profile;
	}
	
	static private function get_profile() {
		$instance = self::fetch();
		return $instance->profile;
	}
	
	static public function authenticate() {
		$instance = self::fetch();
		return $instance->get_profile();
	}
	
	static public function destroy() {
		$instance = self::fetch();
		$instance->get_security()->destroy();
		self::$instance = null;
	}
	
	static private function get_security() {
		$security_class = moojon_config::get('security_class');
		$security = new $security_class;
		if (!is_subclass_of($security, 'moojon_base_security')) {
			throw new moojon_exception('Invalid security class ('.get_class($security).')');
		}
		return $security;
	}
}
?>