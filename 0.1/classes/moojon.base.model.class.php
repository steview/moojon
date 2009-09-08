<?php
abstract class moojon_base_model extends moojon_base {
	
	protected $table;
	protected $class;
	protected $columns = array();
	private $relationships = array();
	private $validations = array();
	private $errors = array();
	private $unsaved = false;
	protected $new_record = false;
	protected $to_string_column = moojon_primary_key::NAME;
	private $params = array();
	
	final public function __construct() {}
	
	abstract protected function add_columns();
	
	protected function add_relationships() {}
	
	protected function add_validations() {}
	
	final static public function strip_base($class) {
		if (substr($class, 0, 5) == 'base_') {
			$class = substr($class, 5);
		}
		return $class;
	}
	
	final static protected function init($class) {
		$class = self::strip_base($class);
		$instance = new $class;
		$instance->add_columns();
		$instance->add_relationships();
		$instance->add_validations();
		$instance->class = $class;
		$instance->table = moojon_inflect::pluralize($class);
		return $instance;
	}
	
	final public function __set($key, $value) {
		if ($this->has_column($key)) {
			$this->columns[$key]->set_value($value);
			$this->unsaved = true;
		} else {
			throw new moojon_exception("$key doesn't exist");
		}
	}
	
	final public function __get($key) {
		if ($this->has_relationship($key)) {
			if (is_subclass_of($this->relationships[$key], 'moojon_base_relationship')) {
				$records = new moojon_model_collection($this, $key);
				$this->relationships[$key] = $records->get($this);
			}
			return $this->relationships[$key];
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
	
	final public function has_relationship($key) {
		return array_key_exists($key, $this->relationships);
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
	
	final public function get_relationships() {
		return $this->relationships;
	}
	
	final protected function add_relationship($relationship_type, $name, $foreign_table, $foreign_key, $key) {
		if ($this->has_property($name)) {
			throw new moojon_exception("duplicate property when adding relationship ($name)");
		}
		if (!$foreign_table) {
			$foreign_table = moojon_inflect::pluralize($name);
		}
		$foreign_table = self::strip_base($foreign_table);
		if (!$foreign_key) {
			$foreign_key = moojon_primary_key::get_foreign_key($foreign_table);
		}
		if (!$key) {
			$key = moojon_primary_key::NAME;
		}
		if (!$this->has_column($key)) {
			throw new moojon_exception("no such column to use as key for relationship ($key)");
		}
		$this->relationships[$name] = new $relationship_type($name, $foreign_table, $foreign_key, $key);
	}
	
	final public function has_validation($key) {
		return array_key_exists($key, $this->validations);
	}
	
	final private function get_validation_type($key) {
		return get_class($this->get_validation($key));
	}
	
	final public function get_validation($key) {
		if  ($this->has_validation($key)) {
			return $this->validations[$key];
		} else {
			throw new moojon_exception("no such validation ($key)");
		}
	}
	
	final public function get_validations() {
		return $this->validations;
	}
	
	final protected function add_validation($name, moojon_base_validation $validation) {
		if (array_key_exists($name, $this->validations)) {
			$validations = $this->validations[$name];
		} else {
			$validations = array();
		}
		$validations[] = $validation;
		$this->validations[$name] = $validations;
	}
	
	final protected function validate_required($name, $message) {
		$this->add_validation($name, new moojon_required_validation($message));
	}
	
	final protected function validate_unique($name, $message, $required = true) {
		$this->add_validation($name, new moojon_unique_validation($message, $this, $required));
	}
	
	final protected function validate_min($name, $message, $min, $required = true) {
		$this->add_validation($name, new moojon_min_validation($message, $min, $required));
	}
	
	final protected function validate_max($name, $message, $max, $required = true) {
		$this->add_validation($name, new moojon_max_validation($message, $max, $required));
	}
	
	final protected function validate_range($name, $message, $min, $max, $required = true) {
		$this->add_validation($name, new moojon_range_validation($message, $min, $max, $required));
	}
	
	final protected function validate_minlength($name, $message, $minlength, $required = true) {
		$this->add_validation($name, new moojon_minlength_validation($message, $minlength, $required));
	}
	
	final protected function validate_maxlength($name, $message, $maxlength, $required = true) {
		$this->add_validation($name, new moojon_maxlength_validation($message, $maxlength, $required));
	}
	
	final protected function validate_rangelength($name, $message, $minlength, $maxlength, $required = true) {
		$this->add_validation($name, new moojon_rangelength_validation($message, $minlength, $maxlength, $required));
	}
	
	final protected function validate_email($name, $message, $required = true) {
		$this->add_validation($name, new moojon_email_validation($message, $required));
	}
	
	final protected function validate_url($name, $message, $required = true) {
		$this->add_validation($name, new moojon_url_validation($message, $required));
	}
	
	final protected function validate_date($name, $message, $required = true) {
		$this->add_validation($name, new moojon_date_validation($message, $required));
	}
	
	final protected function validate_number($name, $message, $required = true) {
		$this->add_validation($name, new moojon_number_validation($message, $required));
	}
	
	final protected function validate_digits($name, $message, $required = true) {
		$this->add_validation($name, new moojon_digits_validation($message, $required));
	}
	
	final protected function validate_creditcard($name, $message, $card_type, $required = true) {
		$this->add_validation($name, new moojon_creditcard_validation($message, $card_type, $required));
	}
	
	final protected function validate_accept($name, $message, $exts, $required = true) {
		$this->add_validation($name, new moojon_accept_validation($message, $exts, $required));
	}
	
	final protected function validate_equal_to($name, $message, $value, $required = true) {
		$this->add_validation($name, new moojon_equal_to_validation($message, $value, $required));
	}
	
	final private function has_column($key) {
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
	
	final public function get_columns() {
		return $this->columns;
	}
	
	final public function get_errors() {
		return $this->errors;
	}
	
	final public function get_column($column_name) {
		foreach ($this->get_columns() as $column) {
			if ($column->get_name() == $column_name) {
				return $column;
			}
		}
		throw new moojon_exception("Invalid column ($column_name)");
	}
	
	final public function get_editable_columns() {
		$columns = array();
		foreach ($this->columns as $column) {
			if (get_class($column) != 'moojon_primary_key') {
				$columns[] = $column;
			}
		}
		return $columns;
	}
	
	final public function get_editable_column_names() {
		$columns = array();
		foreach ($this->get_editable_columns() as $column) {
			$columns[] = $column->get_name();
		}
		return $columns;
	}
	
	final public function get_primary_key_columns() {
		$columns = array();
		foreach ($this->columns as $column) {
			if (get_class($column) == 'moojon_primary_key') {
				$columns[] = $column;
			}
		}
		return $columns;
	}
	
	final public function get_new_record() {
		return $this->new_record;
	}
	
	final public function get_to_string_column() {
		return $this->to_string_column;
	}
	
	final public function validate($cascade = false) {
		$valid = true;
		$errors = array();
		foreach ($this->get_editable_columns() as $column) {
			if ($this->has_validation($column->get_name())) {
				foreach ($this->validations[$column->get_name()] as $validation) {
					if (!$validation->validate($this, $column)) {
						$errors[$column->get_name()] = $validation->get_message();
						$valid = false;
						break;
					}
				}
			}
		}
		if ($cascade) {
			foreach ($this->relationships as $relationship) {
				$validation = $relationship->validate(true);
				if (!$validation) {
					foreach ($relationship->get_errors() as $error) {
						$errors[] = $error;
					}
					$valid = false;
				}
			}
		}
		$this->errors = $errors;
		return $valid;
	}
		
	final public function save($cascade = false) {
		$saved = true;
		if ($this->validate($cascade)) {
			foreach ($this->get_editable_column_names() as $column_name) {
				if (method_exists($this, "set_$column_name")) {
					$this->$column_name = call_user_func_array(array(get_class($this), "set_$column_name"), array($this, $this->get_column($column_name)));
				}
			}
			$data = array();
			$placeholders = array();
			foreach ($this->get_editable_column_names() as $column_name) {
				$data[":$column_name"] = $this->$column_name;
				$placeholders[$column_name] = ":$column_name";
			}
			$id_property = moojon_primary_key::NAME;
			if ($this->new_record) {
				moojon_db::insert($this->table, $placeholders, $data);
				$this->$id_property = moojon_db::last_insert_id($id_property);
				$this->new_record = false;
			} else {
				if ($this->unsaved) {
					$data[":$id_property"] = $this->$id_property;
					$statement = moojon_db::update($this->table, $placeholders, "$id_property = :$id_property", $data);
				} else {
					$saved = false;
				}
			}
			$this->unsaved = (!$saved);
		} else {
			$saved = false;
		}
		if ($saved) {
			if ($cascade) {
				foreach($this->relationships as $relationship) {
					$relationship->save(true);
				}
			}
		}
		return $saved;
	}
	
	final static protected function base_read($class, $where, $order, $limit, $accessor, $param_values, $param_data_types) {
		$class = self::strip_base($class);
		$instance = self::init($class);
		$columns = array();
		foreach($instance->columns as $column) {
			$columns[$instance->table.'.'.$column->get_name()] = strtoupper(moojon_inflect::singularize(get_class($instance)).'_'.$column->get_name());
		}
		$records = new moojon_model_collection($accessor);
		foreach(moojon_db::select($instance->table, $columns, $where, $order, $limit, $param_values, $param_data_types) as $row) {
			$record = self::init($class);
			foreach($instance->columns as $column) {
				$column_name = $column->get_name();
				$record->$column_name = $row[strtoupper($class.'_'.$column_name)];
			}
			$records[] = $record;
		}
		return $records;
	}
	
	final static protected function base_create($class, $data) {
		if (!$data) {
			$data = array();
		}
		$instance = self::init(self::strip_base($class));
		$instance->new_record = true;
		foreach ($instance->get_editable_column_names() as $column_name) {
			if (array_key_exists($column_name, $data)) {
				$instance->$column_name = $data[$column_name];
			}
		}
		return $instance;
	}
	
	final static protected function base_update($class, $data, $where) {
		$instance = self::init(self::strip_base($class));
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
	
	final static protected function base_destroy($class, $where) {
		$instance = self::init($class);
		foreach ($instance->read($where) as $record) {
			foreach($record->get_relationships() as $relationship) {
				if (get_class($relationship) == 'moojon_has_many_relationship' || get_class($relationship) == 'moojon_has_many_to_many_relationship') {
					$relationship->delete();
				}
			}
		}
		moojon_db::delete($instance->table, $where);
	}
	
	final protected function base_delete() {
		$where = '';
		$data = array();
		foreach ($this->get_primary_keys() as $column) {
			$where .= $column->get_name().' = :'.$column->get_name().' AND ';
			$data[':'.$column->get_name()] = $column->get_value();
		}
		$where = substr($where, 0, (strlen($where) - 5)).';';
		$statement = moojon_db::delete($this->table, $where);
		return $statement->execute($data);
	}
	
	final public function set($data, $value = null) {
		if (!is_array($data)) {
			$data = array($data => $value);
		}
		foreach ($this->get_editable_column_names() as $column_name) {
			$this->$column_name = $data[$column_name];
		}
		return $this;
	}
	
	final public function get($key) {
		return $this->$key;
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
}
?>