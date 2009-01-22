<?php
abstract class moojon_base_controller extends moojon_base {
	public function __construct() {}
	
	final public function render() {
		$action = moojon_uri::get_action();
		if (method_exists($this, $action)) {
			$this->$action();
		}
		if ($view_path = moojon_files::find_view_path($action)) {
			require_once($view_path);
		} else {
			moojon_base::handle_error("404 ($action)");
		}
	}
}
?>