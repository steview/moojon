<?php
final class moojon_route_match extends moojon_base {
	private $pattern;
	private $params = array();
	private $route;
	
	public function __construct($pattern, $params = array(), moojon_base_route $route) {
		$this->pattern = $pattern;
		$this->params = $params;
		$this->route = $route;
	}
	
	public function get_pattern() {
		return $this->pattern;
	}
	
	public function get_params() {
		return $this->params;
	}
	
	public function get_route() {
		return $this->route;
	}
	
	public function get($key) {
		if (array_key_exists($key, $this->params)) {
			return $this->params[$key];
		} else {
			throw new moojon_exception("Invalid key ($key)");
		}
	}
}
?>