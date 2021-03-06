<?php
final class moojon_model_collection extends ArrayObject {
	private $accessor;
	private $relationship;
	private $iterator;
	private $validator;
	
	public function __construct(moojon_base_model $accessor = null, moojon_base_relationship $relationship = null) {
		$this->accessor = $accessor;
		$this->relationship = $relationship;
		$this->iterator = $this->getIterator();
		$this->validator = new moojon_validator;
	}
	
	public function __get($key) {
		switch ($key) {
			case 'first':
				if (count($this)) {
					return $this[0];
				} else {
					return null;
				}
				break;
			case 'last':
				if ($count = count($this)) {
					return $this[($count - 1)];
				} else {
					return null;
				}
				break;
			case 'count':
			case 'length':
				return count($this);
				break;
			default:
				if ($this->iterator->valid()) {
					if ($this->iterator->current()->has_property($key)) {
						return $this->iterator->current()->$key;
					} else {
						throw new moojon_exception("moojon_model_collection get what? ($key)");
					}
				} else {
					throw new moojon_exception("moojon_model_collection empty ($key)");
				}
				break;
		}
	}
	
	public function get($where = null, $order = null, $limit = null) {
		if ($this->relationship) {
			if ($where) {
				$where .= ' AND ';
			} else {
				$where = '';
			}
			$where .= $this->relationship->get_object_where($this->accessor);
			$foreign_class_name = $this->relationship->get_table($this->accessor);
			$foreign_class = new $foreign_class_name;
			$param_values = $this->relationship->get_param_values($this->accessor);
			$param_data_types = $this->relationship->get_param_values($this->accessor);
			$records = $foreign_class->read($where, $order, $limit, $param_values, $param_data_types, $this->accessor, $this->relationship->get_name());
			$records->relationship = $this->relationship;
			switch (get_class($this->relationship)) {
				case 'moojon_has_one_relationship':
				case 'moojon_belongs_to_relationship':
					return $records->first;
					break;
				case 'moojon_has_many_relationship':
				case 'moojon_has_many_to_many_relationship':
					return $records;
					break;
			}
		} else {
			if ($this->iterator->valid()) {
				$return = $this->iterator->current();
			    $this->iterator->next();
				return $return;
			} else {
				return $this;
			}
		}
	}
	
	public function where($where, $order = null, $limit = null) {
		return $this->get($where, $order, $limit);
	}
	
	public function order($order, $where = null, $limit = null) {
		return $this->get($where, $order, $limit);
	}
	
	public function limit($limit, $where = null, $order = null) {
		return $this->get($where, $order, $limit);
	}
	
	public function get_relationship() {
		return $this->relationship;
	}
	
	public function filter($property, $value) {
		$collection = new moojon_model_collection($this->accessor, $this->relationship);
		$records = $this->get();
		foreach ($records as $record) {
			if ($record->$property == $value) {
				$collection[] = $record;
			}
		}
		return $collection;
	}
	
	public function get_validator_messages() {
		return $this->validator->get_messages();
	}
	
	public function get_type() {
		return get_class($this->relationship);
	}
	
	public function validate($cascade = false) {
		$valid = true;
		$errors = array();
		foreach ($this as $record) {
			$validation = $record->validate($cascade);
			if (!$validation) {
				foreach ($record->get_errors() as $error) {
					$errors[] = $error;
				}
				$valid = false;
			}
		}
		$this->errors = $errors;
		return $valid;
	}
	
	public function add(moojon_base_model $model) {
		$key = $this->relationship->get_key();
		$foreign_key = $this->relationship->get_foreign_key();
		$accessor = $this->accessor;
		switch (get_class($this->relationship)) {
			case 'moojon_has_many_relationship':
				$model->$foreign_key = $accessor->$key;
				break;
			case 'moojon_has_many_to_many_relationship':
				if ($model->new_record) {
					$model->save();
				}
				$class_name = $this->relationship->get_class($accessor);
				$class = new $class_name;
				$key_property = moojon_primary_key::get_foreign_key($accessor->get_class());
				$class->$foreign_key = $model->$key;
				$class->$key_property = $accessor->$key;
				$model = $class;
				break;
		}
		$this[] = $model;
	}
	
	public function read_column($column_name) {
		$return = array();
		foreach ($this as $record) {
			$return[] = $record->$column_name;
		}
		return $return;
	}
	
	public function remove(moojon_base_model $model) {
		for ($x = 0; $x < (count($this) - 1);  $x++) {
			$record = $this[$x];
			$same = true;
			foreach ($record->get_columns() as $column) {
				$column_name = $column->get_name();
				if ($record->$column_name != $model->$column_name) {
					$same = false;
				}
			}
			if ($same) {
				array_splice($this, $x, 1);
				$x--;
				$model->delete();
				$model = null;
			}
		}
	}
	
	public function delete($cascade = true) {
		foreach ($this as $record) {
			$record->delete($cascade);
		}
	}
	
	public function set($data, $value = null) {
		if (!is_array($data)) {
			$data = array($data => $value);
		}
		foreach ($this as $record) {
			$record->set($data);
		}
	}
	
	public function save($cascade = false) {
		$saved = true;
		if ($this->validate($cascade)) {
			foreach ($this as $record) {
				$record->save($cascade);
			}
		} else {
			$saved = false;
		}
		return $saved;
	}
}
?>