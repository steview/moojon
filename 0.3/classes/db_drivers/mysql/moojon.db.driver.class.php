<?php
final class moojon_db_driver extends moojon_base_db_driver implements moojon_db_driver_queries {
	const NULL = 'NULL';
	const DATE_FORMAT = 'Y-m-d';
	const DATETIME_FORMAT = 'Y-m-d H:i:s';
	const TIME_FORMAT = 'H:i:s';
	const DEFAULT_ORDER_DIRECTION = 'ASC';
	
	static private function get_add_column_string(moojon_base_column $column) {
		switch (get_class($column)) {
			case 'moojon_binary_column':
				return '`'.$column->get_name().'` BINARY('.$column->get_limit().') '.moojon_db_driver::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_boolean_column':
				return '`'.$column->get_name().'` TINYINT(1) '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_date_column':
				return '`'.$column->get_name().'` DATE '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_datetime_column':
				return '`'.$column->get_name().'` DATETIME '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_decimal_column':
				return '`'.$column->get_name().'` DECIMAL('.$column->get_limit().', '.$column->get_decimals().') '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_float_column':
				return '`'.$column->get_name().'` FLOAT('.$column->get_limit().', '.$column->get_decimals().') '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_integer_column':
				return '`'.$column->get_name().'` INTEGER('.$column->get_limit().') '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_primary_key':
				return '`'.$column->get_name().'` INTEGER('.$column->get_limit().') '.self::get_null_string($column).' '.self::get_default_string($column).' '.$column->get_options();
				break;
			case 'moojon_string_column':
				return '`'.$column->get_name().'` VARCHAR('.$column->get_limit().') '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_text_column':
				$binary_string = (!$column->get_binary()) ? '' : 'BINARY ';
				return '`'.$column->get_name()."` TEXT $binary_string".self::get_null_string($column);
				break;
			case 'moojon_time_column':
				return '`'.$column->get_name().'` TIME '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			case 'moojon_timestamp_column':
				return '`'.$column->get_name().'` TIMESTAMP '.self::get_null_string($column).' '.self::get_default_string($column);
				break;
			default;
				throw new moojon_exception('create_table can only accept columns of moojon_base_column ('.get_class($column).' found).');
				break;
		}
	}
	
	static public function create_table($table, $columns = array(), $options = null) {
		if (!is_array($columns)) {
			$columns = array($columns);
		}
		$columns_strings = array();
		foreach ($columns as $column) {
			$columns_strings[] = self::get_add_column_string($column);
		}
		$columns_string = implode(', ', $columns_strings);
		$query = "CREATE TABLE `$table`($columns_string)";
		if ($options) {
			$query .= " $options";
		}
		return $query .= ';';
	}
	
	static public function show_tables($where = null) {
		$return = array();
		$table_name_column = 'Tables_in_'.moojon_config::get('db_dbname');
		if ($where === null) {
			$where = " WHERE $table_name_column != 'schema_migrations'";
		}
		foreach (moojon_db::run(moojon_db::prepare("SHOW TABLES$where;")) as $table) {
			$return[] = $table[$table_name_column];
		}
		return $return;
	}
	
	static public function show_columns($table) {
		return "SHOW COLUMNS FROM `$table`;";
	}
	
	static public function remove_table($table) {
		return "DROP TABLE `$table`;";
	}
	
	static public function rename_table($table, $new_name) {
		return "ALTER TABLE `$table` RENAME TO `$new_name`;";
	}
	
	static public function add_column($table, $column) {
		$column_string = self::get_add_column_string($column);
		return "ALTER TABLE `$table` ADD COLUMN $column_string;";
	}
	
	static public function remove_column($table, $column) {
		return "ALTER TABLE `$table` DROP COLUMN $column;";
	}
	
	static public function rename_column($table, $column) {
		return "ALTER TABLE `$table` CHANGE COLUMN $column;";
	}
	
	static public function modify_column($table, $column) {
		$column_string = self::get_add_column_string($column);
		return "ALTER TABLE `$table` MODIFY COLUMN $column;";
	}
	
	static public function add_index($name, $table, $column) {
		return  "CREATE INDEX $name ON $table($column);";
	}
	
	static public function remove_index($name, $table) {
		return "DROP INDEX $name ON table";
	}
	
	static public function select($table, $columns = array(), $where = null, $order = null, $limit = null) {
		$columns = self::columns($columns);
		$table = self::tables($table);
		$where = self::where($where);
		$order = self::order($order);
		$limit = self::limit($limit);
		return "SELECT $columns FROM $table $where $order $limit;";
	}
	
