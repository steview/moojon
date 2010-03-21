<?php
abstract class moojon_base_uninstall extends moojon_base {
	final public function __construct() {
		$this->run();
	}
	
	abstract private function run();
}
?>