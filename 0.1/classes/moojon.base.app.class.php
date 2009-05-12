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
		if ($action == null) {
			$action = moojon_uri::get_action();
		}
		if ($controller == null) {
			$controller = moojon_uri::get_controller();
		}
		$this->action_name = $ction;
		$this->controller_name = $controller;
		$this->app_name = $app;
		require_once(moojon_paths::get_controller_path($controller));
		$controller = $controller.'_controller';
		$this->controller = new $controller($this);
		$this->controller->render($action);
		$this->render();
		moojon_connection::close();
	}
	
	final public function get_layout() {
		return $this->controller->get_layout();
	}
	
	final public function get_view() {
		return $this->controller->get_view();
	}
	
	final public function get_controller_properties() {
		return get_object_vars($this->controller);
	}
	
	final public function render() {
		require_once(MOOJON_PATH.'/functions/moojon.view.functions.php');
		foreach ($this->get_controller_properties() as $key => $value) {
			$$key = $value;
		}
		foreach (helpers() as $helper) {
			helper($helper);
		}
		ob_start();
		require_once(moojon_paths::get_view_path($this->get_view()));
		define('YIELD', ob_get_clean());
		ob_end_clean();
		if ($this->get_layout() !== false) {
			require_once(moojon_paths::get_layout_path($this->get_layout()));
		} else {
			echo YIELD;
		}
	}
}
?>