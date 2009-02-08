<?php
abstract class moojon_base_controller extends moojon_base {
	protected $app;
	protected $layout;
	protected $view;
	
	public function __construct(moojon_base_app $app) {
		$this->app = $app;
	}
	
	final public function render($action) {
		if (method_exists($this, $action)) {
			$this->$action();
		}		
	}
	
	final protected function forward($action = null, $controller = null, $app = null) {
		$this->app->set_location($action, $controller, $app);
	}
	
	final protected function redirect($uri) {
		header("Location: /index.php/$uri");
		die();
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