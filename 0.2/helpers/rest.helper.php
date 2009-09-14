<?php
foreach (moojon_routes::get_rest_routes() as $rest_route) {
	$collection = $rest_route->get_resource();
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
	eval("function $collection_uri_helper(){return '".moojon_config::key('index_file')."$collection/';}");
	eval("function $member_uri_helper(\$model){return $collection_uri_helper().\$model->$id_property.'/';}");
	eval("function $new_member_uri_helper(){return $collection_uri_helper().'new/';}");
	eval("function $edit_member_uri_helper(\$model){return $collection_uri_helper().\$model->$id_property.'/edit/';}");
	eval("function $delete_member_uri_helper(\$model){return $collection_uri_helper().\$model->$id_property.'/delete/';}");
}
?>