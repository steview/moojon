<?php
abstract class moojon_base_route extends moojon_base {
	protected $pattern;
	protected $params;
	
	final public function __construct($pattern, $params = array()) {
		if (!is_string($pattern)) {
			throw new moojon_exception('Pattern passed to a moojon_base_route must be a string ('.get_class($pattern).' passed )');
		} else {
			$this->pattern = $pattern;
		}
		if (!is_array($params)) {
			throw new moojon_exception('Params passed to a moojon_base_route must be an array ('.get_class($params).' passed )');
		} else {
			$this->params = $params;
		}
	}
	
	abstract public function map_uri($uri);
	
	final protected function match_count($uri) {
		return (count(explode('/', $uri)) == count(explode('/', $this->pattern)));
	}
	
	final protected function get_symbol_name($symbol) {
		return substr($symbol, 1);
	}
	
	final protected function get_symbol_values($uri) {
		$pattern = explode('/', $this->pattern);
		$uri = explode('/', $uri);
		$return = array();
		for ($i = 0; $i < count($pattern); $i ++) {
			if ($this->is_symbol($pattern[$i])) {
				$return[$this->get_symbol_name($pattern[$i])] = $uri[$i];
			}
		}
		return $return;
	}
	
	final protected function contains_no_symbols($subject) {
		foreach ($subject as $element) {
			if ($this->is_symbol($element)) {
				return false;
			}
			return true;
		}
	}
	
	final protected function match_app($app) {
		return moojon_paths::get_app_path($app);
	}
	
	final protected function match_controller($app, $controller) {
		return moojon_paths::get_controller_path($app, $controller);
	}
	
	final protected function validate_sections($data) {
		if (!array_key_exists('app', $data) || !array_key_exists('controller', $data) || !array_key_exists('action', $data)) {
			return false;
		}
		require_once(moojon_paths::get_controller_path($data['app'], $data['controller']));
		if (!method_exists(self::get_controller_class($data['controller']), $data['action']) && !moojon_paths::get_view_path($data['app'], $data['controller'], $data['action'])) {
			return false;
		}
		return $data;
	}
}
?>