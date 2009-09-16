<?php
$form = moojon_model_ui::form($third, $third->get_editable_column_names());
echo $form->render();
?>