<?php
function h1_for($content) {
	return new moojon_h1_tag($content);
}

function h2_for($content) {
	return new moojon_h2_tag($content);
}

function h3_for($content) {
	return new moojon_h3_tag($content);
}

function h4_for($content) {
	return new moojon_h4_tag($content);
}

function h5_for($content) {
	return new moojon_h5_tag($content);
}

function h6_for($content) {
	return new moojon_h6_tag($content);
}

function img_tag($src, $alt, $width = null, $height = null, $attributes = array()) {

}

function actions_ul($actions = array(), $attributes = array()) {
	if (!is_array($actions)) {
		$actions = array($actions);
	}
	$children = array();
	foreach ($actions as $action) {
		$children[] = new moojon_li_tag($action);
	}
	$attributes = try_set_attribute($attributes, 'class', 'actions');
	return new moojon_ul_tag($children, $attributes);
}

function cancel_button($value = 'Cancel', $attributes = array()) {
	if (moojon_server::has('HTTP_REFERER')) {
		$attributes = try_set_attribute($attributes, 'class', 'cancel');
		return new moojon_a_tag($value, array('href' => moojon_server::get('HTTP_REFERER')));
	}
}

function back_button($value = 'Back', $attributes = array()) {
	if (moojon_server::has('HTTP_REFERER')) {
		$attributes = try_set_attribute($attributes, 'class', 'cancel');
		return new moojon_a_tag($value, array('href' => moojon_server::get('HTTP_REFERER')));
	}
}

function uploaded_file_tag(moojon_base_model $model, $column_name, $mime_type_column = null) {
	if (!$mime_type_column) {
		$mime_type_column = $column_name.'_'.moojon_config::get('mime_type_column');
	}
	$mime_type = ($model->has_column($mime_type_column)) ? $model->$mime_type_column : moojon_files::get_mime_content_type($model->$column_name);
	$path = moojon_paths::get_public_column_upload_path($model, $column_name);
	if (substr($mime_type, 0, 5) == 'image') {
		$content = new moojon_img_tag(array('src' => $path, 'alt' => $model));
	} else {
		$content = $path;
	}
	return new moojon_a_tag($content, array('href' => $path, 'target' => '_blank'));
}

function member_tag(moojon_base_model $model, $attributes = array()) {
	return new moojon_a_tag($model, array('href' => moojon_rest_route::get_member_uri($model)), $attributes);
}

function edit_member_tag(moojon_base_model $model, $attributes = array()) {
	return new moojon_a_tag('Edit', array('href' => moojon_rest_route::get_edit_member_uri($model)), $attributes);
}

function delete_member_tag(moojon_base_model $model, $attributes = array()) {
	return new moojon_a_tag('Delete', array('href' => moojon_rest_route::get_delete_member_uri($model)), $attributes);
}

function find_relationship(moojon_base_model $model, $column_name) {
	$name = moojon_primary_key::get_table($column_name);
	return ($model->has_relationship($name)) ? $model->get_relationship($name) : false;
}

function title_text($column_name) {
	return ucfirst(str_replace('_', ' ', moojon_primary_key::get_table($column_name)));
}

function process_attributes(moojon_base_model $model, moojon_base_column $column) {
	$column_name = $column->get_name();
	return array('id' => $column_name, 'name' => model_control_name($model, $column_name));
}

function boolean_form_for(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$column_name = $column->get_name();
	$value = ($column->get_value()) ? '0' : '1';
	$src = '/'.moojon_config::get('images_directory').'/button_boolean'.$column->get_value().'.'.moojon_config::get('default_image_ext');
	$children[] = new moojon_input_tag(array('type' => 'hidden', 'name' => model_control_name($model, $column_name), 'value' => $value));
	$id_property = get_primary_key_id_property($model);
	$children[] = new moojon_input_tag(array('type' => 'hidden', 'name' => model_control_name($model, $id_property), 'value' => $model->$id_property));
	$children[] = new moojon_input_tag(array('type' => 'image', 'value' => "Set $column_name to $value", 'alt' => "Set $column_name to $value", 'value' => "Set $column_name to $value", 'src' => $src, 'class' => 'button_boolean'));
	$attributes = try_set_attribute($attributes, 'method', 'post');
	$attributes = try_set_attribute($attributes, 'class', 'generated');
	$attributes = try_set_action_attribute($attributes, $model);
	return new moojon_form_tag($children, $attributes);
}

