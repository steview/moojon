<?php
abstract class moojon_base_model extends moojon_base {
	
	protected $table;
	protected $class;
	protected $columns = array();
	private $relationships = array();
	private $relationship_data = array();
	private $validator;
	private $new_record = false;
	protected $to_string_column = moojon_primary_key::NAME;
	protected $order_column = moojon_primary_key::NAME;
	protected $order_direction = moojon_db_driver::DEFAULT_ORDER_DIRECTION;
	
	
	final public function __construct($data = array()) {
		$this->class = get_class($this);
		$this->table = moojon_inflect::pluralize($this->class);
		$this->add_columns();
		$this->add_relationships();
		if ($data) {
			$this->set($data);
		}
		$this->validator = new moojon_validator;
		$this->add_validations();
		$this->new_record = true;
	}
	
	final static protected function init($class, $data = array()) {
		if (!$data || !is_array($data)) {
			$data = array();
		}
		$class = self::strip_base($class);
		return new $class($data);
	}
	
	abstract protected function add_columns();
	
	protected function add_relationships() {}
	
	protected function add_validations() {}
	
	final public function __set($key, $value) {
		if ($this->has_column($key)) {
			$set_method = "set_$key";
			if (method_exists($this, $set_method)) {
				if ($this->get_unsaved()) {
					$value = $this->$set_method($value);
				}
			}
			$this->columns[$key]->set_value($value);
		} else {
			throw new moojon_exception(get_class($this)." doesn't contain column ($key)");
		}
	}
	
	final public function __get($key) {
		$class = get_class($this);
		$belongs_to_relationship = ($this->has_belongs_to_relationship($class)) ? $this->get_relationship($class) : null;
		if ($this->has_relationship($key)) {
			$relationship = $this->get_relationship($key);
			$foreign_key = $relationship->get_foreign_key();
			if ($belongs_to_relationship && array_key_exists($foreign_key, $belongs_to_relationship->get_shared_columns())) {
				$relationship = null;
			}
		} else {
			$relationship = null;
		}
		if ($relationship) {
			$get_method = 'get_'.$relationship->get_name();
			if (!array_key_exists($key, $this->relationship_data)) {
				if (method_exists($this, $get_method)) {
					$records = $this->$get_method();
				} else {
					$collection = new moojon_model_collection($this, $relationship);
					$records = $collection->get();
				}
				$this->relationship_data[$key] = $records;
			}
			return $this->relationship_data[$key];
		} else {
			$get_method = "get_$key";
			if (method_exists($this, $get_method)) {
				$return = $this->$get_method();
			} else {
				if ($this->has_column($key)) {
					$return = $this->columns[$key]->get_value();
				} else {
					throw new moojon_exception("unknown property ($key)");
				}
			}
			if ($belongs_to_relationship && $this->relationships[$class]->has_shared_column($key)) {
				$return = $this->$class->$key;
			}
			return $return;
		}
	}
	
	final public function get_relationships_class_where() {
		return moojon_db_driver::get_relationships_class_where($this);
	}
	
	final public function has_property($key) {
		if ($this->has_relationship($key)) {
			return true;
		}
		if ($this->has_column($key)) {
			return true;
		}
		return false;
	}
	
	final public function has_relationships() {
		return (count($this->relationships) > 0);
	}
	
	final public function has_relationship($key) {
		return array_key_exists($key, $this->relationships);
	}
	
	final public function has_has_one_relationship($key) {
		return array_key_exists($key, $this->get_has_one_relationships());
	}
	
	final public function has_has_many_relationship($key) {
		return array_key_exists($key, $this->get_has_many_relationships());
	}
	
	final public function has_has_many_to_many_relationship($key) {
		return array_key_exists($key, $this->get_has_many_to_many_relationships());
	}
	
	final public function has_belongs_to_relationship($key) {
		if (array_key_exists($key, $this->get_belongs_to_relationships())) {
			$relationship = $this->relationships[$key];
			$foreign_key = $relationship->get_foreign_key();
			if ($this->get_column($foreign_key)->get_value()) {
				return true;
			}
		}
		return false;
	}
	
	final public function get_relationship_type($key) {
		return get_class($this->get_relationship($key));
	}
	
	final public function get_relationship_foreign_class($key) {
		$relationship = $this->get_relationship($key);
		return $relationship->get_foreign_class();
	}
	
