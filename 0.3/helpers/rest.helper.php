<?php
foreach (moojon_routes::get_rest_routes() as $rest_route) {
	$collection = $rest_route->get_pattern();
	$member = moojon_inflect::singularize($collection);
	$id_property = $rest_route->get_id_property();
	$collection_path_helper = $collection.'_path';
	$member_path_helper = $member.'_path';
	$new_member_path_helper = 'new_'.$member_path_helper;
	$edit_member_path_helper = 'edit_'.$member_path_helper;
	$delete_member_path_helper = 'delete_'.$member_path_helper;
	eval("function $collection_path_helper(){return '/$collection/';}");
	eval("function $member_path_helper(\$model){return $collection_path_helper().\$model->$id_property.'/';}");
	eval("function $new_member_path_helper(){return $collection_path_helper().'new/';}");
	eval("function $edit_member_path_helper(\$model){return $collection_path_helper().\$model->$id_property.'/edit/';}");
	eval("function $delete_member_path_helper(\$model){return $collection_path_helper().\$model->$id_property.'/delete/';}");
	$collection_uri_helper = $collection.'_uri';
	$member_uri_helper = $member.'_uri';
	$new_member_uri_helper = 'new_'.$member_uri_helper;
	$edit_member_uri_helper = 'edit_'.$member_uri_helper;
	$delete_member_uri_helper = 'delete_'.$member_uri_helper;
	eval("function $collection_uri_helper(){return '".moojon_config::get('index_file')."$collection/';}");
	eval("function $member_uri_helper(\$model){return $collection_uri_helper().\$model->$id_property.'/';}");
	eval("function $new_member_uri_helper(){return $collection_uri_helper().'new/';}");
	eval("function $edit_member_uri_helper(\$model){return $collection_uri_helper().\$model->$id_property.'/edit/';}");
	eval("function $delete_member_uri_helper(\$model){return $collection_uri_helper().\$model->$id_property.'/delete/';}");
}

function collection_tag(moojon_base_model $model, $attributes = array()) {
	return a_tag(title_text($model->get_table()), get_collection_uri($model), $attributes);
}

function edit_member_tag(moojon_base_model $model, $attributes = array()) {
	return a_tag('Edit', get_edit_member_uri($model), $attributes);
}

function delete_member_tag(moojon_base_model $model, $attributes = array()) {
	return a_tag('Delete', get_delete_member_uri($model), $attributes);
}

function new_member_tag(moojon_base_model $model, $attributes = array()) {
	return a_tag('New', get_new_member_uri($model), $attributes);
}

function member_tag(moojon_base_model $model = null, $attributes = array()) {
	return ($model) ? a_tag($model, get_member_uri($model), $attributes) : '-';
}

function get_collection_rest_route($model) {
	$resource = (is_subclass_of($model, 'moojon_base_model')) ? moojon_inflect::pluralize(get_class($model)) : $model;
	return moojon_routes::get_rest_route($resource);
}

function get_collection_uri(moojon_base_model $model) {
	$route = get_collection_rest_route($model);
	$parent_resource = '';
	$pattern = $route->get_pattern();
	if ($resource = moojon_uri::get_or_null('resource')) {
		$resource = moojon_inflect::singularize($resource);
		$resource_model = new $resource;
		$table = $model->get_table();
		$class = $model->get_class();
		if ($resource_model->has_has_many_relationship($table) || $resource_model->has_has_many_to_many_relationship($table)) {
			$parent_resource = moojon_uri::get_uri().'/';
		} else if ($resource == $class) {
			$parent_resource = moojon_uri::get_uri();
			$parent_resource = substr($parent_resource, 0, strrpos($parent_resource, '/'));
			if ($parent_resource) {
				$pattern = '';
			}
		} else if ($resource_model->has_relationship($class)) {
			$belongs_to_relationship = $resource_model->get_relationship($class);
			if (get_class($belongs_to_relationship) == 'moojon_belongs_to_relationship') {
				$parent_resource = moojon_uri::get_uri().'/';
			}
		}
	}
	return moojon_config::get('index_file').$parent_resource.$pattern.'/';
}

function get_member_uri(moojon_base_model $model) {
	$route = get_collection_rest_route($model);
	$id_property = $route->get_id_property();
	return get_collection_uri($model).$model->$id_property.'/';
}

function get_new_member_uri(moojon_base_model $model) {
	return get_collection_uri($model).'new/';
}

function get_edit_member_uri(moojon_base_model $model) {
	return get_member_uri($model).'edit/';
}

function get_delete_member_uri(moojon_base_model $model) {
	return get_member_uri($model).'delete/';
}

function rest_breadcrumb() {
	$segments = explode('/', moojon_uri::get_match_pattern());
	$count = count($segments);
	$ul = ul_tag();
	$href = '/';
	for ($i = 0; $i < $count; $i ++) {
		$segment = $segments[$i];
		$attributes = ($i == ($count - 1)) ? array('class' => 'last') : array();
		if (moojon_base::is_symbol($segment)) {
			$symbol_name = moojon_base::get_symbol_name($segment);
			$href .= moojon_request::get($symbol_name).'/';
			if ($symbol_name != moojon_primary_key::NAME) {
				$content = model_from_symbol($segment);
			} else {
				$content = model_from_id($segments[($i - 1)]);
			}
		} else {
			$href .= $segment.'/';
			$content = title_text($segment);
		}
		if ($i == ($count - 1)) {
			$ul->add_child(li_tag($content, $attributes));
		} else {
			$ul->add_child(li_tag(a_tag($content, $href), $attributes));
		}
	}
	return div_tag($ul, array('class' => 'generated breadcrumb'));
}

function model_from_symbol($symbol) {
	$symbol_name = moojon_base::get_symbol_name($symbol);
	$class = moojon_primary_key::get_class($symbol_name);
	$id = moojon_primary_key::get_id_from_foreign_key($symbol);
	$model = new $class;
	$method_name = "read_by_$id";
	return $model->$method_name(moojon_request::get($symbol_name));
}

function model_from_id($symbol) {
	$class = moojon_inflect::singularize($symbol);
	$id = moojon_primary_key::NAME;
	$model = new $class;
	$method_name = "read_by_$id";
	return $model->$method_name(moojon_request::get($id));
}
?>