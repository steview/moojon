<?php
final class moojon_model_ui extends moojon_base {
	static private function find_relationship(moojon_base_column $column, moojon_base_model $model) {
		foreach ($model->get_relationships() as $relationship) {
			if (is_subclass_of($relationship, 'moojon_base_relationship') == true && $relationship->get_foreign_key() == $column->get_name()) {
				return $relationship;
			}
		}
		return false;
	}
	
	static private function process_text(moojon_base_column $column) {
		return ucfirst(str_replace('_', ' ', moojon_primary_key::get_obj($column->get_name())));
	}
	
	static private function process_attributes(moojon_base_column $column, moojon_base_model $model) {
		return array('id' => $column->get_name(), 'name' => get_class($model).'['.$column->get_name().']');
	}
	
	static public function form(moojon_base_model $model, $column_names = array(), $attributes = array()) {
		$attributes['action'] = '#';
		$attributes['method'] = 'post';
		if ($model->get_new_record() == true) {
			$submit_value = 'Create';
			$id = 'create';
		} else {
			$submit_value = 'Update';
			$id = 'update';
		}
		$attributes['id'] = $id .= '_'.get_class($model).'_form';
		$controls = array();
		foreach ($column_names as $column_name) {
			$column = $model->get_column($column_name);
			if (get_class($column) != 'moojon_primary_key') {
				$controls[] = self::label(self::process_text($column).':', $column, $model);
			}
			$controls[] = self::control($column, $model);
		}
		return new moojon_form_tag(array(new moojon_fieldset_tag($controls, array('id' => 'controls')), self::submit_button($submit_value), self::cancel_button()), $attributes);
	}
	
	static public function dl(moojon_base_model $model, $column_names = array(), $attributes = array()) {
		$attributes['id'] = 'read_'.get_class($model).'_dl';
		$dt_dd_tags = array();
		foreach ($column_names as $column_name) {
			$column = $model->get_column($column_name);
			if (self::find_relationship($column, $model) == false) {
				$content = $column->get_value();
			} else {
				$relationship = self::find_relationship($column, $model);
				$name = $relationship->get_name();
				$content = $model->$name;
			}
			$dt_dd_tags[] = self::dt_tag($column);
			$dt_dd_tags[] = self::dd_tag($column, $content);
		}
		return new moojon_dl_tag($dt_dd_tags, $attributes);
	}
	
	static public function destroy_form(moojon_base_model $model, $message, $attributes = array()) {
		$attributes['action'] = '#';
		$attributes['method'] = 'post';
		$attributes['id'] = 'destroy_'.get_class($model).'_form';
		$controls = array(new moojon_p_tag($message));
		foreach ($model->get_primary_key_columns() as $column) {
			$controls[] = self::primary_key_tag($column, $model);
		}
		$controls[] = self::submit_button('Destroy');
		$controls[] = self::cancel_button();
		return new moojon_form_tag($controls, $attributes);
	}
	
