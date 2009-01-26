<?php
final class moojon_migration_commands extends moojon_base {
	private function __construct() {}
	
	static public function run() {
		$table_exists = false;
		foreach (moojon_query_runner::show_tables() as $table) {
			if (in_array('schema_migrations', $table)) {
				$table_exists = true;
			}			
		}
		if ($table_exists) {
			echo 'has';
		}
	}
}
?>