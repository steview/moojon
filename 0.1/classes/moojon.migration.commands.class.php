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
		if (!$table_exists) {
			moojon_query_runner::create_table('schema_migrations', new moojon_string_column('version'));
		}
		$migrations = array();
		foreach (schema_migration::read(null, 'version') as $migration) {
			$migrations[] = $migration->version;
		}
		foreach (moojon_files::directory_files(PROJECT_PATH.'/models/migrations/') as $migration_file) {
			$migration_class_file = moojon_files::get_filename($migration_file);
			$migration_class_name = self::get_migration_class_name($migration_class_file);
			if (!in_array($migration_class_file, $migrations)) {
				require_once(PROJECT_PATH.'/models/migrations/'.$migration_class_file);
				$migration = new $migration_class_name;
				$migration->up();
				schema_migration::create(array('version' => $migration_class_file))->save();
			}
		}	
	}
	
	static public function roll_back($version, $all = false) {
		if (schema_migration::read("version = '$version'")->count && $all == false) {
			moojon_base::handle_error("no such migration ($version)");
		}
		foreach (schema_migration::read(null, 'version DESC') as $migration_class_file) {
			if ($migration_class_file->version == $version) {
				break;
			}
			require_once(PROJECT_PATH.'/models/migrations/'.$migration_class_file->version);
			$migration_class_name = self::get_migration_class_name($migration_class_file->version);
			$migration = new $migration_class_name;
			$migration->down();
			schema_migration::destroy("version = '".$migration_class_file->version."'");
		}
	}
	
	static public function reset() {
		self::roll_back('', true);
	}
	
	private function get_migration_class_name($migration_class_file) {
		return substr(substr($migration_class_file, (strpos($migration_class_file, '.') + 1)), 0, (strpos(substr($migration_class_file, (strpos($migration_class_file, '.') + 1)), '.') + 0));
	}
}
?>