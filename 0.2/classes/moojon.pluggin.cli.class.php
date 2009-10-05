<?php
final class moojon_pluggin_cli extends moojon_base_cli {
	public function run($arguments) {
		$command = $this->prompt_until_in(array_shift($arguments), $this->get_commands(), 'Please enter a command?');
		switch ($command) {
			case 'install':
				$arguments = self::check_arguments('moojon_pluggin_cli::install()', 2, $arguments);
				$pluggin = $this->prompt_until_in($arguments[1], $this->get_uninstalled_pluggins(), 'Which pluggin?');
				$method = 'install';
				break;
			case 'uninstall':
				$arguments = self::check_arguments('moojon_pluggin_cli::uninstall()', 2, $arguments);
				$pluggin = $this->prompt_until_in($arguments[1], $this->get_installed_pluggins(), 'Which pluggin?');
				$method = 'uninstall';
				break;
		}
		$installer_class = $pluggin.'_installer';
		new $installer_class($method);
	}
	
	private function get_commands() {
		return array('install', 'uninstall');
	}
	
	private function get_uninstalled_pluggins() {
		$uninstalled_pluggins = array();
		foreach (moojon_files::directory_directories(moojon_paths::get_project_pluggins_directory(), false, true) as $pluggin_directory) {
			if (!file_exists($pluggin_directory.'installed')) {
				$uninstalled_pluggins[] = basename($pluggin_directory);
			}
		}
		return $uninstalled_pluggins;
	}
	
	private function get_installed_pluggins() {
		$installed_pluggins = array();
		foreach (moojon_files::directory_directories(moojon_paths::get_project_pluggins_directory(), false, true) as $pluggin_directory) {
			if (file_exists($pluggin_directory.'installed')) {
				$installed_pluggins[] = basename($pluggin_directory);
			}
		}
		return $installed_pluggins;
	}
}
?>
