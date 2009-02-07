<?php
class moojon_request {
	public $config;
	public $paths;
	public $uri;
	public $app;
	
	function __construct($config) {
		$this->moojon_includes();
		$this->config = new moojon_config($config);
		$this->paths = new moojon_paths($this->config);
		$this->config->update(require_once(PROJECT_DIRECTORY.$this->config->config_directory.'/'.PROJECT.'.config.php'));
		$this->uri = new moojon_uri($this->config);
		$this->show_uri();

		$this->paths->app_paths($this->config, $this->uri);
		$this->config->update($this->paths->require_app_config($this->config, $this->uri));
		
		$this->paths->require_app_paths();
		
		$app = $this->config->app_prefix.$this->uri->app;
		$this->app = new $app();
		
		//?????????????????????????????
		//recall $this->files->app_paths(), create app object again, create controller object again
		//?????????????????????????????
		
		//call action of controller object here
	}
	
	private function moojon_includes() {
		require_once('moojon.config.class.php');
		require_once('moojon.files.class.php');
		require_once('moojon.paths.class.php');
		require_once('moojon.uri.class.php');
		require_once('moojon.base.class.php');
		require_once('moojon.controller.class.php');
		require_once('moojon.app.class.php');
		//require: request, model, email, node classes
	}
	
	private function app_includes() {
		
	}
	
	private function show_uri() {
		echo $this->uri->app.'<br />'.$this->uri->controller.'<br />'.$this->uri->action.'<br />';
	}
}
?>