<?php
function create_model_form(moojon_base_model $model) {
	$children = array();
	foreach ($model->get_columns() as $key => $value) {
		if (get_class($value) != 'moojon_primary_key') {
			$label = new moojon_label_tag(ucfirst($model->$key), array('id' => $key.'_label', 'for' => $key));
			$children[] = $label;
			$input = new moojon_input_tag(array('type' => 'text', 'id' => $key, 'name' => $key, 'value' => $model->$key));
			$children[] = $input;
		}
	}
	$fieldset = new moojon_fieldset_tag($children);
	return new moojon_form_tag($fieldset, array('method' => 'post', 'action' => '#'));
}
?>