	final public function get_relationship_foreign_table($key) {
		$relationship = $this->get_relationship($key);
		return $relationship->get_foreign_table();
	}
	
	final public function get_relationship_foreign_key($key) {
		$relationship = $this->get_relationship($key);
		return $relationship->get_foreign_key();
	}
	
	final public function get_relationship_key($key) {
		$relationship = $this->get_relationship($key);
		return $relationship->get_key();
	}
	
	final public function get_relationship_column($key) {
		$relationship = $this->get_relationship($key);
		return $relationship->get_column();
	}
	
	final public function get_relationship_model($key) {
		$model_class = $this->get_relationship_foreign_class($key);
		return new $model_class;
	}
	
	final public function get_relationship($key) {
		if ($this->has_relationship($key)) {
			return $this->relationships[$key];
		} else {
			throw new moojon_exception("no such relationship ($key)");
		}
	}
	
	final static protected function base_get_relationship_names($class, $exceptions = array()) {
		$return = array();
		$instance = self::init($class);
		$relationships = $instance->get_relationships();
		foreach ($relationships as $relationship) {
			$relationship_name = $relationship->get_name();
			if (!in_array($relationship_name, $relationships)) {
				$return[] = $relationship_name;
			}
		}
		return $return;
	}
	
	final public function get_relationships() {
		return $this->relationships;
	}
	
	final private function get_relationships_of_type($type) {
		$return = array();
		foreach ($this->relationships as $key => $value) {
			if (get_class($value) == $type) {
				$return[$key] = $value;
			}
		}
		return $return;
	}
	
	final public function get_has_one_relationships() {
		return $this->get_relationships_of_type('moojon_has_one_relationship');
	}
	
	final public function get_has_many_relationships() {
		return $this->get_relationships_of_type('moojon_has_many_relationship');
	}
	
	final public function get_has_many_to_many_relationships() {
		return $this->get_relationships_of_type('moojon_has_many_to_many_relationship');
	}
	
	final public function get_belongs_to_relationships() {
		return $this->get_relationships_of_type('moojon_belongs_to_relationship');
	}
	
	final public function get_relationship_by_column($key) {
		foreach ($this->relationships as $relationship) {
			if ($relationship->get_foreign_key() == $key) {
				return $relationship;
			}
		}
		throw new moojon_exception("No relationship with foreign_key ($key)");
	}
	
	final public function is_relationship_column($key) {
		foreach ($this->relationships as $relationship) {
			if ($relationship->get_foreign_key() == $key) {
				return true;
			}
		}
		return false;
	}
	
	final public function is_relationship_of_type_column($key, $type) {
		foreach ($this->get_relationships_of_type($type) as $relationship) {
			if ($relationship->get_foreign_key() == $key) {
				return true;
			}
		}
		return false;
	}
	
	final public function is_has_one_relationship_column($key) {
		return $this->is_relationship_of_type_column($key, 'moojon_has_one_relationship');
	}
	
	final public function is_has_many_relationship_column($key) {
		return $this->is_relationship_of_type_column($key, 'moojon_has_many_relationship');
	}
	
	final public function is_has_many_to_many_relationship_column($key) {
		return $this->is_relationship_of_type_column($key, 'moojon_has_many_to_many_relationship');
	}
	
	final public function is_belongs_to_relationship_column($key) {
		return $this->is_relationship_of_type_column($key, 'moojon_belongs_to_relationship');
	}
	
	final protected function add_relationship($relationship_type, $name, $foreign_table, $foreign_key, $key) {
		if ($this->has_property($name)) {
			throw new moojon_exception("Duplicate property when adding relationship ($name)");
		}
		$key = ($key) ? $key : moojon_primary_key::NAME;
		if (!$this->has_column($key)) {
			throw new moojon_exception("no such column to use as key for relationship ($key)");
		}
		$foreign_table = ($foreign_table) ? $foreign_table : moojon_inflect::pluralize($name);
		$foreign_table = self::strip_base($foreign_table);
		$foreign_key = ($foreign_key) ? $foreign_key : moojon_primary_key::get_foreign_key($foreign_table);
		$this->relationships[$name] = new $relationship_type($name, $foreign_table, $foreign_key, $key, $this->get_column($key));
	}
	
