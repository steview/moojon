<?php
final class moojon_migrate_cli extends moojon_base_cli {
	public function run($arguments) {
		$command = $this->prompt_until_in(array_shift($arguments), $this->get_commands(), 'Please enter a command?');
		$this->get_migrations();
		switch ($command) {
			case 'roll_back':
				self::check_arguments('moojon_migrate_cli::roll_back()', 1, $arguments);
				$migration = $this->prompt_until_in($arguments[0], $this->get_migrations(), 'Which migration?');
				moojon_migrator::roll_back($migration);
				break;
			case 'reset':
				self::check_arguments('moojon_migrate_cli::reset()', 0, $arguments);
				moojon_migrator::reset();
				break;
			case 'run':
				self::check_arguments('moojon_migrate_cli::run()', 0, $arguments);
				moojon_migrator::run();
				break;
		}
	}
	
	private function get_commands() {
		return array('roll_back', 'reset', 'run');
	}
	
	private function get_migrations() {
		$migrations = array();
		foreach (moojon_files::directory_files(moojon_paths::get_project_migrations_directory()) as $migration) {
			$migration_file = moojon_migrator::get_migration_class_name($migration);
			$migrations[] = substr($migration_file, 0, (strlen($migration_file) - 10));
		}
		return $migrations;
	}
}
?>
