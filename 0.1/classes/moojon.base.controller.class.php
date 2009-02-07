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
		$layout_path = moojon_paths::get_layouts_directory().$this->get_layout();
		if (!file_exists($layout_path)) {
			self::handle_error("Layout not found ($layout_path)");
		}
		$view_path = moojon_paths::get_views_directory().$this->get_view();
		if (file_exists($view_path)) {
			ob_start();
			require_once($view_path);
			define('YIELD', ob_get_clean());
			ob_end_clean();
			require_once($layout_path);
		} else {
			moojon_base::handle_error('404 ('.$this->get_view().')');
		}
	}
	
	final private function get_layout() {
		if ($this->layout === false) {
			return false;
		} elseif ($this->layout != null) {
			return $this->layout.'.layout.php';
		} else {
			return moojon_uri::get_app().'.layout.php';
		}
	}
	
	final private function get_view() {
		if ($this->view) {
			return $this->view.'.view.php';
		} else {
			return moojon_uri::get_action().'.view.php';
		}
	}
}
?>