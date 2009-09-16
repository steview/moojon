<?php
$delete_form = moojon_model_ui::delete_form($third, 'Please confirm deletion of '.get_class($third));
echo $delete_form->render();
?>