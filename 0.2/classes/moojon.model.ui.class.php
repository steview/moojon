<?php
final class moojon_model_ui extends moojon_base {
	static private function find_relationship(moojon_base_column $column, moojon_base_model $model) {
		foreach ($model->get_relationships() as $relationship) {
			if (is_subclass_of($relationship, 'moojon_base_relationship') && $relationship->get_foreign_key() == $column->get_name()) {
				return $relationship;
			}
		}
		return false;
	}
	
	static private function process_text(moojon_base_column $column) {
		return ucfirst(str_replace('_', ' ', moojon_primary_key::get_table($column->get_name())));
	}
	
	static private function process_attributes(moojon_base_column $column, moojon_base_model $model) {
		return array('id' => $column->get_name(), 'name' => get_class($model).'['.$column->get_name().']');
	}
	
	static public function find_start_year(moojon_base_column $column, moojon_base_model $model) {
		$method = 'get_'.$column->get_name().'_start_year';
		if (method_exists($model, $method)) {
			return $model->$method();
		} else {
			return moojon_config::key('start_year');
		}
	}
	
	static public function find_end_year(moojon_base_column $column, moojon_base_model $model) {
		$method = 'get_'.$column->get_name().'_end_year';
		if (method_exists($model, $method)) {
			return $model->$method();
		} else {
			return moojon_config::key('end_year');
		}
	}
	
	static public function form(moojon_base_model $model, $column_names = array(), $attributes = array()) {
		if (!array_key_exists('method', $attributes)) {
			$attributes['method'] = 'post';
		}
		$model_class = get_class($model);
		$rest_route = moojon_routes::get_rest_route(moojon_inflect::pluralize($model_class));
		if (!array_key_exists('action', $attributes)) {
			$attributes['action'] = $rest_route->get_collection_uri($model);
		}
		$controls = array();
		if ($model->get_new_record()) {
			$submit_value = 'Create';
			$id = 'create';
		} else {
			$controls[] = self::method_tag('put');
			$submit_value = 'Update';
			$id = 'edit';
		}
		if (!array_key_exists('id', $attributes)) {
			$attributes['id'] = $id .= '_'.$model_class.'_form';
		}
		foreach ($column_names as $column_name) {
			$column = $model->get_column($column_name);
			if (get_class($column) != 'moojon_primary_key') {
				$controls[] = self::label(self::process_text($column).':', $column, $model);
			}
			$controls[] = self::control($column, $model);
		}
		$controls[] = self::primary_key_tag($model->get_column($rest_route->get_id_property()), $model);
		return new moojon_form_tag(array(new moojon_fieldset_tag($controls, array('id' => 'controls')), self::submit_button($submit_value)), $attributes);
	}
	
	static public function delete_form(moojon_base_model $model, $message, $attributes = array()) {
		$attributes['method'] = 'post';
		$model_class = get_class($model);
		$rest_route = moojon_routes::get_rest_route(moojon_inflect::pluralize($model_class));
		$attributes['action'] = $rest_route->get_collection_uri($model);
		$attributes['id'] = 'delete_'.get_class($model).'_form';
		$controls = array(new moojon_p_tag($message));
		$controls[] = self::primary_key_tag($model->get_column($rest_route->get_id_property()), $model);
		$controls[] = self::method_tag('delete');
		$controls[] = self::submit_button('Delete');
		return new moojon_form_tag($controls, $attributes);
	}
	
	static public function dl(moojon_base_model $model, $column_names = array(), $attributes = array()) {
		$attributes['id'] = 'show_'.get_class($model).'_dl';
		$dt_dd_tags = array();
		foreach ($column_names as $column_name) {
			$column = $model->get_column($column_name);
			if (!self::find_relationship($column, $model)) {
				$content = $column->get_value();
			} else {
				$relationship = self::find_relationship($column, $model);
				$name = $relationship->get_name();
				$content = self::model_tag($model->$name);
			}
			$dt_dd_tags[] = self::dt_tag($column);
			$dt_dd_tags[] = self::dd_tag($column, $content);
		}
		return new moojon_dl_tag($dt_dd_tags, $attributes);
	}
	
