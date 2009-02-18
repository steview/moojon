<?php
final class moojon_model_form extends moojon_form_tag {
	private $model;
	
	public function __construct(moojon_base_model $model, $action = null, $attributes = null) {
		$this->init();
		if ($action == null) {
			$action = '#';
		}
		$this->action = $action;
		if (is_array($attributes) == true) {
			foreach ($attributes as $key => $value) {
				$this->$key = $value;
			}
		}
		$this->model = $model;
		$this->action = $action;
		$this->method = 'post';
		$fieldset = new moojon_fieldset_tag();
		foreach ($this->model->get_columns() as $column) {
			$column_name = $column->get_name();
			$label = true;
			if ($relationship = $this->find_relationship($column_name) == false) {
				$tag = null;
				switch (get_class($column)) {
					case 'moojon_binary_column':
						$tag = new moojon_string_tag($column);
						break;
					case 'moojon_boolean_column':
						$tag = new moojon_boolean_tag($column);
						break;
					case 'moojon_date_column':

						break;
					case 'moojon_datetime_column':

						break;
					case 'moojon_decimal_column':
						$tag = new moojon_decimal_tag($column);
						break;
					case 'moojon_float_column':
						$tag = new moojon_float_tag($column);
						break;
					case 'moojon_integer_column':
						$tag = new moojon_integer_tag($column);
						break;
					case 'moojon_primary_key':
						$tag = new moojon_primary_key_tag($column);
						$label = false;
						break;
					case 'moojon_string_column':
						$tag = moojon_quick_tags::string_tag($column);
						break;
					case 'moojon_text_column':
						$tag = new moojon_text_tag($column);
						break;
					case 'moojon_time_column':

						break;
					case 'moojon_timestamp_column':

						break;
				}
			} else {
				$relationship_name = $this->find_relationship($column->get_name())->get_name();
				$relationship = new $relationship_name();
				$key = $this->find_relationship($column->get_name())->get_key();
				$tag = new moojon_relationship_tag($column, $relationship->read(), $key);
			}
			if ($label) {
				//$fieldset->add_child(moojon_quick_tags::label(null, $column));
			}
			if ($tag != null) {
				//$fieldset->add_child($tag);
			}
		}
		$this->add_child($fieldset);
		if ($model->get_new_record() == true) {
			$submit_value = 'Create';
		} else {
			$submit_value = 'Update';
		}
		$this->add_child(moojon_quick_tags::datetime_selects(array('name' => 'publish_date')));
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
	
	static private function process_text($text, $attributes) {
		if (is_subclass_of($attributes, 'moojon_base_column') == true) {
			$text = ucfirst(str_replace('_', ' ', moojon_primary_key::get_obj($attributes->get_name())).':');
		}
		return $text;
	}
	
	static public function label($text, $attributes = null) {
		$text = self::process_text($text, $attributes);
		$attributes = self::process_attributes($attributes);
		$label_attributes['id'] = $attributes['id'].'_label';
		$label_attributes['for'] = $attributes['id'];
		return new moojon_label_tag($text, $label_attributes);
	}
	
	static public function label_input($text, $attributes = null) {
		$text = self::process_text($text, $attributes);
		$attributes = self::process_attributes($attributes);
		$label = self::label($text, $attributes);
	}
	
	static public function string_tag(moojon_base_column $column) {
		$attributes = self::process_attributes($column);
		$attributes['maxlength'] = $column->get_limit();
		$attributes['value'] = $column->get_value();
		$attributes['type'] = 'text';
		$attributes['class'] = 'text';
		return new moojon_input_tag($attributes);
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
					$select = self::years_select_options($start, $end, $select_attributes, $selected, $f);
					break;
				case 'm':
				case 'n':
					$select = self::months_select_options($select_attributes, $selected, $f);
					break;
				case 'd':
				case 'j':
					$select = self::days_select_options($select_attributes, $selected, $f);
					break;
				case 'G':
				case 'H':
					$select = self::hours_select_options($select_attributes, $selected, $f);
					break;
				case 'i':
					$select = self::minutes_select_options($select_attributes, $selected);
					break;
				case 's':
					$select = self::seconds_select_options($select_attributes, $selected);
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
	
	static public function seconds_select_options($attributes = null, $selected = null) {
		$seconds = array();
		for($i = 0; $i < 60; $i ++) {
			if ($i < 10) {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$seconds[$key] = $i;
		}
		if ($selected == null) {
			$selected = date('s');
		}
		return self::select_options($seconds, $selected, $attributes);
	}
	
	static public function minutes_select_options($attributes = null, $selected = null) {
		$minutes = array();
		for($i = 0; $i < 60; $i ++) {
			if ($i < 10) {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$minutes[$key] = $i;
		}
		if ($selected == null) {
			$selected = date('i');
		}
		return self::select_options($minutes, $selected, $attributes);
	}
	
	static public function hours_select_options($attributes = null, $selected = null, $format = null) {
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
		if ($selected == null) {
			$selected = date('G');
		}
		return self::select_options($hours, $selected, $attributes);
	}
	
	static public function days_select_options($attributes = null, $selected = null, $format = null) {
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
		if ($selected == null) {
			$selected = date('j');
		}
		return self::select_options($days, $selected, $attributes);
	}
	
	static public function months_select_options($attributes = null, $selected = null, $format = null) {
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
		if ($selected == null) {
			$selected = date('n');
		}
		return self::select_options($months, $selected, $attributes);
	}
	
	static public function years_select_options($start = null, $end = null, $attributes = null, $selected = null, $format = null) {
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
		if ($selected == null) {
			$selected = date('Y');
		}
		for($i = min($start, $end); $i < (max($start, $end) + 1); $i ++) {
			$value = $i;
			if ($format == 'y') {
				$years[substr($value, -2)] = $value;
			} else {
				$years[$value] = $value;
			}
		}
		return self::select_options($years, $selected, $attributes);
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

final class moojon_binary_tag extends moojon_input_tag {
	public function __construct(moojon_binary_column $column) {$this->init();}
}

final class moojon_boolean_tag extends moojon_input_tag {
	public function __construct(moojon_boolean_column $column) {
		$this->init();
		$name = $column->get_name();
		$this->name = $name;
		$this->id = $name;
		$this->type = 'checkbox';
		$this->value = '1';
		if ($column->get_value() > 0) {
			$this->checked = 'checked';
		}
	}
}

final class moojon_date_tag extends moojon_div_tag {
	public function __construct(moojon_date_column $column) {$this->init();}
}

final class moojon_datetime_tag extends moojon_div_tag {
	public function __construct(moojon_datetime_column $column) {$this->init();}
}

final class moojon_decimal_tag extends moojon_input_tag {
	public function __construct(moojon_decimal_column $column) {
		$this->init();
		$name = $column->get_name();
		$this->name = $name;
		$this->id = $name;
		$this->maxlength = $column->get_limit();
		$this->value = $column->get_value();
		$this->type = 'text';
		$this->class = 'decimal';
	}
}

final class moojon_float_tag extends moojon_input_tag {
	public function __construct(moojon_float_column $column) {
		$this->init();
		$name = $column->get_name();
		$this->name = $name;
		$this->id = $name;
		$this->maxlength = $column->get_limit();
		$this->value = $column->get_value();
		$this->type = 'text';
		$this->class = 'float';
	}
}

final class moojon_integer_tag extends moojon_input_tag {
	public function __construct(moojon_integer_column $column) {
		$this->init();
		$name = $column->get_name();
		$this->name = $name;
		$this->id = $name;
		$this->maxlength = $column->get_limit();
		$this->value = $column->get_value();
		$this->type = 'text';
		$this->class = 'integer';
	}
}

final class moojon_primary_key_tag extends moojon_input_tag {
	public function __construct(moojon_primary_key $column) {
		$this->init();
		$name = $column->get_name();
		$this->name = $name;
		$this->id = $name;
		$this->value = $column->get_value();
		$this->type = 'hidden';
	}
}

final class moojon_string_tag extends moojon_input_tag {
	public function __construct(moojon_string_column $column) {
		$this->init();
		$name = $column->get_name();
		$this->name = $name;
		$this->id = $name;
		$this->maxlength = $column->get_limit();
		$this->value = $column->get_value();
		$this->type = 'text';
		$this->class = 'text';
	}
}

final class moojon_text_tag extends moojon_textarea_tag {
	public function __construct(moojon_text_column $column) {
		$this->init();
		$column_name = $column->get_name();
		$this->id = $column_name;
		$this->name = $column_name;
		$this->cols = 40;
		$this->rows = 6;
		$this->add_child($column->get_value());
	}
}

final class moojon_time_tag extends moojon_div_tag {
	public function __construct(moojon_time_column $column) {$this->init();}
}

final class moojon_timestamp_tag extends moojon_div_tag {
	public function __construct(moojon_timestamp_column $column) {$this->init();}
}

final class moojon_relationship_tag extends moojon_select_tag {
	public function __construct(moojon_base_column $column, moojon_model_collection $model_collection, $key) {
		$this->init();
		$column_name = $column->get_name();
		$this->id = $column_name;
		$this->name = $column_name;
		foreach ($model_collection as $model) {
			$option = new moojon_option_tag($model);
			$option->value = $model->$key;
			if ($model->$key == $column->get_value()) {
				$option->selected = 'selected';
			}
			$this->add_child($option);
		}
	}
}

final class moojon_select_with_options_tag extends moojon_select_tag {
	public function __construct($collection, $current, $name, $id) {
		$this->init();
		$this->name = $name;
		$this->id = $id;
		foreach ($collection as $key => $value) {
			if ($value == $current) {
				$attributes['selected'] = 'selected';
			}
			$this->add_child(new moojon_option_tag($key, $attributes));
		}
	}
}
?>