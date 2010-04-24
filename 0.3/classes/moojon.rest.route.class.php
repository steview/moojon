<?php
final class moojon_rest_route extends moojon_base_route {
	private $app;
	private $method;
	private $id_property;
	private $resource;
	private $custom_collection_routes = array();
	private $custom_member_routes = array();
	private $relationship_routes = array();
	private $actions = array();
	
	protected function init() {
		$this->actions = (array_key_exists('actions', $this->params)) ? $this->params['actions'] : array('index', '_new', 'show', 'edit', 'delete', 'update', 'create', 'destroy');
		$this->resource = moojon_inflect::pluralize($this->pattern);
		$this->params['resource'] = $this->resource;
		$this->app = (array_key_exists('app', $this->params)) ? $this->params['app'] : moojon_config::get_or_null('default_app');
		$this->params['app'] = $this->app;
		$this->params['controller'] = (array_key_exists('controller', $this->params)) ? $this->params['controller'] : $this->resource;
		$this->id_property = (array_key_exists('id_property', $this->params)) ? $this->params['id_property'] : moojon_primary_key::NAME;
		$this->method = strtolower(moojon_server::method());
		$this->custom_collection_routes = (array_key_exists('custom_collection_routes', $this->params)) ? $this->params['custom_collection_routes'] : array();
		$this->custom_member_routes = (array_key_exists('custom_member_routes', $this->params)) ? $this->params['custom_member_routes'] : array();
		$model_class = moojon_inflect::singularize($this->pattern);
		$model = new $model_class;
		$this->relationship_routes = $model->get_relationship_names();
	}
	
	public function map($uri, $validate = true) {
		$uris = explode('/', $uri);
		if ($uris[0] == $this->app) {
			array_shift($uris);
		}
		if (array_shift($uris) != $this->pattern) {
			return false;
		}
		$pattern = $this->resource;
		$params = $this->params;
		if ($route = $this->map_collection_routes($uris)) {
			$pattern .= '/'.$route->get_pattern();
			$params = array_merge($this->params, $route->get_params());
		} else if ($route = $this->map_member_routes($uris)) {
			$pattern .= '/'.$route->get_pattern();
			$params = array_merge($this->params, $route->get_params());
		}
		$params['resources'] = $this->resource;
		return $this->match_route($pattern, $params, $validate);
	}
	
	private function map_collection_routes($uris = array()) {
		if (count($uris) && $uris[0] == 'index') {
			array_shift($uris);
		}
		$uri = implode('/', $uris);
		$params = $this->params;
		$pattern = '';
		$custom_collection_routes = (array_key_exists('custom_collection_routes', $this->params)) ? $this->params['custom_collection_routes'] : array();
		if ($this->method == 'get') {
			if ($custom_collection_routes && $route = moojon_routes::map($uri, $custom_collection_routes, false)) {
				$pattern = $route->get_pattern();
				$params = array_merge($params, $route->get_params());
			} else if (!$uri) {
				$params['action'] = 'index';
				$pattern = '/';
			} else if ($uri == 'new') {
				$params['action'] = '_new';
				$pattern = 'new';
			}
		}
		return $this->match_route($pattern, $params, false);
	}
	
	private function map_member_routes($uris = array()) {
		$id = array_shift($uris);
		$uri = implode('/', $uris);
		$id_property = $this->id_property;
		$params = array_merge($this->params, array($id_property => $id));
		$relationship_routes = $this->get_relationship_routes();
		$params['relationship_routes'] = $relationship_routes;
		$pattern = '';
		$routes = array_merge((array_key_exists('member_routes', $this->params)) ? $this->params['member_routes'] : array(), $relationship_routes);
		if ($routes && $route = moojon_routes::map($uri, $routes, false)) {
			$new_params = $route->get_params();
			if (array_key_exists('resource', $new_params)) {
				$id_property = moojon_primary_key::get_foreign_key($this->resource);
				$params[$id_property] = $id;
			}
			$pattern = ":$id_property/".$route->get_pattern();
			$params = array_merge($params, $new_params);
		} else {
			switch ($this->method) {
				case 'get':
					if (!$uri) {
						$pattern = ":$id_property/";
						$params['action'] = 'show';
					} else if ($uri == 'edit' || $uri == 'delete' || $uri == 'show') {
						$pattern = ":$id_property/$uri";
						$params['action'] = $uri;
					}
					break;
				case 'post':
					$pattern = 'create';
					$params['action'] = $pattern;
					break;
				case 'put':
					$pattern = 'update';
					$params['action'] = $pattern;
					break;
				case 'delete':
					$pattern = 'destroy';
						$params['action'] = $pattern;
					break;
			}
		}
		return $this->match_route($pattern, $params, false);
	}
	
	private function match_route($pattern, $params = array(), $validate = true) {
		if ($pattern && $this->validate_matches($params, $validate)) {
			if (array_key_exists('action', $params) && !in_array($params['action'], $this->actions)) {
				return false;
			}
			return new moojon_route_match($pattern, $params, $this);
		} else {
			return false;
		}
	}
	
	private function get_relationship_routes() {
		$relationship_routes = array();
		$model_class = moojon_inflect::singularize($this->resource);
		if ($path = moojon_paths::get_model_path($model_class)) {
			require_once($path = moojon_paths::get_model_path($model_class));
			$model = new $model_class;
			foreach ($model->get_relationships() as $relationship) {
				$foreign_table = $relationship->get_foreign_table();
				if (moojon_routes::has_rest_route($foreign_table)) {
					$relationship_route = moojon_routes::get_rest_route($foreign_table);
				} else {
					$relationship_route = new moojon_rest_route($foreign_table, array_merge($this->params, array('controller' => $foreign_table)));
				}
				$relationship_routes[] = $relationship_route;
			}
		}
		return $relationship_routes;
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
	
	public function get_id_property() {
		return $this->id_property;
	}
	
	public function get_resource() {
		return $this->resource;
	}
	
	public function get_actions() {
		return $this->actions;
	}
}
?>