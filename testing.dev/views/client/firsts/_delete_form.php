<?php
$delete_form = moojon_model_ui::delete_form($first, 'Please confirm destruction of '.get_class($first));
echo $delete_form->render();
?>