<?php
abstract class moojon_base_exception_handler extends moojon_base {
	protected $exception;
	
	final public function __construct() {
		$this->run();
	}
	
	abstract protected function run();
}
?>