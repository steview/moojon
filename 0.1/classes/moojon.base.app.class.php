<?php
abstract class moojon_base_app extends moojon_base {
	
	private $controller;
	private $action_name;
	private $controller_name;
	private $app_name;
	private $layout;
	
	final public function __construct($uri) {
		$this->set_location($uri);
		$this->init();
		$this->render(true);
		$this->close();
	}
	
	final public function set_location($uri) {
		$data = moojon_uri::find($uri);
		$app = $data['app'];
		$controller = $data['controller'];
		$action = $data['action'];
		if (self::get_app_class($app) != get_class($this)) {
			$location = moojon_config::key('index_file').$uri;
			header("Location: $location");
			die();
		}
		$this->action_name = $action;
		$this->controller_name = $controller;
		$this->app_name = $app;
	}
	
	final private function render($echo = false) {
		require_once(moojon_paths::get_controller_path(str_replace('_app', '', get_class($this)), $this->controller_name));
		$controller_class = self::get_controller_class($this->controller_name);
		$this->controller = new $controller_class($this, $this->action_name);
		$return = $this->controller->render();
		if ($this->get_layout() !== false) {
			$return = str_replace('YIELD', $return, moojon_runner::render(moojon_paths::get_layout_path($this->get_layout()), get_object_vars($this->controller)));
		}
		if ($echo) {
			echo $return;
			die();
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