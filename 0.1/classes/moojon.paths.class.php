<?php
class moojon_paths {
	private $app_path;
	private $app_class_path;
	private $controllers_path;
	private $controller_class_path;
	private $views_path;
	private $view_path;
	
	function __construct($config) {
		$this->moojon_paths($config);
	}
	
	private function moojon_paths($config) {
		$this->require_models($config);
		define('PUBLIC_PATH', PROJECT_PATH.$config->public_directory.'/');
	}
	
	public function app_paths($config, $uri) {
		$this->app_path = PROJECT_PATH.$config->apps_directory.'/'.$uri->app.'/';
		$this->app_class_path = $this->app_path.$uri->app.'.app.class.php';
		$this->controllers_path = $this->app_path.$config->controllers_directory.'/';
		$this->controller_class_path = $this->controllers_path.$uri->controller.'.controller.class.php';
		$this->views_path = $this->app_path.$config->views_directory.'/';
		$this->view_path = $this->views_path.$uri->action.'.view.php';
	}
	
	private function require_models($config) {
		moojon_files::require_directory_files(PROJECT_PATH.$config->models_directory.'/'.$config->generated_models_directory.'/');
		moojon_files::require_directory_files(PROJECT_PATH.$config->models_directory.'/');
	}
	
	public function require_app_config($config, $uri) {
		return require_once($this->app_path.'/'.$config->config_directory.'/'.$uri->app.'.config.php');
	}
	
	public function require_app_paths() {
		return require_once($this->app_class_path);
		return require_once($this->controller_class_path);
	}
}
?>