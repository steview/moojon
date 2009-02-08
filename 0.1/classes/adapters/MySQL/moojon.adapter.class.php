<?php
final class moojon_adapter extends moojon_base {
	protected function __construct() {}
	
	static public function list_tables() {
		$tables = array();
		foreach (moojon_query_runner::show_tables() as $table) {
			if ($table['Tables_in_'.moojon_config::get('db')] != 'schema_migrations') {
				$tables[] = $table['Tables_in_'.moojon_config::get('db')];
			}			
		}
		return $tables;
	}
	
	static public function list_columns($table) {
		return moojon_query_runner::show_full_columns($table);
	}
	
	static public function get_add_columns($table) {
		$add_columns = array();
		foreach (self::list_columns($table) as $column) {
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
						moojon_base::handle_error("Data type not mappable ($type)");
						break;
				}
			}
			$add_columns[] = $add_column;
		}
		return implode("\n\t\t", $add_columns);
	}
}
?>