<?php
abstract class moojon_base_relationship extends moojon_base {
	protected $name;
	protected $foreign_class;
	protected $foreign_table;
	protected $foreign_key;
	protected $key;
	protected $column;
	
	final public function __construct($name, $foreign_table, $foreign_key, $key, moojon_base_column $column) {
		$this->name = $name;
		$this->foreign_class = moojon_inflect::singularize($foreign_table);
		$this->foreign_table = moojon_inflect::pluralize($foreign_table);
		$this->foreign_key = $foreign_key;
		$this->key = $key;
		$this->column = $column;
	}
	
	final public function get_class(moojon_base_model $accessor = null) {
		$foreign_table = moojon_inflect::singularize($this->foreign_table);
		if (get_class($this) == 'moojon_has_many_to_many_relationship') {
			$classes = array();
			$classes[] = $foreign_table;
			$classes[] = $accessor->get_class();
			sort($classes);
			$return = $classes[0].'_'.$classes[1];
		} else {
			$return = $foreign_table;
		}
		return $return;
	}
	
	final public function get_table(moojon_base_model $accessor = null) {
		if (get_class($this) == 'moojon_has_many_to_many_relationship') {
			$return = ($accessor->get_table() == $this->foreign_table) ? $this->name : $this->foreign_table;
		} else {
			$return = $this->foreign_table;
		}
		return moojon_inflect::singularize($return);
	}
	
	final public function get_name() {
		return $this->name;
	}
	
	final public function get_foreign_class() {
		return $this->foreign_class;
	}
	
	final public function get_foreign_table() {
		return $this->foreign_table;
	}
	
	final public function get_foreign_key() {
		return $this->foreign_key;
	}
	
	final public function get_key() {
		return $this->key;
	}
	
	final public function get_column() {
		return $this->column;
	}
	
	final public function get_class_where(moojon_base_model $accessor) {
		return moojon_db_driver::get_relationship_class_where($this, $accessor);
	}
	
	final public function get_object_where(moojon_base_model $accessor) {
		return moojon_db_driver::get_relationship_object_where($this, $accessor);
	}
	
	final public function get_param_values(moojon_base_model $accessor) {
		return moojon_db_driver::get_relationship_param_values($this, $accessor);
	}
	
	final public function get_param_data_types(moojon_base_model $accessor) {
		return moojon_db_driver::get_relationship_param_data_types($this, $accessor);
	}
}
?>