	static public function table(moojon_model_collection $models = null, $empty_message, $column_names = array(), $attributes = array()) {
		if ($models->count > 0) {
			$model = $models->first;
			$ths = array(new moojon_th_tag('&nbsp;'));
			foreach ($column_names as $column_name) {
				if ($model->to_string_column != $column_name) {
					$column = $model->get_column($column_name);
					$ths[] = new moojon_th_tag(self::process_text($column), array('id' => $column_name.'_th'));
				}
			}
			$ths[] = new moojon_th_tag('Update');
			$ths[] = new moojon_th_tag('Destroy');
			$trs = array();
			$primary_key = moojon_primary_key::NAME;
			$counter = 0;
			foreach ($models as $model) {
				$counter ++;
				$tds = array(new moojon_td_tag(self::model_read_tag($model)));
				foreach ($column_names as $column_name) {
					if ($model->to_string_column != $column_name) {
						if (self::find_relationship($column, $model) == false) {
							$column = $model->get_column($column_name);
							$content = $column->get_value();
						} else {
							$relationship = self::find_relationship($column, $model);
							$name = $relationship->get_name();
							$content = $model->$name;
						}
						$tds[] = new moojon_td_tag($content, array('id' => get_class($model).'_'.$column_name.'_'.$model->$primary_key.'_td'));
					}
				}
				$tds[] = new moojon_td_tag(self::model_update_tag($model, array('class' => 'update', 'id' => get_class($model).'_update_'.$model->$primary_key.'_a')));
				$tds[] = new moojon_td_tag(self::model_destroy_tag($model, array('class' => 'destroy', 'id' => get_class($model).'_update_'.$model->$primary_key.'_a')));
				$trs[] = new moojon_tr_tag($tds, array('id' => get_class($model).'_'.$model->$primary_key.'_td', 'class' => 'row'.($counter % 2)));
			}
			$tds = array(new moojon_td_tag('&nbsp;'));
			foreach ($column_names as $column_name) {
				if ($model->to_string_column != $column_name) {
					$column = $model->get_column($column_name);
					$tds[] = new moojon_td_tag(null, array('id' => $name.'_td'));
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
	
	static public function cancel_button() {
		return new moojon_input_tag(array('type' => 'submit', 'name' => 'submit_button', 'value' => 'Cancel', 'id' => 'submit_cancel'));
	}
	
	static public function label($text, moojon_base_column $attributes, moojon_base_model $model) {
		$attributes = self::process_attributes($attributes, $model);
		$label_attributes['id'] = $attributes['id'].'_label';
		$label_attributes['for'] = $attributes['id'];
		return new moojon_label_tag($text, $label_attributes);
	}
	
	static public function control(moojon_base_column $column, moojon_base_model $model) {
		$control = null;
		if (self::find_relationship($column, $model) == false) {
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
		$relationship = new $relationship_name();
		$options = array();
		if ($attributes->get_null() == true) {
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
		if ($column->get_value() > 0 || $column->get_default()) {
			$attributes['checked'] = 'checked';
		}
		return new moojon_input_tag($attributes);
	}
	
	static public function date_tag(moojon_base_column $column, moojon_base_model $model) {
		$attributes = self::process_attributes($column, $model);
		$attributes['value'] = $column->get_value();
		$attributes['type'] = 'text';
		$attributes['class'] = 'date';
		return new moojon_input_tag($attributes);
	}
	
	static public function datetime_tag(moojon_base_column $column, moojon_base_model $model, $start = null, $end = null) {
		$attributes = self::process_attributes($column, $model);
		$attributes['value'] = $column->get_value();
		$attributes['type'] = 'text';
		$attributes['class'] = 'datetime';
		return new moojon_input_tag($attributes);
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
		$attributes['value'] = $column->get_value();
		$attributes['type'] = 'text';
		$attributes['class'] = 'time';
		return new moojon_input_tag($attributes);
	}
	
	static public function timestamp_tag(moojon_base_column $column, moojon_base_model $model, $start = null, $end = null) {
		return self::datetime_tag($column, $model, $start, $end);
	}
	
	static public function model_a_tag(moojon_base_model $model, $text, $action, $controller = null, $app = null, $attributes = array()) {
		$primary_key_name = moojon_primary_key::NAME;
		$primary_key_value = $model->$primary_key_name;
		return moojon_quick_tags::link_to($text, "$action/$primary_key_name/$primary_key_value", $controller, $app, $attributes);
	}
	
	static public function model_create_tag($attributes = array(), $controller = null, $app = null) {
		return moojon_quick_tags::link_to('Create', 'create', $controller, $app, $attributes);
	}
	
	static public function model_read_tag(moojon_base_model $model, $attributes = array(), $controller = null, $app = null) {
		return self::model_a_tag($model, $model, 'read', $controller, $app, $attributes);
	}
	
	static public function model_update_tag(moojon_base_model $model, $attributes = array(), $controller = null, $app = null) {
		return self::model_a_tag($model, 'Update', 'update', $controller, $app, $attributes);
	}
	
	static public function model_destroy_tag(moojon_base_model $model, $attributes = array(), $controller = null, $app = null) {
		return self::model_a_tag($model, 'Destroy', 'destroy', $controller, $app, $attributes);
	}
	
	static public function dt_tag(moojon_base_column $column, $attributes = array()) {
		$content = self::process_text($column);
		$column_name = $column->get_name();
		if ($column_name == null) {
			$column_name = str_replace(' ', '_', strtolower($column_name));
		}
		$attributes['id'] = $column_name.'_dt';
		return new moojon_dt_tag("$content:", $attributes);
	}
	
	static public function dd_tag(moojon_base_column $column, $content, $attributes = array()) {
		$column_name = $column->get_name();
		if ($column_name == null) {
			$column_name = str_replace(' ', '_', strtolower($column_name));
		}
		$attributes['id'] = $column_name.'_dd';
		return new moojon_dd_tag($content, $attributes);
	}
}
?>