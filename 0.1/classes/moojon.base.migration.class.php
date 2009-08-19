<?php
abstract class moojon_base_migration extends moojon_base {
	protected $include_primary_key = true;
	
	final public function __construct() {}
		
	abstract public function up();
	
	abstract public function down();
	
	final protected function add_primary_key() {
		return new moojon_primary_key();
	}
	
	final protected function add_binary($name, $limit = 255, $null = false, $default = null) {
		return new moojon_binary_column($name, $limit, $null, $default);
	}
	
	final protected function add_boolean($name, $null = false, $default = null) {
		return new moojon_boolean_column($name, $null, $default);
	}
	
	final protected function add_date($name, $null = false, $default = null) {
		return new moojon_date_column($name, $null, $default);
	}
	
	final protected function add_datetime($name, $null = false, $default = null) {
		return new moojon_datetime_column($name, $null, $default);
	}
	
	final protected function add_decimal($name, $limit = 10, $decimals = 0, $null = false, $default = null) {
		return new moojon_decimal_column($name, $limit, $decimals, $null, $default);
	}
	
	final protected function add_float($name, $limit = 10, $decimals = 0, $null = false, $default = null) {
		return new moojon_float_column($name, $limit, $decimals, $null, $default);
	}
	
	final protected function add_integer($name, $limit = 11, $null = false, $default = null) {
		return new moojon_integer_column($name, $limit, $null, $default);
	}
	
	final protected function add_string($name, $limit = 255, $null = false, $default = null) {
		return new moojon_string_column($name, $limit, $null, $default);
	}
	
	final protected function add_text($name, $binary = null) {
		return new moojon_text_column($name, $binary);
	}
	
	final protected function add_time($name, $null = false, $default = null) {
		return new moojon_time_column($name, $null, $default);
	}
	
	final protected function add_timestamp($name, $null = false, $default = null) {
		return new moojon_timestamp_column($name, $null, $default);
	}
	
	final protected function create_table($name, $columns, $options = null) {
		if (!is_array($columns)) {
			$data = array($columns);
		} else {
			$data = $columns;
		}
		if ($this->include_primary_key) {
			$primary_key = new moojon_primary_key;
			$primary_key->create_table($name, $data, $options);
		} else {
			$data = implode(', ', $data);
			moojon_db::create_table($name, $data, $options);
		}
	}
	
	final protected function remove_table($name) {
		moojon_db::drop_table($name);
	}
	
	final protected function rename_table($old_table_name, $new_table_name) {
		moojon_db::alter_table_rename($old_table_name, $new_table_name);
	}
	
	final protected function add_column($table_name, $column) {
		moojon_db::alter_table_add_column($table_name, $column->get_add_column());
	}
	
	final protected function remove_column($table_name, $column_name) {
		moojon_db::alter_table_drop_column($table_name, $column_name);
	}
	
	final protected function rename_column($table_name, $old_column_name, $new_column_name) {
		moojon_db::alter_table_change_column($table_name, "$old_column_name $new_column_name");
	}
	
	final protected function change_column($table_name, $column) {
		moojon_db::alter_table_modify_column($table_name, $column->get_add_column());
	}
	
	final protected function add_index($table_name, $column_name, $options = null) {
		$data = $column_name;
		if ($options) {
			$data = "$data $options";
		}
		moojon_db::alter_table_add_index($table_name, $data);
	}
	
	final protected function remove_index($table_name, $column_name) {
		moojon_db::alter_table_drop_index($table_name, $column_name);
	}
}
?>