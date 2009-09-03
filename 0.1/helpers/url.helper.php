<?php
function link_to($content, $action, $controller = null, $app = null, $attributes = null) {
	if (!$app) {
		$app = APP;
	}
	if (!$controller) {
		$controller = CONTROLLER;
	}
	return '<a href="'.moojon_config::key('index_file')."$app/$controller/$action\">$content</a>";
}
foreach (moojon_routes::get_rest_routes() as $route) {
	$resource = $route->get_resource();
	$singular = moojon_inflect::singularize($resource);
	$id_property = $route->get_id_property();
	
	$resource_url = $resource.'_url';
	$singular_url = $singular.'_url';

	define($resource.'_url', create_function('', "return moojon_config::key('index_file').'$resource/';"));
	define($singular.'_url', create_function('$model', "\$id_property = '$id_property';return feeds_url().\$model->$id_property.'/';"));
	define('create_'.$singular_url, create_function('$model', "return feed_url(\$model).'create/';"));
	define('update_'.$singular_url, create_function('$model', "return feed_url(\$model).'update/';"));
	define('delete_'.$singular_url, create_function('$model', "return feed_url(\$model).'destroy/';"));
}

/*feeds_url
feed_url $instance
new_feed_url
edit_feed_url $instance
delete_feed_url $instance

function feeds_url() {
	return moojon_config::key('index_file').'feeds/';
}

function feed_url($model) {
	$id_property = 'id';
	return feeds_url().$model->$id_property.'/';
}

function create_feed_url($model) {
	return feed_url($model).'create/';
}

function update_feed_url($model) {
	return feed_url($model).'update/';
}

function destroy_feed_url($model) {
	return feed_url($model).'destroy/';
}


feeds_url, '', "return moojon_config::key('index_file').'feeds/';"
feed_url moojon_base_model, '$model', "$id_property = 'id';return feeds_url().$model->$id_property.'/';"
create_feed_url, '$model', "return feed_url($model).'create/';"
update_feed_url, '$model', "return feed_url($model).'update/';"
destroy_feed_url, '$model', "return feed_url($model).'destroy/';"
*/
?>