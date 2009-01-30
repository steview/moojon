<?php
abstract class moojon_base_migration extends moojon_base {
	final public function __construct() {}
		
	abstract public function up();
	
	abstract public function down();
	
	final protected function create_table($name, $columns, $options = null) {
		array_unshift($columns, 'id INT NOT NULL AUTO_INCREMENT');
		$columns[] = 'PRIMARY KEY(id)';
		moojon_query::run_raw("CREATE TABLE $name (".implode(', ', $columns).") $options;");
	}
	
	final protected function column_definition($column_name, $type, $options = null) {
		return "$column_name $type $options";
	}
	
	final protected function remove_table($name) {
		moojon_query::run_raw("DROP TABLE $name;");
	}
	
	final protected function rename_table($old_table_name, $new_table_name) {
		$this->run($table_name, "RENAME TO $new_table_name");
	}
	
	final protected function add_column($table_name, $column_name, $type, $options = null) {
		$this->run($table_name, "ADD COLUMN $column_name $type $options");
	}
	
	final protected function remove_column($table_name, $column_name) {
		$this->run($table_name, "DROP COLUMN $column_name");
	}
	
	final protected function rename_column($table_name, $old_column_name, $new_column_name) {
		$this->run($table_name, "CHANGE COLUMN $old_column_name $new_column_name");
	}
	
	final protected function change_column($table_name, $column_name, $type, $options = null) {
		$this->run($table_name, "MODIFY COLUMN $column_name $type $options");
	}
	
	final protected function add_index($table_name, $column_name, $options = null) {
		$this->run($table_name, "ADD INDEX $column_name $options");
	}
	
	final protected function remove_index($table_name, $column_name) {
		$this->run($table_name, "DROP INDEX $column_name");
	}
	
	final private function run($table_name, $query) {
		moojon_query::run_raw("ALTER TABLE $table_name $query;");
	}
}
?>