<?php
final class moojon_db_driver extends moojon_base_db_driver implements moojon_db_driver_queries {
	static public function create_table($table, $columns = array(), $options = null) {
		if (is_array($columns)) {
			$columns = implode(', ', $columns);
		}
		$query = "CREATE TABLE $table($columns)";
		if ($options) {
			$query .= " $options";
		}
		return $query .= ';';
	}
	
	static public function show_tables($where = null) {
		$return = array();
		$table_name_column = 'Tables_in_'.moojon_config::key('db_dbname');
		if ($where === null) {
			$where = " WHERE $table_name_column != 'schema_migrations'";
		}
		foreach (moojon_db::run(moojon_db::prepare("SHOW TABLES$where;")) as $table) {
			$return[] = $table[$table_name_column];
		}
		return $return;
	}
	
	static public function show_columns($table) {
		return "SHOW COLUMNS FROM $table;";
	}
	
	static public function drop_table($table) {
		return "DROP TABLE $table;";
	}
	
	static public function rename_table($table, $new_name) {
		return "ALTER TABLE $table RENAME TO $new_name;";
	}
	
	static public function add_column($table, $column) {
		return "ALTER TABLE $table ADD COLUMN $column;";
	}
	
	static public function drop_column($table, $column) {
		return "ALTER TABLE $table DROP COLUMN $column;";
	}
	
	static public function change_column($table, $column) {
		return "ALTER TABLE $table CHANGE COLUMN $column;";
	}
	
	static public function modify_column($table, $column) {
		return "ALTER TABLE $table MODIFY COLUMN $column;";
	}
	
	static public function add_index($table, $index) {
		return "ALTER TABLE $table ADD INDEX $index;";
	}
	
	static public function drop_index($table, $index) {
		return "ALTER TABLE $table REMOVE INDEX $index;";
	}
	
	static public function select($table, $columns = array(), $where = null, $order = null, $limit = null) {
		$where = self::require_prefix($where, 'WHERE ');
		$order = self::require_prefix($order, 'ORDER BY ');
		$limit = self::require_prefix($limit, 'LIMIT ');
		$columns_string = '';
		if (is_array($columns)) {
			foreach(array_keys($columns) as $key) {
				if (!is_string($key)) {
					$columns_string .= ', '.$columns[$key];
				} else {
					$columns_string .= ", $key AS ".$columns[$key];
				}
			}
			$columns_string = substr($columns_string, 2);
			$columns = $columns_string;
		} else {
			if (!$columns) {
				$columns = '*';
			}
		}
		return "SELECT $columns FROM $table $where $order $limit;";
	}
	
	static public function insert($table, $columns = array(), $symbol = false) {
		$values = '';
		foreach($columns as $value) {
			$values .= ", $value";
		}
		$columns = implode(', ', array_keys($columns));
		$values = ' VALUES('.substr($values, 2).')';
		return "INSERT INTO $table ($columns)$values;";
	}
	
	static public function update($table, $columns = array(), $where = null) {
		$where = self::require_prefix($where, ' WHERE ');
		$values = '';
		foreach($columns as $key => $value) {
			$values .= ", $key = $value";
		}
		return "UPDATE $table SET ".substr($values, 2)."$where;";
	}
	
	static public function delete($table, $where = null) {
		$where = self::require_prefix($where, 'WHERE ');
		return "DELETE FROM $table $where;";
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
						$add_column .= "decimals('$name', $limit, 10, $null, $default);";
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
						$add_column .= "text($name);";
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
}
?>