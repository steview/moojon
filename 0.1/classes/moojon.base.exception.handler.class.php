<?php
abstract class moojon_base_exception_handler extends moojon_base {
	protected $exception;
	
	final public function __construct(Exception $exception) {
		if (get_class($exception) != 'moojon_exception') {
			$exception = new moojon_exception($exception->getMessage(), $exception->getCode(), 0, $exception->getFile(), $exception->getLine());
		}
		$this->run();
	}
	
	abstract protected function run();
}
?>