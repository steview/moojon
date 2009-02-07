<?php
require_once(MOOJON_PATH.'/classes/moojon.base.class.php');
require_once(MOOJON_PATH.'/classes/moojon.config.class.php');
require_once(MOOJON_PATH.'/classes/moojon.uri.class.php');
require_once(MOOJON_PATH.'/classes/moojon.paths.class.php');
require_once(MOOJON_PATH.'/classes/moojon.files.class.php');
require_once(MOOJON_PATH.'/classes/moojon.inflect.class.php');
if (defined('PROJECT_DIRECTORY')) {
	foreach (moojon_files::directory_files(moojon_paths::get_project_config_directory(), true) as $file) {
		moojon_config::set(require_once($file));
	}
	foreach (moojon_files::directory_files(moojon_paths::get_app_config_directory(), true) as $file) {
		moojon_config::set(require_once($file));
	}
	if (moojon_config::has('adapter') && moojon_config::has('db_host') && moojon_config::has('db_username') && moojon_config::has('db_password') && moojon_config::has('db')) {
		require_once(MOOJON_PATH.'/classes/moojon.query.utilities.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.query.builder.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.query.runner.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.base.model.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.base.column.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.model.collection.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.base.relationship.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.has.one.relationship.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.has.many.relationship.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.has.many.to.many.relationship.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.connection.class.php');
		$con = moojon_connection::init(moojon_config::get('db_host'), moojon_config::get('db_username'), moojon_config::get('db_password'), moojon_config::get('db'));
		moojon_files::require_files_then_require_directory_files('moojon.join.class.php', MOOJON_PATH.'/classes/adapters/'.moojon_config::get('adapter').'/', true);
		moojon_files::require_directory_files(moojon_paths::get_base_models_directory());
		moojon_files::require_directory_files(moojon_paths::get_models_directory());
		if (strtoupper(UI) == 'CLI') {
			require_once(MOOJON_PATH.'/classes/base.schema_migration.model.class.php');
			require_once(MOOJON_PATH.'/classes/schema_migration.model.class.php');
			require_once(MOOJON_PATH.'/classes/moojon.base.migration.class.php');
		}
	}	
}
switch (strtoupper(UI)) {
	case 'CGI':
		if (!is_dir(PROJECT_DIRECTORY)) {
			moojon_base::handle_error('Invalid PROJECT_DIRECTORY ('.PROJECT_DIRECTORY.')');
		}
		require_once(MOOJON_PATH.'/classes/moojon.request.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.base.app.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.base.controller.class.php');		
		define('APP', moojon_uri::get_app());
		$controller_name = moojon_uri::get_controller();
		require_once(moojon_paths::get_app_directory().moojon_uri::get_app().'.app.class.php');
		require_once(moojon_paths::get_controllers_directory()."$controller_name.controller.class.php");
		$app_class_name = moojon_uri::get_app().'_app';
		new $app_class_name;
		break;
	case 'CLI':
		if (!defined("STDIN")) {
			define("STDIN", fopen('php://stdin','r'));
		}
		require_once(MOOJON_PATH.'/classes/moojon.base.cli.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.cli.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.generate.cli.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.migrate.cli.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.generator.class.php');
		require_once(MOOJON_PATH.'/classes/moojon.migrator.class.php');
		if (defined('PROJECT_DIRECTORY')) {
			moojon_files::require_directory_files(PROJECT_DIRECTORY.'/models/migrations/');
		}
		$arguments = $_SERVER['argv'];
		array_shift($arguments);
		new $cli($arguments);
		break;
	default:
		moojon_base::handle_error('Invalid UI ('.UI.')');
		break;
}
if (get_class($con) == 'moojon_connection') {
	$con->close();
}
?>