<?php
final class moojon_request extends moojon_base {
	
	private function __construct() {}
	
	static public function method() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	static public function post() {
		return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
	}
	
	static public function get() {
		return (strtolower($_SERVER['REQUEST_METHOD']) == 'get');
	}
}
?>