	final protected function add_validation(moojon_base_validation $validation) {
		$this->validator->add_validation($validation);
	}
	
	final protected function validate_accept($exts, $key, $message, $required = true) {
		$this->add_validation(new moojon_accept_validation($exts, $key, $message, $required));
	}
	
	final protected function validate_creditcard($key, $message, $required = true) {
		$this->add_validation(new moojon_creditcard_validation($key, $message, $required));
	}
	
	final protected function validate_date($key, $message, $required = true) {
		$this->add_validation(new moojon_date_validation($key, $message, $required));
	}
	
	final protected function validate_digits($key, $message, $required = true) {
		$this->add_validation(new moojon_digits_validation($key, $message, $required));
	}
	
	final protected function validate_email($key, $message, $required = true) {
		$this->add_validation(new moojon_email_validation($key, $message, $required));
	}
	
	final protected function validate_equal_to($key, $message, $required = true) {
		$this->add_validation(new moojon_equal_to_validation($key, $message, $required));
	}
	
	final protected function validate_max($max, $key, $message, $required = true) {
		$this->add_validation(new moojon_max_validation($max, $key, $message, $required));
	}
	
	final protected function validate_maxlength($maxlength, $key, $message, $required = true) {
		$this->add_validation(new moojon_maxlength_validation($maxlength, $key, $message, $required));
	}
	
	final protected function validate_min($min, $key, $message, $required = true) {
		$this->add_validation(new moojon_min_validation($min, $key, $message, $required));
	}
	
	final protected function validate_minlength($minlength, $key, $message, $required = true) {
		$this->add_validation(new moojon_minlength_validation($minlength, $key, $message, $required));
	}
	
	final protected function validate_number($key, $message, $required = true) {
		$this->add_validation(new moojon_number_validation($key, $message, $required));
	}
	
	final protected function validate_range($min, $max, $key, $message, $required = true) {
		$this->add_validation(new moojon_range_validation($min, $max, $key, $message, $required));
	}
	
	final protected function validate_rangelength($minlength, $maxlength, $key, $message, $required = true) {
		$this->add_validation(new moojon_rangelength_validation($minlength, $maxlength, $key, $message, $required));
	}
	
	final protected function validate_required($key, $message) {
		$this->add_validation(new moojon_required_validation($key, $message));
	}
	
	final protected function validate_unique($key, $message, $required = true) {
		$this->add_validation(new moojon_unique_validation($key, $message, $required));
	}
	
	final protected function validate_uri($key, $message, $required = true) {
		$this->add_validation(new moojon_uri_validation($key, $message, $required));
	}
	
	final protected function validate_future($key, $message, $required = true) {
		$this->add_validation(new moojon_future_validation($key, $message, $required));
	}
	
	final protected function validate_past($key, $message, $required = true) {
		$this->add_validation(new moojon_past_validation($key, $message, $required));
	}
	
	final protected function validate_characters($characters, $key, $message, $required = true) {
		$this->add_validation(new moojon_characters_validation($characters, $key, $message, $required));
	}
	
	final public function has_column($key) {
		return array_key_exists($key, $this->columns);
	}
	
	final protected function add_column(moojon_base_column $column) {
		if (!$this->has_property($column->get_name())) {
			$this->columns[$column->get_name()] = $column;
		} else {
			throw new moojon_exception('duplicate property ('.$column->get_name().')');
		}
	}
	
	final protected function add_primary_key() {
		$this->add_column(new moojon_primary_key());
	}
	
	final protected function add_binary($name, $limit = null, $null = null, $default = null) {
		$this->add_column(new moojon_binary_column($name, $limit, $null, $default));
	}
	
	final protected function add_boolean($name, $null = null, $default = null) {
		$this->add_column(new moojon_boolean_column($name, $null, $default));
	}
	
	final protected function add_date($name, $null = null, $default = null) {
		$this->add_column(new moojon_date_column($name, $null, $default));
	}
	
	final protected function add_datetime($name, $null = null, $default = null) {
		$this->add_column(new moojon_datetime_column($name, $null, $default));
	}
	
	final protected function add_decimal($name, $limit = null, $decimals = null, $null = null, $default = null) {
		$this->add_column(new moojon_decimal_column($name, $limit, $decimals, $null, $default));
	}
	
