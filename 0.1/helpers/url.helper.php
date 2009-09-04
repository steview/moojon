<?php
function link_to($content, $uri, $attributes = null) {
	return '<a href="'.moojon_config::key('index_file')."$uri\">$content</a>";
}
foreach (moojon_routes::get_rest_routes() as $route) {
	$collection = $route->get_resource();
	$member = moojon_inflect::singularize($collection);
	$id_property = $route->get_id_property();
	$collection_uri = $collection.'_uri';
	$member_uri = $member.'_uri';
	$create_member_uri = 'create_'.$member_uri;
	$update_member_uri = 'update_'.$member_uri;
	$destroy_member_uri = 'destroy_'.$member_uri;
	eval("function $collection_uri(){return '".moojon_config::key('index_file')."$collection/';}");
	eval("function $member_uri(\$model){return $collection_uri().\$model->$id_property.'/';}");
	eval("function $create_member_uri(){return $collection_uri().'create/';}");
	eval("function $update_member_uri(\$model){return $collection_uri().\$model->$id_property.'/update/';}");
	eval("function $destroy_member_uri(\$model){return $collection_uri().\$model->$id_property.'/destroy/';}");
}
?>