	static public function insert($table, $columns = array(), $symbol = false) {
		$table = self::tables($table);
		$values = '';
		foreach($columns as $value) {
			$values .= ", $value";
		}
		$columns = implode('`, `', array_keys($columns));
		$values = ' VALUES('.substr($values, 2).')';
		return "INSERT INTO $table (`$columns`)$values;";
	}
	
	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////
	//DO NOT FORGET COLUMN ADDRESS IN UPDATE!!!
	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////
	
	static public function update($table, $columns = array(), $where = null) {
		$table = self::tables($table);
		$values = '';
		foreach($columns as $key => $value) {
			$values .= ", `$key` = $value";
		}
		$where = self::where($where);
		return "UPDATE $table SET ".substr($values, 2)."$where;";
	}
	
	static public function delete($table, $where = null) {
		$table = self::tables($table);
		$where = self::where($where);
		return "DELETE FROM $table $where;";
	}
	
	static public function tables($tables) {
		$tables = (is_array($tables)) ? $tables : array($tables);
		return '`'.implode('`, `', $tables).'`';
	}
	
	static public function columns($columns = array()) {
		if (is_array($columns)) {
			$return = '';
			foreach(array_keys($columns) as $key) {
				if (!is_string($key)) {
					$return .= ', '.$columns[$key];
				} else {
					$column = $columns[$key];
					$return .= ", $key AS $column";
				}
			}
			$return = substr($return, 2);
		} else {
			if (!$columns) {
				$columns = '*';
			}
			$return = $columns;
		}
		return $return;
	}
	
	static public function where($where) {
		if (is_array($where)) {
			$where = implode(' AND ', $where);
		}
		return self::require_prefix($where, 'WHERE ');
	}
	
	static public function order($order) {
		return self::require_prefix($order, 'ORDER BY ');
	}
	
	static public function limit($limit) {
		return self::require_prefix($limit, 'LIMIT ');
	}
	
	static public function get_null() {
		return self::NULL;
	}
	
	static public function get_add_columns($table) {
		$add_columns = array();
		foreach (moojon_db::show_columns($table) as $column) {
			$name = $column['Field'];
			if ($bracket_position = strpos($column['Type'], '(')) {
				$type = substr($column['Type'], 0, $bracket_position);
				$limit = substr($column['Type'], ($bracket_position + 1));
				$limit = substr($limit, 0, (strlen($limit) - 1));
			} else {
				$type = $column['Type'];
			}
			if (strtoupper($column['Null']) == 'NO') {
				$null = 'false';
			} else {
				$null = 'true';
			}
			if ($column['Default']) {
				$default = "'".$column['Default']."'";
			} else {
				$default = 'null';
			}
			if ($column['Key'] == 'PRI') {
				$add_column = '$this->add_primary_key();';
			} else {
				$add_column = '$this->add_';
				switch (strtoupper($type)) {
					case 'BIT':
					case 'TINYINT':
						$add_column .= "boolean('$name', $null, $default);";
						break;
					case 'SMALLINT':
					case 'MEDIUMINT':
					case 'INT':
					case 'INTEGER':
					case 'BIGINT':
						$add_column .= "integer('$name', $limit, $null, $default);";
						break;
					case 'REAL':
					case 'DOUBLE':
					case 'FLOAT':
					case 'DECIMAL':
					case 'NUMERIC':
						$add_column .= "decimal('$name', $limit, 10, $null, $default);";
						break;
					case 'CHAR':
					case 'VARCHAR':
						$add_column .= "string('$name', $limit, $null, $default);";
						break;
					case 'BINARY':
					case 'VARBINARY':
						$add_column .= "binary('$name', $limit, $null, $default);";
						break;
					case 'DATE':
						$add_column .= "date('$name', $null, $default);";
						break;
					case 'TIME':
						$add_column .= "time('$name', $null, $default);";
						break;
					case 'DATETIME':
					case 'TIMESTAMP':
						$add_column .= "datetime('$name', $null, $default);";
						break;
					case 'TINYBLOB':
					case 'BLOB':
					case 'MEDIUMBLOB':
					case 'LONGBLOB':
					case 'TINYTEXT':
					case 'TEXT':
					case 'MEDIUMTEXT':
					case 'LONGTEXT':
						$add_column .= "text('$name');";
						break;
					case 'ENUM':
					case 'SET':
					case 'YEAR':
						throw new moojon_exception("Data type not mappable ($type)");
						break;
				}
			}
			$add_columns[] = $add_column;
		}
		return implode("\n\t\t", $add_columns);
	}
	
