<?php
abstract class moojon_base_exception_handler extends moojon_base {
	final public function __construct(moojon_exception $exception) {
		moojon_exception_buffer::set_exception($exception);
		$this->run();
	}
	
	abstract protected function run();
}
?>