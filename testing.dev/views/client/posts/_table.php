<?php
if (count($posts)) {
	$columns = $posts->first->get_editable_column_names();
} else {
	$columns = array();
}
$table = moojon_model_ui::table($posts, 'No posts available.', $columns);
echo $table->render();
?>