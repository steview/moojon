<?php
final class moojon_request extends moojon_base {
	
	private function __construct() {}
	
	public function method() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	public function post() {
		return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
	}
	
	public function get() {
		return (strtolower($_SERVER['REQUEST_METHOD']) == 'get');
	}
}
?>