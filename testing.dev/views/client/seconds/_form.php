<?php
$form = moojon_model_ui::form($second, $second->get_editable_column_names());
echo $form->render();
?>