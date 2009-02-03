<?php 
require_once('classes/moojon.base.class.php');
require_once('classes/moojon.config.class.php');
require_once('classes/moojon.files.class.php');
require_once('classes/moojon.uri.class.php');
require_once('classes/moojon.request.class.php');
require_once('classes/moojon.base.app.class.php');
require_once('classes/moojon.base.controller.class.php');
require_once('classes/moojon.inflect.class.php');

require_once('classes/moojon.connection.class.php');
require_once('classes/moojon.query.utilities.class.php');

require_once('classes/moojon.query.builder.class.php');
require_once('classes/moojon.query.runner.class.php');
require_once('classes/moojon.base.model.class.php');
require_once('classes/moojon.model.collection.class.php');
require_once('classes/moojon.base.relationship.class.php');
require_once('classes/moojon.has.one.relationship.class.php');
require_once('classes/moojon.has.many.relationship.class.php');
require_once('classes/moojon.has.many.to.many.relationship.class.php');

require_once('classes/adapters/MySQL/moojon.query.class.php');
require_once('classes/adapters/MySQL/moojon.join.class.php');
require_once('classes/adapters/MySQL/moojon.left.join.class.php');
require_once('classes/adapters/MySQL/moojon.right.join.class.php');
require_once('classes/adapters/MySQL/moojon.inner.join.class.php');
require_once('classes/adapters/MySQL/moojon.cross.join.class.php');
require_once('classes/adapters/MySQL/moojon.joins.class.php');
require_once('classes/adapters/MySQL/columns/moojon.base.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.primary_key.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.binary.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.boolean.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.date.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.datetime.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.decimal.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.float.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.integer.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.string.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.text.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.time.column.class.php');
require_once('classes/adapters/MySQL/columns/moojon.timestamp.column.class.php');

require_once('classes/base.schema_migration.model.class.php');
require_once('classes/schema_migration.model.class.php');
require_once('classes/moojon.base.migration.class.php');
require_once('classes/moojon.migration.commands.class.php');

moojon_files::require_directory_files(PROJECT_PATH.'/models/base/');
moojon_files::require_directory_files(PROJECT_PATH.'/models/migrations/');
moojon_files::require_directory_files(PROJECT_PATH.'/models/');

$con = moojon_connection::init(moojon_config::get_db_host(), moojon_config::get_db_username(), moojon_config::get_db_password(), 'bloodbowl');

/*moojon_migration_commands::run();

$con->close();

die();*/

$app_name = moojon_uri::get_app();
$controller_name = moojon_uri::get_controller();

require_once(PROJECT_PATH."/apps/$app_name/$app_name.app.class.php");
require_once(PROJECT_PATH."/apps/$app_name/controllers/$controller_name.controller.class.php");

$app_class_name = moojon_uri::get_app().'_app';
$app = new $app_class_name;

$con->close();
?>