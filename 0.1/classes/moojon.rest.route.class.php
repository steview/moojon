<?php
final class moojon_rest_route extends moojon_base_route {
	private $app;
	private $resource;
	private $controller;
	private $id_property = moojon_primary_key::NAME;
	
	public function map_uri($uri) {
		if (substr($uri, -1) == '/') {
			$uri = substr($uri, 0, (strlen($uri) - 1));
		}
		if (strpos($uri, '?')) {
			$uri = substr($uri, 0, strpos($uri, '?'));
		}
		$this->resource = (strpos($uri, '/')) ? substr($uri, 0, strpos($uri, '/')) : $uri;
		if ($this->resource != $this->pattern) {
			return false;
		}
		$return = $this->params;
		if ($this->app) {
			$return['app'] = $this->app;
		}
		if ($this->controller) {
			$return['controller'] = $this->controller;
		}
		$return['controller'] = (array_key_exists('controller', $return)) ? $return['controller'] : $this->resource;
		$patterns = explode('/', $this->pattern);
		$uris = explode('/', $uri);
		switch (strtolower(moojon_server::method())) {
			case 'get':
				switch (count($uris)) {
					case 1:
						$return['action'] = 'index';
						break;
					case 2:
						if ($uris[1] == 'new') {
							$return['action'] = '_new';
						} else {
							$return['action'] = 'show';
							$return[$this->id_property] = $uris[1];
						}
						break;
					case 3:
						$return[$this->id_property] = $uris[1];
						if ($uris[2] == 'delete') {
							$return['action'] = 'delete';
						} else {
							$return['action'] = 'edit';
						}
						break;
					default;
						$return = $this->resolve_custom_actions();
						break;
				} 
				break;
			case 'post':
				$return['action'] = 'create';
				break;
			case 'put':
				$return['action'] = 'update';
				$columns = moojon_post::key(moojon_inflect::singularize($this->resource));
				$return[$this->id_property] = $columns[$this->id_property];
				break;
			case 'delete':
				$return['action'] = 'destroy';
				$columns = moojon_post::key(moojon_inflect::singularize($this->resource));
				$return[$this->id_property] = $columns[$this->id_property];
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
	
	public function get_app() {
		if ($this->app) {
			return $this->app;
		} else if (array_key_exists('app', $this->params)) {
			return $this->params['app'];
		} else {
			return null;
		}
	}
	
	public function get_resource() {
		return ($this->resource) ? $this->resource : $this->pattern;
	}
	
	public function get_id_property() {
		return $this->id_property;
	}
	
	static public function get_collection_uri(moojon_base_model $model) {
		$route = moojon_routes::get_rest_route(moojon_inflect::pluralize(get_class($model)));
		return moojon_config::key('index_file').$route->resource.'/';
	}
	
	static public function get_member_uri(moojon_base_model $model) {
		$route = moojon_routes::get_rest_route(moojon_inflect::pluralize(get_class($model)));
		$id_property = $route->id_property;
		return self::get_collection_uri($model).$model->$id_property.'/';
	}
	
	static public function get_new_member_uri(moojon_base_model $model) {
		$route = moojon_routes::get_rest_route(moojon_inflect::pluralize(get_class($model)));
		return $route->get_member_uri($model).'new/';
	}
	
	static public function get_edit_member_uri(moojon_base_model $model) {
		$route = moojon_routes::get_rest_route(moojon_inflect::pluralize(get_class($model)));
		return $route->get_member_uri($model).'edit/';
	}
	
	static public function get_delete_member_uri(moojon_base_model $model) {
		$route = moojon_routes::get_rest_route(moojon_inflect::pluralize(get_class($model)));
		return $route->get_member_uri($model).'delete/';
	}
}
?>