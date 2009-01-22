<?php
final class moojon_uri extends moojon_base {
	private function __construct() {}
	
	static public function get_app() {
		return 'client';
	}
	
	static public function get_controller() {
		return 'teams';
	}
	
	static public function get_method() {
		return 'roster';
	}
}
?>