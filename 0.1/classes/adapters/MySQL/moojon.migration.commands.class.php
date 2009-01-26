<?php
final class moojon_migration_commands extends moojon_base {
	private function __construct() {}
	
	static public function find_or_create_schema_migrations_table() {
		foreach (moojon_query::run_raw('SHOW TABLES;') as $table) {
			echo $table['Tables_in_'.moojon_config::get_db()].'<br />';
		}
	}
}
?>