<?php
$dl = moojon_model_ui::dl($second, $second->get_editable_column_names());
echo $dl->render();
?>