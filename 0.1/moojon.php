<?php
require_once(MOOJON_PATH.'/classes/moojon.base.class.php');
require_once(MOOJON_PATH.'/classes/moojon.config.class.php');
require_once(MOOJON_PATH.'/classes/moojon.uri.class.php');
require_once(MOOJON_PATH.'/classes/moojon.paths.class.php');
require_once(MOOJON_PATH.'/classes/moojon.files.class.php');
require_once(MOOJON_PATH.'/classes/moojon.inflect.class.php');
require_once(MOOJON_PATH.'/classes/moojon.request.class.php');
require_once(MOOJON_PATH.'/classes/moojon.base.app.class.php');
require_once(MOOJON_PATH.'/classes/moojon.base.controller.class.php');
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
require_once(MOOJON_PATH.'/classes/moojon.base.tag.class.php');
require_once(MOOJON_PATH.'/classes/moojon.base.tag.attribute.class.php');
require_once(MOOJON_PATH.'/classes/moojon.base.empty.tag.class.php');
require_once(MOOJON_PATH.'/classes/moojon.base.open.tag.class.php');
moojon_files::require_directory_files(MOOJON_PATH.'/classes/tags/');
moojon_files::require_directory_files(MOOJON_PATH.'/classes/tags/attributes/');
if (defined('PROJECT_DIRECTORY') == true) {
	foreach (moojon_files::directory_files(moojon_paths::get_project_config_directory(), true) as $file) {
		moojon_config::set(require_once($file));
	}
	if (defined('APP') == true) {
		foreach (moojon_files::directory_files(moojon_paths::get_app_config_directory(), true) as $file) {
			moojon_config::set(require_once($file));
		}
	}
	if (moojon_config::has('adapter') && moojon_config::has('db_host') && moojon_config::has('db_username') && moojon_config::has('db_password') && moojon_config::has('db')) {
		$con = moojon_connection::init(moojon_config::get('db_host'), moojon_config::get('db_username'), moojon_config::get('db_password'), moojon_config::get('db'));
		moojon_files::require_files_then_require_directory_files('moojon.join.class.php', MOOJON_PATH.'/classes/adapters/'.moojon_config::get('adapter').'/', true);
		if (is_dir(moojon_paths::get_base_models_directory()) && is_dir(moojon_paths::get_models_directory())) {
			moojon_files::require_directory_files(moojon_paths::get_base_models_directory());
			moojon_files::require_directory_files(moojon_paths::get_models_directory());
		}
	}	
}
function helper($helper) {
	$helper = moojon_files::require_suffix($helper, 'helper');
	if (file_exists(moojon_paths::get_helpers_directory().$helper) == true) {
		require_once(moojon_paths::get_helpers_directory().$helper);
	} elseif (file_exists(moojon_paths::get_moojon_helpers_directory().$helper) == true) {
		require_once(moojon_paths::get_moojon_helpers_directory().$helper);
	} else {
		moojon_base::handle_error("Unknown helper ($helper)");
	}
}
function partial($partial) {
	$partial = moojon_files::require_prefix($partial, '_');
	$path = dirname($partial).'/';
	if (strlen($path) < 1) {
		$path = moojon_uri::get_controller();
	}
	$path = "../$path";
	if (file_exists(moojon_paths::get_views_directory().$path.$partial) == true) {
		require_once(moojon_paths::get_views_directory().$path.$partial);
	} elseif (file_exists(moojon_paths::get_shared_views_directory().$partial) == true) {
		require_once(moojon_paths::get_shared_views_directory().$partial);
	} else {
		moojon_base::handle_error("Unknown partial ($partial)");
	}
}
switch (strtoupper(UI)) {
	case 'CGI':
		require_once('classes/moojon.base.tag.attribute.class.php');
		if (!is_dir(PROJECT_DIRECTORY)) {
			moojon_base::handle_error('Invalid PROJECT_DIRECTORY ('.PROJECT_DIRECTORY.')');
		}
		$app = moojon_uri::get_app();
		if (in_array($app, moojon_files::directory_directories(moojon_paths::get_apps_directory())) == true) {
			require_once(moojon_paths::get_apps_directory()."/$app/$app.app.class.php");
			$app = $app.'_app';
			$controller = moojon_uri::get_controller();
			if (in_array(moojon_paths::get_controllers_directory()."$controller.controller.class.php", moojon_files::directory_files(moojon_paths::get_controllers_directory()))) {
				require_once(moojon_paths::get_controllers_directory()."$controller.controller.class.php");						
			} else {
				moojon_base::handle_error("404 controller not found ($controller)");
			}
			$app = new $app;
			$layout = $app->get_layout();
			if ($layout !== false) {
				$layout = moojon_paths::get_layouts_directory().$layout;
				if (file_exists($layout) == false) {
					$shared_layout = moojon_paths::get_shared_layouts_directory().$layout;
					if (file_exists($shared_layout) == true) {
						$layout = $shared_layout;
					} else {
						moojon_base::handle_error("Layout not found ($layout or $shared_layout)");
					}					
				}
			}
			$view = moojon_paths::get_views_directory().$app->get_view();
			if (file_exists($view) == false) {
				$shared_view = moojon_paths::get_shared_views_directory().$app->get_view();;
				if (file_exists($shared_view) == true) {
					$view = $shared_view;
				} else {
					moojon_base::handle_error("404 view not found ($view or $shared_view)");
				}
			}
			foreach ($app->get_controller_properties() as $key => $value) {
				if (isset($$key)) {
					moojon_base::handle_error("Invalid property assignment in $controller ($$key). This is a Moojon reserved variable name");
				} else {
					$$key = $value;
				}
			}
			helper('moojon');
			ob_start();
			require_once($view);
			define('YIELD', ob_get_clean());
			ob_end_clean();
			if ($layout !== false) {
				require_once($layout);
			} else {
				echo YIELD;
			}
		} else {
			moojon_base::handle_error("404 app not found ($app)");
		}
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
		if (is_dir(moojon_paths::get_migrations_directory()) == true) {
			require_once(MOOJON_PATH.'/classes/base.schema_migration.model.class.php');
			require_once(MOOJON_PATH.'/classes/schema_migration.model.class.php');
			require_once(MOOJON_PATH.'/classes/moojon.base.migration.class.php');
			moojon_files::require_directory_files(moojon_paths::get_migrations_directory());
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