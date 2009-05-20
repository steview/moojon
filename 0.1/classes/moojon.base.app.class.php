<?php
abstract class moojon_base_app extends moojon_base {
	
	private $controller;
	private $action_name;
	private $controller_name;
	private $app_name;
	
	final public function __construct($action = null, $controller = null, $app = null) {
		$this->set_location($action, $controller, $app);
	}
	
	final public function set_location($action = null, $controller = null, $app = null) {
		if ($app != null) {
			$location = moojon_config::get('index_file').$app;
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
			$action = moojon_uri::get_action();
		}
		if ($controller == null) {
			$controller = moojon_uri::get_controller();
		}
		$this->action_name = $action;
		$this->controller_name = $controller;
		$this->app_name = $app;
		require_once(moojon_paths::get_controller_path($controller));
		$controller = $controller.'_controller';
		$this->controller = new $controller($this, $action);
		$this->close();
	}
	
	protected function init() {}
	
	protected function close() {}
}
?>