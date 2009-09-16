<?php
$delete_form = moojon_model_ui::delete_form($post, 'Please confirm deletion of '.get_class($post));
echo $delete_form->render();
?>