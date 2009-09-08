<?php
abstract class base_first extends moojon_base_model {
	
	protected function add_columns() {
		$this->add_primary_key();
		$this->add_string('column1', 255, false, null);
	}
	
	final static public function read_by_id($value, $order = null, $limit = null) {
		$instance = self::init($class);
		$column = $instance->get_column('id');
		return self::read("id = :id", $order, $limit, null, array(':id' => $value), array(':id' => $column->get_data_type()));
	}
	
	final static public function create($data = null) {return self::base_create(get_class(), $data);}
	final static public function read($where = null, $order = null, $limit = null, moojon_base_model $accessor = null, $param_values = array(), $param_data_types = array()) {return self::base_read(get_class(), $where, $order, $limit, $accessor, $param_values, $param_data_types);}
	final static public function update($data, $id = null) {return self::base_update(get_class(), $data, $where);}
	final static public function destroy($where = null) {return self::base_destroy(get_class(), $where);}
	final public function delete() {return self::base_delete();}
}
?>