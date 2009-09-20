<?php
function img_tag($src, $alt, $width = null, $height = null, $attributes = array()) {

}

function find_relationship(moojon_base_column $column, moojon_base_model $model) {
	foreach ($model->get_relationships() as $relationship) {
		if ($relationship->get_foreign_key() == $column->get_name()) {
			return $relationship;
		}
	}
	return false;
}

function title_text($column_name) {
	return ucfirst(str_replace('_', ' ', moojon_primary_key::get_table($column_name)));
}

function process_attributes(moojon_base_column $column, moojon_base_model $model) {
	return array('id' => $column->get_name(), 'name' => get_class($model).'['.$column->get_name().']');
}

function find_start_year(moojon_base_column $column, moojon_base_model $model) {
	$find_start_year_method = 'get_'.$column->get_name().'_start_year';
	if (method_exists($model, $find_start_year_method)) {
		return $model->$find_start_year_method();
	} else {
		return moojon_config::key('start_year');
	}
}

function find_end_year(moojon_base_column $column, moojon_base_model $model) {
	$find_end_year_method = 'get_'.$column->get_name().'_end_year';
	if (method_exists($model, $find_end_year_method)) {
		return $model->$find_end_year_method();
	} else {
		return moojon_config::key('end_year');
	}
}

function try_set_attribute($attributes, $key, $value) {
	if (!array_key_exists($key, $attributes)) {
		$attributes[$key] = $value;
	}
	return $attributes;
}

function try_set_action_attribute($attributes, moojon_base_model $model) {
	$resource = moojon_inflect::pluralize(get_class($model));
	if (moojon_routes::has_rest_route($resource)) {
		return try_set_attribute($attributes, 'action', moojon_rest_route::get_collection_uri($model));
	} else {
		return try_set_attribute($attributes, 'action', '#');
	}
}

function get_primary_key_id_property(moojon_base_model $model) {
	$resource = moojon_inflect::pluralize(get_class($model));
	if (moojon_routes::has_rest_route($resource)) {
		$rest_route = moojon_routes::get_rest_route($resource);
		return $rest_route->get_id_property();
	} else {
		return moojon_primary_key::NAME;
	}
}

function form_for(moojon_base_model $model, $column_names = array(), $attributes = array(), $error_message = null) {
	if (!$column_names) {
		$column_names = $model->get_editable_column_names();
	}
	if (!$error_message) {
		$error_message = moojon_config::key('validation_error_message');
	}
	$attributes = try_set_attribute($attributes, 'method', 'post');
	$attributes = try_set_action_attribute($attributes, $model);
	$controls = array();
	if ($model->get_new_record()) {
		$submit_value = 'Create';
		$id = 'new';
	} else {
		$controls[] = method_tag('put');
		$submit_value = 'Update';
		$id = 'edit';
	}
	$form_id = $id .= '_'.get_class($model).'_form';
	$attributes = try_set_attribute($attributes, 'id', $form_id);
	foreach ($column_names as $column_name) {
		$column = $model->get_column($column_name);
		if (get_class($column) != 'moojon_primary_key') {
			$controls[] = label(title_text($column_name).':', $column, $model);
		}
		$controls[] = control($column, $model);
	}
	$controls[] = primary_key_tag($model->get_column(get_primary_key_id_property($model)), $model);
	$children = array();
	if ($model->has_errors()) {
		$d_tags = array(new moojon_dt_tag($error_message, array('id' => $form_id.'_errors_dt')));
		foreach ($model->get_errors() as $key => $value) {
			$d_tags[] = new moojon_dd_tag(new moojon_label_tag($value, array('id' => $key.'_error_label', 'class' => 'column_error_label', 'for' => $key)), array('id' => $key.'_error_dd', 'class' => 'column_error_dd'));
		}
		$children[] = new moojon_dl_tag($d_tags, array('id' => $form_id.'_errors', 'class' => 'errors'));
	}
	$children[] = new moojon_fieldset_tag($controls, array('id' => $form_id.'_controls', 'class' => 'controls'));
	$children[] = submit_button($submit_value);
	return new moojon_form_tag($children, $attributes);
}

