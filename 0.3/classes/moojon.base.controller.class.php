<?php
abstract class moojon_base_controller extends moojon_base {
	protected $app;
	protected $view;
	protected $action;
	
	final public function __construct(moojon_base_app $app, $action) {
		self::require_view_functions();
		$this->app = $app;
		$this->init();
		$this->headers();
		$this->action = $action;
		if (method_exists($this, $action)) {
			$this->$action();
		}
		$this->close();
	}
	
	final public function set_layout($layout) {
		$this->app->set_layout($layout);
	}
	
	protected function headers() {}
	
	final protected function forward($uri) {
		$this->app->set_location($uri);
	}
	
	final protected function redirect($uri) {
		header("Location: $uri");
		die();
	}
	
	protected function init() {}
	
	protected function close() {}
	
	final public function get_view() {
		if ($this->view) {
			return $this->view;
		} else {
			return $this->action;
		}
	}
}
?>