	final protected function add_float($name, $limit = null, $decimals = null, $null = null, $default = null) {
		$this->add_column(new moojon_float_column($name, $limit, $decimals, $null, $default));
	}
	
	final protected function add_integer($name, $limit = null, $null = null, $default = null) {
		$this->add_column(new moojon_integer_column($name, $limit, $null, $default));
	}
	
	final protected function add_string($name, $limit = null, $null = null, $default = null) {
		$this->add_column(new moojon_string_column($name, $limit, $null, $default));
	}
	
	final protected function add_text($name, $null = null, $binary = null) {
		$this->add_column(new moojon_text_column($name, $binary));
	}
	
	final protected function add_time($name, $null = null, $default = null) {
		$this->add_column(new moojon_time_column($name, $null, $default));
	}
	
	final protected function add_timestamp($name, $null = null, $default = null) {
		$this->add_column(new moojon_timestamp_column($name, $null, $default));
	}
	
	final protected function has_many($name, $foreign_table = null, $foreign_key = null, $key = null) {
		$this->add_relationship('moojon_has_many_relationship', $name, $foreign_table, $foreign_key, $key);
	}
	
	final protected function has_one($name, $foreign_table = null, $foreign_key = null, $key = null) {
		$this->add_relationship('moojon_has_one_relationship', $name, $foreign_table, $foreign_key, $key);
	}
	
	final protected function has_many_to_many($name, $foreign_table = null, $foreign_key = null, $key = null) {
		$this->add_relationship('moojon_has_many_to_many_relationship', $name, $foreign_table, $foreign_key, $key);
	}
	
	final protected function belongs_to($name = null, $foreign_table = null, $foreign_key = null, $key = null, $shared_columns = array()) {
		$name = ($name) ? $name : $this->class;
		$foreign_table = $this->table;
		$foreign_key = ($foreign_key) ? $foreign_key : moojon_primary_key::get_foreign_key($foreign_table);
		$key = ($key) ? $key : moojon_primary_key::NAME;
		if ($this->has_property($name)) {
			throw new moojon_exception("Duplicate property when adding relationship ($name)");
		}
		if (!$this->has_column($key)) {
			throw new moojon_exception("No such column to use as key for relationship ($key)");
		}
		$relationship = new moojon_belongs_to_relationship($name, $foreign_table, $foreign_key, $key, $this->get_column($key));
		$relationship->set_shared_columns($shared_columns);
		$this->relationships[$name] = $relationship;
	}
	
	final public function get_class() {
		return $this->class;
	}
	
	final public function get_validator_messages() {
		return $this->validator->get_messages();
	}
	
	final public function has_validator_messages() {
		return (count($this->validator->get_messages()) > 0);
	}
	
	final public function get_validations($key) {
		$return = array();
		foreach ($this->validator->get_validations() as $validation) {
			if ($validation->get_key() == $key) {
				$return[] = $validation;
			}
		}
		return $return;
	}
	
	final public function get_validator() {
		return $this->validator;
	}
	
	final public function get_column($column_name) {
		foreach ($this->columns as $column) {
			if ($column->get_name() == $column_name) {
				return $column;
			}
		}
		throw new moojon_exception("Invalid column ($column_name)");
	}
	
	final public function get_editable_column($column_name) {
		foreach ($this->get_editable_columns() as $column) {
			if ($column->get_name() == $column_name) {
				return $column;
			}
		}
		throw new moojon_exception("Invalid editable column ($column_name)");
	}
	
	final public function get_primary_key_column($column_name) {
		foreach ($this->get_primary_key_columns() as $column) {
			if ($column->get_name() == $column_name) {
				return $column;
			}
		}
		throw new moojon_exception("Invalid primary key column ($column_name)");
	}
	
	final public function get_file_column($column_name) {
		foreach ($this->get_file_columns() as $column) {
			if ($column->get_name() == $column_name) {
				return $column;
			}
		}
		throw new moojon_exception("Invalid file column ($column_name)");
	}
	
	final public function get_columns($exceptions = array()) {
		$return = array();
		$class = get_class($this);
		if ($this->has_belongs_to_relationship($class)) {
			$exceptions = array_merge($exceptions, $this->relationships[$class]->get_shared_columns($exceptions));
		}
		foreach ($this->columns as $column) {
			if (!in_array($column->get_name(), $exceptions)) {
				$return[] = $column;
			}
		}
		return $return;
	}
	
