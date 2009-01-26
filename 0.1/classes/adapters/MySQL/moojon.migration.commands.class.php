<?php
final class moojon_migration_commands extends moojon_base {
	private function __construct() {}
	
	static public function find_or_create_schema_migrations_table() {
		$table_exists = false;
		foreach (moojon_query::run_raw('SHOW TABLES;') as $table) {
			if (in_array('schema_migrations', $table)) {
				$table_exists = true;
			}
		}
		if ($table_exists) {
			die('has');
		} else {
			die('has not');
		}
	}
}
?>