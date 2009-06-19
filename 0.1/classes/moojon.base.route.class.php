<?php
abstract class moojon_base_route extends moojon_base {
	protected $pattern;
	protected $params;
	
	final public function __construct($pattern, $params = array()) {
		$this->pattern = $pattern;
		$this->params = $params;
	}
	
	abstract public function map_uri($uri);
	
	final protected function match_count($uri) {
		return (count(explode('/', $uri)) == count(explode('/', $this->pattern)));
	}
	
	final protected function is_symbol($subject) {
		return (substr($subject, 0, 1) == ':');
	}
	
	final protected function get_symbol_name($symbol) {
		return substr($symbol, 1);
	}
	
	final protected function get_symbol_values($uri) {
		$pattern = explode('/', $this->pattern);
		$uri = explode('/', $this->uri);
		$return = array();
		for ($i = 0; $i < count($pattern); $i ++) {
			if ($this->is_symbol($pattern[$i])) {
				$return[$this->get_symbol_name($pattern[$i])] = $uri[$i];
			}
		}
		return $return;
	}
	
	final protected function match_app($app) {
		return moojon_paths::get_app_path($app);
	}
	
	final protected function match_controller($app, $controller) {
		return moojon_paths::get_controller_path($app, $controller);
	}
}
?>