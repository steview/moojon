<?php
function actions_ul($actions = array(), $attributes = array()) {
	if (!is_array($actions)) {
		$actions = array($actions);
	}
	$children = array();
	foreach ($actions as $action) {
		$children[] = li_tag($action);
	}
	$attributes = try_set_attribute($attributes, 'class', 'actions');
	return ul_tag($children, $attributes);
}

function uploaded_file_tag(moojon_base_model $model, $column_name, $mime_type_column = null) {
	$value = $model->$column_name;
	if ($value) {
		if (!$mime_type_column) {
			$mime_type_column = $column_name.'_'.moojon_config::get('mime_type_column');
		}
		$mime_type = ($model->has_column($mime_type_column)) ? $model->$mime_type_column : moojon_files::get_mime_content_type($value);
		$path = moojon_paths::get_public_column_upload_path($model, $column_name);
		if (substr($mime_type, 0, 5) == 'image') {
			$content = img_tag($path, $model);
		} else {
			$content = $path;
		}
		return a_tag($content, $path, array('target' => '_blank'));
	} else {
		return p_tag('Not set');
	}
}

function edit_member_tag(moojon_base_model $model, $attributes = array()) {
	return a_tag('Edit', moojon_rest_route::get_edit_member_uri($model), $attributes);
}

function delete_member_tag(moojon_base_model $model, $attributes = array()) {
	return a_tag('Delete', moojon_rest_route::get_delete_member_uri($model), $attributes);
}

function new_member_tag(moojon_base_model $model, $attributes = array()) {
	return a_tag('New', moojon_rest_route::get_new_member_uri($model), $attributes);
}

function member_tag(moojon_base_model $model = null, $attributes = array()) {
	return ($model) ? a_tag($model, moojon_rest_route::get_member_uri($model), $attributes) : '-';
}

function find_has_one_relationship(moojon_base_model $model, $column_name) {
	if ($model->is_has_one_relationship_column($column_name)) {
		return $model->get_relationship_by_column($column_name);
	} else {
		return false;
	}
}

function find_belongs_to_relationship(moojon_base_model $model, $column_name) {
	if ($model->is_belongs_to_relationship_column($column_name)) {
		return $model->get_relationship_by_column($column_name);
	} else {
		return false;
	}
}

function try_set_name_and_id_attributes($attributes, moojon_base_model $model, moojon_base_column $column) {
	$column_name = $column->get_name();
	$attributes = try_set_attribute($attributes, 'name', model_control_name($model, $column_name));
	return try_set_attribute($attributes, 'id', $column_name);
}

