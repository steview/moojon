<?php
abstract class base_user extends moojon_base_model {
	
	protected function add_columns() {
		$this->add_primary_key();
		$this->add_string('name', 255, false, null);
		$this->add_string('email', 255, false, null);
		$this->add_binary('password', 255, false, null);
		$this->add_date('date_column', false, null);
		$this->add_time('time_column', false, null);
		$this->add_decimal('float_column', 10,0, 10, false, null);
		$this->add_decimal('decimal_column', 10,0, 10, false, null);
		$this->add_datetime('timestamp_column', false, 'CURRENT_TIMESTAMP');
		$this->add_datetime('created_on', false, null);
		$this->add_datetime('updated_at', true, null);
	}
	
	final static public function read_all_by_id($value, $order = null, $limit = null) {return self::read_by(get_class(), 'id', $value, $order, $limit);}
	final static public function read_all_by_name($value, $order = null, $limit = null) {return self::read_by(get_class(), 'name', $value, $order, $limit);}
	final static public function read_all_by_email($value, $order = null, $limit = null) {return self::read_by(get_class(), 'email', $value, $order, $limit);}
	final static public function read_all_by_password($value, $order = null, $limit = null) {return self::read_by(get_class(), 'password', $value, $order, $limit);}
	final static public function read_all_by_date_column($value, $order = null, $limit = null) {return self::read_by(get_class(), 'date_column', $value, $order, $limit);}
	final static public function read_all_by_time_column($value, $order = null, $limit = null) {return self::read_by(get_class(), 'time_column', $value, $order, $limit);}
	final static public function read_all_by_float_column($value, $order = null, $limit = null) {return self::read_by(get_class(), 'float_column', $value, $order, $limit);}
	final static public function read_all_by_decimal_column($value, $order = null, $limit = null) {return self::read_by(get_class(), 'decimal_column', $value, $order, $limit);}
	final static public function read_all_by_timestamp_column($value, $order = null, $limit = null) {return self::read_by(get_class(), 'timestamp_column', $value, $order, $limit);}
	final static public function read_all_by_created_on($value, $order = null, $limit = null) {return self::read_by(get_class(), 'created_on', $value, $order, $limit);}
	final static public function read_all_by_updated_at($value, $order = null, $limit = null) {return self::read_by(get_class(), 'updated_at', $value, $order, $limit);}
	final static public function read_by_id($value, $order = null, $limit = null) {return self::read_all_by_id($value, $order, $limit)->first;}
	final static public function read_by_name($value, $order = null, $limit = null) {return self::read_all_by_name($value, $order, $limit)->first;}
	final static public function read_by_email($value, $order = null, $limit = null) {return self::read_all_by_email($value, $order, $limit)->first;}
	final static public function read_by_password($value, $order = null, $limit = null) {return self::read_all_by_password($value, $order, $limit)->first;}
	final static public function read_by_date_column($value, $order = null, $limit = null) {return self::read_all_by_date_column($value, $order, $limit)->first;}
	final static public function read_by_time_column($value, $order = null, $limit = null) {return self::read_all_by_time_column($value, $order, $limit)->first;}
	final static public function read_by_float_column($value, $order = null, $limit = null) {return self::read_all_by_float_column($value, $order, $limit)->first;}
	final static public function read_by_decimal_column($value, $order = null, $limit = null) {return self::read_all_by_decimal_column($value, $order, $limit)->first;}
	final static public function read_by_timestamp_column($value, $order = null, $limit = null) {return self::read_all_by_timestamp_column($value, $order, $limit)->first;}
	final static public function read_by_created_on($value, $order = null, $limit = null) {return self::read_all_by_created_on($value, $order, $limit)->first;}
	final static public function read_by_updated_at($value, $order = null, $limit = null) {return self::read_all_by_updated_at($value, $order, $limit)->first;}
	final static public function destroy_by_id($value) {self::destroy_by(get_class(), 'id', $value);}
	final static public function destroy_by_name($value) {self::destroy_by(get_class(), 'name', $value);}
	final static public function destroy_by_email($value) {self::destroy_by(get_class(), 'email', $value);}
	final static public function destroy_by_password($value) {self::destroy_by(get_class(), 'password', $value);}
	final static public function destroy_by_date_column($value) {self::destroy_by(get_class(), 'date_column', $value);}
	final static public function destroy_by_time_column($value) {self::destroy_by(get_class(), 'time_column', $value);}
	final static public function destroy_by_float_column($value) {self::destroy_by(get_class(), 'float_column', $value);}
	final static public function destroy_by_decimal_column($value) {self::destroy_by(get_class(), 'decimal_column', $value);}
	final static public function destroy_by_timestamp_column($value) {self::destroy_by(get_class(), 'timestamp_column', $value);}
	final static public function destroy_by_created_on($value) {self::destroy_by(get_class(), 'created_on', $value);}
	final static public function destroy_by_updated_at($value) {self::destroy_by(get_class(), 'updated_at', $value);}
	final static public function read_or_create_by_name($value, $data = null) {return self::read_or_create_by(get_class(), 'name', $value, $data);}
	final static public function read_or_create_by_email($value, $data = null) {return self::read_or_create_by(get_class(), 'email', $value, $data);}
	final static public function read_or_create_by_password($value, $data = null) {return self::read_or_create_by(get_class(), 'password', $value, $data);}
	final static public function read_or_create_by_date_column($value, $data = null) {return self::read_or_create_by(get_class(), 'date_column', $value, $data);}
	final static public function read_or_create_by_time_column($value, $data = null) {return self::read_or_create_by(get_class(), 'time_column', $value, $data);}
	final static public function read_or_create_by_float_column($value, $data = null) {return self::read_or_create_by(get_class(), 'float_column', $value, $data);}
	final static public function read_or_create_by_decimal_column($value, $data = null) {return self::read_or_create_by(get_class(), 'decimal_column', $value, $data);}
	final static public function read_or_create_by_timestamp_column($value, $data = null) {return self::read_or_create_by(get_class(), 'timestamp_column', $value, $data);}
	final static public function read_or_create_by_created_on($value, $data = null) {return self::read_or_create_by(get_class(), 'created_on', $value, $data);}
	final static public function read_or_create_by_updated_at($value, $data = null) {return self::read_or_create_by(get_class(), 'updated_at', $value, $data);}
	
	final static public function get_column_names($exceptions = array()) {return self::base_get_column_names(get_class(), $exceptions);}
	final static public function get_editable_column_names($exceptions = array()) {return self::base_get_editable_column_names(get_class(), $exceptions);}
	final static public function get_primary_key_column_names($exceptions = array()) {return self::base_get_primary_key_column_names(get_class(), $exceptions);}
	
	final static public function create($data = null, $param_values = array(), $param_data_types = array()) {return self::base_create(get_class(), $data, $param_values, $param_data_types);}
	final static public function read($where = null, $order = null, $limit = null, $param_values = array(), $param_data_types = array(), moojon_base_model $accessor = null) {return self::base_read(get_class(), $where, $order, $limit, $param_values, $param_data_types, $accessor);}
	final static public function update($data, $where = null, $param_values = array(), $param_data_types = array()) {return self::base_update(get_class(), $data, $where, $param_values, $param_data_types);}
	final static public function destroy($where = null, $param_values = array(), $param_data_types = array()) {return self::base_destroy(get_class(), $where, $param_values, $param_data_types);}
}
?>