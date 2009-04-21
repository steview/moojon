<?php
abstract class moojon_base_security extends moojon_base {
	final public function __construct() {}
	
	abstract public function authenticate();
}
?>