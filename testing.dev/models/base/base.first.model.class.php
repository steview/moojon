<?php
abstract class base_first extends moojon_base_model {
	
	protected function add_columns() {
		$this->add_primary_key();
		$this->add_string('column1', 255, false, null);
	}
	
	final static public function read_by_id($value, $order = null, $limit = null) {return self::read_all_by_id($value, $order, $limit)->first;}
	final static public function read_all_by_id($value, $order = null, $limit = null) {return self::read_by(get_class(), 'id', $value, $order, $limit);}
	final static public function destroy_by_id($value) {self::destroy_by(get_class(), 'id', $value);}
	
	final static public function create($data = null) {return self::base_create(get_class(), $data);}
	final static public function read($where = null, $order = null, $limit = null, moojon_base_model $accessor = null, $param_values = array(), $param_data_types = array()) {return self::base_read(get_class(), $where, $order, $limit, $accessor, $param_values, $param_data_types);}
	final static public function update($data, $id = null) {return self::base_update(get_class(), $data, $where);}
	final static public function destroy($where = null, $param_values = array(), $param_data_types = array()) {return self::base_destroy(get_class(), $where, $param_values, $param_data_types);}
	final public function delete() {return self::base_delete();}
}
?>