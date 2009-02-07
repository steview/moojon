<?php
final class moojon_uri extends moojon_base {
	private function __construct() {}
	
	static public function get_app() {
		switch (strtoupper(UI)) {
			case 'CGI':
				return 'client';
				break;
			case 'CLI':
				return APP;
				break;
		}
	}
	
	static public function get_controller() {
		return 'teams';
	}
	
	static public function get_action() {
		return 'index';
	}
}
?>