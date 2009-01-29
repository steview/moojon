<?php
abstract class moojon_base_migration extends moojon_base {
	final public function __construct() {}
		
	abstract public function up();
	
	abstract public function down();
	
	final protected function create_table($name, $options) {}
	
	final protected function drop_table($name) {}
	
	final protected function rename_table($old_name, $new_name) {}
	
	final protected function create_table($name, $options) {}
	
	final protected function add_column($table_name, $column_name, $type, $options) {}
	
	final protected function remove_column($table_name, $column_name) {}
	
	final protected function rename_column($table_name, $old_column_name, $new_column_name) {}
	
	final protected function add_index($table_name, $column_name, $options) {}
	
	final protected function remove_index($table_name, $column_name) {}
}
?>