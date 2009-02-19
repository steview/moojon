 <?php
final class moojon_model_form extends moojon_form_tag {
	private $model;
	
	public function __construct(moojon_base_model $model, $action = null, $attributes = null) {
		$this->init();
		if ($action == null) {
			$action = '#';
		}
		if (is_array($attributes) == true) {
			foreach ($attributes as $key => $value) {
				$this->$key = $value;
			}
		}
		$this->model = $model;
		$this->action = $action;
		$this->method = 'post';
		$fieldset = new moojon_fieldset_tag();
		$fieldset->id = 'controls';
		foreach ($this->model->get_columns() as $column) {
			$column_name = $column->get_name();
			$label = true;
			if ($this->find_relationship($column_name) == false) {
				$tag = null;
				switch (get_class($column)) {
					case 'moojon_binary_column':
						$tag = moojon_quick_tags::binary_tag($column);
						break;
					case 'moojon_boolean_column':
						$tag = moojon_quick_tags::boolean_tag($column);
						break;
					case 'moojon_date_column':
						$tag = moojon_quick_tags::date_tag($column);
						break;
					case 'moojon_datetime_column':
						$tag = moojon_quick_tags::datetime_tag($column);
						break;
					case 'moojon_decimal_column':
						$tag = moojon_quick_tags::decimal_tag($column);
						break;
					case 'moojon_float_column':
						$tag = moojon_quick_tags::float_tag($column);
						break;
					case 'moojon_integer_column':
						$tag = moojon_quick_tags::integer_tag($column);
						break;
					case 'moojon_primary_key':
						$tag = moojon_quick_tags::primary_key_tag($column);
						$label = false;
						break;
					case 'moojon_string_column':
						$tag = moojon_quick_tags::string_tag($column);
						break;
					case 'moojon_text_column':
						$tag = moojon_quick_tags::text_tag($column);
						break;
					case 'moojon_time_column':
						$tag = moojon_quick_tags::time_tag($column);
						break;
					case 'moojon_timestamp_column':
						$tag = moojon_quick_tags::timestamp_tag($column);
						break;
				}
			} else {
				$name = $column->get_name();
				$relationship = $this->find_relationship($name);
				$key = $relationship->get_key();
				$relationship_name = $relationship->get_name();
				$relationship = new $relationship_name();
				$options = array();
				if ($column->get_null() == false) {
					$options['Please select...'] = 0;
				}
				foreach($relationship->read() as $option) {
					$options[(String)$option] = $option->$key;
				}
				$tag = moojon_quick_tags::select_options($options, $model->$key, array('name' => $name, 'id' => $name));
			}
			if ($label) {
				$fieldset->add_child(moojon_quick_tags::label(self::process_text($column).':', $column));
			}
			if ($tag != null) {
				$fieldset->add_child($tag);
			}
		}
		$this->add_child($fieldset);
		if ($model->get_new_record() == true) {
			$submit_value = 'Create';
		} else {
			$submit_value = 'Update';
		}
		$this->add_child(new moojon_input_tag(array('name' => 'submit', 'value' => $submit_value, 'type' => 'submit')));
		$this->add_child(new moojon_input_tag(array('name' => 'submit', 'value' => 'Cancel', 'type' => 'submit')));
	}
	
	private function find_relationship($column_name) {
		foreach ($this->model->get_relationships() as $relationship) {
			if ($relationship->get_foreign_key() == $column_name) {
				return $relationship;
			}
		}
		return false;
	}
	
	static private function process_text(moojon_base_column $column) {
		return ucfirst(str_replace('_', ' ', moojon_primary_key::get_obj($column->get_name())));
	}
}

final class moojon_model_table extends moojon_table_tag {
	private $model;
	
	public function __construct(moojon_base_model $model, $attributes = null) {
		$this->init();
		$this->model = $model;
		$ths = array();
		foreach ($this->model->get_columns() as $column) {
			$name = $column->get_name();
			$ths[] = new moojon_th_tag(self::process_text($column), array('id' => $name.'_th'));
		}
		$tr = new moojon_tr_tag($ths);
		$thead = new moojon_thead_tag($tr);
		$this->add_child($thead);
	}
	
	static private function process_text(moojon_base_column $column) {
		return ucfirst(str_replace('_', ' ', moojon_primary_key::get_obj($column->get_name())));
	}
}

final class moojon_quick_tags extends moojon_base {
	private function __construct() {}
	
	static private function process_attributes($attributes) {
		if (is_subclass_of($attributes, 'moojon_base_column') == true) {
			$name = $attributes->get_name();
			$id = $name;
			$attributes = array('id' => $id, 'name' => $name);
		}
		return $attributes;
	}
	
	static public function label($text, $attributes = null) {
		$attributes = self::process_attributes($attributes);
		$label_attributes['id'] = $attributes['id'].'_label';
		$label_attributes['for'] = $attributes['id'];
		return new moojon_label_tag($text, $label_attributes);
	}
	
