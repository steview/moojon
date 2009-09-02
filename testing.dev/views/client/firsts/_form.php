<?php
$form = moojon_model_ui::form($first, $first->get_editable_column_names());
echo $form->render();
?>