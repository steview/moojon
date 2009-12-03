<?php
final class moojon_rest_route extends moojon_base_route {
	private $app;
	private $method;
	private $id_property;
	private $resource;
	private $custom_collection_routes = array();
	private $custom_member_routes = array();
	private $relationship_routes = array();
	
	protected function init() {
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
		foreach ($model->get_relationships() as $relationship) {
			$this->relationship_routes[] = $relationship->get_name();
		}
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
		return $this->match_route($pattern, $params, $validate);
	}
	
	private function map_collection_routes($uris = array()) {
		$uri = implode('/', $uris);
		$params = $this->params;
		$pattern = '';
		if ($this->method == 'get') {
			if ($route = (array_key_exists('collection_routes', $this->params)) ? moojon_routes::map($uri, $this->params['collection_routes'], false) : false) {
				$pattern = $route->get_pattern();
				$params = array_merge($params, $route->get_params());
			} else if (!$uri || $uri == 'index') {
				$params['action'] = 'index';
				$pattern = 'index';
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
		$pattern = '';
		$routes = array_merge((array_key_exists('member_routes', $this->params)) ? $this->params['member_routes'] : array(), $this->get_relationship_routes());
		switch ($this->method) {
			case 'get':
				if ($routes && $route = moojon_routes::map($uri, $routes, false)) {
					$new_params = $route->get_params();
					if (array_key_exists('resource', $new_params) && $new_params['resource'] != $params['resource']) {
						$foreign_key = moojon_primary_key::get_foreign_key($this->resource);
						$id_property = $foreign_key;
						$params[$foreign_key] = $id;
					}
					$pattern = ":$id_property/".$route->get_pattern();
					$params = array_merge($params, $new_params);
				} else if (!$uri) {
					$pattern = ":$id_property/show";
					$params['action'] = 'show';
				} else if ($uri == 'edit' || $uri == 'delete' || $uri == 'show') {
					$pattern = ":$id_property/$uri";
					$params['action'] = $uri;
				}
				break;
			case 'post':
				$pattern = 'create';
				break;
			case 'put':
				$pattern = 'update';
				break;
			case 'delete':
				$pattern = 'destroy';
				break;
		}
		return $this->match_route($pattern, $params, false);
	}
	
	private function match_route($pattern, $params = array(), $validate = true) {
		if ($pattern && $this->validate_matches($params, $validate)) {
			return new moojon_route_match($pattern, $params);
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
				$relationship_routes[] = new moojon_rest_route($foreign_table, array_merge($this->params, array('controller' => $foreign_table)));
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
	
	static private function get_collection_rest_route($model) {
		$resource = (is_subclass_of($model, 'moojon_base_model')) ? moojon_inflect::pluralize(get_class($model)) : $model;
		return moojon_routes::get_rest_route($resource);
	}
	
	static public function get_relationship_collection_uri(moojon_base_model $model, $relationship_name) {
		$relationship = $model->get_relationship($relationship_name);
		$foreign_table = $relationship->get_foreign_table();
		$key_column = $relationship->get_key();
		$key = $model->$key_column;
		$return = self::get_member_uri($model)."$foreign_table/";
		if (get_class($relationship) == 'moojon_has_one_relationship') {
			$foreign_key_column = $relationship->get_foreign_key();
			$foreign_key = $model->$foreign_key_column;
			$return .= "$foreign_key/";
		}
		return $return;
	}
	
	static public function get_relationship_new_member_uri(moojon_base_model $model, $relationship_name) {
		$relationship = $model->get_relationship($relationship_name);
		$foreign_table = $relationship->get_foreign_table();
		return self::get_member_uri($model)."$foreign_table/new/";
	}
	
	static public function get_collection_uri(moojon_base_model $model) {
		$route = self::get_collection_rest_route($model);
		return moojon_config::get('index_file').$route->get_pattern().'/';
	}
	
	static public function get_member_uri(moojon_base_model $model) {
		$route = self::get_collection_rest_route($model);
		$id_property = $route->id_property;
		return self::get_collection_uri($model).$model->$id_property.'/';
	}
	
	static public function get_new_member_uri(moojon_base_model $model) {
		$route = self::get_collection_rest_route($model);
		return $route->get_member_uri($model).'new/';
	}
	
	static public function get_edit_member_uri(moojon_base_model $model) {
		$route = self::get_collection_rest_route($model);
		return $route->get_member_uri($model).'edit/';
	}
	
	static public function get_delete_member_uri(moojon_base_model $model) {
		$route = self::get_collection_rest_route($model);
		return $route->get_member_uri($model).'delete/';
	}
}
?>