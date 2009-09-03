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
	$collection = $route->get_resource();
	$member = moojon_inflect::singularize($collection);
	$id_property = $route->get_id_property();
	$collection_url = $collection.'_url';
	$member_url = $member.'_url';
	$create_url = 'create_'.$member_url;
	$update_url = 'update_'.$member_url;
	$delete_url = 'url_'.$member_url;
	$$collection_url = create_function('', "return moojon_config::key('index_file').'$collection/';");
	$$member_url = create_function('$model', "\$id_property = '$id_property';return $collection_url().\$model->$id_property.'/';");
	$$create_url = create_function('', "return $$collection_url().'create/';");
	$$update_url = create_function('$model', "return $member_url(\$model).'update/';");
	$$delete_url = create_function('$model', "return $member_url(\$model).'destroy/';");
}
echo $firsts_url().'<br />';
echo $create_first_url().'<br />';
?>