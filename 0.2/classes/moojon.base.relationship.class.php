<?php
abstract class moojon_base_relationship extends moojon_base {
	protected $name;
	protected $foreign_table;
	protected $foreign_key;
	protected $key;
	
	final public function __construct($name, $foreign_table, $foreign_key, $key) {
		$this->name = $name;
		$this->foreign_table = moojon_inflect::pluralize($foreign_table);
		$this->foreign_key = $foreign_key;
		$this->key = $key;
	}
	
	final public function get_name() {
		return $this->name;
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
	
	abstract public function get_where(moojon_base_model $accessor);
	
	public function get_param_values(moojon_base_model $accessor) {
		$key = $this->key;
		return array(":$key" => $accessor->$key);
	}
	
	public function get_param_data_types(moojon_base_model $accessor) {
		$key = $this->key;
		$column = $accessor->get_column($key);
		return array(":$key" => $column->get_data_type());
	}
}
?>