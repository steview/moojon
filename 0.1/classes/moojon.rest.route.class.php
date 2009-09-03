<?php
final class moojon_rest_route extends moojon_base_route {
	private $app;
	private $controller;
	private $id_property = moojon_primary_key::NAME;
	
	public function map_uri($uri) {
		if (strpos($uri, '?')) {
			$uri = substr($uri, 0, strpos($uri, '?'));
		}
		$resource = (strpos($uri, '/')) ? substr($uri, 0, strpos($uri, '/')) : $uri;
		if ($resource != $this->pattern) {
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
				switch (count($uris)) {
					case 1:
						$return['action'] = 'index';
						break;
					case 2:
						if ($uris[1] == 'create') {
							$return['action'] = 'create';
						} else {
							$return['action'] = 'read';
							$return[$this->id_property] = $uris[1];
						}
						break;
					case 3:
						$return['id'] = $uris[1];
						if ($uris[2] == 'destroy') {
							$return['action'] = 'destroy';
						} else {
							$return['action'] = 'update';
						}
						break;
					default;
						$return = $this->resolve_custom_actions();
						break;
				} 
				break;
			case 'post':
				$return['action'] = 'save';
				break;
			case 'put':
				$return['action'] = 'save';
				$return[$this->id_property] = $uris[1];
				break;
			case 'delete':
				$return['action'] = 'destroy';
				$return[$this->id_property] = $uris[1];
				break;
			default:
				return false;
				break;
		}
		return $this->validate_sections($return);
	}
	
	public function set_app($app) {
		$this->app = $app;
	}
	
	public function set_controller($controller) {
		$this->controller = $controller;
	}
	
	public function set_id_property($id_property) {
		$this->id_property = $id_property;
	}
	
	public function resolve_custom_actions() {
		return false;
	}
	
	public function get_resource() {
		return $this->pattern;
	}
	
	public function get_id_property() {
		return $this->id_property;
	}
}
?>