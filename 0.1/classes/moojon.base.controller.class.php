<?php
abstract class moojon_base_controller extends moojon_base {
	protected $layout;
	protected $view;
	
	public function __construct() {}
	
	final public function render() {
		$action = moojon_uri::get_action();	
		if (method_exists($this, $action)) {
			$this->$action();
		}		
	}
	
	final public function get_layout() {
		if ($this->layout === false) {
			return false;
		} elseif ($this->layout != null) {
			return $this->layout.'.layout.php';
		} else {
			return moojon_uri::get_app().'.layout.php';
		}
	}
	
	final public function get_view() {
		if ($this->view) {
			return $this->view.'.view.php';
		} else {
			return moojon_uri::get_action().'.view.php';
		}
	}
}
?>