function model_control_name($model, $column_name) {
	$model = (is_object($model)) ? get_class($model) : $model;
	return attribute_array_name($model, $column_name);
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

function try_set_action_attribute(moojon_base_model $model, $attributes) {
	$action = (moojon_routes::has_rest_route(moojon_inflect::pluralize(get_class($model)))) ? moojon_rest_route::get_collection_uri($model) : '#';
	return try_set_attribute($attributes, 'action', $action);
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
	$return = null;
	switch(get_class($column)) {
		case 'moojon_boolean_column':
			$return = boolean_form_for($model, $column);
			break;
		case 'moojon_date_column':
			$return = moojon_base::get_datetime_format($content, moojon_config::get('date_format'));
			break;
		case 'moojon_datetime_column':
			$return = moojon_base::get_datetime_format($content, moojon_config::get('datetime_format'));
			break;
		case 'moojon_time_column':
			$return = moojon_base::get_datetime_format($content, moojon_config::get('time_format'));
			break;
		case 'moojon_string_column':
			if ($column->is_file()) {
				$return = uploaded_file_tag($model, $column->get_name());
			} else if ($column->is_password()) {
				$return = str_pad('', strlen($content), '*');
			} else {
				$return = $content;
			}
			break;
		default:
			$return = $content;
			break;
	}
	return $return;
}

function form_for(moojon_base_model $model, $column_names = array(), $attributes = array(), $error_message = null) {
	if (!$column_names) {
		foreach ($model->get_editable_columns() as $column) {
			$column_names[] = $column->get_name();
		}
	}
	if (!$error_message) {
		$error_message = moojon_config::get('validation_error_message');
	}
	$attributes = try_set_attribute($attributes, 'method', 'post');
	$attributes = try_set_attribute($attributes, 'class', 'generated');
	$attributes = try_set_action_attribute($model, $attributes);
	$controls = array();
	if ($model->get_new_record()) {
		$submit_value = 'Create';
		$id = 'new';
	} else {
		$controls[] = method_tag('put');
		$controls[] = redirection_tag(moojon_server::redirection());
		$submit_value = 'Update';
		$id = 'edit';
	}
	$form_id = $id .= '_'.get_class($model).'_form';
	$attributes = try_set_attribute($attributes, 'id', $form_id);
	foreach ($column_names as $key => $column_name) {
		if (!is_numeric($key)) {
			$model->$key = $column_name;
			$column_name = $key;
		}
		$column = $model->get_column($column_name);
		if (get_class($column) == 'moojon_string_column' && $column->is_file()) {
			$attributes['enctype'] = 'multipart/form-data';
		}
		$controls[] = control($model, $column_name);
	}
	$controls[] = primary_key_tag($model, $model->get_column(get_primary_key_id_property($model)));
	$children = array();
	if ($model->has_validator_messages()) {
		$children[] = error_dl($error_message, $model->get_validator_messages(), array('id' => $form_id.'_errors'));
	}
	$children[] = fieldset_tag($controls);
	$children[] = actions_ul(array(image_input_tag('button_'.strtolower($submit_value), array('value' => $submit_value, 'class' => 'submit', 'name' => 'submit'))));
	return form_tag($children, $attributes);
}

function delete_form_for(moojon_base_model $model, $attributes = array(), $message = null) {
	if (!$message) {
		$message = moojon_config::get('confirm_deletion_message');
	}
	$attributes = try_set_attribute($attributes, 'method', 'post');
	$attributes = try_set_attribute($attributes, 'class', 'generated');
	$attributes = try_set_action_attribute($model, $attributes);
	$attributes = try_set_attribute($attributes, 'id', 'delete_'.get_class($model).'_form');
	$controls = array(p_tag($message));
	$controls[] = primary_key_tag($model, $model->get_column(get_primary_key_id_property($model)));
	$controls[] = method_tag('delete');
	$controls[] = redirection_tag(moojon_server::redirection());
	$controls[] = actions_ul(array(actions_ul(array(image_input_tag('button_delete', array('value' => 'Delete', 'class' => 'submit', 'name' => 'submit'))))));
	return form_tag($controls, $attributes);
}

function boolean_form_for(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$column_name = $column->get_name();
	$id_property = get_primary_key_id_property($model);
	$id = 'boolean_form_for_'.model_control_name($model, $column_name).$model->$id_property;
	$attributes = try_set_attribute($attributes, 'id', $id);
	$attributes = try_set_attribute($attributes, 'method', 'post');
	$attributes = try_set_attribute($attributes, 'class', 'generated');
	$attributes = try_set_action_attribute($model, $attributes);
	$src = '/'.moojon_config::get('images_directory').'/button_boolean'.$column->get_value().'.'.moojon_config::get('default_image_ext');
	$value = ($column->get_value()) ? '0' : '1';
	$children[] = hidden_input_tag(array('name' => model_control_name($model, $column_name), 'value' => $value));
	$children[] = method_tag('put');
	$children[] = redirection_tag(moojon_server::get('REQUEST_URI')."#$id");
	$children[] = hidden_input_tag(array('name' => model_control_name($model, $id_property), 'value' => $model->$id_property));
	$children[] = image_input_tag($src, array('value' => "Set $column_name to $value", 'alt' => "Set $column_name to $value", 'value' => "Set $column_name to $value", 'class' => 'button_boolean'));
	return form_tag($children, $attributes);
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
		if ($relationship = find_has_one_relationship($model, $column_name)) {
			$name = $relationship->get_name();
			$content = member_tag($model->$name);
		} else if ($relationship = find_belongs_to_relationship($model, $column_name)) {
			$name = $relationship->get_name();
			$content = member_tag($model->$name);
		} else {
			$content = $column->get_value();
		}
		$dt_dd_tags[] = dt_tag(title_text($column_name).':');
		$dt_dd_tags[] = dd_tag(format_content($model, $column, $content));
	}
	return dl_tag($dt_dd_tags, $attributes);
}

