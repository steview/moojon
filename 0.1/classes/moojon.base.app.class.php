<?php
abstract class moojon_base_app extends moojon_base {
	
	final public function __construct() {
		$controller = moojon_uri::get_controller();
		if (in_array(moojon_paths::get_controllers_directory()."$controller.controller.class.php", moojon_files::directory_files(moojon_paths::get_controllers_directory()))) {
			require_once(moojon_paths::get_controllers_directory()."$controller.controller.class.php");
			$controller = $controller.'_controller';
			$controller = new $controller;
			$controller->render();
		} else {
			moojon_base::handle_error("404 controller not found ($controller)");
		}
	}
	
	final public function render() {
		$this->controller->render();
	}
}
?>