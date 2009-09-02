<?php
$destroy_form = moojon_model_ui::destroy_form($first, 'Please confirm destruction of '.get_class($first));
echo $destroy_form->render();
?>