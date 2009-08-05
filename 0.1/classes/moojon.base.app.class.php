<?php
abstract class moojon_base_app extends moojon_base {
	
	private $controller;
	private $action_name;
	private $controller_name;
	private $app_name;
	private $layout;
	
	final public function __construct($action = null, $controller = null, $app = null) {
		$this->set_location($action, $controller, $app);
	}
	
	final public function set_location($action = null, $controller = null, $app = null) {
		/*********************************/
		/*
		In light of the new routing system, this method needs to be re-written
		*/
		/*********************************/
		if ($app != null) {
			$location = moojon_config::key('index_file').$app;
			if ($controller != null) {
				$location .= "/$controller";
			}
			if ($action != null) {
				$location .= "/$action";
			}
			header("Location: $location");
			die();
		}
		$this->init();
		if ($action == null) {
			$action = ACTION;
		}
		if ($controller == null) {
			$controller = CONTROLLER;
		}
		$this->action_name = $action;
		$this->controller_name = $controller;
		$this->app_name = $app;
		$this->close();
	}
	
	final public function render($echo = false) {
		require_once(moojon_paths::get_controller_path(str_replace('_app', '', get_class($this)), $this->controller_name));
		$controller_class = self::get_controller_class($this->controller_name);
		$this->controller = new $controller_class($this, $this->action_name);
		$return = $this->controller->render();
		if ($this->get_layout() !== false) {
			$return = str_replace('YIELD', $return, moojon_runner::render(moojon_paths::get_layout_path($this->get_layout()), $this->controller));
		}
		if ($echo) {
			echo $return;
		} else {
			return $return;
		}
	}
	
	final public function set_layout($layout) {
		$this->layout = $layout;
	}
	
	public function get_layout() {
		if ($this->layout === false) {
			return false;
		} elseif ($this->layout != null) {
			return $this->layout;
		} else {
			if (array_key_exists('X-Requested-With', $_SERVER) && $_SERVER['X-Requested-With'] == 'XMLHttpRequest') {
				return false;
			} else {
				return str_replace('_app', '', get_class($this));
			}
		}
	}
	
	protected function init() {}
	
	protected function close() {}
}
?>