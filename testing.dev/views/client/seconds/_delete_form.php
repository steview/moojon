<?php
$delete_form = moojon_model_ui::delete_form($second, 'Please confirm destruction of '.get_class($second));
echo $delete_form->render();
?>