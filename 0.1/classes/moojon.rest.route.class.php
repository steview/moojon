<?php
final class moojon_rest_route extends moojon_base_route {
	private $app;
	private $controller;
	
	public function map_uri($uri) {
		$resource = (strpos($uri, '/')) ? substr($uri, 0, strpos($uri, '/')) : $uri;
		if ($uri != $resource) {
			return false;
		}
		$return = $this->params;
		if ($this->app) {
			$return['app'] = $this->app;
		}
		if ($this->controller) {
			$return['controller'] = $this->controller;
		}
		$return['controller'] = (array_key_exists('controller', $return)) ? $return['controller'] : $resource;
		$patterns = explode('/', $this->pattern);
		$uris = explode('/', $uri);
		switch (strtolower(moojon_server::method())) {
			case 'get':
				
				break;
			case 'post':
				
				break;
			case 'put':
				
				break;
			case 'delete':
				
				break;
		}
		print_r($return);
		die('<br />'.$uri);
		return false;
	}
	
	public function set_app($app) {
		$this->app = $app;
	}
	
	public function set_controller($controller) {
		$this->controller = $controller;
	}
}
?>