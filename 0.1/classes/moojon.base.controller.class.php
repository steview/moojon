<?php
abstract class moojon_base_controller extends moojon_base {
	protected $app;
	protected $view;
	protected $action;
	
	final public function __construct(moojon_base_app $app, $action) {
		$this->app = $app;
		$this->init();
		$this->headers();
		$this->action = $action;
		if (method_exists($this, $action)) {
			$this->$action();
		}
		$this->close();
	}
	
	final public function render() {
		return moojon_runner::render(moojon_paths::get_view_path($this->app, str_replace('_controller', '', get_class($this))), $this, $this->get_view());
	}
	
	final public function set_layout($layout) {
		$this->app->set_layout($layout);
	}
	
	protected function headers() {}
	
	final protected function forward($action = null, $controller = null, $app = null) {
		$this->app->set_location($action, $controller, $app);
	}
	
	final protected function redirect($uri) {
		header('Location: '.moojon_config::get('index_file').$uri);
		die();
	}
	
	protected function init() {}
	
	protected function close() {}
	
	final public function get_view() {
		if ($this->view) {
			return $this->view.'.view.php';
		} else {
			return $this->action.'.view.php';
		}
	}
}
?>