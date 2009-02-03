<?php
final class moojon_query_runner extends moojon_query_utilities {
	private function __construct() {}
	
	static private function build($command, $obj = null, $data = null, $where = null, $order = null, $limit = null) {
		$builder = self::find_builder(func_get_args());
		return moojon_query_builder::init(self::resolve($command, $builder, 'command'), self::resolve($obj, $builder, 'obj'), self::resolve($data, $builder, 'data'), self::resolve($where, $builder, 'where'), self::resolve($order, $builder, 'order'), self::resolve($limit, $builder, 'limit'));
	}
	
	static private function run($command, $obj = null, $data = null, $where = null, $order = null, $limit = null) {
		return self::build($command, $obj, $data, $where, $order, $limit)->run();
	}
	
	static private function run_join($type, $foreign_table = null, $local_table = null, $foreign_key = null, $local_key = null, $data = null, $where = null, $order = null, $limit = null) {
		$builder = self::find_builder(func_get_args());
		$foreign_key = self::resolve($foreign_key, $builder, 'foreign_key');
		$local_key = self::resolve($local_key, $builder, '');
		if (!$local_key) $local_key = moojon_primary_key::NAME;
		if (!$foreign_key) $foreign_key = moojon_inflect::singularize($local_table).'_'.moojon_primary_key::NAME;
		return self::build('SELECT', $foreign_table, $data, $where, $order, $limit)->join(self::resolve($type, $builder, 'type'), self::resolve($foreign_table, $builder, 'foreign_table'), self::resolve($local_table, $builder, 'primary_obj'), $foreign_key, $local_key)->run();
	}
	
	static public function select($obj, $data = '*', $where = null, $order = null, $limit = null) 	{
		return self::run('SELECT', $obj, $data, $where, $order, $limit);
	}
	
	static public function insert($obj, $data = null) {
		return self::run('INSERT', $obj, $data, null, null, null);
	}
	
	static public function update($obj, $data = null, $where = null) {
		return self::run('UPDATE', $obj, $data, $where, null, null);
	}
	
	static public function delete($obj, $where = null) {
		return self::run('DELETE', $obj, null, $where, null, null);
	}
	
	static public function describe($obj, $data = null) {
		return self::run('DESCRIBE', $obj, $data, null, null, null);
	}
	
	static public function desc($obj, $data = null) {
		return self::run('DESC', $obj, $data, null, null, null);
	}
	
	static public function show_columns($obj, $where = null) {
		return self::run('SHOW COLUMNS', $obj, null, $where, null, null);
	}
	
	static public function show_full_columns($obj, $where = null) {
		return self::run('SHOW FULL COLUMNS', $obj, null, $where, null, null);
	}
	
	static public function show_tables($obj = null, $where = null) {
		return self::run('SHOW TABLES', $obj, null, $where, null, null);
	}
	
	static public function show_full_tables($obj = null, $where = null) {
		return self::run('SHOW FULL TABLES', $obj, null, $where, null, null);
	}
	
	static public function create_table($obj, $data, $option = null) {
		return self::run('CREATE TABLE', $obj, $data, $options, null, null);
	}
	
	static public function drop_table($obj) {
		return self::run('DROP TABLE', $obj, null, null, null, null);
	}
	
	static public function alter_table_rename($obj, $data) {
		return self::run('ALTER TABLE RENAME TO', $obj, $data, null, null, null);
	}
	
	static public function alter_table_rename_to($obj, $data) {
		return self::run('ALTER TABLE RENAME TO', $obj, $data, null, null, null);
	}
	
	static public function alter_table_add_column($obj, $data) {
		return self::run('ALTER TABLE ADD COLUMN', $obj, $data, null, null, null);
	}
	
	static public function alter_table_drop_column($obj, $data) {
		return self::run('ALTER TABLE DROP COLUMN', $obj, $data, null, null, null);
	}
	
	static public function alter_table_change_column($obj, $data) {
		return self::run('ALTER TABLE CHANGE COLUMN', $obj, $data, null, null, null);
	}
	
	static public function alter_table_modify_column($obj, $data) {
		return self::run('ALTER TABLE MODIFY COLUMN', $obj, $data, null, null, null);
	}
	
	static public function alter_table_add_index($obj, $data, $options = null) {
		return self::run('ALTER TABLE ADD INDEX', $obj, $data, $options, null, null);
	}
	
	static public function alter_table_remove_index($obj, $data) {
		return self::run('ALTER TABLE DROP INDEX', $obj, $data, null, null, null);
	}
	
	static public function left($foreign_table, $local_table = null, $foreign_key = null, $local_key = null, $data = '*', $where = null, $order = null, $limit = null) {
		return self::run_join('LEFT', $foreign_table, $local_table, $foreign_key, $local_key, $data, $where, $order, $limit);
	}
	
	static public function right($foreign_table, $local_table = null, $foreign_key = null, $local_key = null, $data = '*', $where = null, $order = null, $limit = null) {
		return self::run_join('RIGHT', $foreign_table, $local_table, $foreign_key, $local_key, $data, $where, $order, $limit);
	}
}
?>