	final public function get_editable_columns($exceptions = array()) {
		$return = array();
		foreach ($this->get_columns($exceptions) as $column) {
			if (get_class($column) != 'moojon_primary_key') {
				$return[] = $column;
			}
		}
		return $return;
	}
	
	final public function get_primary_key_columns($exceptions = array()) {
		$return = array();
		foreach ($this->get_columns($exceptions) as $column) {
			if (get_class($column) == 'moojon_primary_key') {
				$return[] = $column;
			}
		}
		return $return;
	}
	
	final public function get_file_columns($exceptions = array()) {
		$return = array();
		foreach ($this->get_columns($exceptions) as $column) {
			if (get_class($column) == 'moojon_string_column' && $column->is_file()) {
				$return[] = $column;
			}
		}
		return $return;
	}
	
	final public function get_order_columns($exceptions = array()) {
		$return = array();
		foreach ($this->get_columns($exceptions) as $column) {
			if ($column->is_order()) {
				$return[] = $column;
			}
		}
		return $return;
	}
	
	final static protected function base_get_column_names($class, $exceptions = array()) {
		$return = array();
		$instance = self::init($class);
		foreach ($instance->get_columns($exceptions) as $column) {
			$return[] = $column->get_name();
		}
		return $return;
	}
	
	final static protected function base_get_editable_column_names($class, $exceptions = array()) {
		$return = array();
		$instance = self::init($class);
		foreach ($instance->get_editable_columns($exceptions) as $column) {
			$return[] = $column->get_name();
		}
		return $return;
	}
	
	final static protected function base_get_primary_key_column_names($class, $exceptions = array()) {
		$return = array();
		$instance = self::init($class);
		foreach ($instance->get_primary_key_columns($exceptions) as $column) {
			$return[] = $column->get_name();
		}
		return $return;
	}
	
	final static protected function base_get_file_column_names($class, $exceptions = array()) {
		$return = array();
		$instance = self::init($class);
		foreach ($instance->get_file_columns($exceptions) as $column) {
			$return[] = $column->get_name();
		}
		return $return;
	}
	
	final static protected function base_get_order_column_names($class, $exceptions = array()) {
		$return = array();
		$instance = self::init($class);
		foreach ($instance->get_order_columns($exceptions) as $column) {
			$return[] = $column->get_name();
		}
		return $return;
	}
	
	final private function compile_param_values($new_param_values = array(), $exception_param_values = array()) {
		$param_values = array();
		foreach ($this->get_editable_columns($exception_param_values) as $column) {
			$column_name = ':'.$column->get_name();
			if ($column->get_unsaved()) {
				$param_values[$column_name] = $column->get_value_query_format();
			}
		}
		return array_merge($param_values, $new_param_values);
	}
	
	final private function compile_param_data_types($new_param_data_types = array(), $exception_param_data_types = array()) {
		$param_data_types = array();
		foreach ($this->get_columns($exception_param_data_types) as $column) {
			$column_name = ':'.$column->get_name();
			$param_data_types[$column_name] = $column->get_data_type();
		}
		return array_merge($param_data_types, $new_param_data_types);
	}
	
	final public function get_new_record() {
		return $this->new_record;
	}
	
	final public function get_to_string_column() {
		return $this->to_string_column;
	}
	
	final public function get_validation_data_keys($keys) {
		$return = array();
		foreach ($this->get_editable_column_names() as $column_name) {
			$data_keys = array();
			foreach ($this->get_validations($column_name) as $validation) {
				$data_keys = array_merge($data_keys, $validation->get_data_keys());
			}
			$return[$column_name] = $data_keys;
		}
		foreach ($return as $key => $value) {
			if (array_key_exists($key, $keys)) {
				$return[$key] = array_merge($return[$key], $keys[$key]);
			}
		}
		return $return;
	}
	
