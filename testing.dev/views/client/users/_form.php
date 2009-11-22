<?php
$form = form_for($user, $user->get_editable_column_names(array('salt')));

echo $form;
?>