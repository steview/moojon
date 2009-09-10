<?php
if (count($seconds)) {
	$columns = $seconds->first->get_editable_column_names();
} else {
	$columns = array();
}
$table = moojon_model_ui::table($seconds, 'No seconds available.', $columns);
echo $table->render();
?>