function delete_form_for(moojon_base_model $model, $attributes = array(), $message = null) {
	if (!$message) {
		$message = moojon_config::key('confirm_deletion_message');
	}
	$attributes = try_set_attribute($attributes, 'method', 'post');
	$attributes = try_set_action_attribute($attributes, $model);
	$attributes = try_set_attribute($attributes, 'id', 'delete_'.get_class($model).'_form');
	$controls = array(new moojon_p_tag($message));
	$controls[] = primary_key_tag($model->get_column(get_primary_key_id_property($model)), $model);
	$controls[] = method_tag('delete');
	$controls[] = submit_button('Delete');
	return new moojon_form_tag($controls, $attributes);
}

function dl_for(moojon_base_model $model, $column_names = array(), $attributes = array()) {
	if (!$column_names) {
		$column_names = $model->get_editable_column_names(array($model->get_to_string_column()));
	}
	$attributes = try_set_attribute($attributes, 'id', 'show_'.get_class($model).'_dl');
	$dt_dd_tags = array();
	foreach ($column_names as $column_name) {
		$column = $model->get_column($column_name);
		if (!find_relationship($column, $model)) {
			$content = $column->get_value();
		} else {
			$relationship = find_relationship($column, $model);
			$name = $relationship->get_name();
			$content = model_tag($model->$name);
		}
		$dt_dd_tags[] = dt_tag($column);
		$dt_dd_tags[] = dd_tag($column, $content);
	}
	return new moojon_dl_tag($dt_dd_tags, $attributes);
}

function table_for(moojon_model_collection $models, $column_names = array(), $attributes = array(), $no_records_message = null) {
	if (!$no_records_message) {
		$no_records_message = moojon_config::key('no_records_message');
	}
	if ($models->count) {
		$attributes = try_set_attribute($attributes, 'cellpadding', '0');
		$attributes = try_set_attribute($attributes, 'cellspacing', '0');
		$model = $models->first;
		if (!$column_names) {
			$column_names = $model->get_editable_column_names();
		}
		$model_class = get_class($model);
		$ths = array(new moojon_th_tag(title_text($model->get_to_string_column()), array('id' => $model->to_string_column.'_th')));
		foreach ($column_names as $column_name) {
			if ($model->to_string_column != $column_name) {
				$column = $model->get_column($column_name);
				$ths[] = new moojon_th_tag(title_text($column_name), array('id' => $column_name.'_th'));
			}
		}
		$ths[] = new moojon_th_tag('Edit');
		$ths[] = new moojon_th_tag('Delete');
		$trs = array();
		$primary_key = moojon_primary_key::NAME;
		$counter = 0;
		foreach ($models as $model) {
			$counter ++;
			$tds = array(new moojon_td_tag(model_tag($model)));
			foreach ($column_names as $column_name) {
				if ($model->to_string_column != $column_name) {
					$column = $model->get_column($column_name);
					if (!find_relationship($column, $model)) {
						$content = $column->get_value();
					} else {
						$relationship = find_relationship($column, $model);
						$name = $relationship->get_name();
						$content = model_tag($model->$name);
					}
					$tds[] = new moojon_td_tag(format_content($column, $content), array('id' => $model_class.'_'.$column_name.'_'.$model->$primary_key.'_td'));
				}
			}
			$tds[] = new moojon_td_tag(model_edit_tag($model, array('class' => 'edit', 'id' => $model_class.'_edit_'.$model->$primary_key.'_a')));
			$tds[] = new moojon_td_tag(model_delete_tag($model, array('class' => 'delete', 'id' => $model_class.'_delete_'.$model->$primary_key.'_a')));
			$trs[] = new moojon_tr_tag($tds, array('id' => $model_class.'_'.$model->$primary_key.'_tr', 'class' => 'row'.($counter % 2)));
		}
		$tds = array(new moojon_td_tag('&nbsp;'));
		foreach ($column_names as $column_name) {
			if ($model->to_string_column != $column_name) {
				$column = $model->get_column($column_name);
				$tds[] = new moojon_td_tag(null, array('id' => $column_name.'_td'));
			}
		}
		$tds[] = new moojon_td_tag('&nbsp;');
		$tds[] = new moojon_td_tag('&nbsp;');
		$children = array(
			new moojon_thead_tag(new moojon_tr_tag($ths)),
			new moojon_tbody_tag($trs), 
			new moojon_tfoot_tag(new moojon_tr_tag($tds))
		);
		$child = new moojon_table_tag($children);
		foreach ($attributes as $key => $value) {
			$child->$key = $value;
		}
	} else {
		$child = new moojon_p_tag($no_records_message);
	}
	return new moojon_div_tag($child);
}

