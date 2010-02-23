<?php
abstract class moojon_base_exception_handler extends moojon_base {
	protected $exception;
	
	final public function __construct(moojon_exception $exception) {
		$this->run($exception);
	}
	
	abstract protected function run(moojon_exception $exception);
}
?>