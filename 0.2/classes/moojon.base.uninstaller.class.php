<?php
abstract class moojon_base_installer extends moojon_base {
	final public function __construct() {
		$this->uninstall();
	}
	
	abstract private function uninstall();
}
?>