	static final public function get_default_string(moojon_base_column $column) {
		if ($column->get_default()) {
			switch ($column->get_data_type()) {
				case moojon_db::PARAM_STR:
					$apos = "'";
					break;
				default:
					$apos = '';
					break;
			}
			return "DEFAULT $apos".$column->get_default().$apos;
		} else {
			return '';
		}
	}
	
	static final public function get_null_string(moojon_base_column $column) {
		if ($column->get_null()) {
			return 'NULL';
		} else {
			return 'NOT NULL';
		}
	}
	
	static final public function get_value_query_format(moojon_base_column $column) {
		$column_value = $column->get_value();
		switch (get_class($column)) {
			case 'moojon_date_column':
				return self::get_datetime_format($column_value, self::DATE_FORMAT);
				break;
			case 'moojon_datetime_column':
				return self::get_datetime_format($column_value, self::DATETIME_FORMAT);
				break;
			case 'moojon_time_column':
				return self::get_datetime_format($column_value, self::TIME_FORMAT);
				break;
			default:
				return $column_value;
				break;
		}
	}
	
	static public function get_read_all_bys($table) {
		$read_all_bys = array();
		foreach (moojon_db::show_columns($table) as $column) {
			$name = $column['Field'];
			$read_all_bys[] = "final static public function read_all_by_$name(\$value, \$order = null, \$limit = null) {return self::read_by(get_class(), '$name', \$value, \$order, \$limit);}";
		}
		return implode("\n\t", $read_all_bys);
	}
	
	static public function get_read_bys($table) {
		$read_bys = array();
		foreach (moojon_db::show_columns($table) as $column) {
			$name = $column['Field'];
			$read_bys[] = "final static public function read_by_$name(\$value, \$order = null, \$limit = null) {return self::read_all_by_$name(\$value, \$order, \$limit)->first;}";
		}
		return implode("\n\t", $read_bys);
	}
	
	static public function get_destroy_bys($table) {
		$destroy_bys = array();
		foreach (moojon_db::show_columns($table) as $column) {
			$name = $column['Field'];
			$destroy_bys[] = "final static public function destroy_by_$name(\$value) {self::destroy_by(get_class(), '$name', \$value);}";
		}
		return implode("\n\t", $destroy_bys);
	}
	
	static public function get_read_or_create_bys($table) {
		$read_or_create_bys = array();
		foreach (moojon_db::show_columns($table) as $column) {
			$name = $column['Field'];
			if ($column['Key'] != 'PRI') {
				$read_or_create_bys[] = "final static public function read_or_create_by_$name(\$value, \$data = null) {return self::read_or_create_by(get_class(), '$name', \$value, \$data);}";
			}
		}
		return implode("\n\t", $read_or_create_bys);
	}
	
	static public function get_relationships_class_where(moojon_base_model $model) {
		return implode(' AND ', $model->get_relationship_wheres());
	}
	
	static private function in_string($string, $postion) {
		$aposes = 0;
		for ($i = 0; $i < $postion; $i ++) {
			if (substr($string, $i, 1) == "'") {
				$aposes ++;
			}
		}
		return ($aposes % 2 != 0);
	}
	
	static private function next_character($subject, $position) {
		if (($position + 1) > strlen($subject)) {
			return false;
		} else {
			return substr($subject, $position, 1);
		}
	}
	
	static private function previous_character($subject, $position) {
		if ($position < 2) {
			return false;
		} else {
			return substr($subject, ($position - 1), 1);
		}
	}
	
	static private function str_replace2($needle, $replace, $subject) {
		if ($count = substr_count($subject, $needle)) {
			$position = strpos($subject, $needle);
			$valid_surrounding_characters = array(false, ' ', '.', ',', '=', '+', '-', '*', '/', '>', '<', '(', ')', '!');
			for ($i = 0; $i < $count; $i ++) {
				$length = strlen($needle);
				if (!self::is_symbol(substr($subject, ($position - 1), ($length + 1))) && !self::in_string($subject, $position) && in_array(self::previous_character($subject, $position), $valid_surrounding_characters, true) && in_array(self::next_character($subject, ($position + $length)), $valid_surrounding_characters, true)) {
					$subject = substr_replace($subject, $replace, $position, $length);
					$position = strpos($subject, $needle, ($position + strlen($replace)));
				}
			}
		}
		return $subject;
	}
	
