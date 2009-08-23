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
		print_r($migration_files);
		foreach ($migration_files as $migration_file) {
			echo "**$migration_file\n";
			if (!in_array($migration_file, $migrations)) {
				self::run_migration($migration_file, 'up');
			}
		}
	}
	
	static public function roll_back($migration_file, $all = false) {
		self::find_or_create_schema_migrations_table();
		if (schema_migration::read("version = '$migration_file'")->count && !$all) {
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
		echo "run_migration1\n";
		require_once($migration_file);
		echo "run_migration2\n";
		$migration_class_name = self::get_migration_class_name($migration_file);
		echo "run_migration3\n";
		echo "Running $migration_class_name::$direction()\n";
		echo "run_migration4\n";
		$migration = new $migration_class_name;
		echo "run_migration5\n";
		$migration->$direction();
		echo "run_migration6\n";
		switch ($direction) {
			case 'up':
				schema_migration::create(array('version' => $migration_file))->save();
				break;
			case 'down':
				schema_migration::destroy("version = '$migration_file'");
				break;
		}
		echo "run_migration7\n";
	}
	
	static private function find_or_create_schema_migrations_table() {
		$table_exists = false;
		foreach (moojon_db::show_tables() as $table) {
			if ($table) {
				if (in_array('schema_migrations', $table)) {
					$table_exists = true;
				}
			}
		}
		if (!$table_exists) {
			moojon_db::create_table('schema_migrations', array(new moojon_string_column('version')));
		}
	}
	
	static public function get_migration_class_name($migration_class_file) {
		return substr(substr(basename($migration_class_file), (strpos(basename($migration_class_file), '.') + 1)), 0, (strpos(substr(basename($migration_class_file), (strpos(basename($migration_class_file), '.') + 1)), '.') + 0)).'_migration';
	}
}
?>