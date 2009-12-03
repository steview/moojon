<?php
abstract class moojon_base_route extends moojon_base {
	protected $pattern;
	protected $patterns;
	protected $params = array();
	
	final public function __construct($pattern, $params = array()) {
		$this->pattern = $pattern;
		$this->patterns = explode('/', $this->pattern);
		$this->params = $params;
		$this->init();
	}
	
	abstract protected function init();
	
	final public function get_pattern() {
		return $this->pattern;
	}
	
	abstract public function map($uri, $validate = true);
	
	final static protected function validate_matches($matches, $validate = true) {
		if ($validate) {
			if (!array_key_exists('app', $matches) || !array_key_exists('controller', $matches) || !array_key_exists('action', $matches)) {
				return false;
			}
			if ($path = moojon_paths::get_app_path($matches['app'])) {
				require_once($path);
			} else {
				return false;
			}
			if ($path = moojon_paths::get_controller_path($matches['app'], $matches['controller'])) {
				require_once($path);
			} else {
				return false;
			}
			if (!method_exists(self::get_controller_class($matches['controller']), $matches['action']) && !moojon_paths::get_view_path($matches['app'], $matches['controller'], $matches['action'])) {
				return false;
			}
		}
		return true;
	}
}
?>