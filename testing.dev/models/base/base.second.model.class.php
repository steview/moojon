<?php
abstract class base_second extends moojon_base_model {
	
	protected function add_columns() {
		$this->add_primary_key();
		$this->add_string('column1', 255, false, null);
		$this->add_integer('first_id', 11, false, null);
		$this->add_datetime('created_on', false, null);
		$this->add_datetime('updated_at', true, null);
	}
	
	final static public function read_all_by_id($value, $order = null, $limit = null) {return self::read_by(get_class(), 'id', $value, $order, $limit);}
	final static public function read_all_by_column1($value, $order = null, $limit = null) {return self::read_by(get_class(), 'column1', $value, $order, $limit);}
	final static public function read_all_by_first_id($value, $order = null, $limit = null) {return self::read_by(get_class(), 'first_id', $value, $order, $limit);}
	final static public function read_all_by_created_on($value, $order = null, $limit = null) {return self::read_by(get_class(), 'created_on', $value, $order, $limit);}
	final static public function read_all_by_updated_at($value, $order = null, $limit = null) {return self::read_by(get_class(), 'updated_at', $value, $order, $limit);}
	final static public function read_by_id($value, $order = null, $limit = null) {return self::read_all_by_id($value, $order, $limit)->first;}
	final static public function read_by_column1($value, $order = null, $limit = null) {return self::read_all_by_column1($value, $order, $limit)->first;}
	final static public function read_by_first_id($value, $order = null, $limit = null) {return self::read_all_by_first_id($value, $order, $limit)->first;}
	final static public function read_by_created_on($value, $order = null, $limit = null) {return self::read_all_by_created_on($value, $order, $limit)->first;}
	final static public function read_by_updated_at($value, $order = null, $limit = null) {return self::read_all_by_updated_at($value, $order, $limit)->first;}
	final static public function destroy_by_id($value) {self::destroy_by(get_class(), 'id', $value);}
	final static public function destroy_by_column1($value) {self::destroy_by(get_class(), 'column1', $value);}
	final static public function destroy_by_first_id($value) {self::destroy_by(get_class(), 'first_id', $value);}
	final static public function destroy_by_created_on($value) {self::destroy_by(get_class(), 'created_on', $value);}
	final static public function destroy_by_updated_at($value) {self::destroy_by(get_class(), 'updated_at', $value);}
	final static public function read_or_create_by_column1($value, $data = null) {return self::read_or_create_by(get_class(), 'column1', $value, $data);}
	final static public function read_or_create_by_first_id($value, $data = null) {return self::read_or_create_by(get_class(), 'first_id', $value, $data);}
	final static public function read_or_create_by_created_on($value, $data = null) {return self::read_or_create_by(get_class(), 'created_on', $value, $data);}
	final static public function read_or_create_by_updated_at($value, $data = null) {return self::read_or_create_by(get_class(), 'updated_at', $value, $data);}
	
	final static public function create($data = null, $param_values = array(), $param_data_types = array()) {
		return self::base_create(get_class(), $data, $param_values, $param_data_types);
	}
	
	final static public function read($where = null, $order = null, $limit = null, $param_values = array(), $param_data_types = array(), moojon_base_model $accessor = null) {
		return self::base_read(get_class(), $where, $order, $limit, $param_values, $param_data_types, $accessor);
	}
	
	final static public function update($data, $where = null, $param_values = array(), $param_data_types = array()) {
		return self::base_update(get_class(), $data, $where, $param_values, $param_data_types);
	}
	
	final static public function destroy($where = null, $param_values = array(), $param_data_types = array()) {
		return self::base_destroy(get_class(), $where, $param_values, $param_data_types);
	}
}
?>