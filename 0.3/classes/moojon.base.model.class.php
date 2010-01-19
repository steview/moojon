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
	
	final public function __construct($data = array()) {
		$this->add_columns();
		$this->add_relationships();
		$this->set($data);
		$this->validator = new moojon_validator;
		$this->add_validations();
		$this->class = get_class($this);
		$this->table = moojon_inflect::pluralize($this->class);
		$this->new_record = true;
		if ($this->has_column('created_on') && !$this->get_column('created_on')->get_unsaved()) {
			$this->get_column('created_on')->set_value(date(moojon_config::get('datetime_format')));
		}
	}
	
	final static protected function init($class) {
		$class = self::strip_base($class);
		return new $class;
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
		if ($this->has_relationship($key)) {
			if (!array_key_exists($key, $this->relationship_data)) {
				$records = new moojon_model_collection($this, $this->get_relationship($key));
				$this->relationship_data[$key] = $records->get();
			}
			return $this->relationship_data[$key];
		} else {
			if ($this->has_column($key)) {
				return $this->columns[$key]->get_value();
			} else {
				$get_method = "get_$key";
				if (method_exists($this, $get_method)) {
					return $this->$get_method();
				} else {
					throw new moojon_exception("unknown property ($key)");
				}
			}
		}	
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
	
	final private function get_relationship_type($key) {
		return get_class($this->get_relationship($key));
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
		
	final public function get_class() {
		return $this->class;
	}
	
	final public function get_table() {
		return $this->table;
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
		foreach ($this->get_columns() as $column) {
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
				if (moojon_db::insert($this->table, $placeholders, $this->compile_param_values(), $this->compile_param_data_types())) {
					if ($this->has_column($id_property)) {
						$this->$id_property = moojon_db::last_insert_id($id_property);
					}
					$this->new_record = false;
					$saved = true;
				}
			} else {
				if ($this->get_unsaved()) {
					if ($this->has_column('updated_at') && !$this->get_column('updated_at')->get_unsaved()) {
						$this->get_column('updated_at')->set_value(date(moojon_config::get('datetime_format')));
					}
					if (moojon_db::update($this->table, $placeholders, "$id_property = :$id_property", $this->compile_param_values(array(":$id_property" => $this->$id_property)), $this->compile_param_data_types())) {
						$this->new_record = false;
						$saved = true;
					}
				}
			}
		}
		if ($saved) {
			//$this->set_reset_values();
			//$this->reset();
			if ($cascade) {
				foreach($this->relationships as $relationship) {
					$relationship->save(true);
				}
			}
		}
		return $saved;
	}
	
	final static protected function base_read($class, $where, $order, $limit, $param_values, $param_data_types, $accessor, $key) {
		$class = self::strip_base($class);
		$table = moojon_inflect::pluralize($class);
		$instance = self::init($class);
		$placeholders = array();
		$columns = $instance->get_columns();
		foreach($columns as $column) {
			$column_name = $column->get_name();
			$placeholders["$table.$column_name"] = strtoupper($class.'_'.$column_name);
		}
		$return = new moojon_model_collection($accessor);
		foreach(moojon_db::select($table, $placeholders, $where, $order, $limit, $instance->compile_param_values($param_values), $instance->compile_param_data_types($param_data_types)) as $row) {
			$record = self::init($class);
			foreach($instance->columns as $column) {
				$column_name = $column->get_name();
				$record->$column_name = $row[strtoupper($class.'_'.$column_name)];
				$record->reset();
			}
			$record->new_record = false;
			$return[] = $record;
		}
		return $return;
	}
	
	final static protected function base_create($class, $data) {
		if (!$data) {
			$data = array();
		}
		$instance = self::init($class);
		return $instance;
	}
	
	final static protected function base_update($class, $data, $where) {
		$instance = self::init($class);
		$id_property = moojon_primary_key::NAME;
		$placeholders = array();
		$values = array();
		foreach ($data as $key => $value) {
			$placeholders[$key]= ":$key";
			$values[":$key"] = $value;
		}
		$statement = moojon_db::update($instance->table, $placeholders, $where);
		return $statement->execute($values);
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
}
?>