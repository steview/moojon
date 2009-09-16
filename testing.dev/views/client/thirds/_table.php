<?php
if (count($thirds)) {
	$columns = $thirds->first->get_editable_column_names();
} else {
	$columns = array();
}
$table = moojon_model_ui::table($thirds, 'No thirds available.', $columns);
echo $table->render();
?>