<?php
final class exception_controller extends moojon_base_controller {
	public function index() {
		$this->exception = moojon_exception::find();
	}
}
?>