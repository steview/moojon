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
		foreach ($this->model->get_columns() as $column) {
			if ($this->find_relationship($column->get_name()) == false) {
				$label = true;
				$tag = null;
				switch (get_class($column)) {
					case 'moojon_binary_column':

						break;
					case 'moojon_boolean_column':

						break;
					case 'moojon_date_column':

						break;
					case 'moojon_datetime_column':

						break;
					case 'moojon_decimal_column':

						break;
					case 'moojon_float_column':

						break;
					case 'moojon_integer_column':
						
						break;
					case 'moojon_primary_key_column':

						break;
					case 'moojon_string_column':
						echo 'moojon_string_tag<br />';
						$tag = new moojon_string_tag($column);
						break;
					case 'moojon_text_column':

						break;
					case 'moojon_time_column':

						break;
					case 'moojon_timestamp_column':

						break;
				}
				if ($label == true) {
					$this->add_child(new moojon_column_label($column));
				}
				if ($tag != null) {
					$this->add_child($tag);
				}
			} else {
				
			}
		}
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

final class moojon_column_label extends moojon_label_tag {
	public function __construct(moojon_base_column $column) {
		$this->init();
		$name = $column->get_name();
		$this->add_child(ucfirst("$name:"));
		$this->id = $name."_label";
		$this->for = $name;
	}
}

final class moojon_binary_tag extends moojon_input_tag {
	public function __construct(moojon_binary_column $column) {$this->init();}
}

final class moojon_boolean_tag extends moojon_input_tag {
	public function __construct(moojon_boolean_column $column) {$this->init();}
}

final class moojon_date_tag extends moojon_div_tag {
	public function __construct(moojon_date_column $column) {$this->init();}
}

final class moojon_datetime_tag extends moojon_div_tag {
	public function __construct(moojon_datetime_column $column) {$this->init();}
}

final class moojon_decimal_tag extends moojon_input_tag {
	public function __construct(moojon_decimal_column $column) {$this->init();}
}

final class moojon_float_tag extends moojon_input_tag {
	public function __construct(moojon_float_column $column) {$this->init();}
}

final class moojon_integer_tag extends moojon_input_tag {
	public function __construct(moojon_integer_column $column) {$this->init();}
}

final class moojon_primary_key_tag extends moojon_input_tag {
	public function __construct(moojon_primary_key_column $column) {$this->init();}
}

final class moojon_string_tag extends moojon_input_tag {
	public function __construct(moojon_string_column $column) {
		$this->init();
		$name = $column->get_name();
		$this->name = $name;
		$this->id = $name;
		$this->maxlength = $column->get_limit();
		$this->value = $column->get_value();
		$this->class = 'text';
	}
}

final class moojon_text_tag extends moojon_textarea_tag {
	public function __construct(moojon_text_column $column) {$this->init();}
}

final class moojon_time_tag extends moojon_div_tag {
	public function __construct(moojon_time_column $column) {$this->init();}
}

final class moojon_timestamp_tag extends moojon_div_tag {
	public function __construct(moojon_timestamp_column $column) {$this->init();}
}
?>