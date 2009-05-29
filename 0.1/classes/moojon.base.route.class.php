<?php
abstract class moojon_base_route extends moojon_base {
	protected var $pattern;
	
	final public function __construct($pattern) {
		$this->pattern = $pattern;
	}
}
?>