function model_control_name(moojon_base_model $model, $column_name) {
	return get_class($model)."[$column_name]";
}

function find_start_year(moojon_base_model $model, moojon_base_column $column) {
	$find_start_year_method = 'get_'.$column->get_name().'_start_year';
	if (method_exists($model, $find_start_year_method)) {
		return $model->$find_start_year_method();
	} else {
		return moojon_config::get('start_year');
	}
}

function find_end_year(moojon_base_model $model, moojon_base_column $column) {
	$find_end_year_method = 'get_'.$column->get_name().'_end_year';
	if (method_exists($model, $find_end_year_method)) {
		return $model->$find_end_year_method();
	} else {
		return moojon_config::get('end_year');
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

function format_content(moojon_base_model $model, moojon_base_column $column, $content) {
	$column_name = $column->get_name();
	switch(get_class($column)) {
		case 'moojon_boolean_column':
			return boolean_form_for($model, $column);
			break;
		case 'moojon_date_column':
			return moojon_base::get_datetime_format($content, moojon_config::get('date_format'));
			break;
		case 'moojon_datetime_column':
			return moojon_base::get_datetime_format($content, moojon_config::get('datetime_format'));
			break;
		case 'moojon_time_column':
			return moojon_base::get_datetime_format($content, moojon_config::get('time_format'));
			break;
		case 'moojon_string_column':
			if ($column->is_file()) {
				return uploaded_file_tag($model, $column_name);
			}
			break;
	}
	return $content;
}

function form_for(moojon_base_model $model, $column_names = array(), $attributes = array(), $error_message = null) {
	if (!$column_names) {
		$column_names = $model->get_editable_column_names();
	}
	if (!$error_message) {
		$error_message = moojon_config::get('validation_error_message');
	}
	$attributes = try_set_attribute($attributes, 'method', 'post');
	$attributes = try_set_attribute($attributes, 'class', 'generated');
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
		if (get_class($column) == 'moojon_string_column' && $column->is_file()) {
			$attributes['enctype'] = 'multipart/form-data';
		}
		$controls[] = control($model, $column_name);
	}
	$controls[] = primary_key_tag($model, $model->get_column(get_primary_key_id_property($model)));
	$children = array();
	if ($model->has_errors()) {
		$d_tags = array(new moojon_dt_tag($error_message, array('id' => $form_id.'_errors_dt')));
		foreach ($model->get_errors() as $key => $value) {
			$d_tags[] = new moojon_dd_tag(new moojon_label_tag($value, array('id' => $key.'_error_label', 'class' => 'column_error_label', 'for' => $key)), array('id' => $key.'_error_dd', 'class' => 'column_error_dd'));
		}
		$children[] = new moojon_dl_tag($d_tags, array('id' => $form_id.'_errors', 'class' => 'errors'));
	}
	$children[] = new moojon_fieldset_tag($controls, array('id' => $form_id.'_controls', 'class' => 'controls'));
	$children[] = actions_ul(array(submit_tag($submit_value), cancel_button()));
	return new moojon_form_tag($children, $attributes);
}

function delete_form_for(moojon_base_model $model, $attributes = array(), $message = null) {
	if (!$message) {
		$message = moojon_config::get('confirm_deletion_message');
	}
	$attributes = try_set_attribute($attributes, 'method', 'post');
	$attributes = try_set_attribute($attributes, 'class', 'generated');
	$attributes = try_set_action_attribute($attributes, $model);
	$attributes = try_set_attribute($attributes, 'id', 'delete_'.get_class($model).'_form');
	$controls = array(new moojon_p_tag($message));
	$controls[] = primary_key_tag($model, $model->get_column(get_primary_key_id_property($model)));
	$controls[] = method_tag('delete');
	$controls[] = actions_ul(array(submit_tag('Delete'), cancel_button()));
	return new moojon_form_tag($controls, $attributes);
}

function dl_for(moojon_base_model $model, $column_names = array(), $attributes = array()) {
	if (!$column_names) {
		$column_names = $model->get_editable_column_names(array($model->get_to_string_column()));
	}
	$attributes = try_set_attribute($attributes, 'id', 'show_'.get_class($model).'_dl');
	$attributes = try_set_attribute($attributes, 'class', 'generated');
	$dt_dd_tags = array();
	foreach ($column_names as $column_name) {
		$column = $model->get_column($column_name);
		if (!$relationship = find_relationship($model, $column_name)) {
			$content = $column->get_value();
		} else {
			if (get_class($relationship) != 'has_one_relationship') {
				$name = $relationship->get_name();
				$content = member_tag($model->$name);
			}
		}
		$dt_dd_tags[] = new moojon_dt_tag(title_text($column_name).':');
		$dt_dd_tags[] = new moojon_dd_tag(format_content($model, $column, $content));
		
		
	}
	return new moojon_dl_tag($dt_dd_tags, $attributes);
}

function table_for(moojon_model_collection $models, $column_names = array(), $attributes = array(), $no_records_message = null) {
	if (!$no_records_message) {
		$no_records_message = moojon_config::get('no_records_message');
	}
	if ($models->count) {
		$attributes = try_set_attribute($attributes, 'cellpadding', '0');
		$attributes = try_set_attribute($attributes, 'cellspacing', '0');
		$attributes = try_set_attribute($attributes, 'class', 'generated');
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
			$tds = array(new moojon_td_tag(member_tag($model)));
			foreach ($column_names as $column_name) {
				if ($model->to_string_column != $column_name) {
					$column = $model->get_column($column_name);
					if (!$relationship = find_relationship($model, $column_name)) {
						$content = $column->get_value();
					} else {
						$name = $relationship->get_name();
						$content = member_tag($model->$name);
					}
					$tds[] = new moojon_td_tag(format_content($model, $column, $content), array('id' => $model_class.'_'.$column_name.'_'.$model->$primary_key.'_td'));
				}
			}
			$tds[] = new moojon_td_tag(edit_member_tag($model, array('class' => 'edit', 'id' => $model_class.'_edit_'.$model->$primary_key.'_a')));
			$tds[] = new moojon_td_tag(delete_member_tag($model, array('class' => 'delete', 'id' => $model_class.'_delete_'.$model->$primary_key.'_a')));
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

function relationship_tables(moojon_base_model $model) {
	if ($model->has_relationships()) {
		$div = new moojon_div_tag();
		foreach ($model->get_relationships() as $key => $value) {
			switch (get_class($value)) {
				case 'moojon_has_many_relationship':
				case 'moojon_has_many_to_many_relationship':
					$div->add_child(h3_for(ucfirst(str_replace('_', ' ', $key))));
					$relationship_class = moojon_inflect::singularize($value->get_foreign_table());
					$relationship = new $relationship_class;
					$foreign_key = moojon_primary_key::get_foreign_key($model->get_table());
					$key = $value->get_key();
					$key_column = $model->get_column($key);
					$div->add_child(table_for($relationship->read("$foreign_key = :$key", null, null, array(":$key" => $key_column->get_value()), array(":$key" => $key_column->get_data_type()), $model), $relationship->get_editable_column_names(array($foreign_key))));
					break;
			}
		}
		return $div;
	} else {
		return null;
	}
}

function submit_tag($value) {
	return new moojon_input_tag(array('value' => $value, 'class' => 'submit', 'type' => 'image', 'name' => 'submit_tag', 'src' => '/'.moojon_config::get('images_directory').'/button_'.strtolower($value).'.'.moojon_config::get('default_image_ext'), 'id' => 'submit_'.strtolower($value)));
}

function control(moojon_base_model $model, $column_name) {
	$column = $model->get_column($column_name);
	$return = new moojon_div_tag(new moojon_label_tag(title_text($column_name).':', array('for' => $column_name)));
	if (!find_relationship($model, $column_name)) {
		switch (get_class($column)) {
			case 'moojon_binary_column':
				$control = binary_tag($model, $column);
				break;
			case 'moojon_boolean_column':
				$control = boolean_tag($model, $column);
				break;
			case 'moojon_date_column':
				$control = date_tag($model, $column);
				break;
			case 'moojon_datetime_column':
				$control = datetime_tag($model, $column);
				break;
			case 'moojon_decimal_column':
				$control = decimal_tag($model, $column);
				break;
			case 'moojon_float_column':
				$control = float_tag($model, $column);
				break;
			case 'moojon_integer_column':
				$control = integer_tag($model, $column);
				break;
			case 'moojon_primary_key':
				$control = primary_key_tag($model, $column);
				break;
			case 'moojon_string_column':
				if ($column->is_password()) {
					return password_tag($model, $column);
				} else if ($column->is_file()) {
					return file_tag($model, $column);
				} else {
					$control = string_tag($model, $column);
				}
				break;
			case 'moojon_text_column':
				$control = text_tag($model, $column);
				break;
			case 'moojon_time_column':
				$control = time_tag($model, $column);
				break;
			case 'moojon_timestamp_column':
				$control = timestamp_tag($model, $column);
				break;
		}
	} else {
		$control = has_one_tag($model, $column);
	}
	$return->add_child($control);
	return $return;
}

function has_one_tag(moojon_base_model $model, moojon_base_column $column) {
	$name = $column->get_name();
	$relationship = find_relationship($model, $name);
	$key = $relationship->get_key();
	$relationship_name = $relationship->get_name();
	$relationship = new $relationship_name;
	$options = array();
	if ($column->get_null()) {
		$options['Please select...'] = 0;
	}
	foreach($relationship->read() as $option) {
		$options[(String)$option] = $option->$key;
	}
	return select_options($options, $model->$name, process_attributes($model, $column));
}

function binary_tag(moojon_base_model $model, moojon_base_column $column) {
	return string_tag($model, $column);
}

function boolean_tag(moojon_base_model $model, moojon_base_column $column) {
	$attributes = process_attributes($model, $column);
	$attributes['type'] = 'checkbox';
	$attributes['value'] = '1';
	if ($column->get_value()) {
		$attributes['checked'] = 'checked';
	}
	return new moojon_div_tag(array(new moojon_input_tag(array('type' => 'hidden', 'value' => 0, 'name' => $attributes['name'])), new moojon_input_tag($attributes)), array('class' => 'boolean', 'id' => $attributes['id'].'_div'));
}

function date_tag(moojon_base_model $model, moojon_base_column $column) {
	$attributes = process_attributes($model, $column);
	$attributes['class'] = 'date';
	return datetime_label_select_options($attributes, moojon_config::get('date_format'), moojon_base::get_time($column->get_value()), find_start_year($model, $column), find_end_year($model, $column));
}

function datetime_tag(moojon_base_model $model, moojon_base_column $column) {
	$attributes = process_attributes($model, $column);
	$attributes['class'] = 'datetime';
	return datetime_label_select_options($attributes, moojon_config::get('datetime_format'), moojon_base::get_time($column->get_value()), find_start_year($model, $column), find_end_year($model, $column));
}

function decimal_tag(moojon_base_model $model, moojon_base_column $column) {
	return string_tag($model, $column);
}

function float_tag(moojon_base_model $model, moojon_base_column $column) {
	return string_tag($model, $column);
}

function integer_tag(moojon_base_model $model, moojon_base_column $column) {
	return string_tag($model, $column);
}

function method_tag($value) {
	$attributes['name'] = '_method';
	$attributes['value'] = $value;
	$attributes['type'] = 'hidden';
	return new moojon_input_tag($attributes);
}

function primary_key_tag(moojon_base_model $model, moojon_base_column $column) {
	$attributes = process_attributes($model, $column);
	$attributes['value'] = $column->get_value();
	$attributes['type'] = 'hidden';
	return new moojon_input_tag($attributes);
}

function string_tag(moojon_base_model $model, moojon_base_column $column) {
	$attributes = process_attributes($model, $column);
	$attributes['maxlength'] = $column->get_limit();
	$attributes['value'] = $column->get_value();
	$attributes['type'] = 'text';
	$attributes['class'] = 'text';
	return new moojon_input_tag($attributes);
}

function text_tag(moojon_base_model $model, moojon_base_column $column) {
	$attributes = process_attributes($model, $column);
	$attributes['cols'] = 40;
	$attributes['rows'] = 6;
	$attributes['class'] = 'textarea';
	return new moojon_textarea_tag($column->get_value(), $attributes);
}

function time_tag(moojon_base_model $model, moojon_base_column $column) {
	$attributes = process_attributes($model, $column);
	$attributes['class'] = 'time';
	return datetime_label_select_options($attributes, moojon_config::get('time_format'), moojon_base::get_time($column->get_value()));
}

function timestamp_tag(moojon_base_model $model, moojon_base_column $column, $start = null, $end = null) {
	return datetime_tag($column, $model, $start, $end);
}

function password_tag(moojon_base_model $model, moojon_base_column $column) {
	$column_name = $column->get_name();
	$return = new moojon_div_tag;
	$return->add_child(new moojon_label_tag(title_text($column_name).':', array('for' => $column_name)));
	$attributes = process_attributes($model, $column);
	$attributes['value'] = $column->get_value();
	$attributes['type'] = 'password';
	$attributes['class'] = 'password';
	$return->add_child(new moojon_input_tag($attributes));
	$return->add_child(new moojon_label_tag(title_text("Confirm $column_name").':', array('for' => "confirm_$column_name")));
	$attributes['id'] = "confirm_$column_name";
	$attributes['name'] = get_class($model)."[confirm_$column_name]";
	$return->add_child(new moojon_input_tag($attributes));
	return $return;
}

function file_tag(moojon_base_model $model, moojon_base_column $column) {
	$column_name = $column->get_name();
	$value = $column->get_value();
	$return = new moojon_div_tag;
	$return->add_child(new moojon_input_tag(array('type' => 'hidden', 'name' => model_control_name($model, $column_name), 'value' => $value)));
	$return->add_child(new moojon_label_tag(title_text($column_name).':', array('for' => $column_name)));
	$attributes = process_attributes($model, $column);
	$attributes['type'] = 'file';
	$attributes['class'] = 'file';
	$return->add_child(new moojon_input_tag($attributes));
	if (!$column->get_null() && $value) {
		$return->add_child(uploaded_file_tag($model, $column_name));
		$attributes['id'] = "clear_$column_name";
		$attributes['name'] = get_class($model)."[clear_$column_name]";
		$attributes['type'] = 'checkbox';
		$attributes['class'] = 'checkbox';
		$attributes['value'] = 1;
		$return->add_child(new moojon_label_tag(title_text("Clear $column_name").':', array('for' => "clear_$column_name")));
		$return->add_child(new moojon_input_tag($attributes));
	}
	return $return;
}

function year_select_options($start = null, $end = null, $attributes = null, $selected = null, $format = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(year_options($start, $end, $format), date('Y', $selected), $attributes);
}

function year_options($start = null, $end = null, $format = null) {
	if ($format != 'Y' && $format != 'y') {
		$format = 'Y';
	}
	$return = array();
	if (!$start) {
		$start = abs(date('Y') - 50);
	}
	if (!$end) {
		$end = abs(date('Y') + 50);
	}
	for($i = min($start, $end); $i < (max($start, $end) + 1); $i ++) {
		$value = $i;
		if ($format == 'y') {
			$return[substr($value, -2)] = $value;
		} else {
			$return[$value] = $value;
		}
	}
	return $return;
}

function month_select_options($attributes = null, $selected = null, $format = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(month_options($format), date('n', $selected), $attributes);
}

function month_options($format = null) {
	if ($format != 'm' && $format != 'n') {
		$format = 'm';
	}
	$return = array();
	for($i = 1; $i < 13; $i ++) {
		if ($i < 10 && $format == 'm') {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function day_select_options($attributes = null, $selected = null, $format = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(day_options($format), date('j', $selected), $attributes);
}

function day_options($format = null) {
	if ($format != 'd' && $format != 'j') {
		$format = 'd';
	}
	$return = array();
	for($i = 1; $i < 31; $i ++) {
		if ($i < 10 && $format == 'd') {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function hour_select_options($attributes = null, $selected = null, $format = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(hour_options($format), date('H', $selected), $attributes);
}

function hour_options($format = null) {
	if ($format != 'G' && $format != 'H') {
		$format = 'G';
	}
	$return = array();
	for($i = 0; $i < 24; $i ++) {
		if ($i < 10 && $format == 'G') {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function minute_select_options($attributes = null, $selected = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(minute_options(), date('i', $selected), $attributes);
}

function minute_options() {
	$return = array();
	for($i = 0; $i < 60; $i ++) {
		if ($i < 10) {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function second_select_options($attributes = null, $selected = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(second_options(), date('s', $selected), $attributes);
}

function second_options() {
	$return = array();
	for($i = 0; $i < 60; $i ++) {
		if ($i < 10) {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function datetime_select_options($attributes = null, $format = null, $selected = null, $start = null, $end = null, $label = false) {
	if (!$format) {
		$format = moojon_config::get('datetime_format');
	}
	$return = new moojon_div_tag(null, array('class' => 'datetime'));
	$attributes = try_set_attribute($attributes, 'name', 'datetime_select');
	$attributes = try_set_attribute($attributes, 'id', $attributes['name']);
	for ($i = 0; $i < (strlen($format) + 1); $i ++) {
		$select = null;
		$select_attributes = $attributes;
		$f = substr($format, $i, 1);
		$select_attributes['name'] = $attributes['name']."[$f]";
		if ($i) {
			$select_attributes['id'] = $attributes['id']."_$f";
		} else {
			$select_attributes['id'] = $attributes['id'];
		}
		switch ($f) {
			case 'Y':
			case 'y':
				$select = year_select_options($start, $end, $select_attributes, $selected, $f);
				$label_text = 'Year';
				break;
			case 'm':
			case 'n':
				$select = month_select_options($select_attributes, $selected, $f);
				$label_text = 'Month';
				break;
			case 'd':
			case 'j':
				$select = day_select_options($select_attributes, $selected, $f);
				$label_text = 'Day';
				break;
			case 'G':
			case 'H':
				$select = hour_select_options($select_attributes, $selected, $f);
				$label_text = 'Hour';
				break;
			case 'i':
				$select = minute_select_options($select_attributes, $selected);
				$label_text = 'Minute';
				break;
			case 's':
				$select = second_select_options($select_attributes, $selected);
				$label_text = 'Second';
				break;
		}
		if ($select) {
			if ($label) {
				$return->add_child(new moojon_label_tag("$label_text:", array('for' => $select_attributes['id'])));
			}
			$return->add_child($select);
		}
	}
	return $return;
}

function datetime_label_select_options($attributes = null, $format = null, $selected = null, $start = null, $end = null) {
	return datetime_select_options($attributes, $format, $selected, $start, $end, true);
}

function select_options($options, $selected = null, $attributes = null) {
	return new moojon_select_tag(options($options, $selected), $attributes);
}

function options($data, $selected = null) {
	if (!is_array($data)) {
		$data = array($data);
	}
	$return = array();
	foreach ($data as $key => $value) {
		$attributes = array('value' => $value);
		if ($value == $selected) {
			$attributes['selected'] = 'selected';
		}
		$return[] = new moojon_option_tag($key, $attributes);
	}
	return $return;
}
?>