<?php
abstract class moojon_base_app extends moojon_base {
	private $controller;
	private $action_name;
	private $controller_name;
	private $app_name;
	private $layout;
	
	final public function __construct($uri = null) {
		$this->init();
		self::require_view_functions();
		if ($uri) {
			$this->set_location($uri);
		}
		$this->close();
	}
	
	final public function set_location($uri) {
		$route_match = moojon_routes::map($uri);
		$data = $route_match->get_params();
		$this->app_name = $data['app'];
		$this->controller_name = $data['controller'];
		$this->action_name = $data['action'];
		if (self::get_app_class($this->app_name) != get_class($this)) {
			$location = moojon_config::get('index_file').$uri;
			header("Location: $location");
			die();
		}
		require_once(moojon_paths::get_controller_path($this->app_name, $this->controller_name));
		$controller_class = self::get_controller_class($this->controller_name);
		$this->controller = new $controller_class($this, $this->action_name);
	}
	
	final public function render() {
		$return = moojon_runner::render(moojon_paths::get_view_path(self::get_app_name($this), self::get_controller_name($this->controller), $this->controller->get_view()), $this->controller);
		$layout = $this->get_layout();
		if ($layout !== false) {
			$return = str_replace('YIELD', $return, moojon_runner::render(moojon_paths::get_layout_path($layout), $this->controller));
		}
		return $return;
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
			return (moojon_server::is_ajax()) ? false : self::get_app_name($this);
		}
	}
	
	protected function init() {}
	
	protected function close() {}
}
?>