function paginator_ul_for($records, $page_symbol_name = null, $limit_symbol_name = null, $attributes = array()) {
	/*$limit_symbol_name = ($limit_symbol_name) ? $limit_symbol_name : moojon_config::get('paginator_limit_symbol_name');
	$limit = (moojon_uri::has($limit_symbol_name)) ? moojon_uri::get($limit_symbol_name) : moojon_config::get('paginator_limit');
	if ($records > $limit) {
		$page_symbol_name = ($page_symbol_name) ? $page_symbol_name : moojon_config::get('paginator_page_symbol_name');
		$page = (moojon_uri::has($page_symbol_name)) ? moojon_uri::get($page_symbol_name) : 1;
		$page = ($page < 1) ? 1 : (int)$page;
		$params = array();
		foreach (moojon_uri::get_data() as $key => $value) {
			if (is_string($value)) {
				$params[$key] = $value;
			}
		}
		$match_pattern = moojon_uri::get_match_pattern();
		$match_pattern = (substr($match_pattern, -1) != '/') ? "$match_pattern/" : $match_pattern;
		if (!array_key_exists($page_symbol_name, $params)) {
			$match_pattern .= "$page_symbol_name/:$page_symbol_name/";
		}
		$match_pattern = moojon_config::get('index_file').$match_pattern;
		$params[$limit_symbol_name] = $limit;
		$children = array();
		for ($i = 1; $i < (ceil($records / $limit) + 1); $i ++) {
			$params[$page_symbol_name] = $i;
			$attributes = ($i == $page) ? array('class' => 'selected') : array();
			$children[] = li_tag(a_tag($i, moojon_base::parse_symbols($match_pattern, $params), $attributes));
		}
		$return = ul_tag($children, array('class' => 'pagination'));
	} else {
		$return = '';
	}
	return $return;*/
}

function table_for(moojon_model_collection $models, $column_names = array(), $attributes = array(), $no_records_message = null) {
	$no_records_message = ($no_records_message) ? $no_records_message : moojon_config::get('no_records_message');
	if ($models->count) {
		$attributes = try_set_attribute($attributes, 'cellpadding', '0');
		$attributes = try_set_attribute($attributes, 'cellspacing', '0');
		$attributes = try_set_attribute($attributes, 'class', 'generated');
		$model = $models->first;
		if (!$column_names) {
			foreach ($model->get_editable_columns() as $column) {
				$column_names[] = $column->get_name();
			}
		}
		$model_class = get_class($model);
		$ths = array(th_tag(title_text($model->get_to_string_column())));
		foreach ($column_names as $column_name) {
			if ($model->to_string_column != $column_name) {
				$column = $model->get_column($column_name);
				$ths[] = th_tag(title_text($column_name));
			}
		}
		$ths[] = th_tag('Edit');
		$ths[] = th_tag('Delete');
		$trs = array();
		$primary_key = moojon_primary_key::NAME;
		$counter = 0;
		foreach ($models as $model) {
			$counter ++;
			$tds = array(td_tag(member_tag($model)));
			foreach ($column_names as $column_name) {
				if ($model->to_string_column != $column_name) {
					$column = $model->get_column($column_name);
					if ($relationship = find_has_one_relationship($model, $column_name)) {
						$name = $relationship->get_name();
						$content = member_tag($model->$name);
					} else if ($relationship = find_belongs_to_relationship($model, $column_name)) {
						$name = $relationship->get_name();
						$content = member_tag($model->$name);
					} else {
						$content = $model->$column_name;
					}
					$tds[] = td_tag(format_content($model, $column, $content));
				}
			}
			$tds[] = td_tag(edit_member_tag($model));
			$tds[] = td_tag(delete_member_tag($model));
			$trs[] = tr_tag($tds, array('class' => 'row'.($counter % 2)));
		}
		$children = array(thead_tag(tr_tag($ths)), tbody_tag($trs), tfoot_tag(tr_tag(td_tag(paginator_ul_for(count($model->read())), array('colspan' => count($ths))))));
		$child = table_tag($children, $attributes);
	} else {
		$child = p_tag($no_records_message);
	}
	return div_tag($child);
}

