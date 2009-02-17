<?php
abstract class moojon_base_model extends moojon_query_utilities {
	
	protected $obj;
	protected $class;
	protected $columns = array();
	private $relationships = array();
	private $errors = array();
	private $unsaved = false;
	protected $new_record = false;
	
	final public function __construct() {}
	
	abstract protected function add_columns();
	
	protected function add_relationships() {}
	
	final static public function strip_base($class) {
		if (substr($class, 0, 5) == 'base_') {
			$class = substr($class, 5);
		}
		return $class;
	}
	
	final protected function init($class) {
		$class = self::strip_base($class);
		$instance = new $class;
		$instance->add_columns();
		$instance->add_relationships();
		$instance->class = $class;
		$instance->obj = moojon_inflect::pluralize($class);
		return $instance;
	}
	
	final public function __set($key, $value) {
		if ($this->has_column($key)) {
			$this->columns[$key]->set_value($value);
			$this->unsaved = true;
		} else {
			self::handle_error("$key doesn't exist");
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
					self::handle_error("unknown property ($key)");
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
	
	final private function has_relationship($key) {
		return array_key_exists($key, $this->relationships);
	}
	
	final private function get_relationship_type($key) {
		return get_class($this->get_relationship($key));
	}
	
	final public function get_relationship($key) {
		if ($this->has_relationship($key)) {
			return $this->relationships[$key];
		} else {
			self::handle_error("no such relationship ($key)");
		}
	}
	
	final public function get_relationships() {
		return $this->relationships;
	}
	
	final private function add_relationship($relationship_type, $name , $foreign_obj, $foreign_key, $key) {
		if ($this->has_property($name)) {
			self::handle_error("duplicate property when adding relationship ($name)");
		}
		if ($foreign_obj == null) {
			$foreign_obj = $name;
		}
		$foreign_obj = self::strip_base($foreign_obj);
		if ($foreign_key == null) {
			$foreign_key = moojon_primary_key::get_foreign_key(get_class($this));
		}
		if ($key == null) {
			$key = moojon_primary_key::NAME;
		}
		if (!$this->has_column($key)) {
			self::handle_error("no such column to use as key for relationship ($key)");
		}
		$this->relationships[$name] = new $relationship_type($name, $foreign_obj, $foreign_key, $key);
	}
	
	final private function has_column($key) {
		return array_key_exists($key, $this->columns);
	}
	
	final protected function add_column(moojon_base_column $column) {
		if (!$this->has_property($column->get_name())) {
			$this->columns[$column->get_name()] = $column;
		} else {
			self::handle_error('duplicate property ('.$column->get_name().')');
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
	
	final protected function has_many($name, $foreign_obj = null, $foreign_key = null, $key = null) {
		$this->add_relationship('moojon_has_many_relationship', $name, $foreign_obj, $foreign_key, $key);
	}
	
	final protected function has_one($name, $foreign_obj = null, $foreign_key = null, $key = null) {
		$this->add_relationship('moojon_has_one_relationship', $name, $foreign_obj, $foreign_key, $key);
	}
	
	final protected function has_many_to_many($name, $foreign_obj = null, $foreign_key = null, $key = null) {
		$this->add_relationship('moojon_has_many_to_many_relationship', $name, $foreign_obj, $foreign_key, $key);
	}
		
	final public function get_class() {
		return $this->class;
	}
	
	final public function get_obj() {
		return $this->obj;
	}
	
	final public function get_columns() {
		return $this->columns;
	}
	
	final public function get_errors() {
		return $this->errors;
	}
	
	final protected function get_column($column_name) {
		foreach ($this->get_columns() as $column) {
			if ($column->get_name() == $column_name) {
				return $column;
			}
		}
		return false;
	}
	
	final protected function get_editable_columns() {
		$columns = array();
		foreach ($this->columns as $column) {
			if (get_class($column) != 'moojon_primary_key') {
				$columns[] = $column;
			}		
		}
		return $columns;
	}
	
	final protected function get_editable_column_names() {
		$columns = array();
		foreach ($this->get_editable_columns() as $column) {
			$columns[] = $column->get_name();
		}
		return $columns;
	}
	
	final protected function get_primary_key_columns() {
		$columns = array();
		foreach ($this->columns as $column) {
			if ($column->get_primary_key()) {
				$columns[] = $column->get_name();
			}		
		}
		return $columns;
	}
	
	final public function get_new_record() {
		return $this->new_record;
	}
	
	final public function validate($cascade = false) {
		$valid = true;
		$errors = array();
		foreach ($this->get_editable_columns() as $column) {
			$column_name = $column->get_name();
			$validation_method = 'validate_'.$column_name;
			if (method_exists($this, $validation_method)) {
				$validation = $this->$validation_method($column);
				if ($validation !== true) {
					$errors[] = $validation;
					$valid = false;
				}
			}
		}
		if ($cascade) {
			foreach ($this->relationships as $relationship) {
				$validation = $relationship->validate(true);
				if ($validation !== true) {
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
		if ($this->validate($cascade) === true) {
			foreach ($this->get_editable_column_names() as $column_name) {
				if (method_exists($this, "set_$column_name")) {
					$this->$column_name = call_user_func_array(array(get_class($this), "set_$column_name"), array($this, $this->get_column($column_name)));
				}
			}
			$data = array();
			foreach ($this->get_editable_column_names() as $column_name) {
				$data[$column_name] = $this->$column_name;
			}
			$builder = moojon_query_builder::init()->data($data);
			if ($this->new_record == true) {
				$builder->insert($this->obj);
			} else {
				if ($this->unsaved === true) {
					$id_property = moojon_primary_key::NAME;
					$builder->update($this->obj)->where("$id_property = ".$this->$id_property);
				} else {
					$saved = false;
				}
			}			
		} else {
			$saved = false;
		}
		if ($saved == true) {
			$builder->run();
			if ($cascade) {
				foreach($this->relationships as $relationship) {
					$relationship->save(true);
				}
			}
		}
		return $saved;		
	}
	
	final static protected function base_read($class, $where, $order, $limit, $accessor) {
		$class = self::strip_base($class);
		$args = func_get_args();
		array_shift($args);
		$builder = self::find_builder($args);
		$where = self::resolve($where, $builder, 'where');
		$order = self::resolve($order, $builder, 'order');
		$limit = self::resolve($limit, $builder, 'limit');
		$instance = self::init($class);
		$columns = array();
		foreach($instance->columns as $column) {
			$columns[$instance->obj.'.'.$column->get_name()] = strtoupper(moojon_inflect::singularize(get_class($instance)).'_'.$column->get_name());
		}
		$records = new moojon_model_collection($accessor);
		$rows = moojon_query_runner::select($instance->obj, $columns, $where, $order, $limit);
		foreach($rows as $row) {
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
		$args = func_get_args();
		array_shift($args);
		$builder = self::find_builder($args);
		$data = self::resolve($data, $builder, 'data');
		$instance = self::init($class);
		$instance->new_record = true;
		foreach ($instance->get_editable_column_names() as $column_name) {
			if (array_key_exists($column_name, $data)) {
				$instance->$column_name = $data[$column_name];
			}
		}
		return $instance;
	}
	
	final static protected function base_update($class, $data, $where) {
		$args = func_get_args();
		array_shift($args);
		$builder = self::find_builder($args);
		$data = self::resolve($data, $builder, 'data');
		$where = self::resolve($where, $builder, 'where');
		$instance = self::init($class);
		$id_property = moojon_primary_key::NAME;
		$query_data = array();
		foreach ($instance->get_editable_column_names() as $column_name) {
			$query_data[$column_name] = $data[$column_name];
		}
		if (!$where) {
			$where = $data[$id_property];
		}
		$builder = moojon_query_builder::init()->update($instance->obj, $query_data);
		if ($where) {
			$builder->where($where);
		}
		$builder->run();
	}
	
	final static protected function base_destroy($class, $where) {
		$args = func_get_args();
		array_shift($args);
		$builder = self::find_builder($args);
		$where = self::resolve($where, $builder, 'where');
		$instance = self::init($class);
		foreach ($instance->read($where) as $record) {
			foreach($record->get_relationships() as $relationship) {
				if ($relationship->get_relationship() == 'moojon_has_many_relationship' || $relationship->get_relationship() == 'moojon_has_many_to_many_relationship') {
					$relationship->delete();
				}
			}
		}
		moojon_query_runner::delete($instance->obj, $where);				
	}
	
	final protected function base_delete() {
		$where = '';
		foreach ($this->get_primary_keys() as $column) {
			$where .= $column->get_name()." = '".$column->get_value()."' AND ";
		}
		$where = substr($where, 0, (strlen($where) - 5)).';';
		moojon_query_runner::delete($this->obj, $where);
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
		$id_property = moojon_primary_key::NAME;
		return $this->$id_property;
	}
	
	final public function __clone() {
		$this->new_record == true;
		foreach ($this->get_primary_key_columns() as $column) {
			$column_name = $column_get_name();
			$this->$column_name = null;
		}
	}
}
?>