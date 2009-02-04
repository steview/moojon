<?php
final class moojon_config extends moojon_base {
	private function __construct() {}
	
	static public function get_db_host() {
		return 'localhost';
	}
	
	static public function get_db_username() {
		return 'bloodbowl';
	}
	
	static public function get_db_password() {
		return 'bloodbowl99';
	}
	
	static 	public function get_db() {
		return 'bloodbowl2';
	}
}
?>