	static public function column_addresses($subject, $table, $column_names = array()) {
		foreach ($column_names as $column_name) {
			$needle = self::column_address($table, $column_name);
			$replace = "$table.$column_name";
			$subject = self::str_replace2($needle, $replace, $subject);
			$needle = "$table.$column_name";
			$replace = $column_name;
			$subject = self::str_replace2($needle, $replace, $subject);
			$needle = $column_name;
			$replace = self::column_address($table, $column_name);
			$subject = self::str_replace2($needle, $replace, $subject);
		}
		return $subject;
	}
	
	static public function column_address($table, $column_name) {
		return "`$table`.`$column_name`";
	}
	
	static public function full_column_name($class, $column_name) {
		return strtoupper($class.'_'.$column_name);
	}
	
	static public function get_relationship_class_where(moojon_base_relationship $relationship, moojon_base_model $accessor) {
		$accessor_class = get_class($accessor);
		$table = $accessor->get_table(false);
		$key = $relationship->get_key();
		$foreign_table = $relationship->get_foreign_table();
		$foreign_key = $relationship->get_foreign_key();
		$return = '';
		switch (get_class($relationship)) {
			case 'moojon_has_one_relationship':
				$return = "`$foreign_table`.`$key` = `$table`.`$foreign_key`";
				break;
			case 'moojon_has_many_relationship':
				$foreign_key = moojon_primary_key::get_foreign_key($accessor_class);
				$return = "`$table`.`$key` = `$foreign_table`.`$foreign_key`";
				break;
			case 'moojon_has_many_to_many_relationship':
				$return = 'many_to_many';
				break;
			case 'moojon_belongs_to_relationship':
				$return = "`$table`.`$key` = `$foreign_table`.`$foreign_key`";
				break;
		}
		return $return;
	}
	
	static public function get_relationship_object_where(moojon_base_relationship $relationship, moojon_base_model $accessor) {
		$key = $relationship->get_key();
		switch (get_class($relationship)) {
			case 'moojon_has_one_relationship':
				$foreign_table = $relationship->get_foreign_table();
				$foreign_key = $relationship->get_foreign_key();
				$return = "`$foreign_table`.`$key` = :$foreign_key";
				break;
			case 'moojon_has_many_relationship':
				$foreign_table = $relationship->get_foreign_table();
				$foreign_key = moojon_primary_key::get_foreign_key(get_class($accessor));
				$return = "`$foreign_table`.`$foreign_key` = :$key";
				break;
			case 'moojon_has_many_to_many_relationship':
				$foreign_table = moojon_inflect::pluralize($relationship->get_class($accessor));
				$foreign_key1 = moojon_primary_key::get_foreign_key($relationship->get_foreign_table());
				$foreign_key2 = moojon_primary_key::get_foreign_key(get_class($accessor));
				$return = "`$key` IN (SELECT `$foreign_key1` FROM `$foreign_table` WHERE `$foreign_key2` = :$key)";
				break;
			case 'moojon_belongs_to_relationship':
				$foreign_key = moojon_primary_key::get_foreign_key(get_class($accessor));
				$return = "`$key` = :$foreign_key";
				break;
		}
		return $return;
	}
	
	static public function get_relationship_param_values(moojon_base_relationship $relationship, moojon_base_model $accessor) {
		switch (get_class($relationship)) {
			case 'moojon_has_one_relationship':
			case 'moojon_belongs_to_relationship':
				$foreign_key = $relationship->get_foreign_key();
				return array(":$foreign_key" => $accessor->$foreign_key);
				break;
			case 'moojon_has_many_relationship':
			case 'moojon_has_many_to_many_relationship':
				$key = $relationship->get_key();
				return array(":$key" => $accessor->$key);
				break;
		}
	}
	
	static public function get_relationship_param_data_types(moojon_base_relationship $relationship, moojon_base_model $accessor) {
		switch (get_class($relationship)) {
			case 'moojon_has_one_relationship':
			case 'moojon_belongs_to_relationship':
				$foreign_key = $relationship->get_foreign_key();
				$column = $accessor->get_column($foreign_key);
				return array(":$foreign_key" => $column->get_data_type());
				break;
			case 'moojon_has_many_relationship':
			case 'moojon_has_many_to_many_relationship':
				$key = $relationship->get_key();
				$column = $accessor->get_column($key);
				return array(":$key" => $column->get_data_type());
				break;
		}
	}
}
?>