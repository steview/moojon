<?php
class moojon_query_runner extends moojon_query_utilities
{
	final private function __construct() {}
	
	final static private function build($command, $obj = null, $data = null, $where = null, $order = null, $limit = null)
	{
		$builder = self::find_builder(func_get_args());
		return moojon_query_builder::init(self::resolve($command, $builder, 'command'), self::resolve($obj, $builder, 'obj'), self::resolve($data, $builder, 'data'), self::resolve($where, $builder, 'where'), self::resolve($order, $builder, 'order'), self::resolve($limit, $builder, 'limit'));
	}
	
	final static private function run($command, $obj = null, $data = null, $where = null, $order = null, $limit = null, $test = null)
	{
		return self::build($command, $obj, $data, $where, $order, $limit)->run($test);
	}
	
	final static private function run_join($type, $foreign_table = null, $local_table = null, $foreign_key = null, $local_key = null, $data = null, $where = null, $order = null, $limit = null, $test = null)
	{
		$builder = self::find_builder(func_get_args());
		$foreign_key = self::resolve($foreign_key, $builder, 'foreign_key');
		$local_key = self::resolve($local_key, $builder, '');
		if (!$local_key) $local_key = moojon_model_properties::DEFAULT_PRIMARY_KEY;
		if (!$foreign_key) $foreign_key = moojon_inflect::singularize($local_table).'_'.moojon_model_properties::DEFAULT_PRIMARY_KEY;
		return self::build('SELECT', $foreign_table, $data, $where, $order, $limit)->join(self::resolve($type, $builder, 'type'), self::resolve($foreign_table, $builder, 'foreign_table'), self::resolve($local_table, $builder, 'primary_obj'), $foreign_key, $local_key)->run($test);
	}
	
	final static public function select($obj, $data = '*', $where = null, $order = null, $limit = null, $test = null)
	{
		return self::run('SELECT', $obj, $data, $where, $order, $limit, $test);
	}
	
	final static public function insert($obj, $data = null, $test = null)
	{
		return self::run('INSERT', $obj, $data, null, null, null, $test);
	}
	
	final static public function update($obj, $data = null, $where = null, $test = null)
	{
		return self::run('UPDATE', $obj, $data, $where, null, null, $test);
	}
	
	final static public function delete($obj, $data = null, $where = null, $test = null)
	{
		return self::run('DELETE', $obj, $data, $where, null, null, $test);
	}
	
	final static public function describe($obj, $data = null, $test = null)
	{
		return self::run('DESCRIBE', $obj, $data, null, null, null, $test);
	}
	
	final static public function desc($obj, $data = null, $test = null)
	{
		return self::run('DESC', $obj, $data, null, null, null, $test);
	}
	
	final static public function show_columns($obj, $where = null, $test = null)
	{
		return self::run('SHOW COLUMNS', $obj, null, $where, null, null, $test);
	}
	
	final static public function show_full_columns($obj, $where = null, $test = null)
	{
		return self::run('SHOW FULL COLUMNS', $obj, null, $where, null, null, $test);
	}
	
	final static public function left($foreign_table, $local_table = null, $foreign_key = null, $local_key = null, $data = '*', $where = null, $order = null, $limit = null, $test = null)
	{
		return self::run_join('LEFT', $foreign_table, $local_table, $foreign_key, $local_key, $data, $where, $order, $limit, $test);
	}
	
	final static public function right($foreign_table, $local_table = null, $foreign_key = null, $local_key = null, $data = '*', $where = null, $order = null, $limit = null, $test = null)
	{
		return self::run_join('RIGHT', $foreign_table, $local_table, $foreign_key, $local_key, $data, $where, $order, $limit, $test);
	}
}

class query_runner extends moojon_query_runner {}
class runner extends moojon_query_runner {}
class mqr extends moojon_query_runner {}
class qr extends moojon_query_runner {}
?>