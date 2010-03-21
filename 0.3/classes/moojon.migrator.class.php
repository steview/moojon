<?php
final class moojon_migrator extends moojon_base {
	public function __construct() {}
	
	static public function run() {
		self::find_or_create_schema_migrations_table();
		$migrations = array();
		foreach (schema_migration::read(null, 'version') as $migration) {
			$migrations[] = $migration->version;
		}
		$migration_files = moojon_files::directory_files(moojon_paths::get_project_migrations_directory());
		sort($migration_files);
		foreach ($migration_files as $migration_file) {
			if (!in_array(basename($migration_file), $migrations)) {
				self::run_migration($migration_file, 'up');
			}
		}
	}
	
	static public function roll_back($migration_file, $all = false) {
		self::find_or_create_schema_migrations_table();
		if (schema_migration::read("version = '".basename($migration_file)."'")->count && !$all) {
			throw new moojon_exception("No such migration ($migration_file)");
		}
		foreach (schema_migration::read(null, 'version DESC') as $migration) {
			if (self::get_migration_class_name($migration->version) == $migration_file.'_migration') {
				break;
			}
			self::run_migration($migration->version, 'down');
		}
	}
	
	static public function reset() {
		self::roll_back('', true);
	}
	
	static private function run_migration($migration_file, $direction) {
		require_once($migration_file);
		$migration_class_name = self::get_migration_class_name($migration_file);
		$migration = new $migration_class_name;
		$migration->$direction();
		switch ($direction) {
			case 'up':
				$schema_migration = new schema_migration(array('version' => basename($migration_file)));
				$schema_migration->save();
				break;
			case 'down':
				schema_migration::destroy("version = '".basename($migration_file)."'");
				break;
		}
		echo "Running $migration_class_name::$direction()\n";
	}
	
	static private function find_or_create_schema_migrations_table() {
		if (!in_array('schema_migrations', moojon_db::show_tables(''))) {
			moojon_db::create_table('schema_migrations', array(new moojon_string_column('version')));
		}
	}
	
	static public function get_migration_class_name($migration_class_file) {
		return substr(substr(basename($migration_class_file), (strpos(basename($migration_class_file), '.') + 1)), 0, (strpos(substr(basename($migration_class_file), (strpos(basename($migration_class_file), '.') + 1)), '.') + 0)).'_migration';
	}
}
?>