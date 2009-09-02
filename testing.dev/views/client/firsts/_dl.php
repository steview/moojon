<?php
$dl = moojon_model_ui::dl($first, $first->get_editable_column_names());
echo $dl->render();
?>