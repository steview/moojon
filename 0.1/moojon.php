<?php
require_once(MOOJON_PATH.'/classes/moojon.base.class.php');
require_once(MOOJON_PATH.'/classes/moojon.config.class.php');
require_once(MOOJON_PATH.'/classes/moojon.paths.class.php');
require_once(MOOJON_PATH.'/functions/moojon.core.functions.php');
if (is_dir(PROJECT_DIRECTORY) == true) {
	foreach (moojon_files::directory_files(moojon_paths::get_project_config_directory(), true) as $file) {
		moojon_config::set(require_once($file));
	}
	foreach (moojon_files::directory_files(moojon_paths::get_app_config_directory(), true) as $file) {
		moojon_config::set(require_once($file));
	}
	$con = moojon_connection::init(moojon_config::get('db_host'), moojon_config::get('db_username'), moojon_config::get('db_password'), moojon_config::get('db'));
		moojon_files::require_files_then_require_directory_files('moojon.join.class.php', MOOJON_PATH.'/classes/adapters/'.moojon_config::get('adapter').'/', true);
	switch (strtoupper(UI)) {
		case 'CGI':
			require_once(moojon_paths::get_app_path(moojon_uri::get_app()));
			$app_class = moojon_uri::get_app().'_app';
			$app = new $app_class;
			break;
		case 'CLI':
			if (!defined("STDIN")) {
				define("STDIN", fopen('php://stdin','r'));
			}
			$arguments = $_SERVER['argv'];
			array_shift($arguments);
			new $cli($arguments);
			break;
		default:
			moojon_base::handle_error('Invalid UI ('.UI.')');
			break;
	}
} else {
	moojon_base::handle_error('Invalid PROJECT_DIRECTORY ('.PROJECT_DIRECTORY.')');
}
?>