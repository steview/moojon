<?php
final class moojon_exception_controller extends moojon_base_controller {
	public function index() {
		$this->exception = moojon_runner::get_exception();
	}
}
?>