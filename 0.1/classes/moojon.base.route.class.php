<?php
abstract class moojon_base_route extends moojon_base {
	protected $pattern;
	protected $params;
	
	final public function __construct($pattern, $params = array()) {
		$this->pattern = $pattern;
		$this->params = $params;
	}
	
	abstract public function map_uri($uri);
	
	final protected function is_symbol($subject) {
		return (substr($subject, 0, 1) == ':');
	}
}
?>