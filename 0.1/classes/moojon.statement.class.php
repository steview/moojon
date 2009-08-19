<?php
class moojon_statement extends moojon_base {
	static public function create_table($table, $data, $options = null) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::create_table($table, $data, $options));
	}
	
	static public function show_tables() {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::show_tables());
	}
	
	static public function show_columns($table) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::show_columns($table));
	}
	
	static public function drop_table($table) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::drop_table($table));
	}
	
	static public function alter_table_rename($table, $data) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::alter_table_rename($table, $data));
	}
	
	static public function add_column($table, $data) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::add_column($table, $data));
	}
	
	static public function drop_column($table, $data) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::drop_column($table, $data));
	}
	
	static public function change_column($table, $data) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::change_column($table, $data));
	}
	
	static public function modify_column($table, $data) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::modify_column($table, $data));
	}
	
	static public function add_index($table, $data, $options = null) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::add_index($table, $data));
	}
	
	static public function drop_index($table, $data) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::drop_index($table, $data));
	}
	
	static public function select($table, $data = null, $where = null, $order = null, $limit = null) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::select($table, $data, $where, $order, $limit));
	}
	
	static public function insert($table, $data = null) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::insert($table, $data));
	}
	
	static public function update($table, $data = null, $where = null) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::update($table, $data, $where));
	}
	
	static public function delete($table, $where = null) {
		$moojon_db = moojon_db::get();
		return $db::prepare(moojon_db_driver::delete($table, $where));
	}
}
?>