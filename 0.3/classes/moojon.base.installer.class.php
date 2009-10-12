<?php
abstract class moojon_base_installer extends moojon_base {
	final public function __construct($method) {
		$this->$method();
		if ($method == 'install') {
			touch(dirname(__FILE__).'/installed');
		}
	}
	
	abstract protected function install();
	
	abstract protected function uninstall();
}
?>