function relationship_tables(moojon_base_model $model, $relationship_names = array()) {
	if ($model->has_relationships()) {
		$relationship_names = ($relationship_names) ? $relationship_names : $model->get_relationship_names();
		$div = div_tag();
		foreach ($relationship_names as $relationship_name) {
			$key = $relationship_name;
			$value = $model->get_relationship($relationship_name);
			switch (get_class($value)) {
				case 'moojon_has_many_relationship':
				case 'moojon_has_many_to_many_relationship':
					$relationship_class = $value->get_class();
					$relationship = new $relationship_class;
					$div->add_child(h3_tag(title_text($key)));
					$div->add_child(actions_ul(array(new_member_tag($relationship))));
					$div->add_child('<br /><br /><br />');
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

function control(moojon_base_model $model, $column_name, $attributes = array()) {
	$column = $model->get_column($column_name);
	$return = div_tag(label_tag(title_text($column_name).':', $column_name));
	$has_one_relationship = find_has_one_relationship($model, $column_name);
	$belongs_to_relationship = find_belongs_to_relationship($model, $column_name);
	if (!$has_one_relationship && !$belongs_to_relationship) {
		switch (get_class($column)) {
			case 'moojon_binary_column':
				$control = binary_tag($model, $column, $attributes);
				break;
			case 'moojon_boolean_column':
				$control = boolean_tag($model, $column, $attributes);
				break;
			case 'moojon_date_column':
				$control = date_tag($model, $column, $attributes);
				break;
			case 'moojon_datetime_column':
				$control = datetime_tag($model, $column, $attributes);
				break;
			case 'moojon_decimal_column':
				$control = decimal_tag($model, $column, $attributes);
				break;
			case 'moojon_float_column':
				$control = float_tag($model, $column, $attributes);
				break;
			case 'moojon_integer_column':
				$control = integer_tag($model, $column, $attributes);
				break;
			case 'moojon_primary_key':
				$control = primary_key_tag($model, $column, $attributes);
				break;
			case 'moojon_string_column':
				if ($column->is_password()) {
					return password_tag($model, $column, $attributes);
				} else if ($column->is_file()) {
					return file_tag($model, $column, $attributes);
				} else {
					$control = string_tag($model, $column, $attributes);
				}
				break;
			case 'moojon_text_column':
				$control = text_tag($model, $column, $attributes);
				break;
			case 'moojon_time_column':
				$control = time_tag($model, $column, $attributes);
				break;
			case 'moojon_timestamp_column':
				$control = timestamp_tag($model, $column, $attributes);
				break;
		}
	} else if ($has_one_relationship) {
		$control = has_one_tag(null, $model, $column, $has_one_relationship, $attributes);
		if (get_class($control) != 'moojon_select_tag') {
			$return->clear_children();
		}
	} else if ($belongs_to_relationship) {
		$control = belongs_to_tag(null, $model, $column, $belongs_to_relationship, $attributes);
		if (get_class($control) != 'moojon_select_tag') {
			$return->clear_children();
		}
	}
	$return->add_child($control);
	return $return;
}

function has_one_tag(moojon_base_model_collection $models = null, moojon_base_model $model, moojon_base_column $column, moojon_base_relationship $relationship, $attributes = array()) {
	$return = null;
	$name = $column->get_name();
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	if ($value = moojon_request::get_or_null($name)) {
		$attributes['value'] = $value;
		$return = div_tag(array(hidden_input_tag($attributes), redirection_tag(moojon_server::redirection())));
	} else {
		$foreign_key = $relationship->get_foreign_key();
		$key = $relationship->get_key();
		$relationship_name = $relationship->get_class($model);
		$relationship = new $relationship_name;
		$options = array();
		if ($column->get_null()) {
			$options['Please select...'] = 0;
		}
		$models = ($models) ? $models : $relationship->read();
		foreach($models as $option) {
			$options[(String)$option] = $option->$key;
		}
		$selected = ($model->$name) ? $model->$name : moojon_uri::get_or_null($foreign_key);
		$return = select_options($options, $selected, $attributes);
	}
	return $return;
}

function belongs_to_tag(moojon_base_model_collection $models = null, moojon_base_model $model, moojon_base_column $column, moojon_base_relationship $relationship, $attributes = array()) {
	$return = false;
	$name = $column->get_name();
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$foreign_key = $relationship->get_foreign_key();
	$return = div_tag();
	if ($value = moojon_request::get_or_null($name)) {
		$return->add_child(redirection_tag(moojon_server::redirection()));
		if ($model->$foreign_key == $value) {
			$value = 0;
		}
	} else {
		$value = 0;
	}
	$attributes['value'] = $value;
	$return->add_child(hidden_input_tag($attributes));
	return $return;
}

function binary_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	return string_tag($model, $column, $attributes);
}

function boolean_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['type'] = 'checkbox';
	$attributes['value'] = '1';
	if ($column->get_value()) {
		$attributes['checked'] = 'checked';
	}
	return div_tag(array(hidden_input_tag(array('value' => 0, 'name' => $attributes['name'])), input_tag($attributes)), array('class' => 'boolean'));
}

function date_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['class'] = 'date';
	return datetime_label_select_options($attributes, moojon_config::get('date_format'), moojon_base::get_time($column->get_value()), find_start_year($model, $column), find_end_year($model, $column));
}

function datetime_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['class'] = 'datetime';
	return datetime_label_select_options($attributes, moojon_config::get('datetime_format'), moojon_base::get_time($column->get_value()), find_start_year($model, $column), find_end_year($model, $column));
}