	final public function get_validation_data($columns, $data) {
		$return = array();
		foreach ($columns as $column_name => $data_keys) {
			$validation_data = array();
			foreach ($data_keys as $data_key) {
				if ($data_key == 'data') {
					$validation_data['data'] = $this->$column_name;
				} elseif ($data_key == 'data_set') {
					$data_set = array();
					if ($this->new_record) {
						foreach ($this->read("$column_name = :$column_name", null, null, $this->compile_param_values(), $this->compile_param_data_types()) as $record) {
							$data_set[] = $record->$column_name;
						}
					}
					$validation_data['data_set'] = $data_set;
				}
			}
			$return[$column_name] = $validation_data;
		}
		foreach ($return as $key => $value) {
			if (array_key_exists($key, $data)) {
				$return[$key] = array_merge($return[$key], $data[$key]);
			}
		}
		return $return;
	}
	
	final public function validate($keys = array(), $data = array()) {
		$keys = ($keys) ? $keys : $this->get_editable_column_names();
		$data = $this->get_validation_data($this->get_validation_data_keys($keys), $data);
		return $this->validator->validate($keys, $data);
	}
		
	final public function save($cascade = false, $keys = array(), $data = array()) {
		$saved = false;
		if ($this->validate($keys, $data)) {
			$placeholders = array();
			foreach ($this->get_editable_columns() as $column) {
				$column_name = $column->get_name();
				if ($column->get_unsaved()) {
					$placeholders[$column_name] = ":$column_name";
				}
			}
			$id_property = moojon_primary_key::NAME;
			if ($this->new_record) {
				if ($this->has_column('created_on') && !$this->get_column('created_on')->get_unsaved()) {
					$this->get_column('created_on')->set_value(date(moojon_config::get('datetime_format')));
					$placeholders['created_on'] = ':created_on';
				}
				if (moojon_db::insert($this->table, $placeholders, $this->compile_param_values(), $this->compile_param_data_types())) {
					if ($this->has_column($id_property)) {
						$this->$id_property = moojon_db::last_insert_id($id_property);
					}
					$this->new_record = false;
					$saved = true;
				}
			} else {
				$additional_param_values = array();
				if ($this->has_column($id_property)) {
					$additional_param_values[$id_property] = $this->$id_property;
					$where = "$id_property = :$id_property";
				} else {
					throw new moojon_exception('No id primary key available for update');
				}
				if ($this->get_unsaved()) {
					if ($this->has_column('updated_at') && !$this->get_column('updated_at')->get_unsaved()) {
						$this->get_column('updated_at')->set_value(date(moojon_config::get('datetime_format')));
						$placeholders['updated_at'] = ':updated_at';
						$additional_param_values['updated_at'] = $this->updated_at;
					}
					if (moojon_db::update($this->table, $placeholders, $where, $this->compile_param_values($additional_param_values), $this->compile_param_data_types())) {
						$this->new_record = false;
						$saved = true;
					}
				}
			}
		}
		if ($saved) {
			$this->set_reset_values();
			$this->reset();
			if ($cascade) {
				foreach($this->relationships as $relationship) {
					$relationship->save(true);
				}
			}
		}
		return $saved;
	}
	
	final public function get_table($cascade = false) {
		$table = $this->table;
		if ($cascade) {
			$return = $this->get_relationship_tables();
			if (!in_array($table, $return)) {
				$return[] = $table;
			}
		} else {
			$return = $table;
		}
		return $return;
	}
	
	final protected function get_select_columns($cascade = false) {
		if ($cascade) {
			$return = $this->get_select_columns(false);
			$return = array_merge($this->get_relationship_select_columns(), $return);
		} else {
			$table = $this->get_table(false);
			$return = array();
			foreach($this->get_column_names() as $column_name) {
				$return[moojon_db_driver::column_address($table, $column_name)] = moojon_db_driver::full_column_name($this->get_class(), $column_name);
			}
		}
		return $return;
	}
	
	final public function get_relationship_tables($classes = array()) {
		$return = array();
		$class = get_class($this);
		if (!in_array($class, $classes)) {
			$classes[] = $class;
			foreach ($this->get_relationships() as $relationship) {
				$foreign_table = $relationship->get_foreign_table();
				if (!in_array($foreign_table, $return)) {
					$return[] = $foreign_table;
				}
				$instance = self::factory($relationship->get_foreign_class());
				foreach ($instance->get_relationship_tables($classes) as $relationship_table) {
					if (!in_array($relationship_table, $return)) {
						$return[] = $relationship_table;
					}
				}
			}
		}
		return $return;
	}
	
