<?php
abstract class base_schema_migration extends moojon_base_model {
	
	protected function add_columns() {
		$this->add_string('version');
	}
	
	final static public function get_column_names($exceptions = array()) {return self::base_get_column_names(get_class(), $exceptions);}
	final static public function get_editable_column_names($exceptions = array()) {return self::base_get_editable_column_names(get_class(), $exceptions);}
	final static public function get_primary_key_column_names($exceptions = array()) {return self::base_get_primary_key_column_names(get_class(), $exceptions);}
	
	final static public function read_all_by_version($value, $order = null, $limit = null) {return self::read_by(get_class(), 'id', $value, $order, $limit);}
	final static public function read_by_version($value, $order = null, $limit = null) {return self::read_all_by_id($value, $order, $limit)->first;}
	final static public function destroy_by_version($value) {self::destroy_by(get_class(), 'id', $value);}
	final static public function read_or_create_by_version($value, $data = null) {return self::read_or_create_by(get_class(), 'column1', $value, $data);}
	
	final static public function create($data = null, $param_values = array(), $param_data_types = array()) {return self::base_create(get_class(), $data, $param_values, $param_data_types);}	
	final static public function read($where = null, $order = null, $limit = null, $param_values = array(), $param_data_types = array(), moojon_base_model $accessor = null) {return self::base_read(get_class(), $where, $order, $limit, $param_values, $param_data_types, $accessor);}	
	final static public function update($data, $where = null, $param_values = array(), $param_data_types = array()) {return self::base_update(get_class(), $data, $where, $param_values, $param_data_types);}
	final static public function destroy($where = null, $param_values = array(), $param_data_types = array()) {return self::base_destroy(get_class(), $where, $param_values, $param_data_types);}
}
?>