function decimal_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	return string_tag($model, $column, $attributes);
}

function float_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	return string_tag($model, $column, $attributes);
}

function integer_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	return string_tag($model, $column, $attributes);
}

function primary_key_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['value'] = $column->get_value();
	return hidden_input_tag($attributes);
}

function string_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['maxlength'] = $column->get_limit();
	$attributes['value'] = $column->get_value();
	$attributes['class'] = 'text';
	return text_input_tag($attributes);
}

function text_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['cols'] = 40;
	$attributes['rows'] = 6;
	$attributes['class'] = 'textarea';
	return textarea_tag($column->get_value(), $attributes);
}

function time_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['class'] = 'time';
	return datetime_label_select_options($attributes, moojon_config::get('time_format'), moojon_base::get_time($column->get_value()));
}

function timestamp_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	return datetime_tag($column, $model, $attributes);
}

function password_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$column_name = $column->get_name();
	$return = div_tag();
	$return->add_child(label_tag(title_text($column_name).':', $column_name));
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['value'] = $column->get_value();
	$attributes['class'] = 'password';
	$return->add_child(password_input_tag($attributes));
	$return->add_child(label_tag(title_text("Confirm $column_name").':', "confirm_$column_name"));
	$attributes['id'] = "confirm_$column_name";
	$attributes['name'] = model_control_name($model, "confirm_$column_name");
	$return->add_child(password_input_tag($attributes));
	return $return;
}

function file_tag(moojon_base_model $model, moojon_base_column $column, $attributes = array()) {
	$column_name = $column->get_name();
	$value = $column->get_value();
	$return = div_tag();
	$return->add_child(hidden_input_tag(array('name' => model_control_name($model, $column_name), 'value' => $value)));
	$return->add_child(label_tag(title_text($column_name).':', $column_name));
	$attributes = try_set_name_and_id_attributes($attributes, $model, $column);
	$attributes['class'] = 'file';
	$return->add_child(file_input_tag($attributes));
	if (!$column->get_null() && $value) {
		$return->add_child(uploaded_file_tag($model, $column_name));
		$attributes['id'] = "clear_$column_name";
		$attributes['name'] = model_control_name($model, "clear_$column_name");
		$attributes['class'] = 'checkbox';
		$attributes['value'] = 1;
		$return->add_child(label_tag(title_text("Clear $column_name").':', "clear_$column_name"));
		$return->add_child(checkbox_input_tag($attributes));
	}
	return $return;
}
?>