	static public function table(moojon_model_collection $models = null, $empty_message, $column_names = array(), $attributes = array()) {
		if (!array_key_exists('cellpadding', $attributes)) {
			$attributes['cellpadding'] = 0;
		}
		if (!array_key_exists('cellspacing', $attributes)) {
			$attributes['cellspacing'] = 0;
		}
		if ($models->count) {
			$model = $models->first;
			$model_class = get_class($model);
			$ths = array(new moojon_th_tag('&nbsp;'));
			foreach ($column_names as $column_name) {
				if ($model->to_string_column != $column_name) {
					$column = $model->get_column($column_name);
					$ths[] = new moojon_th_tag(self::process_text($column), array('id' => $column_name.'_th'));
				}
			}
			$ths[] = new moojon_th_tag('Edit');
			$ths[] = new moojon_th_tag('Delete');
			$trs = array();
			$primary_key = moojon_primary_key::NAME;
			$counter = 0;
			foreach ($models as $model) {
				$counter ++;
				$tds = array(new moojon_td_tag(self::model_tag($model)));
				foreach ($column_names as $column_name) {
					if ($model->to_string_column != $column_name) {
						$column = $model->get_column($column_name);
						if (!self::find_relationship($column, $model)) {
							$content = $column->get_value();
						} else {
							$relationship = self::find_relationship($column, $model);
							$name = $relationship->get_name();
							$content = self::model_tag($model->$name);
						}
						$tds[] = new moojon_td_tag(self::format_content($column, $content), array('id' => $model_class.'_'.$column_name.'_'.$model->$primary_key.'_td'));
					}
				}
				$tds[] = new moojon_td_tag(self::model_edit_tag($model, array('class' => 'edit', 'id' => $model_class.'_edit_'.$model->$primary_key.'_a')));
				$tds[] = new moojon_td_tag(self::model_delete_tag($model, array('class' => 'delete', 'id' => $model_class.'_delete_'.$model->$primary_key.'_a')));
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
			$child = new moojon_p_tag($empty_message);
		}
		return new moojon_div_tag($child);
	}
	
	static public function submit_button($value) {
		return new moojon_input_tag(array('type' => 'submit', 'name' => 'submit_button', 'value' => $value, 'id' => 'submit_'.strtolower($value)));
	}
	
	static public function label($text, moojon_base_column $attributes, moojon_base_model $model) {
		$attributes = self::process_attributes($attributes, $model);
		$label_attributes = array();
		$label_attributes['id'] = $attributes['id'].'_label';
		$label_attributes['for'] = $attributes['id'];
		return new moojon_label_tag($text, $label_attributes);
	}
	
	static public function control(moojon_base_column $column, moojon_base_model $model) {
		$control = null;
		if (!self::find_relationship($column, $model)) {
			switch (get_class($column)) {
				case 'moojon_binary_column':
					$control = self::binary_tag($column, $model);
					break;
				case 'moojon_boolean_column':
					$control = self::boolean_tag($column, $model);
					break;
				case 'moojon_date_column':
					$control = self::date_tag($column, $model);
					break;
				case 'moojon_datetime_column':
					$control = self::datetime_tag($column, $model);
					break;
				case 'moojon_decimal_column':
					$control = self::decimal_tag($column, $model);
					break;
				case 'moojon_float_column':
					$control = self::float_tag($column, $model);
					break;
				case 'moojon_integer_column':
					$control = self::integer_tag($column, $model);
					break;
				case 'moojon_primary_key':
					$control = self::primary_key_tag($column, $model);
					break;
				case 'moojon_string_column':
					$control = self::string_tag($column, $model);
					break;
				case 'moojon_text_column':
					$control = self::text_tag($column, $model);
					break;
				case 'moojon_time_column':
					$control = self::time_tag($column, $model);
					break;
				case 'moojon_timestamp_column':
					$control = self::timestamp_tag($column, $model);
					break;
			}
		} else {
			$control = self::has_one_select($column, $model);
		}
		return $control;
	}
	
	static public function has_one_select(moojon_base_column $attributes, moojon_base_model $model) {
		$name = $attributes->get_name();
		$relationship = self::find_relationship($attributes, $model);
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
		return moojon_quick_tags::select_options($options, $model->$name, self::process_attributes($attributes, $model));
	}
	
	static public function binary_tag(moojon_base_column $column, moojon_base_model $model) {
		return self::string_tag($column);
	}
	
	static public function boolean_tag(moojon_base_column $column, moojon_base_model $model) {
		$attributes = self::process_attributes($column, $model);
		$attributes['type'] = 'checkbox';
		$attributes['value'] = '1';
		if ($column->get_value() || $column->get_default()) {
			$attributes['checked'] = 'checked';
		}
		return new moojon_input_tag($attributes);
	}
	
	static public function date_tag(moojon_base_column $column, moojon_base_model $model) {
		$attributes = self::process_attributes($column, $model);
		$attributes['class'] = 'date';
		return new moojon_div_tag(moojon_quick_tags::datetime_label_select_options($attributes, moojon_config::key('date_format'), strtotime($column->get_value()), self::find_start_year($column, $model), self::find_end_year($column, $model)), array('class' => 'date', 'id' => $attributes['name'].'_div'));
	}
	
	static public function datetime_tag(moojon_base_column $column, moojon_base_model $model) {
		$attributes = self::process_attributes($column, $model);
		$attributes['class'] = 'datetime';
		return new moojon_div_tag(moojon_quick_tags::datetime_label_select_options($attributes, moojon_config::key('datetime_format'), strtotime($column->get_value()), self::find_start_year($column, $model), self::find_end_year($column, $model)), array('class' => 'datetime', 'id' => $attributes['name'].'_div'));
	}
	
	static public function decimal_tag(moojon_base_column $column, moojon_base_model $model) {
		return self::string_tag($column, $model);
	}
	
	static public function float_tag(moojon_base_column $column, moojon_base_model $model) {
		return self::string_tag($column, $model);
	}
	
	static public function integer_tag(moojon_base_column $column, moojon_base_model $model) {
		return self::string_tag($column, $model);
	}
	
	static public function method_tag($value) {
		$attributes['name'] = '_method';
		$attributes['value'] = $value;
		$attributes['type'] = 'hidden';
		return new moojon_input_tag($attributes);
	}
	
	static public function primary_key_tag(moojon_primary_key $column, moojon_base_model $model) {
		$attributes = self::process_attributes($column, $model);
		$attributes['value'] = $column->get_value();
		$attributes['type'] = 'hidden';
		return new moojon_input_tag($attributes);
	}
	
	static public function string_tag(moojon_base_column $column, moojon_base_model $model) {
		$attributes = self::process_attributes($column, $model);
		$attributes['maxlength'] = $column->get_limit();
		$attributes['value'] = $column->get_value();
		$attributes['type'] = 'text';
		$attributes['class'] = 'text';
		return new moojon_input_tag($attributes);
	}
	
	static public function text_tag(moojon_base_column $column, moojon_base_model $model) {
		$attributes = self::process_attributes($column, $model);
		$attributes['cols'] = $column->get_limit();
		$attributes['rows'] = 'text';
		$attributes['class'] = 'textarea';
		return new moojon_textarea_tag($column->get_value(), $attributes);
	}
	
	static public function time_tag(moojon_base_column $column, moojon_base_model $model) {
		$attributes = self::process_attributes($column, $model);
		$attributes['class'] = 'time';
		return new moojon_div_tag(moojon_quick_tags::datetime_label_select_options($attributes, moojon_config::key('time_format'), strtotime($column->get_value())), array('class' => 'time', 'id' => $attributes['name'].'_div'));
	}
	
	static public function timestamp_tag(moojon_base_column $column, moojon_base_model $model, $start = null, $end = null) {
		return self::datetime_tag($column, $model, $start, $end);
	}
	
	static public function model_tag(moojon_base_model $model, $attributes = array()) {
		return new moojon_a_tag($model, array('href' => moojon_rest_route::get_member_uri($model)), $attributes);
	}
	
	static public function model_edit_tag(moojon_base_model $model, $attributes = array()) {
		return new moojon_a_tag('Edit', array('href' => moojon_rest_route::get_edit_member_uri($model)), $attributes);
	}
	
	static public function model_delete_tag(moojon_base_model $model, $attributes = array()) {
		return new moojon_a_tag('Delete', array('href' => moojon_rest_route::get_delete_member_uri($model)), $attributes);
	}
	
	static public function dt_tag(moojon_base_column $column, $attributes = array()) {
		$content = self::process_text($column);
		$column_name = $column->get_name();
		if (!$column_name) {
			$column_name = str_replace(' ', '_', strtolower($column_name));
		}
		$attributes['id'] = $column_name.'_dt';
		return new moojon_dt_tag("$content:", $attributes);
	}
	
	static public function dd_tag(moojon_base_column $column, $content, $attributes = array()) {
		$column_name = $column->get_name();
		if (!$column_name) {
			$column_name = str_replace(' ', '_', strtolower($column_name));
		}
		$content = self::format_content($column, $content);
		$attributes['id'] = $column_name.'_dd';
		return new moojon_dd_tag($content, $attributes);
	}
	
	static public function format_content(moojon_base_column $column, $content) {
		switch(get_class($column)) {
			case 'moojon_date_column':
				return self::get_datetime_format($content, moojon_config::key('date_format'));
				break;
			case 'moojon_datetime_column':
				return self::get_datetime_format($content, moojon_config::key('datetime_format'));
				break;
			case 'moojon_time_column':
				return self::get_datetime_format($content, moojon_config::key('time_format'));
				break;
			default:
				return $content;
		}
		return $content;
	}
}
?>