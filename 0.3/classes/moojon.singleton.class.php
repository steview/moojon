<?php
abstract class moojon_singleton extends moojon_base {
	abstract protected function __construct();
	
	abstract static protected function factory($class);
	
	abstract static public function fetch();
}
?>