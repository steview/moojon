<?php
$dl = moojon_model_ui::dl($post, $post->get_editable_column_names());
echo $dl->render();
?>