	final public function get_relationship_select_columns($classes = array()) {
		$return = array();
		$class = get_class($this);
		if (!in_array($class, $classes)) {
			$classes[] = $class;
			foreach ($this->get_relationships() as $relationship) {
				$instance = self::factory($relationship->get_foreign_class());
				$select_columns = moojon_db_driver::columns($instance->get_select_columns());
				if (!in_array($select_columns, $return)) {
					$return[] = $select_columns;
				}
				foreach ($instance->get_relationship_select_columns($classes) as $relationship_select_columns) {
					if (!in_array($relationship_select_columns, $return)) {
						$return[] = $relationship_select_columns;
					}
				}
			}
		}
		return $return;
	}
	
	final public function get_relationship_wheres($classes = array()) {
		$return = array();
		$class = get_class($this);
		if (!in_array($class, $classes)) {
			$classes[] = $class;
			foreach ($this->get_relationships() as $relationship) {
				$foreign_class = $relationship->get_foreign_class();
				if ($foreign_class != $class) {
					$where = $relationship->get_class_where($this);
					if (!in_array($where, $return)) {
						$return[] = $where;
					}
					$instance = self::factory($foreign_class);
					foreach ($instance->get_relationship_wheres($classes) as $relationship_where) {
						if (!in_array($relationship_where, $return)) {
							$return[] = $relationship_where;
						}
					}
				}
			}
		}
		return $return;
	}
	
	final protected function get_where($where = null, $cascade = false) {
		$where = moojon_db_driver::column_addresses($where, $this->get_table(), $this->get_column_names());
		if ($cascade) {
			foreach ($this->get_relationships() as $relationship) {
				$instance = self::factory($relationship->get_foreign_class());
				$where = moojon_db_driver::column_addresses($where, $instance->get_table(), $instance->get_column_names());
			}
			$return = $this->get_relationship_wheres();
			$return[] = $where;
		} else {
			$return = $where;
		}
		return $return;
	}
	
	final protected function get_order($order = null, $cascade = false) {
		$order =  moojon_db_driver::column_addresses($order, $this->get_table(), $this->get_column_names());
		if (!$order) {
			$order_columns = $this->get_order_column_names();
			if (count($order_columns)) {
				for ($i = 0; $i < count($order_columns); $i ++) {
					$order_columns[$i] = $order_columns[$i].' '.$this->order_direction;
				}
				$order = implode(', ', $order_columns);
			} else {
				$order = $this->order_column.' '.$this->order_direction;
			}
			$order =  moojon_db_driver::column_addresses($order, $this->get_table(), $this->get_column_names());
		}
		if ($cascade) {
			foreach ($this->get_relationships() as $relationship) {
				$instance = self::factory($relationship->get_foreign_class());
				$order = moojon_db_driver::column_addresses($order, $instance->get_table(), $instance->get_column_names());
			}
		}
		return $order;
	}
	
	final protected function get_limit($limit = null, $cascade = false) {
		return ($limit) ? $limit : '0, 100';
	}
	
	final public function set_full($data = array()) {
		foreach ($this->get_column_names() as $column_name) {
			$full_column_name = moojon_db_driver::full_column_name($this->class, $column_name);
			$this->$column_name = $data[$full_column_name];
			$this->reset();
		}
	}
	
	final static protected function base_read($class, $where, $order, $limit, $param_values, $param_data_types, $accessor, $key) {
		$cascade = false;
		$class = self::strip_base($class);
		$instance = self::factory($class);
		$table = $instance->get_table($cascade);
		$columns = $instance->get_select_columns($cascade);
		$where = $instance->get_where($where, $cascade);
		$order = $instance->get_order($order, $cascade);
		$limit = $instance->get_limit($limit, $cascade);
		$return = new moojon_model_collection($accessor);
		$current_record = self::init($class);
		foreach(moojon_db::select($table, $columns, $where, $order, $limit, $instance->compile_param_values($param_values), $instance->compile_param_data_types($param_data_types)) as $row) {
			$record = new $class;
			$record->set_full($row);
			$record->new_record = false;
			$record->reset();
			if ($current_record != $record) {
				$current_record = $record;
				$return[] = $current_record;
			}
		}
		return $return;
	}
	
	final static protected function base_create($class, $data) {
		$instance = self::init($class, $data);
		return $instance;
	}
	
