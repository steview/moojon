<?php
abstract class moojon_base_javascript_controller extends moojon_base_controller {
	protected function headers() {
		header('Content-Type: text/javascript');
	}

	final public function get_layout() {
		return false;
	}
}
?>