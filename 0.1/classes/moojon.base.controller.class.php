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
		require_once(MOOJON_PATH.'/functions/moojon.view.functions.php');
		foreach (get_object_vars($this) as $key => $value) {
			$$key = $value;
		}
		foreach (helpers() as $helper) {
			helper($helper);
		}
		ob_start();
		require_once(moojon_paths::get_view_path($this->get_view()));
		$return = ob_get_clean();
		ob_end_clean();
		return $return;
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