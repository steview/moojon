<?php
final class moojon_model_collection extends ArrayObject
{
	private $relationship;
	private $iterator;
	private $errors = array();
	
	final public function __construct(moojon_base_relationship $relationship = null) {
		$this->relationship = $relationship;
	}
	
	final public function __get($key) {
		switch ($key) {
			case 'first':
				if (count($this)) {
					return $this[0];
				} else {
					//error
				}
				break;
			case 'first':
				if ($count = count($this)) {
					return $this[($count - 1)];
				} else {
					//error
				}
				break;
			default:
				//error
				break;
		}
	}
	
	final public function get(moojon_base_model $accessor) {
		if (is_subclass_of($this->relationship, 'moojon_base_relationship')) {
			$foreign_class_name = moojon_inflect::singularize($this->relationship->get_foreign_obj());
			$foreign_class = new $foreign_class_name;
			$records = $foreign_class->read($this->relationship->get_where(), null, null, $accessor);
			switch (get_class($this->relationship)) {
				case 'moojon_has_one_relationship':
					return $this->first;
					break;
				case 'moojon_has_many_relationship':
				case 'moojon_has_many_to_many_relationship':
					return $records;
					break;
			}
		} else {
			if ($this->iterator == null) {
				$this->iterator = $this->getIterator();
			}
			if ($this->iterator->valid()) {
				$return = $this->iterator->current();
			    $this->iterator->next();
				return $return;
			} else {
				return false;
			}
		}
	}
	
	final public function get_errors() {
		return $this->errors;
	}	
	
	final public function get_type() {
		return get_class($this->relationship);
	}
	
	final public function validate($cascade = false) {
		$valid = true;
		$errors = array();
		foreach ($this as $record) {
			$validation = $record->validate($cascade);
			if ($validation !== true) {
				foreach ($record->get_errors() as $error) {
					$errors[] = $error;
				}
				$valid = false;
			}
		}
		$this->errors = $errors;
		return $valid;
	}
	
	final public function add(moojon_base_model $model) {
		$key1 = $this->relationship->get_key1();
		$key2 = $this->relationship->get_key2();
		$accessor = $this->relationship->get_accessor();
		switch (get_class($this->relationship)) {
			case 'moojon_has_many_relationship':
				$model->$key2 = $accessor->$key1;
				break;
			case 'moojon_has_many_to_many_relationship':
				if ($model->new_record) {
					$model->save();
				}
				$class_name = $this->relationship->get_class();
				$class = new $class_name;
				$class->$key2 = $model->$key1;
				$key_property = moojon_model_properties::get_foreign_key($accessor->get_class());
				$class->$key_property = $accessor->$key1;
				break;
		}
		$this[] = $model;
	}
	
	final public function remove(moojon_base_model $model) {
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
	
	final public function delete() {
		foreach ($this as $record) {
			$record->delete();
		}
	}
	
	final public function set($data, $value = null) {
		if (!is_array($data)) {
			$data = array($data => $value);
		}
		foreach ($this as $record) {
			$record->set($data);
		}
	}
	
	final public function save($cascade = false) {
		$saved = true;
		if ($this->validate($cascade) === true) {
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