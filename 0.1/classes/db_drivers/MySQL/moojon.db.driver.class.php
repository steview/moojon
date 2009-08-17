<?php
final class moojon_db_driver extends moojon_base_db_driver implements moojon_db_driver_queries {
	final static public function create_table($table, $columns, $options = null) {
		$query .= "CREATE TABLE $table($columns)";
		if ($where) {
			$query .= " $options";
		}
		return $query .= ';';
	}
	
	final static public function show_tables() {
		return 'SHOW FULL TABLES;';
	}
	
	final static public function show_columns($table) {
		return "SHOW FULL COLUMNS FROM $table;";
	}
	
	final static public function drop_table($table) {
		return "DROP TABLE $table;";
	}
	
	final static public function rename_table($table, $new_name) {
		return "ALTER TABLE $table RENAME TO $new_name;";
	}
	
	final static public function add_column($table, $column) {
		return "ALTER TABLE $table ADD COLUMN $column";
	}
	
	final static public function drop_column($table, $column) {
		return "ALTER TABLE $table DROP COLUMN $column";
	}
	
	final static public function change_column($table, $column) {
		return "ALTER TABLE $table CHANGE COLUMN $column";
	}
	
	final static public function modify_column($table, $column) {
		return "ALTER TABLE $table MODIFY COLUMN $column";
	}
	
	final static public function add_index($table, $index) {
		return "ALTER TABLE $table ADD INDEX $index";
	}
	
	final static public function drop_index($table, $index) {
		return "ALTER TABLE $table REMOVE INDEX $index";
	}
	
	final static public function select($table, $columns = array(), $where = null, $order = null, $limit = null) {
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
	
	final static public function insert($table, $columns = array(), $symbol = false) {
		$values = '';
		foreach($columns as $value) {
			$apos = (!$symbol) ? "'" : '';
			$values .= ", $apos$value$apos";
		}
		$columns = ' VALUES('.substr($values, 2).')';
		$columns = implode(', ', array_keys($columns));
		return "INSERT INTO $table ($columns)$values;";
	}
	
	final static public function update($table, $columns = array(), $where = null, $symbol = false) {
		$where = self::require_prefix($where, 'WHERE ');
		$values = '';
		foreach($columns as $key => $value) {
			$values .= ", $key = '$value'";
		}
		return "UPDATE $table SET ".substr($values, 2)."$where;";
	}
	
	final static public function delete($table, $where = null) {
		$where = self::require_prefix($where, 'WHERE ');
		return "DELETE FROM $table $where;";
	}
}
?>