<?php
final class moojon_route_match extends moojon_base {
	private $pattern;
	private $params = array();
	
	public function __construct($pattern, $params = array()) {
		$this->pattern = $pattern;
		$this->params = $params;
	}
	
	public function get_pattern() {
		return $this->pattern;
	}
	
	public function get_params() {
		return $this->params;
	}
}
?>