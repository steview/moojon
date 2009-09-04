<?php
abstract class moojon_base_exception_handler extends moojon_base {
	protected $exception;
	
	final public function __construct(Exception $exception) {
		$this->exception = $exception;
		$this->run();
	}
	
	abstract protected function run();
}
?>