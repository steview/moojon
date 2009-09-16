<?php
$form = moojon_model_ui::form($post, $post->get_editable_column_names());
echo $form->render();
?>