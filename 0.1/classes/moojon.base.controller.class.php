<?php
abstract class moojon_base_controller extends moojon_base {
	private $app;
	protected $layout;
	protected $view;
	
	final public function __construct(moojon_base_app $app, $action, $variables = array()) {
		$this->app = $app;
		$this->headers();
		foreach ($variables as $key => $value) {
			$this->$key = $value;
		}
		$this->init();
		if (method_exists($this, $action)) {
			$this->$action();
		}
		$this->close();
		require_once(MOOJON_PATH.'/functions/moojon.view.functions.php');
		foreach (get_object_vars($this) as $key => $value) {
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
	
	public function get_layout() {
		if ($this->layout === false) {
			return false;
		} elseif ($this->layout != null) {
			return $this->layout.'.layout.php';
		} else {
			if ($_SERVER['X-Requested-With'] == 'XMLHttpRequest') {
				return false;
			} else {
				return moojon_uri::get_app().'.layout.php';
			}
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