function submit_button($value) {
	return new moojon_input_tag(array('type' => 'submit', 'name' => 'submit_button', 'value' => $value, 'id' => 'submit_'.strtolower($value)));
}

function label($text, moojon_base_column $column, moojon_base_model $model) {
	$attributes = process_attributes($column, $model);
	$label_attributes = array();
	$label_attributes['id'] = $attributes['id'].'_label';
	$label_attributes['for'] = $attributes['id'];
	$label_attributes['class'] = 'column_label';
	return new moojon_label_tag($text, $label_attributes);
}

function control(moojon_base_column $column, moojon_base_model $model) {
	$control = null;
	if (!find_relationship($column, $model)) {
		switch (get_class($column)) {
			case 'moojon_binary_column':
				$control = binary_tag($column, $model);
				break;
			case 'moojon_boolean_column':
				$control = boolean_tag($column, $model);
				break;
			case 'moojon_date_column':
				$control = date_tag($column, $model);
				break;
			case 'moojon_datetime_column':
				$control = datetime_tag($column, $model);
				break;
			case 'moojon_decimal_column':
				$control = decimal_tag($column, $model);
				break;
			case 'moojon_float_column':
				$control = float_tag($column, $model);
				break;
			case 'moojon_integer_column':
				$control = integer_tag($column, $model);
				break;
			case 'moojon_primary_key':
				$control = primary_key_tag($column, $model);
				break;
			case 'moojon_string_column':
				$control = string_tag($column, $model);
				break;
			case 'moojon_text_column':
				$control = text_tag($column, $model);
				break;
			case 'moojon_time_column':
				$control = time_tag($column, $model);
				break;
			case 'moojon_timestamp_column':
				$control = timestamp_tag($column, $model);
				break;
		}
	} else {
		$control = has_one_select($column, $model);
	}
	return $control;
}

function has_one_select(moojon_base_column $attributes, moojon_base_model $model) {
	$name = $attributes->get_name();
	$relationship = find_relationship($attributes, $model);
	$key = $relationship->get_key();
	$relationship_name = $relationship->get_name();
	$relationship = new $relationship_name;
	$options = array();
	if ($attributes->get_null()) {
		$options['Please select...'] = 0;
	}
	foreach($relationship->read() as $option) {
		$options[(String)$option] = $option->$key;
	}
	return moojon_quick_tags::select_options($options, $model->$name, process_attributes($attributes, $model));
}

function binary_tag(moojon_base_column $column, moojon_base_model $model) {
	return string_tag($column, $model);
}

function boolean_tag(moojon_base_column $column, moojon_base_model $model) {
	$attributes = process_attributes($column, $model);
	$attributes['type'] = 'checkbox';
	$attributes['value'] = '1';
	if ($column->get_value()) {
		$attributes['checked'] = 'checked';
	}
	return new moojon_div_tag(array(new moojon_input_tag(array('type' => 'hidden', 'value' => 0, 'name' => $attributes['name'])), new moojon_input_tag($attributes)), array('class' => 'boolean', 'id' => $attributes['name'].'_div'));
}

function date_tag(moojon_base_column $column, moojon_base_model $model) {
	$attributes = process_attributes($column, $model);
	$attributes['class'] = 'date';
	return new moojon_div_tag(moojon_quick_tags::datetime_label_select_options($attributes, moojon_config::key('date_format'), moojon_base::get_time($column->get_value()), find_start_year($column, $model), find_end_year($column, $model)), array('class' => 'date', 'id' => $attributes['name'].'_div'));
}

