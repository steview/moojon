<?php
if (count($firsts)) {
	$columns = $firsts->first->get_editable_column_names();
} else {
	$columns = array();
}
$table = moojon_model_ui::table($firsts, 'No firsts available.', $columns);
echo $table->render();
?>