	static public function binary_tag(moojon_base_column $column) {
		return self::string_tag($column);
	}
	
	static public function boolean_tag(moojon_base_column $column) {
		$attributes = self::process_attributes($column);
		$attributes['type'] = 'checkbox';
		$attributes['value'] = '1';
		if ($column->get_value() > 0 || $column->get_default()) {
			$attributes['checked'] = 'checked';
		}
		return new moojon_input_tag($attributes);
	}
	
	static public function date_tag(moojon_base_column $column) {
		$attributes = self::process_attributes($column);
		$attributes['class'] = 'datetime';
		$children = array();
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[Y]';
		$select_attributes['id'] = $select_attributes['id'].'[Y]';
		$children[] = self::year_select_options($start, $end, $select_attributes, $column->get_value(), 'Y');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[m]';
		$select_attributes['id'] = $select_attributes['id'].'[m]';
		$children[] = self::month_select_options($select_attributes, $column->get_value(), 'm');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[d]';
		$select_attributes['id'] = $select_attributes['id'].'[d]';
		$children[] = self::day_select_options($select_attributes, $column->get_value(), 'd');
		return new moojon_div_tag($children, array('id' => $attributes['name'].'_div'));
	}
	
	static public function datetime_tag(moojon_base_column $column, $start = null, $end = null) {
		$attributes = self::process_attributes($column);
		$attributes['class'] = 'datetime';
		$children = array();
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[Y]';
		$select_attributes['id'] = $select_attributes['id'].'[Y]';
		$children[] = self::year_select_options($start, $end, $select_attributes, $column->get_value(), 'Y');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[m]';
		$select_attributes['id'] = $select_attributes['id'].'[m]';
		$children[] = self::month_select_options($select_attributes, $column->get_value(), 'm');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[d]';
		$select_attributes['id'] = $select_attributes['id'].'[d]';
		$children[] = self::day_select_options($select_attributes, $column->get_value(), 'd');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[G]';
		$select_attributes['id'] = $select_attributes['id'].'[G]';
		$children[] = self::hour_select_options($select_attributes, $column->get_value(), 'G');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[i]';
		$select_attributes['id'] = $select_attributes['id'].'[i]';
		$children[] = self::minute_select_options($select_attributes, $column->get_value(), 'i');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[s]';
		$select_attributes['id'] = $select_attributes['id'].'[s]';
		$children[] = self::second_select_options($select_attributes, $column->get_value(), 's');
		return new moojon_div_tag($children, array('id' => $attributes['name'].'_div'));
	}
	
	static public function decimal_tag(moojon_base_column $column) {
		return self::string_tag($column);
	}
	
	static public function float_tag(moojon_base_column $column) {
		return self::string_tag($column);
	}
	
	static public function integer_tag(moojon_base_column $column) {
		return self::string_tag($column);
	}
	
	static public function primary_key_tag(moojon_primary_key $column) {
		$attributes = self::process_attributes($column);
		$attributes['maxlength'] = $column->get_limit();
		$attributes['value'] = $column->get_value();
		$attributes['type'] = 'hidden';
		return new moojon_input_tag($attributes);
	}
	
	static public function string_tag(moojon_base_column $column) {
		$attributes = self::process_attributes($column);
		$attributes['maxlength'] = $column->get_limit();
		$attributes['value'] = $column->get_value();
		$attributes['type'] = 'text';
		$attributes['class'] = 'text';
		return new moojon_input_tag($attributes);
	}
	
	static public function text_tag(moojon_base_column $column) {
		$attributes = self::process_attributes($column);
		$attributes['cols'] = $column->get_limit();
		$attributes['rows'] = 'text';
		$attributes['class'] = 'textarea';
		return new moojon_textarea_tag($column->get_value(), $attributes);
	}
	
	static public function time_tag(moojon_base_column $column) {
		$attributes = self::process_attributes($column);
		$attributes['class'] = 'datetime';
		$children = array();
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[G]';
		$select_attributes['id'] = $select_attributes['id'].'[G]';
		$children[] = self::hour_select_options($select_attributes, $column->get_value(), 'G');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[i]';
		$select_attributes['id'] = $select_attributes['id'].'[i]';
		$children[] = self::minute_select_options($select_attributes, $column->get_value(), 'i');
		$select_attributes = $attributes;
		$select_attributes['name'] = $select_attributes['name'].'[s]';
		$select_attributes['id'] = $select_attributes['id'].'[s]';
		$children[] = self::second_select_options($select_attributes, $column->get_value(), 's');
		return new moojon_div_tag($children, array('id' => $attributes['name'].'_div'));
	}
	
	static public function timestamp_tag(moojon_base_column $column, $start = null, $end = null) {
		return self::datetime_tag($column, $start, $end);
	}
	