function datetime_tag(moojon_base_column $column, moojon_base_model $model) {
	$attributes = process_attributes($column, $model);
	$attributes['class'] = 'datetime';
	return new moojon_div_tag(moojon_quick_tags::datetime_label_select_options($attributes, moojon_config::key('datetime_format'), moojon_base::get_time($column->get_value()), find_start_year($column, $model), find_end_year($column, $model)), array('class' => 'datetime', 'id' => $attributes['name'].'_div'));
}

function decimal_tag(moojon_base_column $column, moojon_base_model $model) {
	return string_tag($column, $model);
}

function float_tag(moojon_base_column $column, moojon_base_model $model) {
	return string_tag($column, $model);
}

function integer_tag(moojon_base_column $column, moojon_base_model $model) {
	return string_tag($column, $model);
}

function method_tag($value) {
	$attributes['name'] = '_method';
	$attributes['value'] = $value;
	$attributes['type'] = 'hidden';
	return new moojon_input_tag($attributes);
}

function primary_key_tag(moojon_primary_key $column, moojon_base_model $model) {
	$attributes = process_attributes($column, $model);
	$attributes['value'] = $column->get_value();
	$attributes['type'] = 'hidden';
	return new moojon_input_tag($attributes);
}

function string_tag(moojon_base_column $column, moojon_base_model $model) {
	$attributes = process_attributes($column, $model);
	$attributes['maxlength'] = $column->get_limit();
	$attributes['value'] = $column->get_value();
	$attributes['type'] = 'text';
	$attributes['class'] = 'text';
	return new moojon_input_tag($attributes);
}

function text_tag(moojon_base_column $column, moojon_base_model $model) {
	$attributes = process_attributes($column, $model);
	$attributes['cols'] = $column->get_limit();
	$attributes['rows'] = 'text';
	$attributes['class'] = 'textarea';
	return new moojon_textarea_tag($column->get_value(), $attributes);
}

function time_tag(moojon_base_column $column, moojon_base_model $model) {
	$attributes = process_attributes($column, $model);
	$attributes['class'] = 'time';
	return new moojon_div_tag(moojon_quick_tags::datetime_label_select_options($attributes, moojon_config::key('time_format'), moojon_base::get_time($column->get_value())), array('class' => 'time', 'id' => $attributes['name'].'_div'));
}

function timestamp_tag(moojon_base_column $column, moojon_base_model $model, $start = null, $end = null) {
	return datetime_tag($column, $model, $start, $end);
}

function model_tag(moojon_base_model $model, $attributes = array()) {
	return new moojon_a_tag($model, array('href' => moojon_rest_route::get_member_uri($model)), $attributes);
}

function model_edit_tag(moojon_base_model $model, $attributes = array()) {
	return new moojon_a_tag('Edit', array('href' => moojon_rest_route::get_edit_member_uri($model)), $attributes);
}

function model_delete_tag(moojon_base_model $model, $attributes = array()) {
	return new moojon_a_tag('Delete', array('href' => moojon_rest_route::get_delete_member_uri($model)), $attributes);
}

function dt_tag(moojon_base_column $column, $attributes = array()) {
	$column_name = $column->get_name();
	if (!$column_name) {
		$column_name = str_replace(' ', '_', strtolower($column_name));
	}
	return new moojon_dt_tag(title_text($column_name).':', try_set_attribute($attributes, 'id', $column_name.'_dt'));
}

function dd_tag(moojon_base_column $column, $content, $attributes = array()) {
	$column_name = $column->get_name();
	if (!$column_name) {
		$column_name = str_replace(' ', '_', strtolower($column_name));
	}
	$content = format_content($column, $content);
	$attributes['id'] = $column_name.'_dd';
	return new moojon_dd_tag($content, $attributes);
}

function format_content(moojon_base_column $column, $content) {
	switch(get_class($column)) {
		case 'moojon_date_column':
			return moojon_base::get_datetime_format($content, moojon_config::key('date_format'));
			break;
		case 'moojon_datetime_column':
			return moojon_base::get_datetime_format($content, moojon_config::key('datetime_format'));
			break;
		case 'moojon_time_column':
			return moojon_base::get_datetime_format($content, moojon_config::key('time_format'));
			break;
		default:
			return $content;
	}
}
?>