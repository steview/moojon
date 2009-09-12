<?php
final class moojon_model_collection extends ArrayObject {
	private $accessor;
	private $relationship;
	private $key;
	private $iterator;
	private $errors = array();
	
	public function __construct(moojon_base_model $accessor = null, $key = null) {
		$this->accessor = $accessor;
		if ($accessor && $key) {
			$this->relationship = $this->accessor->get_relationship($key);
			$this->key = $key;
		}
		$this->iterator = $this->getIterator();
	}
	
	public function __get($key) {
		switch ($key) {
			case 'first':
				if (count($this)) {
					return $this[0];
				} else {
					throw new moojon_exception('moojon_model_collection empty');
				}
				break;
			case 'last':
				if ($count = count($this)) {
					return $this[($count - 1)];
				} else {
					throw new moojon_exception('moojon_model_collection empty');
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
					throw new moojon_exception('moojon_model_collection empty');
				}
				break;
		}
	}
	
	public function get() {
		if ($this->relationship) {
			$foreign_class_name = moojon_inflect::singularize($this->relationship->get_foreign_table());
			$foreign_class = new $foreign_class_name;
			$records = $foreign_class->read($this->relationship->get_where($this->accessor), null, null, $this->relationship->get_get_param_values($this->accessor), $this->relationship->get_param_data_types($this->accessor), $this->accessor);
			switch (get_class($this->relationship)) {
				case 'moojon_has_one_relationship':
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
				return false;
			}
		}
	}
	
	public function get_relationship() {
		return $this->relationship;
	}
	
	public function filter($value, $property = null) {
		$collection = new moojon_model_collection($this->accessor, $this->key);
		if (!$property) {
			$property = moojon_primary_key::NAME;
		}
		foreach ($this as $record) {
			if ($record->$property == $value) {
				$collection[] = $record;
			}
		}
		return $collection;
	}
	
	public function get_errors() {
		return $this->errors;
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
		$accessor = $this->relationship->get_accessor();
		switch (get_class($this->relationship)) {
			case 'moojon_has_many_relationship':
				$model->$foreign_key = $accessor->$key;
				break;
			case 'moojon_has_many_to_many_relationship':
				if ($model->new_record) {
					$model->save();
				}
				$class_name = $this->relationship->get_class();
				$class = new $class_name;
				$class->$foreign_key = $model->$key;
				$key_property = moojon_primary_key::get_foreign_key($accessor);
				$class->$key_property = $accessor->$key;
				break;
		}
		$this[] = $model;
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
	
	public function delete() {
		foreach ($this as $record) {
			$record->delete();
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