	static public function year_select_options($start = null, $end = null, $attributes = null, $selected = null, $format = null) {
		if ($selected == null) {
			$selected = date('Y');
		}
		return self::select_options(self::year_options($start, $end, $format), $selected, $attributes);
	}
	
	static public function year_options($start = null, $end = null, $format = null) {
		if ($format != 'Y' && $format != 'y') {
			$format = 'Y';
		}
		$years = array();
		if ($start == null) {
			$start = abs(date('Y') - 50);
		}
		if ($end == null) {
			$end = abs(date('Y') + 50);
		}
		for($i = min($start, $end); $i < (max($start, $end) + 1); $i ++) {
			$value = $i;
			if ($format == 'y') {
				$years[substr($value, -2)] = $value;
			} else {
				$years[$value] = $value;
			}
		}
		return $years;
	}
	
	static public function month_select_options($attributes = null, $selected = null, $format = null) {
		if ($selected == null) {
			$selected = date('n');
		}
		return self::select_options(self::month_options($format), $selected, $attributes);
	}
	
	static public function month_options($format = null) {
		if ($format != 'm' && $format != 'n') {
			$format = 'm';
		}
		$months = array();
		for($i = 1; $i < 13; $i ++) {
			if ($i < 10 && $format == 'm') {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$months[$key] = $i;
		}
		return $months;
	}
	
	static public function day_select_options($attributes = null, $selected = null, $format = null) {
		if ($selected == null) {
			$selected = date('j');
		}
		return self::select_options(self::day_options($format), $selected, $attributes);
	}
	
	static public function day_options($format = null) {
		if ($format != 'd' && $format != 'j') {
			$format = 'd';
		}
		$days = array();
		for($i = 1; $i < 31; $i ++) {
			if ($i < 10 && $format == 'd') {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$days[$key] = $i;
		}
		return $days;
	}
	
	static public function hour_select_options($attributes = null, $selected = null, $format = null) {
		if ($selected == null) {
			$selected = date('G');
		}
		return self::select_options(self::hour_options($format), $selected, $attributes);
	}
	
	static public function hour_options($format = null) {
		if ($format != 'G' && $format != 'H') {
			$format = 'G';
		}
		$hours = array();
		for($i = 0; $i < 24; $i ++) {
			if ($i < 10 && $format == 'G') {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$hours[$key] = $i;
		}
		return $hours;
	}
	
	static public function minute_select_options($attributes = null, $selected = null) {
		if ($selected == null) {
			$selected = date('i');
		}
		return self::select_options(self::minute_options(), $selected, $attributes);
	}
	
	static public function minute_options() {
		$minutes = array();
		for($i = 0; $i < 60; $i ++) {
			if ($i < 10) {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$minutes[$key] = $i;
		}
		return $minutes;
	}
	
	static public function second_select_options($attributes = null, $selected = null) {
		if ($selected == null) {
			$selected = date('s');
		}
		return self::select_options(self::second_options(), $selected, $attributes);
	}
	
	static public function second_options() {
		$seconds = array();
		for($i = 0; $i < 60; $i ++) {
			if ($i < 10) {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$seconds[$key] = $i;
		}
		return $seconds;
	}
	
	static public function datetime_selects($attributes = null, $format = null, $selected = null, $start = null, $end = null) {
		if ($format == null) {
			$format = 'Y/m/d G:i:s';
		}
		$return = '';
		for ($i = 0; $i < strlen($format); $i ++) {
			$select_attributes = $attributes;
			$f = substr($format, $i, 1);
			$select_attributes['name'] = $attributes['name']."_$f";
			$select_attributes['id'] = $attributes['id']."_$f";
			switch ($f) {
				case 'Y':
				case 'y':
					$select = self::year_select_options($start, $end, $select_attributes, $selected, $f);
					break;
				case 'm':
				case 'n':
					$select = self::month_select_options($select_attributes, $selected, $f);
					break;
				case 'd':
				case 'j':
					$select = self::day_select_options($select_attributes, $selected, $f);
					break;
				case 'G':
				case 'H':
					$select = self::hour_select_options($select_attributes, $selected, $f);
					break;
				case 'i':
					$select = self::minute_select_options($select_attributes, $selected);
					break;
				case 's':
					$select = self::second_select_options($select_attributes, $selected);
					break;
				default:
					$select = $f;
					break;
			}
			if (method_exists($select, 'render')) {
				$return .= $select->render();
			} else {
				$return .= $select;
			}
		}
		return $return;
	}
	
	static public function select_options($options, $selected = null, $attributes = null) {
		return new moojon_select_tag(self::options($options, $selected), $attributes);
	}
	
	static public function options($data, $selected = null) {
		if (is_array($data) == false) {
			$data = array($data);
		}
		$options = array();
		foreach ($data as $key => $value) {
			$attributes = array('value' => $value);
			if ($value == $selected) {
				$attributes['selected'] = 'selected';
			}
			$options[] = new moojon_option_tag($key, $attributes);
		}
		return $options;
	}
}
?>