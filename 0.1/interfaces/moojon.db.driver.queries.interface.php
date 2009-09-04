<?php
interface moojon_db_driver_queries {
	static public function create_table($table, $columns = array(), $options = null);
	
	static public function show_tables($where = null);
	
	static public function show_columns($table);
	
	static public function drop_table($table);
	
	static public function rename_table($table, $new_name);
	
	static public function add_column($table, $column);
	
	static public function drop_column($table, $column);
	
	static public function change_column($table, $column);
	
	static public function modify_column($table, $column);
	
	static public function add_index($table, $index);
	
	static public function drop_index($table, $index);
	
	static public function select($table, $columns = array(), $where = null, $order = null, $limit = null);
	
	static public function insert($table, $columns = array());
	
	static public function update($table, $columns = array(), $where = null);
	
	static public function delete($table, $where = null);
}
?>