	final static protected function base_update($class, $data, $where, $param_values, $param_data_types) {
		$instance = self::init($class);
		$id_property = moojon_primary_key::NAME;
		$placeholders = array();
		$values = array();
		foreach ($data as $key => $value) {
			$placeholders[$key]= ":$key";
			$values[":$key"] = $value;
		}
		return moojon_db::update($instance->table, $placeholders, $where, array_merge($values, $param_values), $param_data_types);
	}
	
	final static protected function base_destroy($class, $where, $param_values, $param_data_types) {
		$instance = self::init($class);
		foreach ($instance->read($where, null, null, $param_values, $param_data_types) as $record) {
			foreach($record->get_relationships() as $relationship) {
				if (get_class($relationship) == 'moojon_has_many_relationship' || get_class($relationship) == 'moojon_has_many_to_many_relationship') {
					$relationship_name = $relationship->get_name();
					$record->$relationship_name->delete();
				}
			}
		}
		moojon_db::delete($instance->table, $where, $param_values, $param_data_types);
	}
	
	final public function delete($cascade = true) {
		$where = '';
		$param_values = array();
		foreach ($this->get_primary_key_columns() as $column) {
			$column_name = $column->get_name();
			$where .= "$column_name = :$column_name AND ";
			$param_values[":$column_name"] = $column->get_value();
		}
		moojon_db::delete($this->table, substr($where, 0, (strlen($where) - 5)), $param_values, $this->compile_param_data_types());
		if ($cascade) {
			foreach($this->get_relationships() as $relationship) {
				if (get_class($relationship) == 'moojon_has_many_relationship' || get_class($relationship) == 'moojon_has_many_to_many_relationship') {
					$relationship_name = $relationship->get_name();
					$this->$relationship_name->delete($cascade);
				}
			}
		}
	}
	
	final public function set($data, $value = null) {
		if (!is_array($data)) {
			$data = array($data => $value);
		}
		foreach ($data as $key => $value) {
			if (in_array($key, $this->get_editable_column_names())) {
				$this->$key = $value;
			}
		}
	}
	
	final public function get($key) {
		return $this->$key;
	}
	
	final public function get_unsaved() {
		$unsaved = false;
		foreach ($this->get_editable_columns() as $column) {
			if ($column->get_unsaved()) {
				$unsaved = true;
				break;
			}
		}
		return $unsaved;
	}
	
	final public function reset() {
		foreach ($this->get_editable_columns() as $column) {
			$column->reset();
		}
	}
	
	final public function set_reset_values() {
		foreach ($this->get_editable_columns() as $column) {
			$column->set_reset_value();
		}
	}
	
	public function __toString() {
		$to_string_column = $this->to_string_column;
		return $this->$to_string_column;
	}
	
	final public function __clone() {
		$this->new_record = true;
		foreach ($this->get_primary_key_columns() as $column) {
			$column_name = $column->get_name();
			$this->$column_name = null;
		}
	}
	
	final static public function read_by($class, $column_name, $value, $order, $limit) {
		$instance = self::init($class);
		$column = $instance->get_column($column_name);
		return self::base_read($class, "$column_name = :$column_name", $order, $limit, array(":$column_name" => $value), array(":$column_name" => $column->get_data_type()), null, null);
	}
	
	final static public function destroy_by($class, $column_name, $value) {
		$instance = self::init($class);
		$column = $instance->get_column($column_name);
		self::base_destroy($class, "$column_name = :$column_name", array(":$column_name" => $value), array(":$column_name" => $column->get_data_type()));
	}
	
	final static public function read_or_create_by($class, $column_name, $value, $data) {
		$instance = self::init($class);
		$column = $instance->get_column($column_name);
		$collection = self::read_by($class, $column_name, $value, null, null);
		if ($collection->count) {
			return $collection->first;
		} else {
			$instance = self::base_create($class, $data);
			$instance->$column_name = $value;
			return $instance;
		}
	}
	
	final static public function factory($class, $data = null) {
		return self::base_create($class, $data);
	}
	
	public function get_ui_column_names($exceptions = array()) {
		return $this->get_editable_column_names($exceptions);
	}
	
	public function get_ui_editable_column_names($exceptions = array()) {
		return $this->get_editable_column_names($exceptions);
	}
}
?>