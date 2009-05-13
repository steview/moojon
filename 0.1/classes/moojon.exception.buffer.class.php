<?php
final class moojon_exception_buffer extends moojon_base {
	static private $instance;
	static private $exception;
	
	final private function __construct() {}
	
	final static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_exception_buffer();
		}
		return self::$instance;
	}
	
	final static public function set_exception(Exception $exception) {
		self::try_define('EXCEPTION', true);
		$instance = self::get();
		$instance->exception = $exception;
	}
	
	final static public function get_exception() {
		$instance = self::get();
		return $instance->exception;
	}
}
?>